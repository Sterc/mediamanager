<?php
if (!class_exists('MediaManagerInputRender')) {
    class MediaManagerInputRender extends modTemplateVarInputRender
    {
        public function getTemplate()
        {
            return $this->modx->getOption(
                    'mediamanager.core_path',
                    null,
                    $this->modx->getOption('core_path') . 'components/mediamanager/'
                ) . 'elements/tv/input/tpl/mm_input_image.tpl';
        }

        public function process($value, array $params = [])
        {
            if (!is_numeric($value)) {
                return $value;
            }

            $path = '';
            $file = $this->modx->getObject('MediamanagerFiles', $value);
            if ($file) {
                $mediaSourceId  = $file->get('media_sources_id');
                $mediaSource    = $this->modx->getObject('sources.modFileMediaSource', ['id' => $mediaSourceId]);
                $basePath       = $mediaSource->getProperties()['basePath']['value'];
                $path           = $basePath . $file->get('path');
            }

            $this->setPlaceholder('path', $path);

            return $value;
        }
    }
}
return 'MediaManagerInputRender';