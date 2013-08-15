<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

class UniteRevolutionTableSliders extends JTable
{
	public function __construct(&$db) {
		parent::__construct(GlobalsUniteRev::TABLE_SLIDERS, 'id', $db);
	}

	function bind($array, $ignore = '')
	{
		$array["params"] = UniteFunctionJoomlaRev::encodeArrayToRegistry($array, "params");
		
		if(empty($array['alias'])) {
			$array['alias'] = $array['title'];
		}
		
		$array['alias'] = UniteFunctionJoomlaRev::normalizeAlias($array['alias']);
		
		return parent::bind($array, $ignore);
	}
	
	
}
