<?php

namespace Sterc\MediaManager\Traits;

use modX;
use modMail;

use function PHPSTORM_META\map;

trait JobNotifierTrait
{
    /**
     * Holds a list of frequencies when notifications should be send.
     *
     * @var array
     */
    protected $frequencies = [];

    /**
     * Holds all expired items.
     *
     * @var array
     */
    protected $expiredItems = [];

    /**
     * Holds all items that needs notification, but is not expired yet.
     *
     * @var array
     */
    protected $notifyItems = [];

    protected $properties = [
        'subject'       => '',
        'tpl'           => '',
        'itemTpl'       => '',
        'recipients'    => ''
    ];

    /**
     * Set properties.
     *
     * @param array $properties
     * @return array
     */
    protected function setProperties(array $properties)
    {
        $this->properties = array_merge($this->properties, $properties);
    }

    /**
     * Get property.
     *
     * @param string $key
     * @return null|string|array
     */
    protected function getProperty(string $key)
    {
        return $this->properties[$key];
    }

    /**
     * Set frequencies when notifications should be send out.
     *
     * @param array $frequencies
     * @return void
     */
    public function setFrequencies(array $frequencies)
    {
        $this->frequencies = $frequencies;
    }

    /**
     * Determine if the passed difference is listed in the notify frequencies list.
     *
     * @param string $frequency
     * @return boolean
     */
    public function isListedFrequency(string $frequency)
    {
        return in_array($frequency, $this->frequencies, true);
    }

    /**
     * Determine if notification should be send.
     *
     * @return boolean
     */
    public function shouldSendNotification()
    {
        return count($this->expiredItems) > 0 || count($this->notifyItems) > 0;
    }

  /**
   * Send notification email.
   * @return void
   */
    public function sendNotification()
    {
        $this->mediamanager = $this->modx->getService('mediamanager', 'MediaManager', $this->modx->getOption('mediamanager.core_path', '', MODX_CORE_PATH . '/components/mediamanager/') . 'model/mediamanager/');
    
        $message = $this->mediamanager->getChunk($this->getProperty('tpl'), [
            'expiredItems' => $this->getItemHTML($this->expiredItems),
            'notifyItems'  => $this->getItemHTML($this->notifyItems)
        ]);

        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->set(modMail::MAIL_BODY, $message);
        $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
        $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
        $this->modx->mail->set(modMail::MAIL_SUBJECT, $this->getProperty('subject'));

        if ($this->getProperty('recipients')) {
            foreach ($this->getProperty('recipients') as $recipient) {
                $this->modx->mail->address('to', $recipient);
            }
        }
        
        $this->modx->mail->setHTML(true);

        if (!$this->modx->mail->send()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: ' . $this->modx->mail->mailer->ErrorInfo);
        }

        $this->modx->mail->reset();
    }

    /**
     * Return items HTML used in email.
     *
     * @param array $items
     * @return string
     */
    protected function getItemHTML(array $items)
    {
        $html = [];

        if (count($items) > 0) {
            foreach ($items as $key => $item) {
                $phs         = $item;
                $phs['even'] = $key % 2 === 1;

                $html[] = $this->mediamanager->getChunk($this->getProperty('itemTpl'), $phs);
            }
        }

        return implode('', $html);
    }
}
