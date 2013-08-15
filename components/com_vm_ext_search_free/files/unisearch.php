<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class uniSearch extends vmAbstractObject {

    function __construct() {

    }

    function list_category($category_id = array(), $name = "category_id", $vid='', $prefix='') {
        echo "<select class=\"inpbox\" onchange=\"".$prefix."categoryChange()\" name=\"".$name."\">\n";
        echo "<option value=\"\">Выбрать категорию</option>\n";
        echo "<option value=\"\">Все категории</option>\n";
        $this->list_tree($category_id);
        echo "</select>\n";
        return True;
    }

    function list_tree($category_id = "", $cid = '0', $level = '0', $selected_categories = Array()) {

        $ps_vendor_id = $_SESSION["ps_vendor_id"];
        $db = new ps_DB;

        $level++;

        $q = "SELECT category_id, category_child_id,category_name FROM #__{vm}_category,#__{vm}_category_xref ";
        $q .= "WHERE #__{vm}_category_xref.category_parent_id='$cid' ";
        $q .= "AND #__{vm}_category.category_id=#__{vm}_category_xref.category_child_id ";
        $q .= "AND #__{vm}_category.vendor_id ='$ps_vendor_id' ";
        $q .= "AND #__{vm}_category.category_publish ='Y' ";
        $q .= "ORDER BY #__{vm}_category.list_order, #__{vm}_category.category_name ASC";
        $db->setQuery($q);
        $db->query();

        while ($db->next_record()) {
            $child_id = $db->f("category_child_id");
            if ($child_id != $cid) {
                $selected = ($child_id == $category_id[0]) ? "selected=\"selected\"" : "";
                if ($selected == "" && @$selected_categories[$child_id] == "1") {
                    $selected = "selected=\"selected\"";
                }
                echo "<option $selected value=\"$child_id\">\n";
            }
            for ($i = 0;$i < $level;$i++) {
                echo "–";
            }
            echo "|$level|";
            echo "&nbsp;" . $db->f("category_name") . "</option>";
            $this->list_tree($category_id, $child_id, $level, $selected_categories);
        }
    }

    function getAllcidArray($cid = array()) {
        $sub = array();

        $db = new ps_DB;
        $q = "SELECT category_child_id FROM #__{vm}_category_xref WHERE ";
        if(is_array($cid)) {
            $q .= "category_parent_id IN (".implode(", ", $cid).") ";
        }
        else $q .= "category_parent_id=".$cid;

        $db->setQuery($q);
        $result = $db->loadObjectList();

        if (count($result) > 0) {
            $sub_tmp = array();
            foreach ($result as $db) {
                $sub_tmp[] = $db->category_child_id;
            }
            $sub = array_merge ($sub, $sub_tmp);
            $sub = array_merge ($sub, $this->getAllcidArray($sub_tmp));
        }
        return $sub;
    }

    function getAllcid($cid = array()) {
        $sub = $this->getAllcidArray($cid);
        if(is_array($cid)) {
            foreach ($cid as $db) {
                $sub[] = $db;
            }
        }
        $cat = implode(', ', $sub);
        return $cat;
    }

    function product_count($category_id) {

        $db = new ps_DB;

        $q = "SELECT count(#__{vm}_product.product_id) as num_rows from #__{vm}_product, ";
        $q .= "#__{vm}_product_category_xref, #__{vm}_category WHERE ";
        $q .= "#__{vm}_product_category_xref.category_id='$category_id' ";
        $q .= "AND #__{vm}_category.category_id=#__{vm}_product_category_xref.category_id ";
        $q .= "AND #__{vm}_product.product_id=#__{vm}_product_category_xref.product_id ";
        $q .= " AND product_publish='Y'";
//        if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
//            $q .= " AND product_in_stock > 0 ";
//        }
        $db->setQuery($q);
        $num_rows = $db->loadResult();
        return $num_rows;
    }

    function get_parent_category($cid) {

        $ps_vendor_id = $_SESSION["ps_vendor_id"];
        $db = new ps_DB;

        $level++;

        $q = "SELECT category_id,category_name FROM #__{vm}_category,#__{vm}_category_xref ";
        $q .= "WHERE (#__{vm}_category_xref.category_parent_id='$cid[0]' OR #__{vm}_category_xref.category_child_id='$cid[0]') ";
        $q .= "AND #__{vm}_category.category_id=#__{vm}_category_xref.category_child_id ";
        $q .= "AND #__{vm}_category.vendor_id ='$ps_vendor_id' ";
        $q .= "AND #__{vm}_category.category_publish ='Y' ";
        $q .= "ORDER BY #__{vm}_category.category_name ASC";
        $db->setQuery($q);
        $rows = $db->loadObjectList();

        $categories = array();
        $parent_category = array();
        $i = 0;
        foreach ($rows as $category) {
            if ($category->category_id == $cid[0]) {
                $parent_category['category_id'] = $category->category_id;
                $parent_category['category_name'] = $category->category_name;
            }
            else {
                $categories[$i]['category_id'] = $category->category_id;
                $categories[$i]['category_name'] = $category->category_name;
                $i++;
            }
        }
        return array ($parent_category, $categories);
    }


    function get_manufacturer($cid = array()) {
        $db = new ps_DB;
        if (isset($cid) && !empty($cid) && count($cid) > 0) $cids = $this->getAllcid($cid);

        $q = "SELECT distinct manufacturer.manufacturer_id, manufacturer.mf_name FROM ";
        if (!empty($cids)) $q .= " #__{vm}_product_category_xref as product_category_xref, ";
        $q .= " #__{vm}_product_mf_xref as mf_xref, ";
        $q .= " #__{vm}_manufacturer as manufacturer, ";
        $q .= " #__{vm}_product as product, ";
        $q .= " #__{vm}_product_price as product_price ";
        $q .= " WHERE ";
        $q .= " mf_xref.product_id = product_price.product_id ";
        $q .= " AND mf_xref.product_id = product.product_id ";
        $q .= " AND product.product_publish = 'Y' ";

        if (!empty($cids)) $q .= " AND mf_xref.product_id = product_category_xref.product_id AND product_category_xref.category_id IN (" .$cids. ") AND product.product_id = product_category_xref.product_id ";

        $q .= " AND manufacturer.manufacturer_id = mf_xref.manufacturer_id  ORDER BY manufacturer.mf_name";
        $db->setQuery($q);
        $rows = $db->loadObjectList();

        $i = 0;
        $lists = array();
        foreach ($rows as $row) {
            $lists[$i]->manufacturer_id = $row->manufacturer_id;
            $lists[$i]->mf_name = $row->mf_name;
            $i++;
        }
        return $lists;
    }

    function list_manufacturer($data, $mf_id, $vid = '', $prefix='') {
        print '<div class="label">Производители: </div>';
        print '<select class = "inpbox" name="mf_id[]" onchange="'.$prefix.'mfChangeMulti()">';
        print '<option value="">Выбрать производителя</option>';
        foreach ($data as $item) {
            $selected = '';
            if (!empty($mf_id) && in_array($item->manufacturer_id, $mf_id)) $selected = 'selected="selected"';
            print '<option value="'.$item->manufacturer_id.'" '.$selected.' >'.$item->mf_name.'</option>';
        }
        print '</select>';
    }

    function get_type($cid=array(), $mf_id=array()) {

        $db = new ps_DB;
        if (isset($cid) && !empty($cid) && count($cid) > 0) $cids = $this->getAllcid($cid);

        //определяем наличие выбранного производителя
        if (sizeof($mf_id) > 0 && !empty ($mf_id[0])) $mf = TRUE;
        else $mf = FALSE;

        $query = "SELECT distinct product_type.product_type_id, product_type.product_type_name ";
        $query .= "\n FROM ";
        $query .= "\n #__{vm}_product_type AS product_type, ";
        $query .= "\n #__{vm}_product AS product, ";

        if (!empty($cids)) $query .= "\n #__{vm}_product_category_xref AS product_category_xref, ";
        if ($mf)$query .= "\n #__{vm}_product_mf_xref AS product_mf_xref, ";
        $query .= "\n #__{vm}_product_product_type_xref AS product_type_xref ";
        $query .= "\n WHERE product.product_id = product_type_xref.product_id AND product_type_xref.product_type_id = product_type.product_type_id ";
        if (!empty($cids)) $query .= "\n AND product_category_xref.product_id = product.product_id " ;
        if (!empty($cids)) $query .= " AND  product_category_xref.category_id IN (" .$cids. ") ";
        if ($mf) $query .= "\n AND product_mf_xref.manufacturer_id IN (". implode(", ", $mf_id) .") AND product_type_xref.product_id = product_mf_xref.product_id ";
        if (!empty($cids) && $mf) $query .= "\n AND product_category_xref.product_id = product_mf_xref.product_id ";
        $query .= "ORDER BY product_type.product_type_name ASC";

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $i = 0;
        $lists = array();

        foreach ($rows as $row) {
            $lists[$i]->product_type_id = $row->product_type_id;
            $lists[$i]->product_type_name = $row->product_type_name;
            $i++;
        }

        return $lists;
        if (empty($rows)) {
            echo 'Error.';
            return;
        }
    }

    function list_type ($types, $type_id, $vid='', $prefix= '') {
        if (count($types) == 0) {
            print '<script language="javascript" type="text/javascript" >';
            print 'jQuery("#'.$prefix.'harakt_div").html("");';
            print '</script>';
        }
        elseif (count($types) == 1) {
            print '<input type="hidden" name="product_type_id[]" value="'.$types[0]->product_type_id.'" />';
        }
        else {
            print '<script language="javascript" type="text/javascript" >';
            print 'jQuery("#'.$prefix.'harakt_div").html("");';
            print '</script>';
            print '<div class="label">Типы товаров: </div>';
            print '<select class="inpbox" name="product_type_id[]" onchange="'.$prefix.'typeChange()">';
            print '<option value="0">Выбрать тип</option>';
            foreach ($types  as $item) {
                $selected = '';
                if(!empty($type_id) && in_array($item->product_type_id, $type_id)) $selected = 'selected="selected"';
                print '<option value="'.$item->product_type_id.'" '.$selected.'>'.$item->product_type_name.'</option>';
            }
            print '</select>';
        }
    }

    function get_harakt($product_type_id, $cid=0, $mf_id=array(), $conf=array(), $prefix='') {
        if($conf['debug_show'] == 1) {
            print 'product_type_id = ';
            print_r($product_type_id);
            print '<br>cid = ';
            print_r($cid);
            print '<br>mf_id = ';
            print_r($mf_id);
            print '<br>';
        }
        //определяем наличие выбранного производителя
        if (sizeof($mf_id) > 0 && !empty ($mf_id[0])) $mf = TRUE;
        else $mf = FALSE;

        print '<div class="label">Характеристики товаров: </div>';
        $db = new ps_DB;
        //если тип передан не массивом, значит его нет, прекращаем функцию
        if (!is_array($product_type_id)) return;
        //проверяем опубликован-ли тип
        $q = "SELECT * FROM #__{vm}_product_type ";
        $q .= "WHERE product_type_id IN (".implode(", ", $product_type_id).") ";
        $q .= "AND product_type_publish='Y' LIMIT 1";
        $db->query($q);

        if ($db->num_rows() > 0) {

            if (isset($cid) && !empty($cid) && count($cid) > 0) $cids = $this->getAllcid($cid);
            //запрашиваем иды товаров, соответствующие выбранным категориям, производителям из таблицы текущего типа
            $query = "SELECT distinct product_type.product_id FROM ";
            if (!empty($cids))      $query .= "\n #__{vm}_product_category_xref AS product_category_xref, ";
            if ($mf)  $query .= "\n #__{vm}_product_mf_xref AS product_mf_xref, ";
            $query .= "\n `#__{vm}_product_type_" . $product_type_id[0] . "` AS product_type ";
            if (!empty($cids)) {
                $query .= "\n WHERE  product_category_xref.category_id IN (" .$cids. ")  ";
                $query .= "\n AND product_type.product_id = product_category_xref.product_id ";
            }
            if ($mf && empty($cids)) $query .= "\n WHERE ";
            if ($mf && !empty($cids)) $query .= "\n AND ";
            if ($mf)  $query .= "\n product_mf_xref.manufacturer_id IN (" . implode(", ", $mf_id) . ") AND product_mf_xref.product_id = product_type.product_id ";
            if ($mf && !empty($cids)) $query .= "\n AND product_category_xref.product_id = product_mf_xref.product_id ";

            $db->setQuery($query);
            $product_ids = $db->loadResultArray();

            //запрашиваем названия свойств
            $q = "SELECT `parameter_name`, `parameter_label`, `parameter_type`, ";
            $q .= "`parameter_values`, `parameter_multiselect`, `parameter_unit` ";
            $q .= "FROM #__{vm}_product_type_parameter ";
            $q .= "WHERE product_type_id IN (".implode(", ", $product_type_id).") ";
            $q .= "ORDER BY parameter_list_order";
            $db->setQuery($q);
            $patams = $db->loadObjectList();

            $param_type = array ();
            $selected_params = array ();
            $parameter_multiselect = array ();
            $i = 0;
            $disabled = '';
            $q1 = '';
            foreach  ($patams as $patam) {
                $parameter_values = $patam->parameter_values;
                if (!empty($parameter_values)) {
                    $i++;
                    $param_name = $patam->parameter_name;
                    $param_id = $product_type_id[0];
                    $parameter_label = $patam->parameter_label;
                    $item_name = "product_type_" .$product_type_id[0]. "_" . $param_name;

                    $parameter_type = $patam->parameter_type;
                    $param_type[$i] = $parameter_type;
                    $parameter_multiselect[$i] = $patam->parameter_multiselect;
                    $parameter_name[$i] = $patam->parameter_name;
                    //получаем выбранный параметр из реквеста
                    $selected_param = array();
                    $selected_parameter = vmGet($_REQUEST, $item_name, '');
                    $selected_param = array();
                    for ($j=0; $j< count($selected_parameter); $j++) {
                        if(!empty ($selected_parameter[$j])) {
                            $selected_param[$j] = urldecode($selected_parameter[$j]);
                        }
                    }
                    unset ($selected_parameter);
                    $selected_params[$i] = $selected_param;
                    //Печатаем название характеристики
                    if ($conf['show_label'] == 1) echo "\n <div class=\"parameter_label\">" . $parameter_label. ":</div>\n";
                    if($conf['debug_show'] == 1) {
                        print '<br><b>selected_param= ';
                        print_r($selected_param);
                        print '<br>selected_params= ';
                        print_r($selected_params);
                        print '</b><br>';
                    }
                    $q = "SELECT distinct `$param_name` FROM #__{vm}_product_type_" . $param_id . " ";
                    $q .= " WHERE  `product_id` IN (" . implode(", ", $product_ids) . ") ";

                    for ($j=0; $j< count($selected_params); $j++) {
                        if (!empty ($selected_params[$j])) {
                            if ($parameter_multiselect[$j] == 'N') $q .= "AND (`$parameter_name[$j]` = '".implode( "' OR `".$parameter_name[$j]."` =  '",$selected_params[$j])."') ";
                            else $q .= "AND (`$parameter_name[$j]` LIKE '%".implode( "' OR `".$parameter_name[$j]."` LIKE '%",$selected_params[$j])."%') ";
                        }
                    }
                    $q .= "AND `$param_name` != 'null' ";
                    $db->setQuery($q);
                    $parameters = $db->loadResultArray();

                    if($conf['debug_show'] == 1) {
                        print '<br><b>$parameters= ';
                        print $q;
                        print '<br>';
                    }

                    echo'<div>';
                    if (count($parameters) > 0) {
                        $parameters = implode(';', $parameters);
                        $parameters = explode(';', $parameters);
                        $parameters = array_unique($parameters);
                        asort($parameters);
                        //запрашиваем вид вывода характеристик из конфигурации
                        $vid = $param_name.'_'.$product_type_id[0];
                        $vid = $conf[$vid];
                        //выводим характеристики
                        $this->list_harakt($parameters, $selected_param, $item_name, $parameter_label, $vid, $disabled, $prefix);
                        //составляем запрос идов товара для передачи в компонент
                        if (!empty ($selected_params[$i])) {
                            if ($vid == 'diapazon') {
                                if(isset ($selected_params[$i][0]) && isset ($selected_params[$i][1])) {
                                    if ($selected_params[$i][0] > $selected_params[$i][1]) {
                                        $min = $selected_params[$i][1];
                                        $max = $selected_params[$i][0];
                                    }
                                    else {
                                        $min = $selected_params[$i][0];
                                        $max = $selected_params[$i][1];
                                    }
                                    $q1 .= "AND `$parameter_name[$i]` >= '".$min."' AND `$parameter_name[$i]` <= '".$max."' ";
                                }
                                elseif(isset ($selected_params[$i][0]) && !isset ($selected_params[$i][1])) {
                                    $q1 .= "AND `$parameter_name[$i]` >= '".$selected_params[$i][0]."' ";
                                }
                                elseif(!isset ($selected_params[$i][0]) && isset ($selected_params[$i][1])) {
                                    $q1 .= "AND `$parameter_name[$i]` <= '".$selected_params[$i][1]."' ";
                                }
                            }

                            elseif ($parameter_multiselect[$i] == 'N') $q1 .= "AND (`$parameter_name[$i]` = '".implode( "' OR `".$parameter_name[$i]."` =  '",$selected_params[$i])."') ";
                            else $q1 .= "AND (`$parameter_name[$i]` LIKE '%".implode( "' OR `".$parameter_name[$i]."` LIKE '%",$selected_params[$i])."%') ";
                        }
                    }
                    echo'</div>';
                }
                if (empty($selected_param)) $disabled = 'disabled';
                echo "  \n";
            }
            //запрашиваем иды товара соответствующие выбранным характеристикам
            $q = "SELECT distinct `product_id` FROM #__{vm}_product_type_" . $param_id . " ";
            $q .= " WHERE  `product_id` IN (" . implode(", ", $product_ids) . ") ";
            $q .= $q1;
            //print_r($q);
            $db->setQuery($q);
            $prod_ids = $db->loadResultArray();

            if($conf['debug_show'] == 1) {
                print 'Запрос идов товара '.$q.'<br>';
            }
            if (count($prod_ids) > 0) {
                echo "<input type=\"hidden\" name=\"product_ids\" value=\"" . base64_encode(serialize($prod_ids)) . "\" />";
                if($conf['debug_show'] == 1) print '<b>ИДЫ =</b> ' . implode(' ', $prod_ids) . '<br>';
            }
            print'<div class="colichestvo">Количество товаров в выборке = ' . count($prod_ids) . '</div>';
            //echo "<input type=\"hidden\" name=\"total\" value=\"" . count($prod_ids) . "\" />";
        }
    }

    function list_harakt($parameters, $selected_param=array(), $item_name, $parameter_label, $vid='', $disabled = '', $prefix='') {
        echo "<select class=\"inpbox\" ";
        echo "name=\"" . $item_name ."[]\" onchange=\"".$prefix."typeChange()\">\n";
        echo "<option value=\"\">Выбрать " . $parameter_label . "</option>\n";
        foreach ($parameters as $parameter) {
            if ($parameter != '') {
                $selected = '';
                if (!empty($selected_param) && in_array($parameter, $selected_param)) $selected = "selected=\"selected\"";
                echo "<option value=\"" . urlencode($parameter) . "\" " . $selected . ">" . $parameter . "</option>\n";
            }
        }
        echo "</select>";
    }

    public  static function addCSS($path) {
        global $mainframe, $mosConfig_live_site;
        $header = '<link rel="stylesheet" type="text/css" href="'.$mosConfig_live_site.'/components/com_vm_ext_search_free/css/'.$path.'" />';
        $mainframe->addCustomHeadTag($header);
    }

    public  static function addJS($path) {
        global $mainframe, $mosConfig_live_site;
        $header = '<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_vm_ext_search_free/js/'.$path.'"></script>';
        $mainframe->addCustomHeadTag($header);
    }

    function __destruct() {

    }
}

?>