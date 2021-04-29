<?php
/**
 * The default Permission scheme for the MediaManagerTemplate ACL Policy Template.
 *
 * @package mediamanager
 * @subpackage build
 */

$permissions    = [];
$permissions[]  = $modx->newObject('modAccessPermission', [
    'name'          => 'mediamanager_admin',
    'description'   => 'mediamanager.permissions.admin',
    'value'         => true
]);