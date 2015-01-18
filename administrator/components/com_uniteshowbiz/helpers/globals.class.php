<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

	class GlobalsUniteShowbiz{
		
		const EXTENSION_NAME = "uniteshowbiz"; 
		const COMPONENT_NAME = "com_uniteshowbiz";
		
		const TABLE_SLIDERS = "#__uniteshowbiz_sliders";
		const TABLE_SLIDES = "#__uniteshowbiz_slides";
		const FIELDS_SLIDE = "`slider_id`,`slide_order`,`params`";
		const FIELDS_SLIDER = "title,alias,published,ordering,params";
		
		const VIEW_SLIDER = "slider";
		const VIEW_SLIDERS = "sliders";
		const VIEW_ITEMS = "items";
		const VIEW_ITEM = "item";
		const VIEW_TEMPLATES = "templates";
		
		const LAYOUT_SLIDER = "edit";
		
		public static $version;
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
		public static $pathSettings;
		
		
		/**
		 * 
		 * init globals
		 */
		public static function init(){
			$urlRoot = JURI::root();
			if(JURI::getInstance()->isSSL() == true)
				$urlRoot = str_replace("http://","https://",$urlRoot);
			
			//set global vars
			self::$urlBase = $urlRoot;
			self::$urlAssets = $urlRoot."administrator/components/".self::COMPONENT_NAME."/assets/";
			self::$urlAssetsMedia = $urlRoot."media/".self::COMPONENT_NAME."/assets/";
						
			self::$urlAssetsArrows = self::$urlAssets."arrows/";
			self::$urlAssetsBullets = self::$urlAssets."bullets/";
			self::$urlDefaultSlideImage = self::$urlAssetsMedia."images/slide_image.jpg";
			
			self::$pathComponent = JPATH_ADMINISTRATOR."/components/".self::COMPONENT_NAME."/";
			self::$pathAssets = self::$pathComponent."assets/";
			self::$pathAssetsMedia = JPATH_ROOT."/media/".self::COMPONENT_NAME."/assets/";
			
			self::$pathAssetsArrows = self::$pathAssets."arrows/";
			self::$pathAssetsBullets = self::$pathAssets."bullets/";
			self::$pathViews = self::$pathComponent."views/";
			self::$pathSettings = self::$pathComponent."settings/";
			
			self::$pathItemPlugin = self::$pathAssetsMedia."showbiz-plugin/";			
			self::$urlItemPlugin = self::$urlAssetsMedia."showbiz-plugin/";
			
		}
				
	}

?>