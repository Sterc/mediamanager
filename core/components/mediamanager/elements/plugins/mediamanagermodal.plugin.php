<?php
$corePath     = $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path') . 'components/mediamanager/');
$mediamanager = $modx->getService('mediamanager', 'MediaManager', $corePath . 'model/mediamanager/', array(
    'core_path' => $corePath
));

switch ($modx->event->name) {
    case 'OnManagerPageBeforeRender':

        $modx->regClientCSS($mediamanager->config['assets_url'] . 'libs/jquery-ui/1.11.4/css/jquery-ui.min.css');
        $modx->regClientCSS($mediamanager->config['assets_url'] . 'libs/jquery-ui/1.11.4/css/jquery-ui.structure.min.css');
        $modx->regClientCSS($mediamanager->config['assets_url'] . 'libs/jquery-ui/1.11.4/css/jquery-ui.theme.min.css');
        $modx->regClientCSS($mediamanager->config['assets_url'] . 'css/mgr/mediamanager-tv-input.css');
        $modx->regClientStartupScript($mediamanager->config['assets_url'] . 'libs/jquery/1.12.1/js/jquery.min.js');
        $modx->regClientStartupScript($mediamanager->config['assets_url'] . 'libs/jquery-ui/1.11.4/js/jquery-ui.min.js');
        $modx->regClientStartupScript($mediamanager->config['assets_url'] . 'js/mgr/mediamanager-modal.js');

        break;
}
