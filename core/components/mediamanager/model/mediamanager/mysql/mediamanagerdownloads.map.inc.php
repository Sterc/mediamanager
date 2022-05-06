<?php
/**
 * @package mediamanager
 */
$xpdo_meta_map['MediamanagerDownloads']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_downloads',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'created_on' => 'CURRENT_TIMESTAMP',
    'expires_on' => NULL,
    'path' => '',
    'hash' => '',
    'is_deleted' => 0,
  ),
  'fieldMeta' => 
  array (
    'created_on' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
    'expires_on' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
    ),
    'path' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'hash' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
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
  ),
  'indexes' => 
  array (
    'is_deleted' => 
    array (
      'alias' => 'is_deleted',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'is_deleted' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
