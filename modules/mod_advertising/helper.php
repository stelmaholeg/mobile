<?php

/**
 * Blank Module
 * 
 * */

defined('_JEXEC') or die;

// Используем route.php для создания ссылок на контент
require_once JPATH_SITE.'/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class modAdvertising
{
	public static function getList(&$params)
	{
        
	}
}
?>
