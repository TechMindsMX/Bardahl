<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class UniteShowbizViewTemplates extends JMasterViewUniteShowbiz {

    protected $items;
    protected $pagination;
    protected $state;

    
    public function display($tpl = null) {
        
        $this->items = $this->get('Items');
        
        if(empty($this->items)){
        	BizOperations::insertOriginalTemplatesToDB();
        	
        	//redirect to templates view again
        	$viewTemplates = HelperUniteShowbiz::getViewUrl_templates();
        	header("location:{$viewTemplates}");
        	exit();
        }
        
        $view = JRequest::getVar("navigation");
        ShowbizSliderHelper::addSubmenu($view ? "nav_templates" : "templates");

        $this->state = $this->get('State');
        $this->arrOriginalTemplates = BizOperations::getArrInitItemTemplates(true);
        
        $operations = new BizOperations();
        $this->arrButtons = $operations->getArrEditorButtons();
        $objWildcards = new ShowBizWildcards();
        $this->arrWildcards = $objWildcards->getWildcardsSettingNames();
        $this->arrCustomOptions = $objWildcards->getArrCustomOptions();
        $this->arrClasses = $operations->getArrEditorClasses();

//           $this->pagination = $this->get('Pagination');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * 
     * add toolbar
     */
    protected function addToolbar() {
        $view = JRequest::getVar("navigation");
        if ($view) {
            $title = JText::_('COM_UNITESHOWBIZ') . " - " . JText::_('COM_UNITESHOWBIZ_NAV_SKIN');
        } else {
            $title = JText::_('COM_UNITESHOWBIZ') . " - " . JText::_('COM_UNITESHOWBIZ_SKIN');
        }
        JToolBarHelper::title($title, 'generic.png');
    }

}