<?php
/**
 * The default Permission scheme for the MediaManagerTemplate ACL Policy Template.
 *
 * @package mediamanager
 * @subpackage build
 */

$permissions = array();
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'mediamanager_admin',
    'description' => 'mediamanager.permissions.admin',
    'value' => true,
));