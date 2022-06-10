<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALLY GENERATED, NO CHANGES WILL APPLY
 *
 * @package mediamanager
 * @subpackage build
 *
 * @var mixed $object
 * @var modX $modx
 * @var array $options
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('mediamanager.core_path', null, $modx->getOption('core_path') . 'components/mediamanager/') . 'model/';
            
            $modx->addPackage('mediamanager', $modelPath, null);


            $manager = $modx->getManager();

            $manager->createObjectContainer('MediamanagerCategories');
            $manager->createObjectContainer('MediamanagerTags');
            $manager->createObjectContainer('MediamanagerFiles');
            $manager->createObjectContainer('MediamanagerDownloads');
            $manager->createObjectContainer('MediamanagerFilesCategories');
            $manager->createObjectContainer('MediamanagerFilesLicense');
            $manager->createObjectContainer('MediamanagerFilesLicenseFile');
            $manager->createObjectContainer('MediamanagerFilesTags');
            $manager->createObjectContainer('MediamanagerFilesContent');
            $manager->createObjectContainer('MediamanagerFilesRelations');
            $manager->createObjectContainer('MediamanagerFilesVersions');
            $manager->createObjectContainer('MediamanagerFilesMeta');

            break;
    }
}

return true;