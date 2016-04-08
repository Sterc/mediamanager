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
                $data = $this->add();

                break;
            case 'move':
                $data = $this->move();

                break;
            case 'archive':
                $data = $this->archive();

                break;
            case 'list':
                $data = $this->getList();

                break;
        }

        return $data;
    }

    private function add()
    {
        $response = $this->mediaManager->files->addFile();
        if ($response['status'] === 'error') {
            header('HTTP/1.1 400 Bad Request');
        }

        return $this->toJSON($response);
    }

    private function move()
    {
        return $this->outputArray(
            $this->mediaManager->files->moveFiles(
                $this->getProperty('files'),
                $this->getProperty('category')
            )
        );
    }

    private function archive()
    {
        return $this->outputArray(
            $this->mediaManager->files->archiveFiles(
                $this->getProperty('files')
            )
        );
    }

    private function getList()
    {
        return $this->outputArray(
            (array) $this->mediaManager->files->getListHtml(
                (int)    $this->getProperty('context'),
                (int)    $this->getProperty('category'),
                (string) $this->getProperty('search'),
                (array)  $this->getProperty('filters'),
                (array)  $this->getProperty('sorting'),
                (string) $this->getProperty('viewMode')
            )
        );
    }

}

return 'MediaManagerFilesProcessor';
