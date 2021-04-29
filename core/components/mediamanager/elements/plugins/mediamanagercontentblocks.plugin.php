<?php
/**
 * @var modX $modx
 * @var ContentBlocks $contentBlocks
 * @var array $scriptProperties
 */
if ($modx->event->name == 'ContentBlocks_RegisterInputs') {
    // Load your own class. No need to require cbBaseInput, that's already loaded.
    $path = $modx->getOption('mediamanager.core_path', null, MODX_CORE_PATH . 'components/mediamanager/');
    
    require_once $path . 'elements/inputs/cb_mediamanager_input.class.php';
    require_once $path . 'elements/inputs/cb_mediamanager_gallery_input.class.php';
    require_once $path . 'elements/inputs/cb_mediamanager_image_input.class.php';
    
    // Create an instance of your input type, passing the $contentBlocks var
    $cbMMInput          = new cbMediaManagerInput($contentBlocks);
    $cbMMImageInput     = new cbMediaManagerImageInput($contentBlocks);
    $cbMMGalleryInput   = new cbMediaManagerGalleryInput($contentBlocks);
    
    // Pass back your input reference as key, and the instance as value
    $modx->event->output([
        'cb_mediamanager_input'         => $cbMMInput,
        'cb_mediamanager_image_input'   => $cbMMImageInput,
        'cb_mediamanager_gallery_input' => $cbMMGalleryInput
    ]);
}