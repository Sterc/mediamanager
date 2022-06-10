<?php

namespace Sterc\MediaManager\Cronjob\Jobs;

use Sterc\MediaManager\Traits\JobNotifierTrait;
use DateTime;
use DateTimeZone;
use modMediaSource;

class TestImageSourceValidity extends Job
{
    use JobNotifierTrait;

    /**
     * Test image source validity.
     */
    public function process()
    {
        if (count($this->getMediaSources()) > 0) {
            foreach ($this->getMediaSources() as $mediaSource) {
                /* Reset expired and notify items for every media source. */
                $this->resetItems();

                $frequencies = !empty($mediaSource->getPropertyList()['mediamanagerLicenseTestFrequencies']) ? json_decode($mediaSource->getPropertyList()['mediamanagerLicenseTestFrequencies'], true) : [];

                /* Set test frequencies. */
                $this->setFrequencies($frequencies);

                $imageSources = !empty($mediaSource->getPropertyList()['mediamanagerLicenseSources']) ? json_decode($mediaSource->getPropertyList()['mediamanagerLicenseSources'], true) : [];
                if (count($imageSources) > 0) {
                    $this->validateImageSources($imageSources, $mediaSource);
                }

                if ($this->shouldSendNotification()) {
                    $recipients = !empty($mediaSource->getPropertyList()['mediamanagerLicenseTestRecipients']) ? explode(',', $mediaSource->getPropertyList()['mediamanagerLicenseTestRecipients']) : [];

                    /* Set properties used in email. */
                    $this->setProperties([
                        'subject'    => $this->modx->lexicon(
                            'mediamanager.license.email.image_source_validity.subject',
                            [
                                'name'        => $this->modx->getOption('site_name'),
                                'mediasource' => $mediaSource->get('name')
                            ]
                        ),
                        'tpl'        => 'emails/license/sources-notification',
                        'itemTpl'    => 'emails/license/source/item',
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
     * Validate image sources and list expired or about to expire image sources.
     *
     * @param array $imageSources
     * @param modMediaSource $modMediaSource
     * @return void
     */
    protected function validateImageSources(array $imageSources, modMediaSource $modMediaSource)
    {
        foreach ($imageSources as $imageSource) {
            if (!isset($imageSource['expireson'])) {
                continue;
            }

            $expireDate = new DateTime($imageSource['expireson'], new DateTimeZone('UTC'));
            $curDate    = new DateTime('now', new DateTimeZone('UTC'));
            date_sub($curDate, date_interval_create_from_date_string('1 day'));

            /* Check if expired. */
            if ($expireDate->getTimestamp() < $curDate->getTimestamp()) {
                $this->expiredItems[] = [
                    'item' => $imageSource,
                    'link' => $this->makeManagerUrl(['a'  => 'source/update', 'id' => $modMediaSource->get('id')])
                ];

                continue;
            }

            $diff = $this->formatDateInterval($expireDate->diff($curDate));

            /* Check if a notification needs to be send. */
            if ($this->isListedFrequency($diff)) {
                $this->notifyItems[] = [
                    'item'          => $imageSource,
                    'expires_in'    => $diff,
                    'link'          => $this->makeManagerUrl(['a'  => 'source/update', 'id' => $modMediaSource->get('id')])
                ];
            }
        }
    }
}
