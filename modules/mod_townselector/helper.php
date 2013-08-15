<?php
/**
 * @package	Joomla.Tutorials
 * @subpackage	Module
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license	License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;
class modTownSelectorHelper
{
    public static function getGreeting() {
        return "Message from the helper";
    }

    public static function getCurrentCat(){
        return JRequest::getInt('virtuemart_category_id');
    }

    public static function getCurrentUsers(){
        $db = JFactory::getDbo();
        $catid = JRequest::getInt('virtuemart_category_id');
        $db->setQuery('SELECT virtuemart_product_id FROM #__virtuemart_product_categories WHERE virtuemart_category_id = ' . $catid);
        $test = $db->loadObjectList();
        $products = array();
        foreach($test as $key=>$id){
             $products[] = $id->virtuemart_product_id;
        }
        $where = "(".implode(',',$products).")";
        $db->setQuery('SELECT c.virtuemart_custom_id, c.custom_title, cf.virtuemart_customfield_id,
                              cf.virtuemart_product_id, cf.custom_value, c.is_on_filterlist,
                              c.filter_order_num
                         FROM xdno1_virtuemart_customs c
                        INNER JOIN xdno1_virtuemart_product_customfields cf
                           ON c.virtuemart_custom_id = cf.virtuemart_custom_id
                        WHERE cf.virtuemart_product_id in ' . $where .
                       'ORDER BY custom_title');
        $test[0] = $db->loadObjectList();
        $test[1] = $catid;
        return $test;
    }
}
