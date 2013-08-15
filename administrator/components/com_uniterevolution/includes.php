<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @version 1.0.0
 * @author UniteCMS.net
 * @copyright (C) 2012- Unite CMS
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$currentDir = dirname(__FILE__)."/";

require_once $currentDir."helpers/globals.class.php";
require_once $currentDir."helpers/helper.class.php";
require_once $currentDir."helpers/helper_operations.class.php";
require_once $currentDir."helpers/output.class.php";
require_once $currentDir."helpers/actions.class.php";
require_once $currentDir."unitejoomla/includes.php";

//init the globals
GlobalsUniteRev::init();
UniteFunctionJoomlaRev::$componentName = GlobalsUniteRev::COMPONENT_NAME;


?>