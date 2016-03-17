<?php
require_once __DIR__ . '/../model/mediamanager/mediamanager.class.php';

abstract class MediaManagerManagerController extends modExtraManagerController
{

    private $mediaManager = null;

    public function initialize()
    {
        $this->mediaManager = new MediaManager($this->modx);

        /*$this->addJavascript('https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
        $this->addJavascript($this->mediamanager->config['assetsUrl'].'libs/bootstrap/js/bootstrap.min.js');
        $this->addCss($this->mediamanager->config['assetsUrl'].'libs/bootstrap/css/bootstrap.min.css');
        $this->addCss($this->mediamanager->config['assetsUrl'].'libs/bootstrap/css/bootstrap-theme.min.css');
        $this->addCss($this->mediamanager->config['cssUrl'].'mgr/mediamanager.css');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Ext.getCmp("modx-layout").hideLeftbar(true, false);
            MediaManager.config.connector_url = "'.$this->mediamanager->config['connectorUrl'].'";
        });
        </script>');*/

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                Ext.getCmp("modx-layout").hideLeftbar(true, false);
                MediaManager.config.connector_url = "'.$this->mediaManager->config['connectorUrl'].'";
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