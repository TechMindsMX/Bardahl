<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

	$generalSettings = new UniteSettingsBiz();
	
	$generalSettings->addRadio("includes_globally", 
							   array("on"=>__("On",SHOWBIZ_TEXTDOMAIN),"off"=>__("Off",SHOWBIZ_TEXTDOMAIN)),
							   __("Include Showbiz libraries globally",SHOWBIZ_TEXTDOMAIN),
							   "on",
							   array("description"=>"<br>".__("Add css and js includes only on all pages. Id turned to off they will added to pages where the showbiz shortcode exists only. This will work only when the slider added by a shortcode.",SHOWBIZ_TEXTDOMAIN)));
	
	$generalSettings->addTextBox("pages_for_includes", "",__("Pages to include ShowBiz libraries",SHOWBIZ_TEXTDOMAIN),
								  array("description"=>"<br>".__("Specify the page id's that the front end includes will be included in. Example: 2,3,5 also: homepage,3,4",SHOWBIZ_TEXTDOMAIN)));
									  
	$generalSettings->addRadio("js_to_footer", 
							   array("on"=>__("On",SHOWBIZ_TEXTDOMAIN),"off"=>__("Off",SHOWBIZ_TEXTDOMAIN)),
							   __("Put JS Includes To Footer",SHOWBIZ_TEXTDOMAIN),
							   "off",
							   array("description"=>"<br>".__("Putting the js to footer (instead of the head) is good for fixing some javascript conflicts.",SHOWBIZ_TEXTDOMAIN)));
	
	
	//get stored values
	$operations = new BizOperations();
	$arrValues = $operations->getGeneralSettingsValues();
	$generalSettings->setStoredValues($arrValues);
	
	self::storeSettings("general", $generalSettings);

?>