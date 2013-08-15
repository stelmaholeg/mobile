<?php
/**
 *Blank module
 */

// no direct access
defined('_JEXEC') or die;

// Reqiured helper
require_once dirname(__FILE__).'/helper.php';

//$list = modCatalog::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_advertising', $params->get('layout', 'default'));
?>

 