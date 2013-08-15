<?php
/**
 * @package    Favorites & Wishlist
 * @subpackage com_wishlist
 * @license  GNU/GPL v2
 * @copyright Copyright (C) 2010 MobyJam.net. All rights reserved.
 * This program is distributed under the terms of the GNU General Public License
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');


// define default controller & view if you need routing...
/*
if(!JRequest::getWord('controller')){
	JRequest::setVar( 'view', '***' ); // insert here!! 
}
*/

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

// Create the controller
$classname	= 'FavoritesController'.$controller;
$controller = new $classname();

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>
