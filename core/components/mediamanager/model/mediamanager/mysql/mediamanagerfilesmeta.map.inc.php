<?php
/**
 * @package mediamanager
 */
$xpdo_meta_map['MediamanagerFilesMeta']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files_meta',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'mediamanager_files_id' => 0,
    'meta_key' => '',
    'meta_value' => '',
  ),
  'fieldMeta' => 
  array (
    'mediamanager_files_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'meta_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'meta_value' => 
    array (
      'dbtype' => 'longtext',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'Files' => 
    array (
      'class' => 'MediamanagerFiles',
      'local' => 'mediamanager_files_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
