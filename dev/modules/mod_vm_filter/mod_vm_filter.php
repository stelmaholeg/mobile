<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// Подключение файла helper.php
require_once(dirname(__FILE__).DS.'helper.php');

// Получение параметров из конфигурации модуля
// Эти параметры вводятся и административной панели в управлении модулем
$name = $params->get('name');

// включение шаблона для отображения
require(JModuleHelper::getLayoutPath('mod_name'));
?>