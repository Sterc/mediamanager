<?php
if(!class_exists('MediaManagerOutputRender')) {
    class MediaManagerOutputRender extends modTemplateVarOutputRender {
        public function process($value,array $params = array()) {
            return '<div class="template">'.$value.'</div>';
        }
    }
}
return 'MediaManagerOutputRender';