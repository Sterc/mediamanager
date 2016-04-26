<?php
$xpdo_meta_map['MediamanagerFilesContent']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files_content',
  'extends' => 'xPDOSimpleObject',
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
