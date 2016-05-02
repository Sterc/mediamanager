<?php
$corePath = $modx->getOption('mediamanager.core_path',null, $modx->getOption('core_path') . 'components/mediamanager/');
$mediamanager = $modx->getService('mediamanager', 'MediaManager', $corePath . 'model/mediamanager/', array(
    'core_path' => $corePath
));
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnDocFormRender':
        $mediamanager->includeScriptAssets();
        break;
    case 'OnDocFormSave':
        $template = $resource->get('template');
        $tmplvars = $modx->getCollection('modTemplateVar',array('type:IN' => array('image','file')));
        foreach($tmplvars as $tv) {
            $tv_template = $modx->getObject('modTemplateVarTemplate',array('tmplvarid' => $tv->get('id'),'templateid' => $template));
            if($tv_template) {
                $value = $modx->getObject('modTemplateVarResource',array('tmplvarid' => $tv->get('id'), 'contentid' => $id));
                if($value) {
                    $mm_file = $modx->getObject('MediamanagerFiles',array('path' => $value));
                    if($mm_file) {
                        $mm_relation = $modx->getObject('MediamanagerFilesContent',array('mediamanager_files_id' => $mm_file->get('id')));
                        if(!$mm_relation) {
                            $mm_relation = $modx->newObject('MediamanagerFilesContent');
                            $mm_relation->set('mediamanager_files_id',$mm_file->get('id'));
                            $mm_relation->set('site_content_id',$id);
                            $mm_relation->set('site_tmplvars_id',$tv->get('id'));
                            $mm_relation->set('is_tmplvar',1);
                            $mm_relation->save();
                        }
                    }
                }
            }
        }
        // $tv_values = $modx->getCollection('modTemplateVarResource',array('contentid' => $id));
        // foreach($tv_values as $value) {
        //     $tv_template = $modx->getObject('modTemplateVarTemplate',array('tmplvarid' => $tv->get('tmplvarid')));
        //     if($tv_template) {
        //         $mm_relation = $modx->getObject('MediamanagerFilesContent',array('site_content_id' => $id,'mediamanager_files_id'));
        //     }
        // }
        
        break;
}