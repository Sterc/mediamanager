<?php

if (!$object->xpdo) return true;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        /** @var modX $modx */
        $modx =& $object->xpdo;

        $menus = array(
            array(
                'text'        => 'mediamanager',
                'description' => 'mediamanager.desc',
                'action'      => 'home'
            ),
            array(
                'text'        => 'mediamanager.categories',
                'description' => 'mediamanager.categories.desc',
                'action'      => 'categories'
            ),
            array(
                'text'        => 'mediamanager.tags',
                'description' => 'mediamanager.tags.desc',
                'action'      => 'tags'
            )
        );

        foreach ($menus as $menu) {
            $menuItem = $modx->newObject('modMenu');
            $menuItem->fromArray(array(
                'text'        => $menu['text'],
                'parent'      => 'media',
                'description' => $menu['description'],
                'icon'        => '',
                'menuindex'   => 0,
                'params'      => '',
                'handler'     => '',
                'permissions' => 'file_manager',
                'namespace'   => 'mediamanager',
                'action'      => $menu['action']
            ), '', true, true);
            $menuItem->save();
        }

        break;
}

return true;
