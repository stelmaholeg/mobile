<?php 
    defined('_JEXEC') or die('Restricted access');
/* ������� ����� */
class ModNameToClass {
    /* ������� ������� */
    function getNameTofunction($params)  {
/* ���� ����� ������� */
/* �������� �������� �� �������� ������ ��� ������ name */
$youname = $params->get('name');
/* ������� ��� �������� � ���������� $youname � ��������� ������ ��� ��� ��� */
if($youname) {
/* ���� ��� �������, �� ���������� $view �������� ��� */
$view = $youname;
} else {
/* ���� ��� �� �������, �� $view �������� ��������� ����� */
$view = '������! �� �� ����� �������';
}
/* ������� ����� ��������� �� ��������� � ���������� $view */
return $view;
    }
}
?>