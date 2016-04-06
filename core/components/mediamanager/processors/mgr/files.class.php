<?php
/**
 * Files processor
 */
class MediaManagerFilesProcessor extends modProcessor
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
            case 'add':
                $data = $this->addFile();

                break;
            case 'list':
                $data = $this->getList();

                break;
        }

        return $data;
    }

    private function addFile()
    {
        $response = $this->mediaManager->files->addFile();
        if ($response['status'] === 'error') {
            header('HTTP/1.1 400 Bad Request');
        }

        return $this->toJSON($response);
    }

    private function getList()
    {
        $search = '';
        $filters = array();
        $sorting = array();
        $viewMode = '';

        if ($this->getProperty('search') !== null) {
            $search = $this->getProperty('search');
        }

        if ($this->getProperty('filters') !== null) {
            $filters = $this->getProperty('filters');
        }

        if ($this->getProperty('sorting') !== null) {
            $sorting = $this->getProperty('sorting');
        }

        if ($this->getProperty('viewMode') !== null) {
            $viewMode = $this->getProperty('viewMode');
        }

        return $this->outputArray((array) $this->mediaManager->files->getListHtml($search, $filters, $sorting, $viewMode));
    }

}

return 'MediaManagerFilesProcessor';
