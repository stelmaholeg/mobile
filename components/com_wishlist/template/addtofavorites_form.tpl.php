<?php 
/**
 * Favorites Template Page for Favorites Component
 * 
 * @package    Favorites & Wishlist
 * @subpackage com_wishlist
 * @license  GNU/GPL v2
 * @copyright Copyright (C) 2010 MobyJam.net. All rights reserved.
 * This program is distributed under the terms of the GNU General Public License
 *
 */
		//Load the com_favorite language file
		$language =& JFactory::getLanguage();
		$language_tag = $language->getTag();
		JFactory::getLanguage()->load('com_wishlist', JPATH_SITE, $language_tag, true);
		
		//Loading Layout Options
		$params = &JComponentHelper::getParams( 'com_wishlist' );
		$qty_enabled = $params->get( 'tmpl_qty_enabled' );
		/* FAVORITES & WISHLIST ENTRY */
		$db =& JFactory::getDBO();
		$view = JRequest::getString('view',  "");
		$itemid = JRequest::getInt('Itemid',  1);
		$user =& JFactory::getUser();
		$user_id = $user->id;
		if ($view == "category")
		{
			$product_id = $product->virtuemart_product_id;
			$category_id = $product->virtuemart_category_id;
		} else if ($view == "productdetails") {
			$product_id = $this->product->virtuemart_product_id;
			$category_id = $this->product->virtuemart_category_id;
		}
		$favorite_id = JRequest::getInt('favorite_id',  1);
		$quantity = JRequest::getInt('quantity',  1);
		$mode = JRequest::getString('mode',  "null");
		$q = "SELECT COUNT(*) FROM #__virtuemart_favorites WHERE user_id =".$user_id." AND product_id=".$product_id;
		$db->setQuery($q);
		$result = $db->loadResult();
		$url_favlist=JRoute::_('index.php?option=com_wishlist&view=favoriteslist&Itemid='.$itemid);
		if ($result == 0) {
			if ($product_id == $favorite_id && $mode=="fav_add" && $user_id > 0) {
				$Sql = "INSERT INTO #__virtuemart_favorites SET product_id='$product_id', product_qty='$quantity', user_id='$user_id', fav_date=NOW()";
				$db->setQuery($Sql);
				$db->query();
				$addtofavorites = '<a href="'.$url_favlist.'">'.JText::_('VM_FAVORITE_ADDED').'</a>';
			} else {
				$url_fav = JRoute::_('index.php?option=com_virtuemart&view='.$view.'&virtuemart_product_id='.$product_id.'&virtuemart_category_id='.$category_id.'&Itemid='.$itemid.'&mode=fav_add&favorite_id='.$product_id);
				//Loading the Component Stylesheet
				JHTML::stylesheet("template.css", "components/com_wishlist/");
				if ( $user_id == 0 ) {
					$addtofavorites = '<b>'. JText::_('VM_FAVORITE_NOLOGIN') .'</b>';
				}
				$redirectUrl = $url_fav;
				$redirectUrl = urlencode(base64_encode($redirectUrl));
				$redirectUrl = '&return='.$redirectUrl;
				$joomlaLoginUrl = 'index.php?option=com_users&view=login';
				$finalUrl = $joomlaLoginUrl . $redirectUrl;
				if ( $user_id > 0 ) {
					$addtofavorites = '<a class="show-login-form" href="'.$finalUrl.'" title="Login"><input style="cursor:pointer;" type="submit" class="addtofav_button" value="'.JText::_('VM_ADD_TO_FAVORITES').'&nbsp;" name="addtofavorites" title="'.JText::_('VM_ADD_TO_FAVORITES').'" /></a>';
				} else {
					$addtofavorites = '<a class="show-login-form" href="'.$finalUrl.'" title="Login"><input style="cursor:pointer;" type="submit" class="addtofav_button" value="Добавить в лист желаний&nbsp;" name="addtofavorites" title="'.JText::_('VM_ADD_TO_FAVORITES').'" /></a>';
				}
				if ( $user_id > 0 ) {
					$addtofavorites = '<div class="addtofavorites"><form class="addtofavs" method="post" action="'.$url_fav.'" name="addtofavorites" id="addtofavorites_'.$product_id.'">';
					if ($qty_enabled) {
						$addtofavorites .= '<div style="float:left"><input id="quantity_'.$product_id.'" class="quantity-input" size="1" name="quantity" value="1" /></div>';
					}
					$addtofavorites .= '<input type="submit" class="addtofav_button" value="'.JText::_('VM_ADD_TO_FAVORITES').'" name="addtofavorites" title="'.JText::_('VM_ADD_TO_FAVORITES').'&nbsp;" /><input type="hidden" name="favorite_id" value="'.$product_id.'" /><input type="hidden" name="user_id" value="'.$my->id.'" /><input type="hidden" name="mode" value="fav_add" /></form></div>';
				}
			}
		}
		if ($result > 0 ){ 
			$addtofavorites = '<a href="'.$url_favlist.'">'.JText::_('VM_FAVORITE_EXIST').'</a>';
		}
		echo $addtofavorites;
?>