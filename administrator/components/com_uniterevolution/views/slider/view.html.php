<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.view');
class UniteRevolutionViewSlider extends JMasterViewUniteRev
{
	protected $form;
	protected $item;
	protected $state;
	protected $isNew = true;
	protected $sliderID;
	protected $sap_counter = 0;
	protected $viewSliderPattern;
	protected $messageOnStart = "";
	
	
	/**
	 * 
	 * add toolbars
	 */
	protected function addToolbar(){
		
		$title = JText::_('COM_UNITEREVOLUTION')." - ";
		if($this->isNew)
			$title .= '<small>[ ' . JText::_( 'COM_UNITEREVOLUTION_NEW' ).' ]</small>'; 
		else 
			$title .= $this->item->title." <small>[".JText::_("COM_UNITEREVOLUTION_EDIT_SETTINGS")."]</small>";
		
		JToolBarHelper::title($title   , 'generic.png' );
		
		JUniteToolBarHelperRev::addComboButton("button_save", "Save", "Saving...", "Saved!","icon-32-apply");
		
		if($this->isNew == false)
			JUniteToolBarHelperRev::addComboButton("button_save_duplicate", "Save & Copy", "Saving...", "Saved!","icon-32-save-copy");
			
		JUniteToolBarHelperRev::addComboButton("button_save_close", "Save & Close", "Saving...", "Saved!","icon-32-save");			
		JUniteToolBarHelperRev::addCustomButton("button_cancel","Cancel","icon-32-cancel");			
	}
	
	
	/**
	 * 
	 * check export / import actions
	 */
	private function checkExportImport(){
		
		//set message on start
		$message = UniteFunctionsRev::getGetVar("m");
		if($message == "is")	//import success message
			$this->messageOnStart = "Import slider parameters and slides success!!!";
		
		$clientAction = UniteFunctionsRev::getPostGetVariable("client_action");
		$helper = new HelperUniteOperationsRev();
		
		switch($clientAction){
			case "export_slider":
				$helper->exportSlider($this->sliderID);				
			break;
			case "import_slider":
				$helper->importSlider($this->sliderID);
				//redirect
				$urlSlider = HelperUniteRev::getViewUrl_Slider($this->sliderID);
				$urlSlider .= "&m=is";
				UniteFunctionsRev::redirectToUrl($urlSlider);
			break;
		}
	}
	
	
	/**
	 * the main disply function
	 */
	public function display($tpl = null)
	{
		
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->isNew	= ($this->item->id == 0);
		
		$this->sliderID = $this->item->id;
		
		
		$this->checkExportImport();
		
		$this->viewSliderPattern = HelperUniteRev::getViewUrl_Slider("");
		
		if($this->_layout == "default" || $this->_layout == "edit"){
			
			if($this->isNew == false){
				$this->linkEditSlides = HelperUniteRev::getViewUrl_Items($this->item->id);
			}
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
			
		parent::display($tpl);
	}
	
}
