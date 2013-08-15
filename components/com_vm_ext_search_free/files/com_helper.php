<?php
header('Content-Type: text/html; charset=utf-8');
// no direct access
defined('_JEXEC') or die('Restricted access');
//require_once(JPATH_BASE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart_parser.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'config.php' );
require_once(JPATH_COMPONENT . DS . 'files' . DS . 'unisearch.php');

//объявляем экземпляры классов
$uniSearch = new uniSearch();
$db = new ps_DB;

//данные из реквеста
$product_type_id = vmGet($_REQUEST, 'product_type_id', 0);
$cid = vmGet($_REQUEST, 'catid', 0);
//$getMf = vmGet($_REQUEST, 'getMf', 0);
$task2 = vmGet($_REQUEST, 'task2', '');
//$mf = vmGet($_REQUEST, 'mf', 0);
$mf_id = vmGet($_REQUEST, 'mf_id');
//$print_kol = vmGet($_REQUEST, 'print_kol', 1);

switch($task2) {

case 'manufacturer': // Ищем производителей, соответствующих категории
	$manufacturers = $uniSearch->get_manufacturer($cid);
	$uniSearch->list_manufacturer($manufacturers, $mf_id, $conf['viev_man']);
	unset($manufacturers);
    break;

case 'typ': // Ищем типы для товаров, соответствующих производителю и категории
	$types = $uniSearch->get_type($cid, $mf_id);
	$uniSearch->list_type($types, $product_type_id, $conf['viev_type']);
	unset($types);
	break;
case 'harakt': // Ищем характеристики для товаров, соответствующих типу
	$harakt = $uniSearch->get_harakt($product_type_id, $cid, $mf_id, $conf);
	break;
case 'load_page': // подгружаем страницу товаров
         require_once( JPATH_COMPONENT . DS . 'files' . DS . 'vm_ext_search_helper.php' );
        break;
default: 
	break;
}
?>