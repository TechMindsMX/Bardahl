<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class UniteShowbizModelSlider extends JModelAdmin {

    public function getTable($type = 'Sliders', $prefix = 'UniteShowbizTable', $config = array()) {
        $table = JTable::getInstance($type, $prefix, $config);
        return $table;
    }

    /**
     * 
     * get the form
     */
    public function getForm($data = array(), $loadData = true) {
        jimport('joomla.form.form');

        // Get the form.
        $form = $this->loadForm('com_uniteshowbiz.slider', 'slider', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public static function getTemplates($type = "item") {
        $view = JRequest::getVar("navigation");
        // Create a new query object.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select("id, title");
        $query->from(GlobalsShowBiz::$table_templates . ' AS a');
        if ($type != "item") {
            $query->where("type='button'");
        } else {
            $query->where("type='item'");
        }
        // Add the list ordering clause.
        $query->order('id ASC');
        $result = $db->setQuery($query);
        return $result->loadObjectList();
    }

}

