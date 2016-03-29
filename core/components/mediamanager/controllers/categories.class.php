<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerCategoriesManagerController extends MediaManagerManagerController
{

    public function process(array $scriptProperties = array())
    {

        $lexicon = $this->modx->lexicon->fetch('mediamanager');

        $placeholders = [
            'pagetitle'          => $this->getPageTitle(),
            'list'               => $this->mediaManager->tags->getList(),
            'token'              => $this->modx->user->getUserToken($this->modx->context->get('key')),
            // '_lang'              => $lexicon
        ];

        // echo '<pre>';
        // print_r(array_merge($placeholders, $this->mediaManager->config,$lexicon));exit;

        $this->setPlaceholders(array_merge($placeholders, $this->mediaManager->config,$lexicon));

    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('mediamanager.categories');
    }
    public function getTemplateFile()
    {
        return $this->mediamanager->config['templatesPath'] . 'categories.tpl';
    }

    public function loadCustomCssJs()
    {

    }

}