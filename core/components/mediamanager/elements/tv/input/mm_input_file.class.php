<?php
if (!class_exists('MediaManagerInputFileRender')) {
    class MediaManagerInputFileRender extends modTemplateVarInputRender
    {
        public function getTemplate()
        {
            return $this->modx->getOption('mediamanager.core_path', null, $this->modx->getOption('core_path') . 'components/mediamanager/') . 'elements/tv/input/tpl/mm_input_file.tpl';
        }

        public function process($value, array $params = array())
        {
            return $value;
        }
    }
}

return 'MediaManagerInputFileRender';