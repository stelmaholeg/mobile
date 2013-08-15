<?php
/**
* description: Virtuemart Add to Favourite Module
* Serjoka serjoka@gmail.com
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2012 2Kweb Solutions. All rights reserved.
* This program is distributed under the terms of the GNU General Public License
*/

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

// change the number of items to display
$num_prodstat = $params->get ('num_prodstat', 10);
//change statistic settings
$prodlike_enabled = $params->get ('prodlike_enabled', 1);
$prodstat_enabled = $params->get ('prodstat_enabled', 1);
$favstat_enabled = $params->get ('favstat_enabled', 1);
$sharestat_enabled = $params->get ('sharestat_enabled', 1);
$wishstat_enabled = $params->get ('wishstat_enabled', 1);

$mainframe = Jfactory::getApplication();

$cache	= &JFactory::getCache('mod_virtuemart_wishlist_stats', 'output');

$key = 'favorites'.$user->id.'.'.$prodlike_enabled.'.'.$prodstat_enabled.'.'.$favstat_enabled.'.'.$sharestat_enabled.'.'.$wishstat_enabled;

$cache = JFactory::getCache('mod_menu', '');

if (!($output = $cache->get($key))) {

	ob_start();

	// Try to load the data from cache.


	/* Load  VM function */

	if (!class_exists( 'mod_virtuemart_wishlist_stats' )) require('helper.php');

//Get current user object
$user =& JFactory::getUser();

	/* load the template */

	require(JModuleHelper::getLayoutPath('mod_virtuemart_wishlist_stats'));

	$output = ob_get_clean();

	$cache->store($output, $key);

}

echo $output;

?>
<!--Stats End-->
