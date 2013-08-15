<?php // no direct access
if (!defined('_JEXEC')) die('Direct Access is not allowed.');

// Load the virtuemart main parse code
require_once( JPATH_BASE . DS . 'components' . DS . 'com_virtuemart' . DS . 'virtuemart_parser.php' );

$task = vmGet($_REQUEST, 'task', '');

if ($task == 'ajax_mod') {
    require_once( JPATH_COMPONENT . DS . 'files' . DS . 'mod_helper.php' );
}
else if ($task == 'ajax_com') {
    require_once( JPATH_COMPONENT . DS . 'files' . DS . 'com_helper.php' );
}
else {
    //подключаем внешние классы
    require_once(JPATH_COMPONENT . DS . 'files' . DS . 'unisearch.php');
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
    $jquery = (!empty($conf['jquery']))?$conf['jquery']:0;
    $jquery_form = (!empty($conf['jquery_form']))?$conf['jquery_form']:0;

    //данные из реквеста
    $cid = vmGet($_REQUEST, 'catid', array());
    $category_id = vmGet($_REQUEST, 'category_id', '');
    if (empty($cid[0]) && !empty($category_id)) $cid[0] = $category_id;
    $mf_id = vmGet($_REQUEST, 'mf_id');
    $product_ids = unserialize(base64_decode($_REQUEST['product_ids']));
    $product_type_ids = vmGet($_REQUEST, 'product_type_id', '');

    //подключаем скрипты, стили
    if ($jquery == 1)       $uniSearch->addJS('jquery-1.4.2.min.js');
    if ($jquery_form == 1)  $uniSearch->addJS('jquery.form.js');
    $uniSearch->addJS('universal_search.js');
    $uniSearch->addCSS('style.css');

        $manufacturers = $uniSearch->get_manufacturer($cid);
        $types = $uniSearch->get_type($cid, $mf_id);
        ?>

<div id ="s_form">
    <form action="index.php" method="get" name="com_vm_search_form" id="com_vm_search_form">
        <input type="hidden" name="option" value="com_vm_ext_search_free" />
        <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
<?php if($conf['show_category'] == 1): ?>
        <div id = "com_category_div">
            <div class="label">Категории:  </div>
                    <?php $uniSearch->list_category($cid, "catid[]", $conf['viev_category']); ?>
        </div>
<?php endif;
    if($conf['show_man'] == 1): ?>
        <div id = "com_mf_div">
                    <?php $uniSearch->list_manufacturer($manufacturers, $mf_id, $conf['viev_man']); ?>
        </div>
<?php endif;
    if($conf['show_type'] == 1): ?>
        <div id="com_typ_div" >
                    <?php $uniSearch->list_type($types, $product_type_ids, $conf['viev_type']); ?>
        </div>
        <div id="com_harakt_div" >
                    <?php
                    if (count($types) == 1) {
                        $typ = array();
                        $typ[] = $types[0]->product_type_id;
                        $uniSearch->get_harakt($typ, $cid, $mf_id, $conf);
                    }
                    ?>
        </div>
<?php endif;
    if($conf['show_price_form'] == 1): ?>
        <div id="com_price_div" >
	    Диапазон цен: от <?php echo $GLOBALS['product_currency']; ?>
            <input type="text" name="pf" value="<?php echo $pf; ?>" />
	    до <?php echo $GLOBALS['product_currency']; ?>
            <input type="text" name="pt" value="<?php echo $pt; ?>" />
        </div>
<?php endif; ?>
        <input type="button" value="Поиск" onclick="loadProduct(0)"/>

    </form>
</div>
<div id="product_print">
</div>
    <?php
}
?>