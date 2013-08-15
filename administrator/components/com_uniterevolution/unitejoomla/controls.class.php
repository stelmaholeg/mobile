<?php

/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;

	class UniteControlsRev{
		
		private static $arrControls; 
		private static $arrIndexParent; 
		private static $arrIndexChildren;
		private static $arrStates;
		private static $arrGroupsAssoc;
		
		const TYPE_ENABLE = "enable";
		const TYPE_DISABLE = "disable";
		const TYPE_SHOW = "show";
		const TYPE_HIDE = "hide";
		
		
		/**
		 * 
		 * empty thye controls array
		 */
		public static function emptyControls(){
			self::$arrGroupsAssoc = array();
			self::$arrControls = array();
			self::$arrIndexParent = array();
			self::$arrIndexChildren = array();
			self::$arrStates = array();
		}
		
		
		/**
		 * 
		 * get controls array from form
		 * get all groups on the way
		 * @param JForm $form
		 */
		private static function getControlsFromForm(JForm $form){
			$fieldsets = $form->getFieldsets();
			
			self::$arrGroupsAssoc = array();
			
			$arrControls = array();			
			
			foreach($fieldsets as $key=>$fieldsetObj){
				$fieldset = $form->getFieldset($key);
				
				foreach($fieldset as $fieldName => $field){

					$group = (string)$field->group;
					
					if(!empty($group)){
						self::$arrGroupsAssoc[$field->group] = "";
					} 
						
					 if($field->type == "control"){
					 	//get controls array from the control (can be multiple children)
					 	$controls = $field->getControlFields();
					 	foreach($controls as $control){
					 		$arrControls[] = $control;
					 	}//end control foreach
					 }
				}
			}
			
			return($arrControls);
		}
		
		
		/**
		 * 
		 * get some form field by looking it in all fieldsets
		 */
		private static function getFormField(JForm $form,$fieldName){
			
			$field = $form->getField($fieldName);
			if(!empty($field))
				return($field);
			
			foreach(self::$arrGroupsAssoc as $group=>$nothing){
				$field = $form->getField($fieldName,$group);
				if(!empty($field))
					return($field);
			}
			
			UniteFunctionsRev::throwError("Field not found: $fieldName");
		}
		
		
		/**
		 * 
		 * make index array by the parent
		 */
		private static function makeIndexArray(JForm $form){
			
			self::$arrIndexChildren = array();
			self::$arrIndexParent = array();
						
			
			//make parent index - multiple children by parent. This array will be for js output
			foreach(self::$arrControls as $control){
				$parentName = $control["parent"];
				$childName = $control["child"];
				
				//get parent and child fields				
				$parentField = self::getFormField($form, $parentName);
				$childField = self::getFormField($form,$childName); 
				
				$parentID = $parentField->id;
				$childID = $childField->id;
				
				$arrControl = array();
				$arrControl["ctype"] = $control["ctype"];
				$arrControl["value"] = $control["value"];
				$arrControl["child"] = $childID;
				
				if(empty($parentField))
					UniteFunctionsRev::throwError("control parent field: $parentName not exists.");
								
				if(empty($childField))
					UniteFunctionsRev::throwError("control child field: $childField not exists.");

				//add the control to the array 
				if(!isset(self::$arrIndexParent[$parentID]))
					self::$arrIndexParent[$parentID] = array();
				
				self::$arrIndexParent[$parentID][] = $arrControl;
			}

			
			//make children index (multiple parents by one child)
			foreach(self::$arrControls as $control){
				$child = $control["child"];
				if(array_key_exists($child, self::$arrIndexChildren) == true){
					self::$arrIndexChildren[$child][] = $control;
				}else{
					self::$arrIndexChildren[$child] = array($control);
				}				
			}
		}
		
		
		/**
		 * 
		 * set control states
		 */
		private static function setStates(JForm $form){
			
			self::$arrStates = array();
			
			foreach(self::$arrIndexChildren as $childName => $arrParents){
				
				$childStates = array("disabled"=>false,"hidden"=>false);
				
				if(array_key_exists($childName, self::$arrStates))
					$childStates = self::$arrStates[$childName];
				
				foreach($arrParents as $arrParent){
					$parentName = $arrParent["parent"];
					$parentField = self::getFormField($form, $parentName);
					$parentValue = $parentField->value;
					$controlValue = $arrParent["value"];
					$ctype = $arrParent["ctype"];
					
					$valueMatch = ($controlValue == $parentValue);
					
					switch($ctype){
						case self::TYPE_ENABLE:
							$childStates["disabled"] = !$valueMatch;
						break;
						case self::TYPE_DISABLE:
							$childStates["disabled"] = $valueMatch;
						break;
						case self::TYPE_SHOW:
							$childStates["hidden"] = !$valueMatch;
						break;
						case self::TYPE_HIDE:
							$childStates["hidden"] = $valueMatch;
						break;
						default:
							UniteFunctionsRev::throwError("Wrong ctype: " . $ctype);
						break;
					}
					self::$arrStates[$childName] = $childStates;
				}
			}
		}
		
		/**
		 * 
		 * load the controls from the current form, and save them 
		 */
		public static function loadControlsFromForm(JForm $form){
			
			self::emptyControls();
			self::$arrControls = self::getControlsFromForm($form);
			self::makeIndexArray($form);
			self::setStates($form);
		}
		
		/**
		 * 
		 * get form element state by name
		 */
		public static function getState($childName){
			
			$childStates = array("found"=>false);
			
			if(array_key_exists($childName, self::$arrStates)){
				$childStates = self::$arrStates[$childName];
				$childStates["found"] = true;
			}
				
			return($childStates);			
		}
		
		/**
		 * get array for js output
		 */
		public static function getArrayForJsOutput(){
			
			return(self::$arrIndexParent);
		}
		
	}	

?>