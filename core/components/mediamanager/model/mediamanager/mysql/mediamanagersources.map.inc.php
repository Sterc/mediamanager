<?php
$xpdo_meta_map['MediamanagerSources']= array (
    'package' => 'mediamanager',
    'version' => NULL,
    'table' => 'mediamanager_sources',
    'extends' => 'xPDOSimpleObject',
    'fields' =>
        array (
            'name' => '',
            'is_deleted' => 0,
            'is_all' => 0,
            'is_main' => 0,
        ),
    'fieldMeta' =>
        array (
            'name' =>
                array (
                    'dbtype' => 'varchar',
                    'precision' => '50',
                    'phptype' => 'string',
                    'null' => false,
                    'default' => '',
                ),
            'is_deleted' =>
                array (
                    'dbtype' => 'tinyint',
                    'precision' => '1',
                    'attributes' => 'unsigned',
                    'phptype' => 'boolean',
                    'null' => false,
                    'default' => 0,
                    'index' => 'index',
                ),
            'is_all' =>
                array (
                    'dbtype' => 'tinyint',
                    'precision' => '1',
                    'attributes' => 'unsigned',
                    'phptype' => 'boolean',
                    'null' => false,
                    'default' => 0,
                    'index' => 'index',
                ),
            'is_main' =>
                array (
                    'dbtype' => 'tinyint',
                    'precision' => '1',
                    'attributes' => 'unsigned',
                    'phptype' => 'boolean',
                    'null' => false,
                    'default' => 0,
                    'index' => 'index',
                ),
        ),
    'composites' =>
        array (
            'CategoriesExcludes' =>
                array (
                    'class' => 'MediamanagerCategoriesExcludes',
                    'local' => 'id',
                    'foreign' => 'media_sources_id',
                    'cardinality' => 'many',
                    'owner' => 'local',
                ),
        ),
);