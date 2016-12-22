<?php
$xpdo_meta_map['MediamanagerFiles']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'media_sources_id' => 0,
    'name' => '',
    'path' => '',
    'version' => 0,
    'file_type' => '',
    'file_size' => 0,
    'file_dimensions' => '',
    'file_hash' => '',
    'upload_date' => 'CURRENT_TIMESTAMP',
    'uploaded_by' => 0,
    'edited_on' => '0',
    'edited_by' => 0,
    'is_archived' => 0,
    'archive_date' => NULL,
    'archive_path' => NULL,
  ),
  'fieldMeta' => 
  array (
    'media_sources_id' => 
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
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
    'file_type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
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
    'upload_date' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
      'index' => 'index',
    ),
    'uploaded_by' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'edited_on' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => '0',
      'index' => 'index',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
    'edited_by' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'is_archived' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'archive_date' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => true,
      'default' => NULL,
    ),
    'archive_path' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => NULL,
    ),
  ),
  'composites' => 
  array (
    'Categories' => 
    array (
      'class' => 'MediamanagerFilesCategories',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Tags' => 
    array (
      'class' => 'MediamanagerFilesTags',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Content' => 
    array (
      'class' => 'MediamanagerFilesContent',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Relations' => 
    array (
      'class' => 'MediamanagerFilesRelations',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Relations2' => 
    array (
      'class' => 'MediamanagerFilesRelations',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id_relation',
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
    'Meta' => 
    array (
      'class' => 'MediamanagerFilesMeta',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
