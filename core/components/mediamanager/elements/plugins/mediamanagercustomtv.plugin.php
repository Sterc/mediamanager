<?php
$corePath = $modx->getOption('mediamanager.core_path',null, $modx->getOption('core_path') . 'components/mediamanager/');
$mediamanager = $modx->getService('mediamanager', 'MediaManager', $corePath . 'model/mediamanager/', array(
    'core_path' => $corePath
));
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnTVOutputRenderList':
        $modx->event->output($corePath.'elements/tv/output/');
        break;
    // case 'OnTVInputPropertiesList':
    //     $modx->event->output($corePath.'elements/tv/inputoptions/');
    //     break;
    // case 'OnTVOutputRenderPropertiesList':
    //     $modx->event->output($corePath.'elements/tv/properties/');
    //     break;
    // case 'OnManagerPageBeforeRender':
    //     break;
    case 'OnDocFormRender':
        $mediamanager->includeScriptAssets();
        break;
}