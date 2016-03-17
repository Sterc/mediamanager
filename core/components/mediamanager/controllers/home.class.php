<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerHomeManagerController extends MediaManagerManagerController
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
    }

    public function getPageTitle()
    {
        //return $this->modx->lexicon('mediamanager');
    }
    public function getTemplateFile()
    {
        return $this->mediamanager->config['templatesPath'] . 'home.tpl';
    }

    public function loadCustomCssJs()
    {

    }

}