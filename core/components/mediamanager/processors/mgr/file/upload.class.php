<?php
/**
 * Upload file processor
 */
class MediaManagerFileUploadProcessor extends modProcessor
{

//    public $source;

    public function checkPermissions() {
        return $this->modx->hasPermission('file_manager');
    }

//    public function getLanguageTopics() {
//        return array('file');
//    }

//    public function initialize() {
//        $this->setDefaultProperties(array(
//            'dir' => '',
//        ));
//        if ($this->getProperty('dir') == 'root') {
//            $this->setProperty('dir','');
//        }
//        return true;
//    }

    public function process() {
        $data = array('test');
//        if (!$this->getSource()) {
//            return $this->failure($this->modx->lexicon('permission_denied'));
//        }
//        $allowedFileTypes = $this->getProperty('allowedFileTypes');
//        if (empty($allowedFileTypes)) {
//            // Prevent overriding media source configuration
//            unset($this->properties['allowedFileTypes']);
//        }
//        $this->source->setRequestProperties($this->getProperties());
//        $this->source->initialize();
//        if (!$this->source->checkPolicy('list')) {
//            return $this->failure($this->modx->lexicon('permission_denied'));
//        }
//        $list = $this->source->getObjectsInContainer($this->getProperty('dir'));
        return $this->outputArray($data);
    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
//    public function getSource() {
//        $this->modx->loadClass('sources.modMediaSource');
//        $this->source = modMediaSource::getDefaultSource($this->modx,$this->getProperty('source'));
//        if (empty($this->source) || !$this->source->getWorkingContext()) {
//            return false;
//        }
//        return $this->source;
//    }

}

return 'MediaManagerFileUploadProcessor';
