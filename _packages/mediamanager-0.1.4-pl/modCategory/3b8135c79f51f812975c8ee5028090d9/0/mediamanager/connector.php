<?php
/**
 * Media Manager Connector
 *
 * @package mediamanager
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';
$corePath = $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path') . 'components/mediamanager/');
require_once $corePath . 'model/mediamanager/mediamanager.class.php';
$modx->mediamanager = new MediaManager($modx);
$modx->lexicon->load('mediamanager:default');
/* handle request */
$path = $modx->getOption('processorsPath', $modx->mediamanager->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => ''
));