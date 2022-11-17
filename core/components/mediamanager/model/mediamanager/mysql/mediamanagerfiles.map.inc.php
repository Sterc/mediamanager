<?php
/**
 * @package mediamanager
 */
$xpdo_meta_map['MediamanagerFiles']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
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
    'upload_date' => NULL,
    'uploaded_by' => 0,
    'edited_on' => 'CURRENT_TIMESTAMP',
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
      'null' => true,
      'default' => NULL,
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
      'default' => 'CURRENT_TIMESTAMP',
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
  'indexes' => 
  array (
    'media_sources_id' => 
    array (
      'alias' => 'media_sources_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'media_sources_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'version' => 
    array (
      'alias' => 'version',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'version' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'file_type' => 
    array (
      'alias' => 'file_type',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'file_type' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'file_hash' => 
    array (
      'alias' => 'file_hash',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'file_hash' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'upload_date' => 
    array (
      'alias' => 'upload_date',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'upload_date' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'uploaded_by' => 
    array (
      'alias' => 'uploaded_by',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'uploaded_by' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'edited_on' => 
    array (
      'alias' => 'edited_on',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'edited_on' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'edited_by' => 
    array (
      'alias' => 'edited_by',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'edited_by' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'is_archived' => 
    array (
      'alias' => 'is_archived',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'is_archived' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'FileLicense' => 
    array (
      'class' => 'MediamanagerFilesLicenseFile',
      'local' => 'id',
      'foreign' => 'mediamanager_files_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
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
      'class' => 'sources.modMediaSource',
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
