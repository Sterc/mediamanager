<?php
require_once __DIR__ . '/../model/mediamanager/mediamanager.class.php';

abstract class MediaManagerManagerController extends modExtraManagerController
{

    private $mediaManager = null;

    public function initialize()
    {
        $this->mediaManager = new MediaManager($this->modx);

        /**
         * Add the CSS.
         */
        $this->addCss($this->mediaManager->config['css_url'] . 'mgr/mediamanager.css');

        /**
         * Add the javascript.
         */
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/jquery/jquery-1.12.1.min.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                Ext.getCmp("modx-layout").hideLeftbar(true, false);
                MediaManager.config.connector_url = "'.$this->mediaManager->config['connector_url'].'";
            });
        </script>');

        return parent::initialize();
    }

    public function getLanguageTopics()
    {
        return array('mediamanager:default');
    }

    public function checkPermissions()
    {
        return true;
    }

}