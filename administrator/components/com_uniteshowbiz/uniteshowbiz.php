<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

$currentDir = dirname(__FILE__)."/";
require_once JPATH_COMPONENT.'/helpers/showbizslider.php';
require_once $currentDir."includes.php";


// Include dependancies
jimport('joomla.application.component.controller');

if(UniteFunctionJoomlaBiz::isJoomla3())
	$controller	= JControllerLegacy::getInstance(GlobalsUniteShowbiz::EXTENSION_NAME);
else	
	$controller	= JController::getInstance(GlobalsUniteShowbiz::EXTENSION_NAME);

// Perform the Request task
//$task = JRequest::getCmd('task');
$task = JFactory::getApplication()->input->get('task');
	
$controller->execute($task);
$controller->redirect();

?>