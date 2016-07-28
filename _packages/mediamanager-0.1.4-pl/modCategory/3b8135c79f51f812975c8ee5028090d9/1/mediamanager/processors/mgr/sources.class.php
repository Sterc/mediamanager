<?php
/**
 * Sources processor
 */
class MediaManagerSourcesProcessor extends modProcessor
{

    private $mediaManager = null;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    public function process()
    {
        $this->mediaManager = $this->modx->getService('mediamanager', 'MediaManager', $this->modx->getOption('mediamanager.core_path', null, $this->modx->getOption('core_path') . 'components/mediamanager/') . 'model/mediamanager/');

        $method = $this->getProperty('method');
        $data   = array();

        switch ($method) {
            case 'list':
                $data = $this->getList();

                break;
        }

        return $this->outputArray($data);
    }

    private function getList()
    {
        return $this->mediaManager->sources->getListHtml();
    }

}

return 'MediaManagerSourcesProcessor';
