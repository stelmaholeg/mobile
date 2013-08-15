<?php 
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die;
abstract class modIceVmFilterHelper {
	
	/**
	 * get list articles
	 */
	public static function getListTypes( $params ){

        $typeIds = $params->get('producttypes','');
        if( !is_array($typeIds) ){
            $typeIds = array( $typeIds );
        }
        $db = new ps_DB;
        $query  = " SELECT * FROM `#__{vm}_product_type_parameter`"
                . " WHERE `product_type_id` IN ('".implode("','", $typeIds)."')"
                . " ORDER BY parameter_list_order ";
       	$db->setQuery( $query );
        $data = $db->loadObjectList();

        return $data;
    }
    
   /**
    * 
    */
   public static function getTextAbove( $params ){
       $text = $params->get('text_above','Select From %CAT%');
        if(!preg_match("/%CAT%/", $text)){
            return $text;
        }
        return str_replace( "%CAT%", '<span class="ice-catname">'
                . modIceVmFilterHelper::getCategoryName(JRequest::getVar('category_id'))
                .'</span>', $text );

   }

   /**
    *  
    *
    */
   public static function getCategoryName( $category_id=0 ){
        $db = new ps_DB;
        $query = "SELECT * FROM `#__{vm}_category` WHERE `category_id` = ".(int)$category_id;
		$s=$db->setQuery( $query );
        $data = $db->loadObject($s);
           return $data->category_name;
    }


    /**
     *
     * 
     */
    public static function getFieldsInfor( $field, $type ){ 
        
         $db = new ps_DB;
        // build sql query
        $query = " SELECT DISTINCT `#__{vm}_product_type_".$type->product_type_id."`.`product_id` "
               . " FROM `#__{vm}_product_type_".$type->product_type_id."` , `#__{vm}_product`, `#__{vm}_product_category_xref`  ";

        if( $type->parameter_type != "V" && $type->parameter_multiselect == "Y" ) {
            $url[$field] = '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'_comp=in';
            $params[$field]='&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'[]='.$field;;
            $query .= "WHERE `".$type->parameter_name."` = '$field' ";
        }

        elseif ( $type->parameter_type == "V" && $type->parameter_multiselect != "Y" ) {
            $url[$field] 		= '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'_comp=find_in_set';
			$params[$field] 	= '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'='.$field;

            $query .= "WHERE FIND_IN_SET('$field',REPLACE(`".$type->parameter_name."`,';',',')) ";
        }
        elseif ( $type->parameter_type == "V" && $type->parameter_multiselect == "Y" ) {
            $tmp_url 		= "product_type_".$type->product_type_id."_".$type->parameter_name;
			$filter_comp	= JRequest::getVar($tmp,'');

            if ( !empty($filter_comp) ) { $comp = $filter_comp; } else { $comp = 'find_in_set_all'; }
			$url[$field] 			= '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'_comp='.$comp.'';
			$params[$field]	= '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'[]='.$field;

            $query .= "WHERE FIND_IN_SET('$field',REPLACE(`".$type->parameter_name."`,';',',')) ";
        }

        else {
            $url[$field] 	 = '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'_comp=texteq';
             $params[$field] = '&product_type_'.$type->product_type_id.'_'.$type->parameter_name.'='.$field;
            $query .= "WHERE `".$type->parameter_name."` = '$field' ";
        }

        // filter with category
        $category_id = (int)JRequest::getVar( 'category_id', 0 );
       	$query .= ' AND ( (`#__{vm}_product`.`product_publish` = "Y" AND `#__{vm}_product`.`product_id` = `#__{vm}_product_category_xref`.`product_id`) '.
						'AND (`#__{vm}_product_category_xref`.`product_id` = `#__{vm}_product_type_'.$type->product_type_id.'`.`product_id`) ';

        if( $category_id ){
            $categories = ps_product_category::get_child_list( $category_id );
            $cats[]=$category_id;
            foreach( $categories as $cat ){
                $cats[] = $cat["category_id"];
            }
            $query .= " AND  (`#__{vm}_product_category_xref`.`category_id` IN ('".implode("','", $cats)."') ))";
        }


        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
            $query .= ' AND `#__{vm}_product`.`product_in_stock` > 0';
        }

        $db->setQuery($query);

        $output = array();
        $output['count'] = count($db->loadObjectList());
        $output['url'] = $url[$field];
        $output['params'] = $params[$field];

     //    echo '<pre>'.print_r($output,1); die;
        return $output;
    }

    /**
     *
     * 
     */
    public static function countProductsByParams( $field, $type ){
        $db = new ps_DB;
        // build sql query
        $query = " SELECT DISTINCT `#__{vm}_product_type_".$type->product_type_id."`.`product_id` "
               . " FROM `#__{vm}_product_type_".$type->product_type_id."` , `#__{vm}_product`, `#__{vm}_product_category_xref`  ";

        if( $type->parameter_type != "V" && $type->parameter_multiselect == "Y" ) {
            $query .= "WHERE `".$type->parameter_name."` = '$field' ";
        }

        elseif ( $type->parameter_type == "V" && $type->parameter_multiselect != "Y" ) {
            $query .= "WHERE FIND_IN_SET('$field',REPLACE(`".$type->parameter_name."`,';',',')) ";
        }
        elseif ( $type->parameter_type == "V" && $type->parameter_multiselect == "Y" ) {
           $query .= "WHERE FIND_IN_SET('$field',REPLACE(`".$type->parameter_name."`,';',',')) ";
        }

        else {
            $query .= "WHERE `".$type->parameter_name."` = '$field' ";
        }

        // filter with category 
        $category_id = (int)JRequest::getVar( 'category_id', 0 );
       	$query .= ' AND ( (`#__{vm}_product`.`product_publish` = "Y" AND `#__{vm}_product`.`product_id` = `#__{vm}_product_category_xref`.`product_id`) '.
						'AND (`#__{vm}_product_category_xref`.`product_id` = `#__{vm}_product_type_'.$type->product_type_id.'`.`product_id`) ';

        if( $category_id ){
            $categories = ps_product_category::get_child_list( $category_id );
            $cats[]=$category_id;
            foreach( $categories as $cat ){
                $cats[] = $cat["category_id"];
            }
            $query .= " AND  (`#__{vm}_product_category_xref`.`category_id` IN ('".implode("','", $cats)."') ))";
        }

  
        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
            $query .= ' AND `#__{vm}_product`.`product_in_stock` > 0';
        }
        $db->setQuery($query);
        return count($db->loadObjectList());
    }

    /**
	 * load css - javascript file.
	 *
	 * @param JParameter $params;
	 * @param JModule $module
	 * @return void.
	 */
	public static function loadMediaFiles( $params, $module, $theme='' ){

		global $mainframe;
		//load style of module
		if(file_exists(JPATH_BASE.DS.'templates/'.$mainframe->getTemplate().'/css/'.$module->module.'.css')){
			JHTML::stylesheet(  $module->module.'.css','templates/'.$mainframe->getTemplate().'/css/' );
		}
		// load style of theme follow the setting
		if( $theme && $theme != -1 ){
			$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'css'.DS.$module->module.'_'.$theme.'.css';
			if( file_exists($tPath) ){
				JHTML::stylesheet( $module->module.'_'.$theme.'.css','templates/'.$mainframe->getTemplate().'/css/');
			} else {
				JHTML::stylesheet('style.css','modules/'.$module->module.'/themes/'.$theme.'/assets/');
			}
		} else {
           JHTML::stylesheet( 'style.css','modules/'.$module->module.'/assets/' );
		}
	//	JHTML::script( 'script.js','modules/'.$module->module.'/assets/' );

	}

}
?>
