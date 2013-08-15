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
class JFormFieldControl extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'control';
	
	/**
	 * 
	 * get fields that relevant for the control
	 */
	public function getControlFields(){
		
		$arrControls = array();
		
		//get base elements array
		$arrBase = array();
		$arrBase["parent"] = (string)UniteFunctionsRev::getVal($this->element, 'parent');
		$arrBase["value"] = (string)UniteFunctionsRev::getVal($this->element, 'value');
		$arrBase["ctype"] = (string)UniteFunctionsRev::getVal($this->element, 'ctype');
		
		//validate fields:
		if(empty($arrBase["parent"]))
			UniteFunctionsRev::throwError("The parent can't be empty in control");

		if(empty($arrBase["value"]))
			UniteFunctionsRev::throwError("The value can't be empty in control: {$arrBase['parent']}");
			
		if(empty($arrBase["ctype"]))
			UniteFunctionsRev::throwError("The ctype can't be empty in control: {$arrBase['parent']}");

			
		//get children
		$strchild = (string)UniteFunctionsRev::getVal($this->element, 'child');
		
		//validate child
		if(empty($strchild))
			UniteFunctionsRev::throwError("The child can't be empty in control: {$arrBase['parent']}");
		
		$strchild = trim($strchild);		
		$children = explode(",", $strchild);
				
		foreach($children as $child){
			$arrControl = $arrBase;
			$arrControl["child"] = $child;
			$arrControls[] = $arrControl;
		}
		
		return($arrControls);
	}
	
	
	
	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		return "";
	}
	
	
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		return "";		
	}
}
