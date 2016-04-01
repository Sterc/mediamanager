<?php
$xpdo_meta_map['MediamanagerFilesRelations']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files_relations',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'mediamanager_files_id' => 0,
    'mediamanager_files_id_relation' => 0,
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
    'mediamanager_files_id_relation' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
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
    'Files2' =>
    array (
      'class' => 'MediamanagerFiles',
      'local' => 'mediamanager_files_id_relation',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
