<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class UniteRevolutionModelSlider extends JModelAdmin{
	
	public function getTable($type = 'Sliders', $prefix = 'UniteRevolutionTable', $config = array())
	{
		$table = JTable::getInstance($type, $prefix, $config);
		return $table;
	}

	
	/**
	 * 
	 * get the form
	 */
	public function getForm($data = array(), $loadData = true)
	{				
		jimport('joomla.form.form');
		
		// Get the form.
		$form = $this->loadForm('com_uniterevolution.slider', 'slider', array('control' => 'jform', 'load_data' => $loadData));
				
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}
	
	/**
	 * 
	 * load the form data
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.		
		$data = JFactory::getApplication()->getUserState('com_uniterevolution.edit.slider.data', array());
		
		if (empty($data)) {
			$data = $this->getItem();			
		}
		
		return $data;
	}
	
	
	/**
	 * 
	 * prepare table for saving
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}
		
		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__uniterevolution_sliders');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		
	}
	
	
}
