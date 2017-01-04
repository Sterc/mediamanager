<?php
/**
 * If package is being updated, sets the category media source ids to the default media source ID if not set already.
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modx->log(modX::LOG_LEVEL_INFO, '- Resolving default media source ID\'s for categories.');

            $defaultMediaSource = $modx->getOption('default_media_source', null, 0);
            $categories = $modx->getIterator('MediamanagerCategories');
            if ($categories) {
                foreach ($categories as $category) {
                    if ($category->get('media_sources_id') > 0) {
                        continue;
                    }

                    $category->set('media_sources_id', $defaultMediaSource);

                    if ($category->save()) {
                        $modx->log(
                            modX::LOG_LEVEL_INFO,
                            '-- Setting Media Source ID for category: ' . $category->get('name')
                        );
                    }
                }
            }
            break;
    }
}

return true;
