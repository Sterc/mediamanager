<?php

namespace Sterc\MediaManager\Cronjob;

use modX;

class Worker
{
    /**
     * @var modX
     */
    public $modx;

    /**
     * @param modX $modx
     */
    public function __construct(modX $modx)
    {
        $this->modx = $modx;   
    }

    /**
     * Run jobs passed to the worker.
     *
     * @param array $jobs
     * @return void
     */
    public function run(array $jobs = [])
    {
        if (count($jobs) === 0) {
            exit('Please specify one or more jobs to process.');
        }

        foreach ($jobs as $job) {
            $class = __NAMESPACE__ . '\\Jobs\\' . $job;

            if (!class_exists($class)) {
                exit('Job "' . $job . '" does not exist. Please provide a valid job name.');
            }
        
            $jobClass = new $class($this);
            $jobClass->process();
        }
    }
}
