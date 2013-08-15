<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die( 'Restricted access' );
	
	abstract class JMasterFieldUniteRev extends JFormField{
		
		protected $disabled = false;
		
		/**
		 * Method to get the field label markup.
		 *
		 * @return  string  The field label markup.
		 *
		 * @since   11.1
		 */
		protected function getLabel(){
			
			$this->operateControlStates();		
			
			// Initialise variables.
			$label = '';
			
			// Get the label text from the XML element, defaulting to the element name.
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = $this->translateLabel ? JText::_($text) : $text;
	
			// Build the class for the label.
			$class = !empty($this->description) ? 'hasTip' : '';
			$class = $this->required == true ? $class . ' required' : $class;
			
			//add 'disabled' to the class if disabled
			if($this->disabled == true){
				if(empty($class))
					$class = "field_disabled";
				else 
					$class .= " field_disabled";
			}
			
			$style = "";
			
			//add "hidden" to the class if hidden
			if ($this->hidden == true){
				
				$hiddenType = $this->element["hiddentype"];
							
				if($hiddenType != "soft"){
					$style = ' style="display:none;" ';
					if(empty($class))
						$class = "hidden";
					else
						$class .= " hidden";
				}	
			}
			
			// Add the opening label tag and main attributes attributes.
			$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '"'.$style.' class="' . $class . '"';
	
			// If a description is specified, use it to build a tooltip.
			if (!empty($this->description))
			{
				$label .= ' title="'
					. htmlspecialchars(
					trim($text, ':') . '::' . ($this->translateDescription ? JText::_($this->description) : $this->description),
					ENT_COMPAT, 'UTF-8'
				) . '"';
			}
	
			// Add the label text and closing tag.
			if ($this->required)
			{
				$label .= '>' . $text . '<span class="star">&#160;*</span></label>';
			}
			else
			{
				$label .= '>' . $text . '</label>';
			}
	
			return $label;
		}
		
		
		/**
		 * 
		 * setup overwrite
		 */
		public function setup(&$element, $value, $group = null){
			$response = parent::setup($element, $value, $group );
			
			$this->disabled = ($this->element["disabled"] == 'true')?true:false;
			
			return($response);
		}
		
		/**
		 * 
		 * operate the field with the contorls
		 */
		protected function operateControlStates(){
			
			$arrState = UniteControlsRev::getState($this->fieldname);
			
			if($arrState["found"] == true){
				$this->disabled = $arrState["disabled"];
				$this->hidden = $arrState["hidden"];
			}
		}
		 
		
	}
	

?>