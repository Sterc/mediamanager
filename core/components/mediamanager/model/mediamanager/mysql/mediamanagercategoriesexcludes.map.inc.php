<?php
$xpdo_meta_map['MediamanagerCategoriesExcludes']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_categories_excludes',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'mediamanager_contexts_id' => 0,
    'mediamanager_categories_id' => 0,
  ),
  'fieldMeta' => 
  array (
    'mediamanager_contexts_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'mediamanager_categories_id' => 
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
    'Context' => 
    array (
      'class' => 'MediamanagerContexts',
      'local' => 'mediamanager_contexts_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Categories' => 
    array (
      'class' => 'MediamanagerCategories',
      'local' => 'mediamanager_categories_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
