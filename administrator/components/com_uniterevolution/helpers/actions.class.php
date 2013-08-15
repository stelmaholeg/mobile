<?php

/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;

class ActionsUniteRev{
	
	private $data = array();
	private $action = "";
	private $operations;
	
	
	/**
	 * 
	 * save slide
	 */
	private function saveSlide(){
		$slideID = $this->data["slideid"];
		$sliderID = $this->data["sliderid"];
		
		$params = UniteFunctionsRev::getVal($this->data, "params",array());
		$params = UniteFunctionJoomlaRev::clearParamsArray($params);
		
		$arrLayers = UniteFunctionsRev::getVal($this->data, "layers",array());
		
		if(empty($slideID))
			$slideID = $this->operations->addNewSlide($sliderID, $params, $arrLayers);
		else{
			$this->operations->saveSlide($slideID, $params, $arrLayers);
		}
		
		return($slideID);
	}
	
	
	/**
	 * 
	 * save the slider
	 */
	private function saveSlider(){
				
		$sliderID = $this->data["sliderid"];
		
		$params = UniteFunctionsRev::getVal($this->data, "params",array());
		$settings = UniteFunctionsRev::getVal($this->data, "settings",array());
		$params = array_merge($settings,$params);
				
		$params = UniteFunctionJoomlaRev::clearParamsArray($params);
		
		//validate title not empty:
		$title = UniteFunctionsRev::getVal($params, "title");
		UniteFunctionsRev::validateNotEmpty($title,"Title");
		
		//unset sldier id from the params
		unset($params["id"]);
		if(empty($sliderID))
			$sliderID = $this->operations->addNewSlider($params);
		else 		
			$this->operations->saveSlider($sliderID, $params);
		
		return($sliderID);
	}
	
	
	/**
	 * 
	 * operate actions
	 */
	public function operate(){
		
		$this->operations = new HelperUniteOperationsRev();
		$this->action = UniteFunctionsRev::getPostVariable("action");
		if(empty($this->action))
			$this->action = UniteFunctionsRev::getPostVariable("client_action");
			
		$this->data = UniteFunctionsRev::getPostVariable("data",array());
		
		try{
		
			switch($this->action){
				case "add_slide":
					$slideID = $this->operations->addSlideFromData($this->data);
					UniteFunctionsRev::ajaxResponseSuccess("");
				break;
				case "update_slider_duplicate":
					$sliderID = $this->saveSlider();
					$newSliderID = $this->operations->duplicateSlider($sliderID);
					UniteFunctionsRev::ajaxResponseData(array("newSliderID"=>$newSliderID));
				break;
				case "update_slider":
					$sliderID = $this->saveSlider();
					UniteFunctionsRev::ajaxResponseData(array("sliderID"=>$sliderID));
				break;
				case "update_slide_close":
				case "update_slide_new":
				case "update_slide":
					$slideID = $this->saveSlide();
					UniteFunctionsRev::ajaxResponseData(array("slideID"=>$slideID));
				break;
				case "update_slide_duplicate":
					$slideID = $this->saveSlide();
					$newSlideID = $this->operations->duplicateSlide($slideID);
					UniteFunctionsRev::ajaxResponseData(array("slideID"=>$newSlideID));
				break;
				case "get_captions_css":
					$contentCSS = $this->operations->getCaptionsContent();
					UniteFunctionsRev::ajaxResponseData($contentCSS);
				break;
				case "update_captions_css":
					$arrCaptions = $this->operations->updateCaptionsContentData($this->data);
					UniteFunctionsRev::ajaxResponseSuccess("CSS file saved succesfully!",array("arrCaptions"=>$arrCaptions));
				break;
				case "restore_captions_css":
					$this->operations->restoreCaptionsCss();
					$contentCSS = $this->operations->getCaptionsContentOgirinal();
					UniteFunctionsRev::ajaxResponseData($contentCSS);
				break;
				case "get_release_log":
					$content = HelperUniteRev::getReleaseLogContent();
					UniteFunctionsRev::ajaxResponseData($content);
				break;
				case "preview_slide":
					$this->operations->putSlidePreviewByData($this->data);
				break;
				case "preview_slider":
					$sliderID = UniteFunctionsRev::getPostVariable("sliderid");
					UniteFunctionsRev::validateNotEmpty($sliderID,"SliderID");
					$this->operations->previewOutput($sliderID);
				break;
				case "update_items_order":
					$this->operations->updateSlidesOrderFromData($this->data);
					UniteFunctionsRev::ajaxResponseSuccess("order updated");
				break;
				case "toggle_publish_state":	//publish / unpublish item
					$newState = $this->operations->publishUnpublishItemFromData($this->data);
					UniteFunctionsRev::ajaxResponseSuccess("state updated",array("newstate"=>$newState));
				break;
				case "delete_slide":
					$this->operations->deleteSlideFromData($this->data);
					UniteFunctionsRev::ajaxResponseSuccess("Slide Deleted");
				break;
				case "duplicate_slide":
					$this->operations->duplicateSlideFromData($this->data);
					UniteFunctionsRev::ajaxResponseSuccess("Slide Duplicated");
				break;
				default:
					UniteFunctionsRev::ajaxResponseError("ajax action not found: ".$this->action);
				break;
			}
		
		}catch(Exception $e){
			$message = $e->getMessage();
			UniteFunctionsRev::ajaxResponseError($message);
		}
		exit();
	}
	
}
	
	