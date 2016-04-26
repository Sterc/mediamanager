<?php

require_once __DIR__ . '/../../model/mediamanager/mediamanager.class.php';

class MediaManagerTagsProcessor extends modProcessor
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
            case 'create':
                $data = $this->create();

                break;
            case 'edit':
                $data = $this->edit();

                break;
            case 'delete':
                $data = $this->delete();

                break;
            case 'getTagsByName':
                $data = $this->getTagsByName();

                break;
        }

        return $this->outputArray($data);
    }

    private function create()
    {
        return $this->mediaManager->tags->createTag($this->getProperty('tag'));
    }

    private function edit()
    {

        return $this->mediaManager->tags->editTag($this->getProperty('tag_id'), $this->getProperty('tag'));
    }

    private function delete()
    {
        return $this->mediaManager->tags->deleteTag($this->getProperty('tag_id'));
    }

    private function getTagsByName()
    {
        return $this->mediaManager->tags->getTagsByName(
            $this->getProperty('search'),
            (bool) $this->getProperty('isContextTag')
        );
    }
}

return 'MediaManagerTagsProcessor';