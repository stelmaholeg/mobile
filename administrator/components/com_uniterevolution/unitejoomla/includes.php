<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


// No direct access.
defined('_JEXEC') or die;

	/**
	 * include the unitejoomla library
	 */
	$currentDir = dirname(__FILE__)."/";
	
	require_once $currentDir.'functions.class.php';
	require_once $currentDir.'functions_joomla.class.php';
	require_once $currentDir.'db.class.php';
	require_once $currentDir.'admintable.class.php';
	require_once $currentDir.'image_view.class.php';
	require_once $currentDir.'masterview.class.php';
	require_once $currentDir.'masterfield.class.php';
	require_once $currentDir.'controls.class.php';
	require_once $currentDir.'cssparser.class.php';
	require_once $currentDir.'toolbar.class.php';
	
?>