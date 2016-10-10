<?php
$corePath     = $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path') . 'components/mediamanager/');
$mediamanager = $modx->getService('mediamanager', 'MediaManager', $corePath . 'model/mediamanager/', array(
    'core_path' => $corePath
));

switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnTVOutputRenderList':
        $modx->event->output($corePath.'elements/tv/output/');
        break;
    case 'OnDocFormPrerender':
        $mediamanager->includeScriptAssets();
        break;
    case 'OnDocFormSave':
        $template = $resource->get('template');
        $tmplVars = $modx->getCollection('modTemplateVar', array(
            'type:IN' => array(
                'mm_input_image',
                'image',
                'file',
                'richtext',
                'migx'
            )
        ));

        foreach ($tmplVars as $tv) {
            $tvTemplate = $modx->getObject('modTemplateVarTemplate', array(
                'tmplvarid'  => $tv->get('id'),
                'templateid' => $template
            ));

            if (!$tvTemplate) {
                continue;
            }

            $value = $modx->getObject('modTemplateVarResource', array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id')
            ));
            
            // Get all the img src values from migx
            if ($value && $tv->get('type') == 'migx') {
                $doc = new DOMDocument();
                $html = '';
                $array = json_decode($value->get('value'), true);
                foreach ($array as $a) {
                    foreach ($a as $b) {
                        $html .= $b;
                    }
                }
                $doc->loadHTML($html);
                
                $tags = $doc->getElementsByTagName('img');
                $out = '';
                foreach ($tags as $tag) {
                    $file = $modx->getObject('MediamanagerFiles', array(
                        'path:LIKE' => '%'.$tag->getAttribute('src')
                    ));
                    
                    if (!$file) {
                        continue;
                    }
                    
                    $fileContent = $modx->getObject('MediamanagerFilesContent', array(
                        'mediamanager_files_id' => $file->get('id'),
                        'site_content_id'       => $resource->get('id'),
                        'is_tmplvar'            => 1
                    ));
        
                    if (!$fileContent) {
                        $fileContent = $modx->newObject('MediamanagerFilesContent');
                    }
        
                    $fileContent->set('mediamanager_files_id', $file->get('id'));
                    $fileContent->set('site_content_id', $resource->get('id'));
                    $fileContent->set('site_tmplvars_id', $tv->get('id'));
                    $fileContent->set('is_tmplvar', 1);
                    $fileContent->save();
                }
            }

            if (!$value || !is_numeric($value->get('value'))) {
                continue;
            }

            $file = $modx->getObject('MediamanagerFiles', array(
                'id' => $value->get('value')
            ));

            if (!$file) {
                continue;
            }

            $fileContent = $modx->getObject('MediamanagerFilesContent', array(
                'mediamanager_files_id' => $file->get('id'),
                'site_content_id'       => $resource->get('id'),
                'is_tmplvar'            => 1
            ));

            if (!$fileContent) {
                $fileContent = $modx->newObject('MediamanagerFilesContent');
            }

            $fileContent->set('mediamanager_files_id', $file->get('id'));
            $fileContent->set('site_content_id', $resource->get('id'));
            $fileContent->set('site_tmplvars_id', $tv->get('id'));
            $fileContent->set('is_tmplvar', 1);
            $fileContent->save();
        }

        // @TODO: Get image paths from resource content. Check and save them.

        // $tv_values = $modx->getCollection('modTemplateVarResource',array('contentid' => $id));
        // foreach($tv_values as $value) {
        //     $tv_template = $modx->getObject('modTemplateVarTemplate',array('tmplvarid' => $tv->get('tmplvarid')));
        //     if($tv_template) {
        //         $mm_relation = $modx->getObject('MediamanagerFilesContent',array('site_content_id' => $id,'mediamanager_files_id'));
        //     }
        // }
        
        break;
}
