<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

//define dmp function
if (function_exists("dmp") == false) {

    function dmp($str) {
        echo "<pre>";
        print_r($str);
        echo "</pre>";
    }

}

abstract class ShowbizSliderHelper {

    /**
     * get Joomla version
     */
    public static function isJoomla3() {

        if (defined("JVERSION")) {
            $version = JVERSION;
            $version = (int) $version;
            return($version == 3);
        }

        if (class_exists("JVersion")) {
            $jversion = new JVersion;
            $version = $jversion->getShortVersion();
            $version = (int) $version;
            return($version == 3);
        }

        return(!defined("DS"));
    }

    
    /**
     * 
     * add submenu
     */
    public static function addSubmenu($vName) {
    	
        JSubMenuHelper::addEntry(
                JText::_('COM_UNITESHOWBIZ_SUBMENU_SLIDERS'), 'index.php?option=com_uniteshowbiz', $vName == 'sliders'
        );
    	
        JSubMenuHelper::addEntry(
                JText::_('COM_UNITESHOWBIZ_SKIN_EDITOR'), 'index.php?option=com_uniteshowbiz&view=templates', $vName == 'templates'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_UNITESHOWBIZ_SKIN_NAV_EDITOR'), 'index.php?option=com_uniteshowbiz&view=templates&navigation=1', $vName == 'nav_templates'
        );

        //no need for the categories
        /*
_
          JSubMenuHelper::addEntry(
          JText::_('COM_NIVOSLIDER_SUBMENU_CATEGORIES'),
          'index.php?option=com_categories&extension=com_nivoslider',
          $vName == 'categories'
          );

          if ($vName=='categories') {
          JToolBarHelper::title(
          JText::sprintf('COM_NIVOSLIDER_CATEGORIES_TITLE',JText::_('com_nivoslider')),
          'slider-categories');
          }
         */
    }

}

?>