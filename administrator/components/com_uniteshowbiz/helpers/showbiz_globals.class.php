<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;
	
	define("SHOWBIZ_TEXTDOMAIN","showbiz");
	
	class GlobalsShowBiz{

		const SHOW_SLIDER_TO = "admin";		//options: admin, editor, author
		const INCLUDE_FANCYBOX = true;		
		
		const TEMPLATE_TYPE_ITEM = "item";
		const TEMPLATE_TYPE_BUTTON = "button";
		const TABLE_SLIDERS_NAME = "#__uniteshowbiz_sliders";
		const TABLE_SLIDES_NAME = "#__uniteshowbiz_slides";
		const TABLE_TEMPLATES_NAME = "#__uniteshowbiz_templates";
		const TABLE_SETTINGS_NAME = "#__uniteshowbiz_settings";

		const FIELDS_SLIDE = "slider_id,slide_order,params";
		const FIELDS_SLIDER = "title,alias,params";
		const FIELDS_TEMPLATE = "title,content,css,ordering,params,type";

		const LINK_HELP_TEMPLATES_ITEMS = "http://showbizpro.damojothemes.com/documentation/#creating_item_templates";
		const LINK_HELP_TEMPLATES_NAVS = "http://showbizpro.damojothemes.com/documentation/#navigation_templates";
		const LINK_HELP_SLIDERS = "http://showbizpro.damojothemes.com/documentation/#create_new_slider";
		const LINK_HELP_SLIDER = "http://showbizpro.damojothemes.com/documentation/#slider_settings";
		const LINK_HELP_SLIDES = "http://showbizpro.damojothemes.com/documentation";
		const LINK_HELP_SLIDE = "http://showbizpro.damojothemes.com/documentation";

		public static $table_sliders;
		public static $table_slides;
		public static $table_templates;
		public static $table_settings;
		public static $isNewVersion;
                public static $path_init_data;

		/**
		 *
		 * init the global variables
		 */
		public static function initGlobals(){
			//set table names
			GlobalsShowBiz::$table_sliders = GlobalsShowBiz::TABLE_SLIDERS_NAME;
			GlobalsShowBiz::$table_slides = GlobalsShowBiz::TABLE_SLIDES_NAME;
			GlobalsShowBiz::$table_templates = GlobalsShowBiz::TABLE_TEMPLATES_NAME;
			GlobalsShowBiz::$table_settings = GlobalsShowBiz::TABLE_SETTINGS_NAME;
                        GlobalsShowBiz::$path_init_data = JPATH_COMPONENT_ADMINISTRATOR."/init_data/";
		}

	}

?>