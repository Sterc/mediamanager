<?php
$xpdo_meta_map['MediamanagerFilesVersions']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files_versions',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'mediamanager_files_id' => 0,
    'version' => 0,
    'path' => '',
    'file_name' => '',
    'file_size' => 0,
    'file_dimensions' => '',
    'file_hash' => '',
    'created' => 'CURRENT_TIMESTAMP',
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
    'version' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'path' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'file_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'file_size' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'file_dimensions' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '15',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'file_hash' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'created' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
      'index' => 'index',
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
