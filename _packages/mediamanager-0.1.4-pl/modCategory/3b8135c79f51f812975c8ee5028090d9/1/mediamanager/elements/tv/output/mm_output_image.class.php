<?php
if (!class_exists('MediaManagerOutputRender')) {
    class MediaManagerOutputRender extends modTemplateVarOutputRender
    {
        public function process($value, array $params = array())
        {
            if (!is_numeric($value)) {
                return $value;
            }

            $path = '';
            $image = $this->modx->getObject('MediamanagerFiles', $value);
            if ($image) {
                $path = $image->get('path');
            }

            return $path;
        }
    }
}

return 'MediaManagerOutputRender';