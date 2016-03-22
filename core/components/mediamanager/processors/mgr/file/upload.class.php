<?php
/**
 * Upload file processor
 */
class MediaManagerFileUploadProcessor extends modProcessor
{

    public function checkPermissions() {
        return $this->modx->hasPermission('file_manager');
    }

    public function process() {
        $mediaManager = $this->modx->getService('mediamanager', 'MediaManager', $this->modx->getOption('mediamanager.core_path', null, $this->modx->getOption('core_path') . 'components/mediamanager/') . 'model/mediamanager/');
        if (!$mediaManager instanceof MediaManager) {
            return $this->failure();
        }

        $result = $mediaManager->files->processFiles();
        return $this->outputArray($result);
    }

}

return 'MediaManagerFileUploadProcessor';
