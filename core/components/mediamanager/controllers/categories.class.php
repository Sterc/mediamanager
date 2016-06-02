<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerCategoriesManagerController extends MediaManagerManagerController
{

    public function process(array $scriptProperties = array())
    {
        $placeholders = [
            'pagetitle'           => $this->getPageTitle(),
            'parent_options'      => $this->mediaManager->categories->getParentOptions(),
            'create_title'        => $this->modx->lexicon('mediamanager.categories.title'),
            'create_label'        => $this->modx->lexicon('mediamanager.categories.label'),
            'create_parent_label' => $this->modx->lexicon('mediamanager.categories.parent_label'),
            'create_placeholder'  => $this->modx->lexicon('mediamanager.categories.placeholder'),
            'create_button'       => $this->modx->lexicon('mediamanager.categories.button'),
            'create_exclude'      => $this->modx->lexicon('mediamanager.categories.exclude'),
            'create_checkboxes'   => $this->mediaManager->categories->getMediaSourcesCheckboxes(),
            'list'                => $this->mediaManager->categories->getList(),
            'token'               => $this->modx->user->getUserToken($this->modx->context->get('key')),
        ];

        $this->setPlaceholders(array_merge($placeholders, $this->mediaManager->config));
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('mediamanager.categories');
    }
    public function getTemplateFile()
    {
        return $this->mediaManager->config['templatesPath'] . 'categories.tpl';
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->mediaManager->config['assets_url'] . 'libs/jquery-nested-sortable/2.0.0/js/jquery.nestedSortable.js');
        $this->addJavascript($this->mediaManager->config['js_url'] . 'mgr/mediamanager-categories.js');
    }

}