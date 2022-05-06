<?php

namespace Sterc\MediaManager\Cronjob\Jobs;

use Sterc\MediaManager\Cronjob\Worker;
use DateInterval;

class Job
{
    /**
     * @var Worker
     */
    protected $worker;

    /**
     * @var modX
     */
    protected $modx;

    /**
     * @var Mediamanager
     */
    protected $mediamanager;

    /**
     * Holds array of all mediamanager enabled media sources.
     *
     * @var array
     */
    protected $mediaSources = [];

    /**
     * @param Worker $worker
     */
    public function __construct(Worker $worker)
    {
        $this->worker       = $worker;   
        $this->modx         = $worker->modx;
        $this->mediamanager = $this->modx->getService('mediamanager', 'MediaManager', $this->modx->getOption('mediamanager.core_path', '', MODX_CORE_PATH . '/components/mediamanager/') . 'model/mediamanager/');

        $this->modx->lexicon->load('mediamanager:default');
    }

    /**
     * Return formatted date interval. For example: 3 days.
     *
     * @param DateInterval $interval
     * @return string
     */
    protected function formatDateInterval(DateInterval $interval)
    {
        if ($interval->d) {
            return $interval->format('%d days');
        }
    
        return '';
    }

    /**
     * Return array of mediamanager media sources.
     *
     * @return array
     */
    protected function getMediaSources()
    {
        if (count($this->mediaSources) === 0) {
            $this->setMediaSources();
        }

        return $this->mediaSources;
    }

    /**
     * Set array of mediamanager media sources.
     *
     * @return void
     */
    protected function setMediaSources()
    {
        foreach ($this->modx->getIterator('sources.modMediaSource') as $source) {
            if (isset($source->getPropertyList()['mediamanagerSource']) && (int) $source->getPropertyList()['mediamanagerSource'] === 1) {
                $this->mediaSources[] = $source;
            }
        }
    }

    /**
     * Create manager URL.
     *
     * @param array $params
     * @return string
     */
    protected function makeManagerUrl(array $params)
    {
        return rtrim(MODX_SITE_URL, '/') . '/' . trim(MODX_MANAGER_URL, '/') . '?' . http_build_query($params);
    }
}
