<?php
if(!class_exists('MediaManagerInputRender')) {
    class MediaManagerInputRender extends modTemplateVarInputRender
    {
        public function getTemplate()
        {
            return $this->modx->getOption('mediamanager.core_path', null, $this->modx->getOption('core_path') . 'components/mediamanager/') . 'elements/tv/input/tpl/mm_input_image.tpl';
        }

        public function process($value, array $params = array())
        {
            if (!is_numeric($value)) {
                return $value;
            }

            $path   = '';
            $source = '';
            $image  = $this->modx->getObject('MediamanagerFiles', $value);

            if ($image) {
                $path   = $image->get('path');
                $source = $image->get('media_sources_id');
            }

            $this->setPlaceholder('path', $path);
            $this->setPlaceholder('source', $source);

            return $value;
        }

    }
}

return 'MediaManagerInputRender';
