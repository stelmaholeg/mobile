<?php
/**
* @version $Id: toolbar.weblinks.php 10002 2008-02-08 10:56:57Z willebil $
* @package Joomla RE
* @subpackage Weblinks
* @localized Авторские права (C) 2005 Joom.Ru - Русский дом Joomla!
* @copyright Авторские права (C) 2005 Open Source Matters. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL, смотрите LICENSE.php
* Joomla! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*
* @translator Oleg A. Myasnikov aka Sourpuss (sourpuss@mail.ru)
*/

// запрет прямого доступа
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
TOOLBAR_vm_ext_search::_DEFAULT();
?>