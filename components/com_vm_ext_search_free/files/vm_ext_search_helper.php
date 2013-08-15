<?php // no direct access
if (!defined('_JEXEC')) die('Direct Access is not allowed.');

// Load the virtuemart main parse code
require_once( JPATH_BASE . DS . 'components' . DS . 'com_virtuemart' . DS . 'virtuemart_parser.php' );

//подключаем внешние классы
//require_once(JPATH_COMPONENT . DS . 'files' . DS . 'unisearch.php');
require_once( JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_vm_ext_search_free' . DS . 'config.php' );
require_once( CLASSPATH . 'ps_product.php');
require_once(JPATH_COMPONENT . DS . 'files' . DS . 'pageNavigation.php');
//глобальные переменные
GLOBAL $CURRENCY_DISPLAY, $sess, $mm_action_url;


//объявляем экземпляры классов
$uniSearch = new uniSearch();
$ps_product = new ps_product;
$db = new ps_DB;

//настройки компонента
$show_search_form = (!empty($conf['show_search_form']))?$conf['show_search_form']:0;
$search_result_per_page = (!empty($conf['search_result_per_page']))?$conf['search_result_per_page']:10;
$show_price = (!empty($conf['show_price']))?$conf['show_price']:0;
$show_add_to_cart_in_search_result = (!empty($conf['show_add_to_cart_in_search_result']))?$conf['show_add_to_cart_in_search_result']:0;
$show_image = (!empty($conf['show_image']))?$conf['show_image']:0;
$show_desc = (!empty($conf['show_desc']))?$conf['show_desc']:0;
$t_width = (!empty($conf['t_width']))?$conf['t_width']:90;
$t_height = (!empty($conf['t_height']))?$conf['t_height']:0;
//$jquery = (!empty($conf['jquery']))?$conf['jquery']:0;

//данные из реквеста
$task = vmGet($_REQUEST, 'task', '');
$cid = vmGet($_REQUEST, 'catid', array());
$category_id = vmGet($_REQUEST, 'category_id', '');
if (empty($cid[0]) && !empty($category_id)) $cid[0] = $category_id;
$mf_id = vmGet($_REQUEST, 'mf_id');
$pf = vmGet($_REQUEST, 'pf', 0);
$pt = vmGet($_REQUEST, 'pt', 0);
$product_ids = unserialize(base64_decode($_REQUEST['product_ids']));
$product_type_ids = vmGet($_REQUEST, 'product_type_id', '');
//$total = vmGet($_REQUEST, 'total', '');

//определяем наличие выбранного производителя
if (sizeof($mf_id) > 0 && !empty ($mf_id[0])) $mf = TRUE;
else $mf = FALSE;
//определяем подкатегории
if (isset($cid) && !empty($cid) && count($cid) > 0) $cids = $uniSearch->getAllcid($cid);
//запрашиваем группу покупателей по умолчанию
$db->query("SELECT shopper_group_id FROM #__{vm}_shopper_group WHERE #__{vm}_shopper_group.default='1'");
$default_group = $db->f("shopper_group_id");

//запрашиваем иды товаров для производителя, строим строку запроса производителей
if ($mf) {
    static $mf_arr = array();
    $q = "SELECT `product_id` FROM #__{vm}_product_mf_xref WHERE `manufacturer_id` IN (" . implode(', ', $mf_id) . ")";
    $db->query($q);
    if ($db->num_rows() > 0) {
        while ($db->next_record()) {
            $mf_arr[] = $db->f('product_id');
        }
    }
}

$final_price_query = "IF(
	((SELECT #__{vm}_tax_rate.tax_rate FROM #__{vm}_tax_rate WHERE #__{vm}_tax_rate.tax_rate_id=#__{vm}_product.product_tax_id)>0),
	(IF(
		(#__{vm}_product.product_discount_id=0),
		(#__{vm}_product_price.product_price+((SELECT #__{vm}_tax_rate.tax_rate FROM #__{vm}_tax_rate WHERE #__{vm}_tax_rate.tax_rate_id=#__{vm}_product.product_tax_id)*#__{vm}_product_price.product_price)),
		(IF(
			((SELECT amount FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id AND start_date<=UNIX_TIMESTAMP() AND (end_date>=UNIX_TIMESTAMP() OR end_date=0) > 0)),
			(IF(
				((SELECT is_percent FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id)>0),
				(((#__{vm}_product_price.product_price+((SELECT #__{vm}_tax_rate.tax_rate FROM #__{vm}_tax_rate WHERE #__{vm}_tax_rate.tax_rate_id=#__{vm}_product.product_tax_id)*#__{vm}_product_price.product_price)))-(((SELECT amount FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id)/100)*((#__{vm}_product_price.product_price+((SELECT #__{vm}_tax_rate.tax_rate FROM #__{vm}_tax_rate WHERE #__{vm}_tax_rate.tax_rate_id=#__{vm}_product.product_tax_id)*#__{vm}_product_price.product_price))))),
				((#__{vm}_product_price.product_price+((SELECT #__{vm}_tax_rate.tax_rate FROM #__{vm}_tax_rate WHERE #__{vm}_tax_rate.tax_rate_id=#__{vm}_product.product_tax_id)*#__{vm}_product_price.product_price))-(SELECT amount FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id))
			)),
			(#__{vm}_product_price.product_price+((SELECT #__{vm}_tax_rate.tax_rate FROM #__{vm}_tax_rate WHERE #__{vm}_tax_rate.tax_rate_id=#__{vm}_product.product_tax_id)*#__{vm}_product_price.product_price))
		))
	)),
	(IF(
		(#__{vm}_product.product_discount_id>0),
		(IF(
			(SELECT amount FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id AND start_date<=UNIX_TIMESTAMP() AND (end_date>=UNIX_TIMESTAMP() OR end_date=0)),
			(IF(
				((SELECT is_percent FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id) >= 1),
				(#__{vm}_product_price.product_price-(((SELECT amount FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id)/100)*#__{vm}_product_price.product_price)),
				(#__{vm}_product_price.product_price-(SELECT amount FROM #__{vm}_product_discount WHERE discount_id=#__{vm}_product.product_discount_id))
			)),
			#__{vm}_product_price.product_price
		)),
		#__{vm}_product_price.product_price
	))
)";


$q = "SELECT #__{vm}_product.product_id, #__{vm}_product.product_parent_id, #__{vm}_product.product_thumb_image, ";
$q .= "#__{vm}_product.product_name, #__{vm}_product.product_s_desc, #__{vm}_product_price.product_currency, ";
$q .= $final_price_query . " AS final_price ";
$q .= " FROM #__{vm}_product, #__{vm}_product_category_xref, #__{vm}_category, #__{vm}_product_price ";

$q .= "WHERE ";
$q .= "product_parent_id=''";
//из-за следующей строчки не показывает товары без цены
$q .= "AND #__{vm}_product.product_id=#__{vm}_product_price.product_id ";
$q .= "AND #__{vm}_product.product_publish='Y' ";
$q .= "AND #__{vm}_product.product_id=#__{vm}_product_category_xref.product_id ";
$q .= "AND #__{vm}_category.category_id=#__{vm}_product_category_xref.category_id ";

if ($product_ids) {
    $q .= "AND #__{vm}_product.product_id IN (" . implode(", ", $product_ids) . ") ";
}

if ($mf) {
    $q .= "AND #__{vm}_product.product_id  IN (" . implode(", ", $mf_arr) . ") ";
}

if (!empty($cids)) {
    $q .= " AND #__{vm}_category.category_id IN (" .$cids. ") ";
}

if (CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
    $q .= " AND product_in_stock > 0 ";
}
$q .= "AND #__{vm}_product.product_publish='Y' ";
$q .= "AND #__{vm}_product_price.shopper_group_id=$default_group ";

if ((!empty($pf)) && (!empty($pt))) {
    if ($pf == $pt) {
        $q .= "AND " . $final_price_query . " = " . floatval($pt) . " ";
    } elseif (floatval($pf) < floatval($pt)) {
        $q .= "AND " . $final_price_query . " >= " . floatval($pf) . " AND " . $final_price_query . " <= " . floatval($pt) . " ";
    } else {
        $q .= "AND " . $final_price_query . " <= " . floatval($pt) . " AND " . $final_price_query . " >= " . floatval($pt) . " ";
    }
} else if ((empty($pf)) && (!empty($pt))) {
    $q .= "AND " . $final_price_query . " <= " . floatval($pt) . " ";
} else if ((!empty($pf)) && (empty($pt))) {
    $q .= "AND " . $final_price_query . " >= " . floatval($pf) . " ";
}


//if(!$total){
    $total_q = 'SELECT COUNT(*) AS num_rows FROM ('.$q.') as vm';
    $db->setQuery($total_q);
    $total = $db->loadResult();
//}

//лимиты на вывод товаров
$limit2 = $search_result_per_page;
$limitstart2 = vmGet($_REQUEST, 'limitstart', 0);

$pageNav = new mosPageNav($total, $limitstart2, $limit2);

$q .= " GROUP BY #__{vm}_product.product_id ";
$q .= "ORDER BY #__{vm}_product.product_id DESC ";
$q .= "LIMIT $pageNav->limitstart, $pageNav->limit ";

$db->query($q);
$col_res = $db->num_rows();

if ($col_res > 0) {
    $i = 0;

    while ($db->next_record()) {

        if ($db->f("product_parent_id")) {
            $url = "?page=shop.product_details&category_id=$cid&flypage=" . $ps_product->get_flypage($db->f("product_parent_id"));
            $url .= "&product_id=" . $db->f("product_parent_id");
        } else {
            $url = "?page=shop.product_details&category_id=$cid&flypage=" . $ps_product->get_flypage($db->f("product_id"));
            $url .= "&product_id=" . $db->f("product_id");
        }
        $product_link = $sess->url($mm_action_url . "index.php" . $url);

        if ($db->f("product_thumb_image")) {
            $product_thumb_image = $db->f("product_thumb_image");
        }
        else {

            $product_thumb_image = 0;
        }

        if ($product_thumb_image) {
            if (substr($product_thumb_image, 0, 4) != "http") {
                if (PSHOP_IMG_RESIZE_ENABLE == '1') {
                    $product_thumb_image = $mosConfig_live_site . "/components/com_virtuemart/show_image_in_imgtag.php?filename=" . urlencode($product_thumb_image) . "&newxsize=" . PSHOP_IMG_WIDTH . "&newysize=" . PSHOP_IMG_HEIGHT . "&fileout=";
                }
                else {
                    if (file_exists(IMAGEPATH . "product/" . $product_thumb_image)) {
                        $product_thumb_image = IMAGEURL . "product/" . $product_thumb_image;
                    }
                    else {
                        $product_thumb_image = IMAGEURL . NO_IMAGE;
                    }
                }
            }
        }
        else {
            $product_thumb_image = IMAGEURL . NO_IMAGE;
        }

        $img = "<img align=\"left\" src=\"" . $product_thumb_image . "\" />" ;

                $final_price = $GLOBALS['CURRENCY']->convert( $db->f("final_price"), $db->f("product_currency") );
                $final_price = $CURRENCY_DISPLAY->getFullValue($final_price);

        if ($show_image == 1) {
            ?>
<div style="float:none;">
    <div style="width:130px; height:130px; padding: 5px 5px 5px 5px; float:left;">
        <a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>">
                        <?php print$img; ?>
        </a>
    </div>
</div>
            <?php }
        ?>
<h3>
    <a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>">
                <?php echo $db->f("product_name") ?>
    </a>
</h3>
        <?php
        if (_SHOW_PRICES == '1' AND $show_price == 1) {
            ?>
<div class="final_price">
    <?php echo $final_price; ?>
</div>
            <?php
        }
        if (!empty($show_desc)) {
            ?>
<div class="product_s_desc">
                <?php  echo $db->f("product_s_desc"); ?>
</div>
            <?php
        }
        if (!empty($show_add_to_cart_in_search_result)) {
            ?>
<div class="addtocart_form">
    <form action="<?php echo  $mm_action_url ?>index.php" method="post" name="addtocart" id="addtocart">
        <input type="hidden" name="option" value="com_virtuemart" />
        <input type="hidden" name="page" value="shop.cart" />
        <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
        <input type="hidden" name="func" value="cartAdd" />
        <input type="hidden" name="prod_id" value="<?php echo $db->f("product_id"); ?>" />
        <input type="hidden" name="product_id" value="<?php echo $db->f("product_id"); ?>" />
        <input type="hidden" name="quantity" value="1" />
        <input type="hidden" name="set_price[]" value="" />
        <input type="hidden" name="adjust_price[]" value="" />
        <input type="hidden" name="master_product[]" value="" />
        <input class="add_to_cart_button" value="Купить" title="Купить" type="submit">
    </form>
</div>
            <?php
        }
        ?>
<br style="clear:both"/>
<hr />
        <?php
    }
    if ($col_res >= $limit2 || $limitstart2 != 0){
        if ($task == 'ajax_mod')$prefix = 'mod_';
        else $prefix = '';
        echo $pageNav->writePagesLinks($prefix); 
    }
} else {
    echo "Поиск не дал результатов.";
}
?>
