<?php
/**
* description: Virtuemart Wishlist Module
* Serjoka serjoka@gmail.com
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2012 2Kweb Solutions. All rights reserved.
* This program is distributed under the terms of the GNU General Public License
*/

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

/* Setting */
// change the number of items to display
$share_enabled = $params->get ('share_enabled', 1);
$num_favorites = $params->get ('num_favorites', 10);

$mainframe = Jfactory::getApplication();

$cache	= &JFactory::getCache('mod_virtuemart_wishlist_products', 'output');

$key = 'favorites'.$user->id.'.'.$share_enabled.'.'.$num_favorite;

$cache = JFactory::getCache('mod_menu', '');

if (!($output = $cache->get($key))) {

	ob_start();

	// Try to load the data from cache.


	/* Load  VM function */

	if (!class_exists( 'mod_virtuemart_wishlist_products' )) require('helper.php');

//Get current user object
$user =& JFactory::getUser();

	/* load the template */

	require(JModuleHelper::getLayoutPath('mod_virtuemart_wishlist_products'));

	$output = ob_get_clean();

	$cache->store($output, $key);

}

echo $output;

?>
<!--Favorites End-->
