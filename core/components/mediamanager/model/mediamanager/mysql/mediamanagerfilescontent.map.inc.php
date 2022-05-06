<?php
/**
 * @package mediamanager
 */
$xpdo_meta_map['MediamanagerFilesContent']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files_content',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'mediamanager_files_id' => 0,
    'site_content_id' => 0,
    'site_tmplvars_id' => 0,
    'is_tmplvar' => 0,
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
    'site_content_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'site_tmplvars_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'is_tmplvar' => 
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
    'mediamanager_files_id' => 
    array (
      'alias' => 'mediamanager_files_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'mediamanager_files_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'site_content_id' => 
    array (
      'alias' => 'site_content_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'site_content_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'site_tmplvars_id' => 
    array (
      'alias' => 'site_tmplvars_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'site_tmplvars_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'is_tmplvar' => 
    array (
      'alias' => 'is_tmplvar',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'is_tmplvar' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
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
    'modResource' => 
    array (
      'class' => 'modResource',
      'local' => 'site_content_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'TemplateVariable' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'site_tmplvars_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
