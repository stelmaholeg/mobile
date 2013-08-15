<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;


class UniteRevolutionController extends JController
{
	protected $default_view = GlobalsUniteRev::VIEW_SLIDERS;
	protected $default_layout = GlobalsUniteRev::LAYOUT_SLIDER;
	
	/**
	 * show some image
	 */
	public function showimage(){
		UniteFunctionJoomlaRev::showImageFromRequest();
		exit();
	}
	
	/**
	 * 
	 * check that captions file exists and if not - copy it to it's place.
	 */
	private function checkCopyCaptionsCssFile(){
		
		if(file_exists(GlobalsUniteRev::$pathCaptionsCss) == false)
			copy(GlobalsUniteRev::$pathCaptionsCssOriginal,GlobalsUniteRev::$pathCaptionsCss);
		
		if(file_exists(GlobalsUniteRev::$pathCaptionsCss) == false)
			UniteFunctionsRev::throwError("The captions file couldn't be copied to it's place: {GlobalsUniteRev::$pathCaptionsCss}, please copy it by hand from captions-original.css from the same folder, or turn to support.");
	}
	
	/**
	 *
	 * display some view
	 */
	public function display($cachable = false, $urlparams = false){
				
		$urlAssets = GlobalsUniteRev::$urlAssets;
		
		//add style
		$document = JFactory::getDocument();
		$document->addStyleSheet($urlAssets."style.css");
		
		//add jquery ui
		$document->addStyleSheet($urlAssets."jui/jquery-ui-1.8.24.custom.css");
		
		//add codemirror
		$document->addStyleSheet($urlAssets."codemirror/codemirror.css");
		$document->addScript($urlAssets."codemirror/codemirror.js");
		$document->addScript($urlAssets."codemirror/css.js");

		//add custom scripts
		$document->addScript($urlAssets."jquery.min.js");
		$document->addScript($urlAssets."jquery-ui-1.8.24.custom.min.js");
		$document->addScript($urlAssets."settings.js");
		$document->addScript($urlAssets."admin.js");
		$document->addScript($urlAssets."revslider.js");
		
		//add ajax url:
		$currentView = JRequest::getCmd('view', $this->default_view);
		$ajaxUrl = UniteFunctionJoomlaRev::getViewUrl($currentView, "ajax");
		$document->addScriptDeclaration("var g_urlAjax='$ajaxUrl';");
		
		$this->checkCopyCaptionsCssFile();
		
		parent::display();

		return $this;
	}
	
}