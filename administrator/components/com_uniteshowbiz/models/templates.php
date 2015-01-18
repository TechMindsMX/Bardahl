<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class UniteShowbizModelTemplates extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'ordering', 'a.ordering',
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information.
        parent::populateState('a.title', 'asc');
    }

    protected function getStoreId($id = '') {
        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $id = JRequest::getVar("id");
        $view = JRequest::getVar("navigation");

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select("*");
        $query->from(GlobalsShowBiz::$table_templates . ' AS a');
        if ($view) {
            $query->where("type='button'");
        } else {
            $query->where("type='item'");
        }
        if ((int) $id) {
            $query->where("a.id = $id");
        } else {
            // Add the list ordering clause.
            $query->order('id ASC');
        }
        return $query;
    }

}
