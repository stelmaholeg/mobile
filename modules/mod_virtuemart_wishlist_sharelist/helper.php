<?php

/*
* Module Helper
*
* @package VirtueMart
* Serjoka serjoka@gmail.com
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2012 MobyJam.net. All rights reserved.
* This program is distributed under the terms of the GNU General Public License
*/

defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');

VmConfig::loadConfig();

// Load the language file of com_virtuemart.

JFactory::getLanguage()->load('com_virtuemart');

if (!class_exists( 'calculationHelper' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'calculationh.php');

if (!class_exists( 'CurrencyDisplay' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'currencydisplay.php');

if (!class_exists( 'VirtueMartModelVendor' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'models'.DS.'vendor.php');

if (!class_exists( 'VmImage' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'image.php');

if (!class_exists( 'shopFunctionsF' )) require(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'shopfunctionsf.php');

if (!class_exists( 'calculationHelper' )) require(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'cart.php');

if (!class_exists( 'VirtueMartModelProduct' )){

   JLoader::import( 'product', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'models' );

}



class mod_virtuemart_favorite_sharelist {



	function getsharelist($num_lists) {
		
		$list  = "SELECT sh.user_id, sh.share_date, sh.share_title, sh.isWishList, u.name, u.id ";
		$list .= "FROM #__virtuemart_favorites_sh sh, #__users u WHERE ";
		$q = "sh.user_id = u.id AND sh.share_date > '1900-01-01' ";
		$q .= "ORDER BY sh.share_date DESC";
		$list .= $q . " LIMIT 0,".$num_lists;
		
		$db =& JFactory::getDBO();
		$db->setQuery($list);
		$result = $db->loadObjectList();
		
		return $result;
     }

}

