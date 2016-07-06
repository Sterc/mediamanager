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
    $media_sources_url = $modx->getObject('sources.modFileMediaSource', ['id' => $file->get('media_sources_id')]);
    $base_url = $media_sources_url->get('baseUrl');
    $path = $file->get('path');

    if($media_sources_url->get('baseUrlRelative')){
        $path = $modx->getOption('site_url') . '/' .$base_url . '/' . $path;
    }else{
        $path = $base_url . '/' . $path;
    }
}

return $path;