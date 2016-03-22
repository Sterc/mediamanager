<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerHomeManagerController extends MediaManagerManagerController
{

    public function process(array $scriptProperties = array())
    {
        $placeholders = array(
            '_lang' => array(
                'mediamanager' => $this->modx->lexicon('mediamanager'),
                'mediamanager_upload_media' => $this->modx->lexicon('mediamanager.upload_media'),
                'mediamanager_new_category' => $this->modx->lexicon('mediamanager.new_category'),
                'mediamanager_advanced_search' => $this->modx->lexicon('mediamanager.advanced_search'),
                'mediamanager_upload_selected_files' => $this->modx->lexicon('mediamanager.upload_selected_files'),
                'mediamanager_search' => $this->modx->lexicon('mediamanager.search'),
                'mediamanager_dropzone_maximum_upload_size' => $this->modx->lexicon('mediamanager.dropzone.maximum_upload_size'),
                'mediamanager_dropzone_button' => $this->modx->lexicon('mediamanager.dropzone.button'),
                'mediamanager_dropzone_title' => $this->modx->lexicon('mediamanager.dropzone.title'),
            ),
            'token' => $this->modx->user->getUserToken($this->modx->context->get('key'))
        );
        $this->setPlaceholders($placeholders);
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('mediamanager');
    }

    public function getTemplateFile()
    {
        return $this->mediaManager->config['templatesPath'] . 'home.tpl';
    }

    public function loadCustomCssJs()
    {

    }

}