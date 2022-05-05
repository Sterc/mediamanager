<?php

use Sterc\MediaManager\Cronjob\Worker;

if(!(php_sapi_name() === 'cli')) {
    header("HTTP/1.1 400 Bad Request");
    print 'Only runnable from CLI.';
    exit;
}

require_once dirname(__DIR__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->getService('error','error.modError', '', '');

$corePath = $modx->getOption('mediamanager.core_path', null, MODX_CORE_PATH . '/components/mediamanager/');
require_once $corePath . '/vendor/autoload.php';

$options = getopt('', ['jobs::']);
$jobs    = isset($options['jobs']) ? explode(',', $options['jobs']) : [];

$worker = new Worker($modx);
$worker->run($jobs);

exit;