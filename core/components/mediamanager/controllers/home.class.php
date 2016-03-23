<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerHomeManagerController extends MediaManagerManagerController
{

    public function process(array $scriptProperties = array())
    {
        $placeholders = array(
            'pagetitle'                    => $this->modx->lexicon('mediamanager'),
            'upload_media'                 => $this->modx->lexicon('mediamanager.files.upload_media'),
            'advanced_search'              => $this->modx->lexicon('mediamanager.files.advanced_search'),
            'upload_selected_files'        => $this->modx->lexicon('mediamanager.files.upload_selected_files'),
            'search'                       => $this->modx->lexicon('mediamanager.files.search'),
            'dropzone_maximum_upload_size' => $this->modx->lexicon('mediamanager.files.dropzone_maximum_upload_size'),
            'dropzone_button'              => $this->modx->lexicon('mediamanager.files.dropzone_button'),
            'dropzone_title'               => $this->modx->lexicon('mediamanager.files.dropzone_title'),
            'token'                        => $this->modx->user->getUserToken($this->modx->context->get('key'))
        );

        $this->setPlaceholders(array_merge($placeholders, $this->mediaManager->config));
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
        $this->addJavascript($this->mediaManager->config['js_url'] . 'mgr/mediamanager-files.js');
    }

}