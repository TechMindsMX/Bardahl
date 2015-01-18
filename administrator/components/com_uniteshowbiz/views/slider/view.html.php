<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class UniteShowbizViewSlider extends JMasterViewUniteShowbiz {

    protected $isNew = true;
    protected $sliderID;
    protected $messageOnStart = "";
    protected $outputMain;
	protected $outputParams;
    
	
	
	/**
	 * 
	 * set main settings
	 */
	private function setMainSettings(){
		
	  	$arrValues = $this->slider->params;
		
		$settingsMain = new UniteSettingsAdvancedBiz();
		$settingsMain->addTextBox("title", "","Slider Title",array("description"=>"The title of the slider. Example: Slider1","required"=>"true"));	
		$settingsMain->addTextBox("alias", "","Slider Alias",array("description"=>"The alias that will be used for embedding the slider. Example: slider1","required"=>"true"));
		$settingsMain->addHr();
		
		//set select item template
		$templates = new ShowBizTemplate();
		$arrTemplates = $templates->getArrShortAssoc(GlobalsShowBiz::TEMPLATE_TYPE_ITEM);
	
		$addHtml = "<a href='javascript:void(0)' id='button_edit_item_template' class='button-primary revblue mleft_10'><i class='revicon-pencil-1'></i>Edit</a>";
		$params = array("description"=>"The template that set the look of the item",UniteSettingsBiz::PARAM_ADDTEXT => $addHtml);
		$settingsMain->addSelect("template_id", $arrTemplates, "Item Template", "", $params);
		
		$settingsMain->setStoredValues($arrValues);
		
        $this->outputMain = new UniteSettingsBizProductBiz();
        $this->outputMain->init($settingsMain,"form_slider_main");
		
	}
	
	
	/**
	 * 
	 * set params settings
	 */
	private function setParamsSettings(){
		
	  	$arrValues = $this->slider->params;
	  	
		$settingsParams = new UniteSettingsAdvancedBiz();	
		$settingsParams->loadXMLFile(GlobalsUniteShowbiz::$pathSettings."slider_settings.xml");
		
		//update navigation template
		$templates = new ShowBizTemplate();
		$arrTemplates = $templates->getArrShortAssoc(GlobalsShowBiz::TEMPLATE_TYPE_BUTTON);
		$addHtml = "<a href='javascript:void(0)' id='button_edit_item_template_nav' class='button-secondary mleft_10' style='margin-top:17px;'>Edit</a>";
		
		$settingsParams->updateSettingField("nav_template_id", "items", $arrTemplates);
		$settingsParams->updateSettingField("nav_template_id", UniteSettingsBiz::PARAM_ADDTEXT, $addHtml);
	  	
		$settingsParams->setStoredValues($arrValues);
	  	
		$this->outputParams = new UniteSettingsProductSidebarBiz();
        $this->outputParams->init($settingsParams,"form_slider_params");
        $this->outputParams->isAccordion(true);
        
	}
	
	
    /**
     * the main disply function
     */
    public function display($tpl = null) {
        // Initialiase variables.

        $this->slider = $this->get('Item');
        $this->isNew = ($this->slider->id == 0);
        $model = $this->getModel("Slider");
        $this->templates = $model->getTemplates("item");
        $this->nav_templates = $model->getTemplates("nav");
        $this->sliderID = $this->slider->id;

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        $this->addToolbar();
	        
	  	$this->setParamsSettings();
	  	$this->setMainSettings();
        
        
        
        parent::display($tpl);
    }

    /**
     * 
     * add toolbar
     */
    protected function addToolbar() {
        $title = JText::_('COM_UNITESHOWBIZ') . " - " . JText::_('COM_UNITESHOWBIZ_EDIT_SLIDER_SETTINGS');
        JToolBarHelper::title($title, 'generic.png');
    }

}

