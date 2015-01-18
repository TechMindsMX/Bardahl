<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class UniteShowbizViewSliders extends JMasterViewUniteShowbiz {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * 
     * check if templates installed, if not, install them
     */
    private function checkInstallTemplates(){
    	$tempaltes = new ShowBizTemplate();
    	$arrTemplates = $tempaltes->getList();
    	if(empty($arrTemplates))
    	       	BizOperations::insertOriginalTemplatesToDB(); 
    }
    
    
    public function display($tpl = null) {
    	
    	$this->checkInstallTemplates();
    	
        ShowbizSliderHelper::addSubmenu("sliders");

        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

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

        $title = JText::_('COM_UNITESHOWBIZ') . " - " . JText::_('COM_UNITESHOWBIZ_SLIDERS');
        JToolBarHelper::title($title, 'generic.png');
    }

}