<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;



$currentDir = dirname(__FILE__) . "/";
// showbiz
require_once $currentDir . "helpers/showbiz_globals.class.php";
require_once $currentDir . "helpers/framework/include_framework.php";
require_once $currentDir . "helpers/showbiz_operations.class.php";
require_once $currentDir . 'helpers/showbiz_globals.class.php';
require_once $currentDir . 'helpers/showbiz_operations.class.php';
require_once $currentDir . '/helpers/showbiz_slider.class.php';
require_once $currentDir . '/helpers/showbiz_output.class.php';
require_once $currentDir . 'helpers/showbiz_params.class.php';
require_once $currentDir . 'helpers/showbiz_slide.class.php';
require_once $currentDir . 'helpers/showbiz_template.class.php';
require_once $currentDir . 'helpers/showbiz_wildcards.class.php';
// old

require_once $currentDir . "helpers/globals.class.php";
require_once $currentDir . "helpers/helper.class.php";
require_once $currentDir . "helpers/helper_operations.class.php";
require_once $currentDir . "helpers/actions.class.php";
require_once $currentDir . "unitejoomla/includes.php";

GlobalsShowBiz::initGlobals();


//init the globals
GlobalsUniteShowbiz::init();

GlobalsUniteShowbiz::$version = "1.1.10";

UniteFunctionJoomlaBiz::$componentName = GlobalsUniteShowbiz::COMPONENT_NAME;
?>