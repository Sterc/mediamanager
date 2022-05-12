<?php

namespace Sterc\MediaManager\Cronjob\Jobs;

use Sterc\MediaManager\Traits\JobNotifierTrait;
use DateTime;
use DateTimeZone;
use xPDOQuery;

class TestImageValidity extends Job
{
    use JobNotifierTrait;

    protected $expiredImageSources = [];

    /**
     * Test validity of images.
     * @return void
     */
    public function process()
    {
        if (count($this->getMediaSources()) > 0) {
            foreach ($this->getMediaSources() as $mediaSource) {
                $this->resetItems();

                $frequencies = !empty($mediaSource->getPropertyList()['mediamanagerLicenseTestFrequencies']) ? json_decode($mediaSource->getPropertyList()['mediamanagerLicenseTestFrequencies'], true) : [];

                /* Set test frequencies. */
                $this->setFrequencies($frequencies);

                $imageSources = !empty($mediaSource->getPropertyList()['mediamanagerLicenseSources']) ? json_decode($mediaSource->getPropertyList()['mediamanagerLicenseSources'], true) : [];
                $this->setExpiredImageSources($imageSources);

                /**
                 * Collect expired or about to expire images.
                 * Images are also marked as expired even if the image itself is not expired, but the attached source is.
                 */
                $query = $this->modx->newQuery('MediamanagerFiles');
                $query->rightJoin('MediamanagerFilesLicenseFile', 'MediamanagerFilesLicenseFile', 'MediamanagerFilesLicenseFile.mediamanager_files_id = MediamanagerFiles.id');
                $query->leftJoin('MediamanagerFilesLicense', 'MediamanagerFilesLicense', 'MediamanagerFilesLicenseFile.license_id = MediamanagerFilesLicense.id');

                $frequencyDates = [];
                foreach ($frequencies as $frequency) {
                    $frequencyDates[] = date('Y-m-d', strtotime('+' . $frequency));
                }

                $where = [
                    [
                        'MediamanagerFilesLicense.image_valid_enddate:<=' => date('Y-m-d 00:00:00')
                    ], [
                        'MediamanagerFilesLicense.image_valid_enddate:IN' => $frequencyDates
                    ]
                ];

                /* If expired or about to expire image sources have been found, also collect images attached to these sources. */
                if (count($this->expiredImageSources) > 0) {
                    $where[] = [
                        'MediamanagerFilesLicense.image_source:IN' => array_column($this->expiredImageSources, 'key')
                    ];
                }

                $query->where($where, xPDOQuery::SQL_OR);
                $query->where(['media_sources_id' => $mediaSource->get('id')]);

                foreach ($this->modx->getIterator('MediamanagerFiles', $query) as $image) {
                    $expired         = false;
                    $expiredBySource = false;
                    $messages        = [];
                    $resources       = [];

                    $query = $this->modx->newQuery('modResource');
                    $query->select(['modResource.*', '`modContext`.`name` as context_name']);
                    $query->leftJoin('MediamanagerFilesContent', 'MediamanagerFilesContent', '`MediamanagerFilesContent`.`site_content_id` = `modResource`.`id`');
                    $query->leftJoin('MediamanagerFiles', 'MediamanagerFiles', '`MediamanagerFiles`.`id` = `MediamanagerFilesContent`.`mediamanager_files_id`');
                    $query->leftJoin('modContext', 'modContext', '`modResource`.`context_key` = `modContext`.`key`');

                    $query->where(['MediamanagerFiles.id' => $image->get('id')]);
                    $query->sortby('modResource.context_key', 'asc');

                    /* Collect linked resources. */
                    $contextKey = null;
                    $contextIdx = 0;
                    foreach ($this->modx->getIterator('modResource', $query) as $modResource) {
                        $phs                    = $modResource->toArray();
                        $phs['contextHeading']  = '';
                        $phs['manager_url']     = $this->makeManagerUrl(['a' => 'resource/update', 'id' => $modResource->get('id')]);

                        if ($contextKey !== $modResource->get('context_key')) {
                            $phs['contextHeading'] = $this->mediamanager->getChunk('emails/license/image/context', [
                                'name'       => $modResource->get('context_name'),
                                'contextIdx' => $contextIdx
                            ]);

                            $contextKey = $modResource->get('context_key');
                            $contextIdx++;
                        }

                        $resources[] = $this->mediamanager->getChunk('emails/license/image/resource', $phs);
                    }

                    $license = $image->getLicense();
                    if (strtotime($license->get('image_valid_enddate')) < time()) {
                        $expired = true;
                    }

                    if (isset($this->expiredImageSources[$license->get('image_source')]) && $this->expiredImageSources[$license->get('image_source')]['is_expired'] === true) {
                        $expiredBySource = true;

                        $messages[] = $this->modx->lexicon('mediamanager.license.email.image_source_expired');
                    }

                    if ($expired || $expiredBySource) {
                        $this->expiredItems[] = [
                            'item'          => $image->toArray(),
                            'link'          => $this->makeManagerUrl(['a' => 'home', 'namespace' => 'mediamanager', 'file' => $image->get('id'), 'source' => $mediaSource->get('id')]),
                            'message'       => implode('<br/>', $messages),
                            'resources'     => implode('', $resources)
                        ];
                    } else {
                        $expireDate = new DateTime($license->get('image_valid_enddate'), new DateTimeZone('UTC'));
                        $curDate    = new DateTime('now', new DateTimeZone('UTC'));
                        date_sub($curDate, date_interval_create_from_date_string('1 day'));

                        $diff = $this->formatDateInterval($expireDate->diff($curDate));

                        if (isset($this->expiredImageSources[$license->get('image_source')])) {
                            $messages[] = $this->modx->lexicon('mediamanager.license.email.image_about_to_expire_by_image_source');

                            $expireDate = new DateTime($this->expiredImageSources[$license->get('image_source')]['expireson'], new DateTimeZone('UTC'));
                            $diff       = $this->formatDateInterval($expireDate->diff($curDate));
                        }

                        $this->notifyItems[] = [
                            'item'              => $image->toArray(),
                            'link'              => $this->makeManagerUrl(['a' => 'home', 'namespace' => 'mediamanager', 'file' => $image->get('id')]),
                            'expires_in'        => $diff,
                            'message'           => implode('<br/>', $messages),
                            'resources'         => implode('<br/>', $resources)
                        ];
                    }
                }

                if ($this->shouldSendNotification()) {
                    $recipients = !empty($mediaSource->getPropertyList()['mediamanagerLicenseTestRecipients']) ? explode(',', $mediaSource->getPropertyList()['mediamanagerLicenseTestRecipients']) : [];

                    $this->setProperties([
                        'subject'    => $this->modx->lexicon(
                            'mediamanager.license.email.image_validity.subject',
                            [
                                'name'        => $this->modx->getOption('site_name'),
                                'mediasource' => $mediaSource->get('name')
                            ]
                        ),
                        'tpl'        => 'emails/license/images-notification',
                        'itemTpl'    => 'emails/license/image/item',
                        'recipients' => $recipients
                    ]);

                    if (count($recipients) > 0) {
                        $this->sendNotification();
                    }
                }
            }
        }
    }

    /**
     * Set expired or about to expire image sources.
     *
     * @param array $imageSources
     * @return void
     */
    protected function setExpiredImageSources(array $imageSources)
    {
        $expired = [];

        if (count($imageSources) > 0) {
            $curDate    = new DateTime('now', new DateTimeZone('UTC'));
            date_sub($curDate, date_interval_create_from_date_string('1 day'));

            foreach ($imageSources as $imageSource) {
                if (!empty($imageSource['expireson'])) {
                    $expireDate = new DateTime($imageSource['expireson'], new DateTimeZone('UTC'));

                    if ($expireDate->getTimestamp() < $curDate->getTimestamp()) {
                        $expired[$imageSource['key']] = array_merge($imageSource, ['is_expired' => true]);
                    } else {
                        $diff = $this->formatDateInterval($expireDate->diff($curDate));

                        /* Check if a notification needs to be send. */
                        if ($this->isListedFrequency($diff)) {
                            $expired[$imageSource['key']] = array_merge($imageSource, ['is_expired' => false]);
                        }
                    }
                }
            }
        }

        $this->expiredImageSources = $expired;
    }
}

