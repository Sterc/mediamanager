<?php
$xpdo_meta_map['MediamanagerFilesTags']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_files_tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'mediamanager_files_id' => 0,
    'mediamanager_tags_id' => 0,
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
    'mediamanager_tags_id' => 
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
    'File' => 
    array (
      'class' => 'MediamanagerFiles',
      'local' => 'mediamanager_files_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Tag' => 
    array (
      'class' => 'MediamanageMediamanagerTagsrFiles',
      'local' => 'mediamanager_tags_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
