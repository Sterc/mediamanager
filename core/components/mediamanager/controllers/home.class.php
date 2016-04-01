<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerHomeManagerController extends MediaManagerManagerController
{

    public function process(array $scriptProperties = array())
    {
        $contextList = $this->mediaManager->contexts->getListHtml();
        $sortOptions = $this->mediaManager->files->getSortOptionsHtml();
        $filterOptions = $this->mediaManager->files->getFilterOptionsHtml();

        $placeholders = array(
            'pagetitle'                    => $this->modx->lexicon('mediamanager'),
            'upload_media'                 => $this->modx->lexicon('mediamanager.files.upload_media'),
            'advanced_search'              => $this->modx->lexicon('mediamanager.files.advanced_search'),
            'upload_selected_files'        => $this->modx->lexicon('mediamanager.files.upload_selected_files'),
            'search'                       => $this->modx->lexicon('mediamanager.files.search'),
            'dropzone_maximum_upload_size' => $this->modx->lexicon('mediamanager.files.dropzone.maximum_upload_size'),
            'dropzone_button'              => $this->modx->lexicon('mediamanager.files.dropzone.button'),
            'dropzone_title'               => $this->modx->lexicon('mediamanager.files.dropzone.title'),
            'token'                        => $this->modx->user->getUserToken($this->modx->context->get('key')),
            'context_list'                 => $contextList,
            'sort_options'                 => $sortOptions,
            'filter_options'               => $filterOptions
        );

        $filters = $this->mediaManager->getChunk('files/filters', $placeholders);
        $placeholders['filters'] = $filters;

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