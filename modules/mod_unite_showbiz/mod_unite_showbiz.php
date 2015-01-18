<?php
/**
 * @package Unite Showbiz Slider Module for Joomla 1.7-3.1
 * @version 1.0
 * @author UniteCMS.net
 * @copyright (C) 2012- Unite CMS
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// no direct access
defined('_JEXEC') or die;

//include item files
$pathIncludes = JPATH_ADMINISTRATOR . "/components/com_uniteshowbiz/includes.php";
require_once $pathIncludes;

//set active menu link
$urlBase = JURI::base();

$sliderID = $params->get("sliderid");

$document = JFactory::getDocument();
$include_jquery = $params->get("include_jquery", "true");
$isJsToBody = ($params->get("js_load_type", "head") == "body");
$isNoConflict = ($params->get("no_conflict_mode", "false") == "true");  
$urlPlugin = GlobalsUniteShowbiz::$urlItemPlugin;


if ($include_jquery == "true") {
	
    if (UniteFunctionJoomlaBiz::isJqueryIncluded() == false) {
    	
        $jsPrefix = "http";
        if (JURI::getInstance()->isSSL() == true)
            $jsPrefix = "https";

        $document->addScript("{$jsPrefix}://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js?app=showbiz_slider");
    }
}


$slider = new ShowBizSlider();
$slider->initByMixed($sliderID);

    $document->addStyleSheet($urlPlugin . 'css/settings.css');
    $document->addStyleSheet($urlPlugin . 'fancybox/jquery.fancybox.css');

if ($isJsToBody == false) {
    $document->addScript($urlPlugin . 'fancybox/jquery.fancybox.pack.js');
    $document->addScript($urlPlugin . "fancybox/helpers/jquery.fancybox-media.js");
    
    $document->addScript($urlPlugin . 'js/jquery.themepunch.plugins.min.js');
    $document->addScript($urlPlugin . 'js/jquery.themepunch.showbizpro.min.js');
}

$output = new ShowBizOutput();
$output->setOutputParams($isJsToBody, $isNoConflict);
$output->putSliderBase($sliderID);


