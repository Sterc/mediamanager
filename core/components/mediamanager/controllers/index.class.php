<?php
require_once __DIR__ . '/../model/mediamanager/mediamanager.class.php';

abstract class MediaManagerManagerController extends modExtraManagerController
{

    protected $mediaManager = null;

    public function initialize()
    {
        $this->mediaManager = new MediaManager($this->modx);

        $mediaSource = $this->mediaManager->modx->getObject('modMediaSource', $this->mediaManager->modx->getOption('mediamanager.media_source'));
        $mediaSource = json_decode(json_encode($mediaSource->getProperties()));

        /**
         * Add the CSS.
         */
        $this->addCss($this->mediaManager->config['assets_path'] . 'libs/jquery-ui/1.11.4/css/jquery-ui.min.css');
        $this->addCss($this->mediaManager->config['assets_path'] . 'libs/jquery-cropper/2.3.0/css/cropper.min.css');
        $this->addCss($this->mediaManager->config['assets_path'] . 'libs/bootstrap/3.3.6/css/bootstrap.min.css');
        $this->addCss($this->mediaManager->config['assets_path'] . 'libs/bootstrap-treeview/1.2.0/css/bootstrap-treeview.min.css');
        $this->addCss($this->mediaManager->config['assets_path'] . 'libs/select2/4.0.2/css/select2.min.css');
        $this->addCss($this->mediaManager->config['assets_path'] . 'libs/font-awesome/4.6.1/css/font-awesome.min.css');
        $this->addCss($this->mediaManager->config['css_url'] . 'mgr/mediamanager.css');

        /**
         * Add the javascript.
         */
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/jquery/1.12.1/js/jquery.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/jquery-ui/1.11.4/js/jquery-ui.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/jquery-lazyload/1.9.7/js/jquery.lazyload.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/jquery-cropper/2.3.0/js/cropper.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/bootstrap/3.3.6/js/bootstrap.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/bootstrap-treeview/1.2.0/js/bootstrap-treeview.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/select2/4.0.2/js/select2.min.js');
        $this->addJavascript($this->mediaManager->config['assets_path'] . 'libs/dropzone/4.3.0/js/dropzone.min.js');

        $this->addHtml('<script type="text/javascript">
            var mediaManagerOptions = {
                cancel : "' . $this->modx->lexicon('mediamanager.global.cancel') . '",
                dropzone : {
                    maxFileSize       : ' . MediaManagerFilesHelper::MAX_FILE_SIZE . ',
                    maxFileSizeImages : ' . MediaManagerFilesHelper::MAX_FILE_SIZE_IMAGES . ',
                    acceptedFiles     : ".' . str_replace(',', ',.', $mediaSource->allowedFileTypes->value) . '"
                },
                message : {
                    maxFileSize    : "' . $this->modx->lexicon('mediamanager.files.error.filetoobig') . '",
                    minCategory    : "' . $this->modx->lexicon('mediamanager.categories.minimum_categories_message') . '",
                    replaceButton  : "' . $this->modx->lexicon('mediamanager.files.archive_and_replace') . '",
                    replaceConfirm : "' . $this->modx->lexicon('mediamanager.files.archive_and_replace_select_confirm') . '"
                }
            }

            Ext.onReady(function() {
                Ext.getCmp("modx-layout").hideLeftbar(true, false);
            });
        </script>');

        if(isset($_REQUEST['tv_frame']) && $_REQUEST['tv_frame'] == '1') {
            $this->addHtml('<style type="text/css">
                #modx-leftbar-tabs-xcollapsed,
                #modx-header {
                    display: none;
                }
                #modx-content {
                    top: 0;
                }
                .file-preview:hover .tv-tiny-use {
                    display: block;
                }
            </style>');
        }

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