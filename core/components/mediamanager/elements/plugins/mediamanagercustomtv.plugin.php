<?php
$corePath = $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path') . 'components/mediamanager/');
$mediamanager = $modx->getService('mediamanager', 'MediaManager', $corePath . 'model/mediamanager/', [
    'core_path' => $corePath
]);

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
        $modx->removeCollection('MediamanagerFilesContent', [
            'site_content_id' => $resource->get('id')
        ]);

        $criteria = $modx->newQuery('modTemplateVar');

        $criteria->select($modx->getSelectColumns('modTemplateVar', 'modTemplateVar'));

        $criteria->innerJoin('modTemplateVarTemplate', 'modTemplateVarTemplate', [
            '`modTemplateVarTemplate`.`tmplvarid` = `modTemplateVar`.`id`'
        ]);

        $criteria->where([
            'modTemplateVar.type:IN'            => ['mm_input_image', 'mm_input_file', 'migx'],
            'modTemplateVarTemplate.templateid' => $resource->get('template')
        ]);

        foreach ($modx->getCollection('modTemplateVar', $criteria) as $tv) {
            $value = $modx->getObject('modTemplateVarResource', [
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id')
            ]);

            if ($value) {
                if ($tv->get('type') === 'migx') {
                    $doc = new DOMDocument();

                    $html = '';

                    if ($array = json_decode($value->get('value'), true)) {
                        foreach ($array as $a) {
                            foreach ($a as $b) {
                                $html .= $b;
                            }
                        }
                    }

                    $doc->loadHTML($html);

                    foreach ($doc->getElementsByTagName('img') as $tag) {
                        $file = $modx->getObject('MediamanagerFiles', [
                            'path:LIKE' => '%' . $tag->getAttribute('src')
                        ]);

                        if ($file) {
                            $mediamanager->saveFileContent($file->get('id'), $resource->get('id'), $tv->get('id'));
                        }
                    }
                } else {
                    if (is_numeric($value->get('value'))) {
                        $mediamanager->saveFileContent($value->get('value'), $resource->get('id'), $tv->get('id'));
                    }
                }
            }
        }

        $properties = $resource->get('properties');

        if (isset($properties['contentblocks'])) {
            $contentblocks = $modx->getService('contentblocks', 'ContentBlocks', $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path') . 'components/contentblocks/') . 'model/contentblocks/');

            if ($contentblocks instanceof ContentBlocks) {
                if (isset($properties['contentblocks']['_isContentBlocks']) && (int) $properties['contentblocks']['_isContentBlocks'] === 1) {
                    $layout = json_decode($properties['contentblocks']['content'], true);

                    if ($layout) {
                        foreach ($layout as $layoutValue) {
                            $files = [];

                            foreach ($layoutValue['content'] as $contentValue) {
                                $mediamanager->cbCheckMMField($contentValue, $files);
                            }

                            foreach ($files as $file) {
                                $mediamanager->saveFileContent($file, $resource->get('id'));
                            }
                        }
                    }
                }
            }
        }

        break;
    case 'OnEmptyTrash':
        foreach ($ids as $id) {
            $modx->removeCollection('MediamanagerFilesContent', [
                'site_content_id' => $id
            ]);
        }

        break;
}