<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldMytext extends JMasterFieldUniteRev
{	
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	
	protected $type = 'MyText';

	
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		
		$this->operateControlStates();
		
		// Initialize some field attributes.
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		
		$classOutput = "";
		$class = (string)$this->element['class'];
		if($this->hidden == true){
			$hiddenType = $this->element["hiddentype"];
			if($hiddenType != "soft")			
				$class .= " hidden"; 
		}
		
		if(!empty($class))
			$classOutput = " class=\"$class\"";
		
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true' || $this->disabled == true) ? ' disabled="disabled"' : '';
		$unit = (string)UniteFunctionsRev::getVal($this->element, 'unit',"");
		
		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		$html = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $classOutput . $size . $disabled . $readonly . $onchange . $maxLength . '/>';
		
		if(!empty($unit)){
			$unitClass = "setting_unit";
			if($disabled == true)
				$unitClass .= " field_disabled";
			
			if($this->hidden == true)
				$unitClass .= " hidden";
			
			$unitID = $this->id."-unit";
			
			$html .= "<span id='$unitID' class='$unitClass'>$unit</span>";
		}
		
		return $html;
			
	}
}
