<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;

	class GlobalsUniteRev{
		
		const EXTENSION_NAME = "uniterevolution"; 
		const COMPONENT_NAME = "com_uniterevolution";
		
		const TABLE_SLIDERS = "#__uniterevolution_sliders";
		const TABLE_SLIDES = "#__uniterevolution_slides";
		const FIELDS_SLIDE = "sliderid,title,image,published,ordering,params,layers";
		const FIELDS_SLIDER = "title,alias,published,ordering,params";
		
		const VIEW_SLIDER = "slider";
		const VIEW_SLIDERS = "sliders";
		const VIEW_ITEMS = "items";
		const VIEW_ITEM = "item";
		
		const LAYOUT_SLIDER = "edit";
		
		public static $urlBase;
		public static $urlAssets;
		public static $urlAssetsMedia;		
		public static $urlAssetsArrows;
		public static $urlAssetsBullets;
		public static $urlItemPlugin;
		public static $urlCaptionsCss;
		public static $urlCaptionsCssOriginal;
		public static $urlDefaultSlideImage;
		
		public static $pathAssets;
		public static $pathAssetsMedia;
		public static $pathComponent;
		public static $pathAssetsArrows;
		public static $pathAssetsBullets;
		public static $pathViews;
		public static $pathItemPlugin;
		public static $pathCaptionsCss;
		public static $pathCaptionsCssOriginal;
		
		
		/**
		 * 
		 * init globals
		 */
		public static function init(){
			//set global vars
			self::$urlBase = JURI::root();
			self::$urlAssets = JURI::root()."administrator/components/".self::COMPONENT_NAME."/assets/";
			self::$urlAssetsMedia = JURI::root()."media/".self::COMPONENT_NAME."/assets/";
						
			self::$urlAssetsArrows = self::$urlAssets."arrows/";
			self::$urlAssetsBullets = self::$urlAssets."bullets/";
			self::$urlDefaultSlideImage = self::$urlAssetsMedia."images/slide_image.jpg";
			
			self::$pathComponent = JPATH_ADMINISTRATOR."/components/".self::COMPONENT_NAME."/";
			self::$pathAssets = self::$pathComponent."assets/";
			self::$pathAssetsMedia = JPATH_ROOT."/media/".self::COMPONENT_NAME."/assets/";
			
			self::$pathAssetsArrows = self::$pathAssets."arrows/";
			self::$pathAssetsBullets = self::$pathAssets."bullets/";
			self::$pathViews = self::$pathComponent."views/";
			
			self::$pathItemPlugin = self::$pathAssetsMedia."rs-plugin/";			
			self::$urlItemPlugin = self::$urlAssetsMedia."rs-plugin/";
			
			self::$pathCaptionsCss = self::$pathItemPlugin."css/captions.css";
			self::$urlCaptionsCss = self::$urlItemPlugin."css/captions.css";
			
			self::$pathCaptionsCssOriginal = self::$pathItemPlugin."css/captions-original.css";
			self::$urlCaptionsCssOriginal = self::$urlItemPlugin."css/captions-original.css";
		}
		
	}

?>