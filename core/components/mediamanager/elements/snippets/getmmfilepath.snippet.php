<?php
$mediaManager = $modx->getService('mediamanager', 'MediaManager', $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path').'components/mediamanager/').'model/mediamanager/');
if (!($mediaManager instanceof MediaManager)) {
    return false;
}

if (!is_numeric($input)) {
    return $input;
}

$path = '';
$file = $modx->getObject('MediamanagerFiles', $input);
if ($file) {
    $mediaSourceId = $file->get('media_sources_id');
    $mediaSource = $modx->getObject('sources.modFileMediaSource', ['id' => $mediaSourceId]);
    $basePath = $mediaSource->getProperties()['basePath']['value'];
    $isRelative = $mediaSource->getProperties()['baseUrlRelative']['value'];

    if($isRelative){
        $path = MODX_BASE_PATH . $basePath . $file->get('path');
    }else{
        $path = $basePath . $file->get('path');
    }

}

return $path;
