<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// ����������� ����� helper.php
require_once(dirname(__FILE__).DS.'helper.php');

// ��������� ���������� �� ������������ ������
// ��� ��������� �������� � ���������������� ������ � ���������� �������
$name = $params->get('name');

// ��������� ������� ��� �����������
require(JModuleHelper::getLayoutPath('mod_name'));
?>