<?php
/**
 * Categories processor
 */
class MediaManagerCategoriesProcessor extends modProcessor
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
            case 'getCategoriesByName':
                $data = $this->getCategoriesByName();

                break;
        }

        return $this->outputArray($data);
    }

    private function getCategoriesByName()
    {
        return $this->mediaManager->categories->getCategoriesByName($this->getProperty('search'));
    }

}

return 'MediaManagerCategoriesProcessor';
