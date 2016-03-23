<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerCategoriesManagerController extends MediaManagerManagerController
{

    public function process(array $scriptProperties = array())
    {
        /*$ph = array();
        $dir_list = '';
        $files_list = '';
        $ph['pagetitle'] = $this->modx->lexicon('mediamanager');

        // getting the directories from processor
        $dir_res = $this->modx->runProcessor(
            'mgr/directory/getlist',array('hideFiles'=>1),array('processors_path'=>$this->mediamanager->config['processorsPath'])
        );
        if ($dir_res->isError()) { return $dir_res->getMessage();}
        $dirs = $this->modx->fromJSON($dir_res->response);
        foreach($dirs as $f) {
            $dir_list .= $this->mediamanager->getChunk('directory_item',$f);
        }
        $ph['dir_list'] = $dir_list;

        // getting the files from processor
        $files_res = $this->modx->runProcessor(
            'mgr/file/getlist',array(),array('processors_path'=>$this->mediamanager->config['processorsPath'])
        );
        if ($files_res->isError()) { return $files_res->getMessage();}
        $files = $this->modx->fromJSON($files_res->response);
        foreach($files['results'] as $f) {
            $files_list .= $this->mediamanager->getChunk('file_item',$f);
        }
        $ph['files_list'] = $files_list;

        $this->setPlaceholders($ph);*/

        $lexicon = $this->modx->lexicon->fetch('mediamanager');
        // print_r($lexicon);exit;

        $placeholders = [
            'pagetitle'          => $this->getPageTitle(),
            // 'create_title'       => $this->modx->lexicon('mediamanager.categories.title'),
            // 'create_label'       => $this->modx->lexicon('mediamanager.categories.label'),
            // 'create_placeholder' => $this->modx->lexicon('mediamanager.categories.placeholder'),
            // 'create_button'      => $this->modx->lexicon('mediamanager.categories.button'),
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