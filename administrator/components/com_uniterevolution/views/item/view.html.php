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

class UniteRevolutionViewItem extends JMasterViewUniteRev
{
	protected $form;
	protected $item;
	protected $state;
	protected $params;
	protected $urlPreview;
	protected $slider;
	protected $isEmpty = true;
	protected $styleLayers = "";
	protected $styleIframe = "";
	protected $formLayer;
	protected $operations;
	protected $jsonCaptions;
	protected $jsonLayers;
	protected $arrButtonClasses;
	protected $urlViewItems;
	protected $urlViewItemPattern;
	protected $urlViewItemNew;
	protected $slideID;
	protected $sliderID;
	protected $arrSlides = array();
	protected $arrSlidesForLink;
	protected $slideDelay;
	
	
	/**
	 * 
	 * put some field input and label
	 */
	public function putField($name){
		UniteFunctionJoomlaRev::putFormField($this->form, $name);
	}
	
	/**
	 * 
	 * put some optional field
	 */
	public function putOptionalField($name){
		UniteFunctionJoomlaRev::putFormField($this->form, $name,"params");
	}

	/**
	 * 
	 * put some optional field in list format
	 */
	public function putOptionalFieldList($name){			
		echo "<li id='$name-li'>";
			UniteFunctionJoomlaRev::putFormField($this->form, $name,"params");
		echo "</li>";
	}
	
	/**
	 * 
	 * put custom field - slide link
	 */
	public function putSlideLinkField($isLayer=false){
				
		if($isLayer == true){	//layer option
			$name = "layer_slide_link";
			$state = array("found"=>false);
			$defaultValue = "";
			$rowID = $name."_row";
			$selectID = $name;						
		}
		else{	//slide option
			$name = "slide_link";
			$state = UniteControlsRev::getState($name);
			$state = UniteControlsRev::getState($name);
			$defaultValue = $this->params->get($name);
			$rowID = $name."-li";
			$selectID = "jform_params_{$name}";
		}
		
		$arrSlides = array();
		$arrSlides["nothing"] = "-- Not Chosen --";
		$arrSlides["next"] = "-- Next Slide --";
		$arrSlides["prev"] = "-- Previous Slide --";
		
		foreach($this->arrSlides as $key=>$value)
			$arrSlides[$key] = $value;
		
		$this->arrSlidesForLink = $arrSlides; 
		
		$style = "";
		$disabled = "";
		$class = "";
		if($state["found"] == true){
			
			if($state["hidden"] == true)
				$style = " style='display:none;' ";
				
			if($state["disabled"] == true){
				$disabled = " disabled='disabled'";
				$class = "field_disabled";
			}
		}
		
		$htmlParams = ' id="'.$selectID.'" class="" name="jform[params]['.$name.']" aria-invalid="false" '.$style.$disabled;
		$htmlSelect = UniteFunctionsRev::getHTMLSelect($arrSlides,$defaultValue,' '.$htmlParams,true);
		
		echo "<li id='{$rowID}'>";
		
		?>
			<label id="jform_params_slide_link-lbl" class="hasTip <?php echo $class?>" title="Link To Slide::Choose a slide that this slide will be link to." for="jform_params_slide_link" aria-invalid="false" <?php echo $style?>>Link To Slide</label>
			<?php echo $htmlSelect?>
		<?php 
		echo "</li>";
	}
	
	
	/**
	 * 
	 * put field for the layers
	 */
	public function putLayersField($name,$status=""){
		
		$fieldset = $this->formLayer->getFieldset("optional");
		$field = UniteFunctionsRev::getVal($fieldset, $name);
		
		if(empty($field)){
			UniteFunctionsRev::throwError("field not found:".$name);
			exit(); 
		}
		
		$style = "";
		if($field->hidden || $status == "hidden")
			$style = "style='display:none;'";
		
		echo "<li id='{$name}_row' $style>";
			echo $this->formLayer->getLabel($name);
			echo $this->formLayer->getInput($name);
		echo "</li>";
	}
	
	/**
	 * 
	 * put layer field but without li
	 */
	public function putLayersFieldRow($name){

		echo $this->formLayer->getLabel($name);
		echo $this->formLayer->getInput($name);
		
	}
	
	/**
	 * 
	 * get json layers, and return json layers
	 * build layer image url's
	 */
	private function prepareLayersForOutput($jsonLayers){
		$jsonLayers = trim($jsonLayers);
		if(empty($jsonLayers))
			return($jsonLayers);
			
		$arrLayers = json_decode($jsonLayers);
		$arrLayers = UniteFunctionsRev::convertStdClassToArray($arrLayers);
		
		foreach($arrLayers as $key=>$layer){
			$layer = (array)$layer;
			if(isset($layer["image_url"]))
				$arrLayers[$key]["image_url"] = UniteFunctionJoomlaRev::getImageUrl($layer["image_url"]);
		}
		
		$jsonLayers = json_encode($arrLayers);
		return($jsonLayers);
	}
	
	
	/**
	 * 
	 * set image style and preview url
	 */
	private function setEditLayersSettings(){
				
		//get slider id
		if(!empty($this->item->id)){
			$sliderID = $this->item->sliderid;
		}else 
			$sliderID = JRequest::getCmd("sliderid");

		$this->urlViewItems = HelperUniteRev::getViewUrl_Items($sliderID);
		$this->urlViewItemPattern = HelperUniteRev::getViewUrl_Item($sliderID, "");
		$this->urlViewItemNew = HelperUniteRev::getViewUrl_Item($sliderID);
		
		$this->slideID = $this->item->id;
		$this->sliderID = $sliderID;
		
		$slider = HelperUniteRev::getSlider($sliderID);
		$this->slider = $slider;
		
		$params = $slider["params"];
		
		$sliderHeight = $params->get("slider_height",960);
		$sliderWidth = $params->get("slider_width",350);
		
		//get image url
		$urlImage = $this->params->get("image");
		if(empty($urlImage))
			$urlImage = GlobalsUniteRev::$urlDefaultSlideImage;
		else 
			$urlImage = UniteFunctionJoomlaRev::getImageUrl($urlImage);
		
		//create the style:
		$this->styleLayers = "width:{$sliderWidth}px;height:{$sliderHeight}px;";
		if(!empty($urlImage))
			$this->styleLayers .= "background-image:url($urlImage);";
		
		//create iframe style:
		
		//set iframe parameters
		$iframeWidth = $sliderWidth+60;
		$iframeHeight = $sliderHeight+50;
		
		$this->styleIframe = "width:{$iframeWidth}px;height:{$iframeHeight}px;";
			
		//load edit layers 
		$this->formLayer = new JForm("layer");
		$this->formLayer->loadFile("layers");
		
		//set captions list
		//prepare 
		$contentCSS = $this->operations->getCaptionsContent();
		$arrCaptionClasses = $this->operations->getArrCaptionClasses($contentCSS);
		
		$firstCaption = !empty($arrCaptionClasses)?$arrCaptionClasses[0]:"";
		
		//set caption field value of the first caption
		$this->formLayer->setValue("layer_caption","",$firstCaption);
				
		$jsonLayers = $this->prepareLayersForOutput($this->item->layers);
		
		$this->jsonCaptions = UniteFunctionsRev::jsonEncodeForClientSide($arrCaptionClasses);
		$this->jsonLayers = UniteFunctionsRev::jsonEncodeForClientSide($jsonLayers);
		$this->arrButtonClasses = $this->operations->getButtonClasses();
		
		//set slide delay
		$this->slideDelay = $this->params->get("delay");
		if(empty($slideDelay))
			$this->slideDelay = $params->get("delay","9000");

	}
	
	
	/**
	 * 
	 * add scripts and styles declarations.
	 */
	private function addScriptsAndStyles(){
		
		//add scripts and styles
		UniteFunctionJoomlaRev::addScript(GlobalsUniteRev::$urlAssets."edit_layers.js");
		UniteFunctionJoomlaRev::addStyle(GlobalsUniteRev::$urlAssets."edit_layers.css");
		
		//add plugin css:
		UniteFunctionJoomlaRev::addStyle(GlobalsUniteRev::$urlItemPlugin."/css/settings.css");
		UniteFunctionJoomlaRev::addStyle(GlobalsUniteRev::$urlCaptionsCss,"rs-plugin-captions-css");
	}
	
	
	/**
	 * display function
	 * 
	 */
	public function display($tpl = null){
		
		$this->operations = new HelperUniteOperationsRev();
		
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');		
		$this->state	= $this->get('State');
		
		if(!empty($this->item->id)){
			$this->isEmpty = false;
			$this->arrSlides = $this->operations->getSlidesShort($this->item->id);
		}
		
		$arrParams = $this->item->get("params");
		
		$this->params = new JRegistry();
		$this->params->loadArray($arrParams);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addScriptsAndStyles();
		$this->setEditLayersSettings();
		$this->addToolbar();
		parent::display($tpl);
	}

	
	/**
	 * 
	 * add the toolbar
	 */
	protected function addToolbar(){
		JRequest::setVar('hidemainmenu', true);
		
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= false;
		$canDo		= true;
		$sliderTitle = $this->slider["title"];
		
		$title = JText::_('COM_UNITEREVOLUTION').' - '.$sliderTitle;
		
		if($isNew){
			$title .= " - <small>[". JText::_( 'COM_UNITEREVOLUTION_NEW_SLIDE' )."]</small>";
		}else{
			$title .= " <small>[".JText::_('COM_UNITEREVOLUTION_EDIT_SLIDE').", ID:{$this->item->id}]</small>";
		}
		
		JToolBarHelper::title($title, 'generic.png' );
		
		JUniteToolBarHelperRev::addComboButton("button_save_slide", "Save", "Saving...", "Saved!");
		JUniteToolBarHelperRev::addComboButton("button_save_slide_new", "Save & New", "Saving...", "Saved!","icon-32-save-new");
		JUniteToolBarHelperRev::addComboButton("button_save_slide_copy", "Save & Copy", "Saving...", "Saved!","icon-32-save-copy");
		JUniteToolBarHelperRev::addComboButton("button_save_slide_close", "Save & Close", "Saving...", "Saved!","icon-32-save");
		JUniteToolBarHelperRev::addCustomButton("button_cancel", "Cancel","icon-32-cancel");
		
	}
	
}

