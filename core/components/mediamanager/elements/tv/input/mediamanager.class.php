<?php
if(!class_exists('MediaManagerInputRender')) {
    class MediaManagerInputRender extends modTemplateVarInputRender {
        public function getTemplate() {
            return $this->modx->getOption('mediamanager.core_path',null, $this->modx->getOption('core_path') . 'components/mediamanager/').'elements/tv/input/tpl/mediamanager.tpl';
        }
        public function process($value,array $params = array()) {
        }
    }
}
return 'MediaManagerInputRender';