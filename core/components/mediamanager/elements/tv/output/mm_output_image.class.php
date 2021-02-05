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
            if ($image = $this->modx->getObject('MediamanagerFiles', $value)) {
                if ($source = $this->modx->getObject('sources.modMediaSource', $image->get('media_sources_id'))) {
                    $source->initialize();

                    $path = $source->getBaseUrl();
                }

                $path .= $image->get('path');
            }

            return $path;
        }
    }
}

return 'MediaManagerOutputRender';