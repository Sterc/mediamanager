<?php
$xpdo_meta_map['MediamanagerTags']= array (
  'package' => 'mediamanager',
  'version' => NULL,
  'table' => 'mediamanager_tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'mediamanager_contexts_id' => 0,
    'name' => '',
    'is_deleted' => 0,
  ),
  'fieldMeta' => 
  array (
    'mediamanager_contexts_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
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
  'composites' => 
  array (
    'Files' => 
    array (
      'class' => 'MediamanagerFilesTags',
      'local' => 'id',
      'foreign' => 'mediamanager_tags_id',
      'cardinality' => 'many',
      'owner' => 'local',
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
  ),
);
