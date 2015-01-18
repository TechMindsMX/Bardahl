<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

/**
 * include the unitejoomla library
 */
$currentFolder = dirname(__FILE__) . "/";

jimport('joomla.application.component.view');
jimport('joomla.application.component.controller');

require_once $currentFolder . 'functions.class.php';
require_once $currentFolder . 'functions_joomla.class.php';

$isJoomla3 = UniteFunctionJoomlaBiz::isJoomla3();

if ($isJoomla3) {
    require_once $currentFolder . 'masterfield_joomla3.class.php';

    class JMasterViewUniteBaseBiz extends JViewLegacy {
        
    }

;

    class JControllerUniteBaseBiz extends JControllerLegacy {
        
    }

;
} else {  //joomla 2.5
    require_once $currentFolder . 'masterfield.class.php';

    class JMasterViewUniteBaseBiz extends JView {
        
    }

;

    class JControllerUniteBaseBiz extends JController {
        
    }

;
}


require_once $currentFolder . 'db.class.php';
require_once $currentFolder . 'admintable.class.php';
require_once $currentFolder . 'image_view.class.php';
require_once $currentFolder . 'masterview.class.php';
require_once $currentFolder . 'controls.class.php';
require_once $currentFolder . 'cssparser.class.php';
require_once $currentFolder . 'toolbar.class.php';
?>