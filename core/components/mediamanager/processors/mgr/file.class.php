<?php
/**
 * File processor
 */
class MediaManagerFileProcessor extends modProcessor
{

    private $mediaManager = null;

    public function process()
    {
        $this->mediaManager = $this->modx->getService('mediamanager', 'MediaManager', $this->modx->getOption('mediamanager.core_path', null, $this->modx->getOption('core_path') . 'components/mediamanager/') . 'model/mediamanager/');

        $id   = $this->getProperty('id');
        $file = $this->mediaManager->files->getFile($id);

        if (empty($file) || empty($file['file'])) {
            return $this->outputArray([]);
        }

        $url = sprintf('%ssystem/phpthumb.php?w=400&source=%s&src=%s',
            MODX_CONNECTORS_URL,
            $file['file']->get('media_sources_id'),
            $file['file']->get('path')
        );

        return $this->outputArray([
            'url' => $url
        ]);
    }

}

return 'MediaManagerFileProcessor';
