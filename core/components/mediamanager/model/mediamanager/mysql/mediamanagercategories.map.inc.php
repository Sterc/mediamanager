<?php
/**
 * @package mediamanager
 */
$xpdo_meta_map['MediamanagerCategories']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_categories',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'media_sources_id' => 0,
    'parent_id' => 0,
    'name' => '',
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'media_sources_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'parent_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'rank' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '6',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'Files' => 
    array (
      'class' => 'MediamanagerFilesCategories',
      'local' => 'id',
      'foreign' => 'mediamanager_categories_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'MediaSource' => 
    array (
      'class' => 'modMediaSource',
      'local' => 'media_sources_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
