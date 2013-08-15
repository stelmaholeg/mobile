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



class mod_virtuemart_favorite_products {



	function getfavorites($user_id, $num_favorites) {
		
		// getting the language tag for virtuemart_products table
		$siteLang = JFactory::getLanguage()->getTag();
		$lang = strtolower(strtr($siteLang,'-','_'));
		
		$list  = "SELECT f.product_id, f.user_id, p.product_parent_id, pl.product_name, p.published, pc.virtuemart_product_id, c.virtuemart_category_id, c.category_layout ";
		$list .= "FROM #__virtuemart_favorites f, #__virtuemart_products p, #__virtuemart_products_".$lang." pl, #__virtuemart_product_categories pc, #__virtuemart_categories c WHERE ";
		$q = "f.user_id = " .$user_id. " AND ";
		$q .= "p.virtuemart_product_id = f.product_id AND ";
		$q .= "p.published ='1' AND ";
		$q .= "pc.virtuemart_product_id = IF (p.product_parent_id=0, p.virtuemart_product_id, p.product_parent_id) AND ";
		$q .= "pc.virtuemart_category_id = c.virtuemart_category_id AND ";
		$q .= "pl.virtuemart_product_id = p.virtuemart_product_id ";
		$q .= "GROUP BY p.virtuemart_product_id ";
		$q .= "ORDER BY pl.product_name ";
		$list .= $q . " LIMIT 0,".$num_favorites;
		
		$db =& JFactory::getDBO();
		$db->setQuery($list);
		$result = $db->loadObjectList();
		
		return $result;
     }

}

