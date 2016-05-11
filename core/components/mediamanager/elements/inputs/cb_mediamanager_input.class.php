<?php

class cbMediaManagerInput extends cbBaseInput {
    public $defaultIcon = 'attachment';
    public $defaultTpl = '[[!mmRenderFile? &id=`[[+file_id]]`]]';

    public function getName()
    {
        return 'Media Manager - File';
    }

    public function getDescription()
    {
        return 'File input for the new Media Manager.';
    }

    /**
     * @return array
     */
    public function getJavaScripts()
    {
        $assetsUrl = $this->modx->getOption('mediamanager.assets_url', null, MODX_ASSETS_URL . 'components/mediamanager/');

        return array(
            $assetsUrl . 'js/inputs/cb_mediamanager_input.js',
        );
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        $tpls = array();

        // Grab the template from a .tpl file
        $corePath = $this->modx->getOption('mediamanager.core_path', null, MODX_CORE_PATH . 'components/mediamanager/');
        $template = file_get_contents($corePath . 'elements/templates/cb_mediamanager_input.tpl');

        // Wrap the template, giving the input a reference of "my_awesome_input", and
        // add it to the returned array.
        $tpls[] = $this->contentBlocks->wrapInputTpl('cb_mediamanager_input', $template);
        return $tpls;
    }
}
