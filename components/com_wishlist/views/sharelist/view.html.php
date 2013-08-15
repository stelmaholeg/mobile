<?php
/**
 * Favorites View for com_wishlist Component
 * 
 * @package    Favorites & Wishlist
 * @subpackage com_wishlist
 * @license  GNU/GPL v2
 * @copyright Copyright (C) 2010 MobyJam.net. All rights reserved.
 * This program is distributed under the terms of the GNU General Public License
 *
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Favorites Component
 *
 * @package		Favorites
 * @subpackage	Components
 */

class FavoritesViewSharelist extends JView
{
	
	function display($tpl = null){
		$params = &JComponentHelper::getParams( 'com_wishlist' );
		$lic_validation = $params->get( 'lic_validation' );
		require_once(JPATH_BASE.DS.'administrator/components/com_wishlist/classes/vmwish.class.php');

		$app =& JFactory::getApplication();
		/*
		$params =& JComponentHelper::getParams( 'com_wishlist' );
		$params =& $app->getParams( 'com_wishlist' );	
		$dummy = $params->get( 'dummy_param', 1 ); 
		*/
	
		$data =& $this->get('Data');
		$this->assignRef('data', $data);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}
?>
