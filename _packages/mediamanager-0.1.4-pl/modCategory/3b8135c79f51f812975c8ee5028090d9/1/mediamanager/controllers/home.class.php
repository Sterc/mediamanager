<?php

require_once __DIR__ . '/index.class.php';

class MediaManagerHomeManagerController extends MediaManagerManagerController
{

    private $templateFile = 'home.tpl';

    public function process(array $scriptProperties = array())
    {
        $uploadMediaButton = '';
        if ($this->mediaManager->permissions->upload()) {
            $uploadMediaButton = $this->mediaManager->getChunk('files/upload_media_button', array(
                'label' => $this->modx->lexicon('mediamanager.files.upload_media'),
            ));
        }

        $placeholders = array(
            'pagetitle'                    => $this->modx->lexicon('mediamanager'),
            'upload_media_button'          => $uploadMediaButton,
            'upload_selected_files'        => $this->modx->lexicon('mediamanager.files.upload_selected_files'),
            'search'                       => $this->modx->lexicon('mediamanager.files.search'),
            'dropzone_maximum_upload_size' => $this->modx->lexicon('mediamanager.files.dropzone.maximum_upload_size', array(
                'limit' => MediaManagerFilesHelper::MAX_FILE_SIZE . ' MB',
                'limit_images' => MediaManagerFilesHelper::MAX_FILE_SIZE_IMAGES . ' MB'
            )),
            'dropzone_button'              => $this->modx->lexicon('mediamanager.files.dropzone.button'),
            'dropzone_title'               => $this->modx->lexicon('mediamanager.files.dropzone.title'),
            'token'                        => $this->modx->user->getUserToken($this->modx->context->get('key')),
            'source_list'                  => $this->mediaManager->sources->getListHtml(),
            'sort_options'                 => $this->mediaManager->files->getSortOptionsHtml(),
            'filter_options'               => $this->mediaManager->files->getFilterOptionsHtml(),
            'popup'                        => $this->mediaManager->getChunk('files/popup'),
            'dropzoneFile'                 => $this->mediaManager->getChunk('files/dropzone_file')
        );

        $filters = $this->mediaManager->getChunk('files/filters', $placeholders);
        $placeholders['filters'] = $filters;

        if ($this->mediaManagerError) {
            $placeholders['message'] = $this->mediaManagerError;
            $this->templateFile = 'error.tpl';
        }

        $this->setPlaceholders(array_merge($placeholders, $this->mediaManager->config));
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('mediamanager');
    }

    public function getTemplateFile()
    {
        return $this->mediaManager->config['templatesPath'] . $this->templateFile;
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->mediaManager->config['js_url'] . 'mgr/mediamanager-files-cropper.js');
        $this->addJavascript($this->mediaManager->config['js_url'] . 'mgr/mediamanager-files.js');
    }
}
