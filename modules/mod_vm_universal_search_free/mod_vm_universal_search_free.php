<?php // no direct access
if (!defined('_JEXEC')) die('Direct Access is not allowed.');

// Load the virtuemart main parse code
require_once( JPATH_BASE . DS . 'components' . DS . 'com_virtuemart' . DS . 'virtuemart_parser.php' );

//подключаем внешние классы
require_once( JPATH_BASE . DS . 'components' . DS . 'com_vm_ext_search_free' . DS . 'files' . DS .'unisearch.php');
require( JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_vm_ext_search_free' . DS . 'config.php' );

//объявляем экземпляры классов
$uniSearch = new uniSearch();

//настройки компонента
$show_cat = $params->get('show_cat', '');
$show_manuf = $params->get('show_manuf', '');
$show_types = $params->get('show_types', '');
$show_prices = $params->get('show_prices', '');
$jquery = $params->get('jq', '');
$jquery_form = $params->get('jqf', '');

//данные из реквеста
$cid = vmGet($_REQUEST, 'catid', array());
$category_id = vmGet($_REQUEST, 'category_id', '');
if (empty($cid[0]) && !empty($category_id)) $cid[0] = $category_id;
$mf_id = vmGet($_REQUEST, 'mf_id');
$product_ids = unserialize(base64_decode(@$_REQUEST['product_ids']));
$product_type_ids = vmGet($_REQUEST, 'product_type_id', '');

global $mainframe, $mosConfig_live_site;

//подключаем скрипты, стили
if ($jquery == 1)       $uniSearch->addJS('jquery-1.4.2.min.js');
if ($jquery_form == 1)  $uniSearch->addJS('jquery.form.js');

$header = '<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/modules/mod_vm_universal_search_free/files/mod_universal_search.js"></script>';
$mainframe->addCustomHeadTag($header);
$header = '<link rel="stylesheet" type="text/css" href="'.$mosConfig_live_site.'/modules/mod_vm_universal_search_free/files/style.css" />';
$mainframe->addCustomHeadTag($header);

$manufacturers = $uniSearch->get_manufacturer($cid);
$types = $uniSearch->get_type($cid, $mf_id);
?>
<div id="mod_s_form">
<form action="index.php" method="get" name="mod_vm_search_form" id="mod_vm_search_form">
    <input type="hidden" name="option" value="com_vm_ext_search_free" />
    <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
    <?php if($show_cat == 1): ?>
    <div id = "mod_category_div">
        <div class="label">Категории:  </div>
            <?php $uniSearch->list_category($cid, "catid[]", $conf['viev_category'], 'mod_'); ?>
    </div>
    <?php endif;
    if($show_manuf == 1): ?>
    <div id = "mod_mf_div">
            <?php $uniSearch->list_manufacturer($manufacturers, $mf_id, $conf['viev_man'], 'mod_'); ?>
    </div>
    <?php endif;
    if($show_types == 1): ?>
    <div id="mod_typ_div" >
            <?php $uniSearch->list_type($types, $product_type_ids, $conf['viev_type'], 'mod_'); ?>
    </div>
    <div id="mod_harakt_div" >
            <?php
            if (count($types) == 1) {
                $typ = array();
                $typ[] = $types[0]->product_type_id;
                $uniSearch->get_harakt($typ, $cid, $mf_id, $conf);
            }
            ?>
    </div>
    <?php endif;
    if($show_prices == 1): ?>
    <div id="mod_price_div" >
	    Диапазон цен: от <?php echo $GLOBALS['product_currency']; ?>
        <input type="text" name="pf" value="<?php echo $pf; ?>" />
	    до <?php echo $GLOBALS['product_currency']; ?>
        <input type="text" name="pt" value="<?php echo $pt; ?>" />
    </div>
    <?php endif; ?>
    <input type="button" value="Поиск" onclick="mod_loadProduct(0)"/>
</form>
</div>