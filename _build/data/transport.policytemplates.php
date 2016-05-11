<?php
/**
 * Default MediaManager Access Policy Templates
 *
 * @package mediamanager
 * @subpackage build
 */
$templates = array();
/* administrator template/policy */
$templates['1']= $modx->newObject('modAccessPolicyTemplate');
$templates['1']->fromArray(array(
    'id' => 1,
    'name' => 'MediaManagerTemplate',
    'description' => 'Access Policy Template for the Media Manager.',
    'lexicon' => 'mediamanager',
    'template_group' => 1,
));
$permissions = include dirname(__FILE__).'/mediamanagertemplate.permissions.php';
if (is_array($permissions)) {
    $templates['1']->addMany($permissions);
} else { 
	$modx->log(modX::LOG_LEVEL_ERROR,'Could not load Quip Moderator Policy Template.');
}
return $templates;