<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;


jimport('joomla.application.component.view');

class UniteShowbizViewItem extends JMasterViewUniteShowbiz {

    protected $form;
    protected $isNew = true;
    protected $sliderID;

    public function putOptionalField($name, $value = null) {
        UniteFunctionJoomlaBiz::putFormField($this->form, $name, "params", $value);
    }
 
    /**
     * the main disply function
     */
    public function display($tpl = null) {
        ShowbizSliderHelper::addSubmenu("sliders");
        // Initialiase variables.
        $this->form = $this->get("form");

        $this->slide = $this->get('Item');
        $params = new JRegistry();
        $params->loadString($this->slide->params, "json");
        $this->title = $params->get("title", "Slide");
        $this->params = $params;
        $urlImage = $this->params->get("slide_image");
        if ($urlImage)
            $urlImage = UniteFunctionJoomlaBiz::getImageUrl($urlImage);

        //create the style:
        $this->styleLayers = "width:300px;height:200px;";
        if (!empty($urlImage))
            $this->styleLayers .= "background-image:url('{$urlImage}');";

        $this->templates = HelperUniteShowbiz::getTemplates("item");
        $this->nav_templates = HelperUniteShowbiz::getTemplates("nav");

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
        $title = JText::_('COM_UNITESHOWBIZ') . " - " . " Edit Slide " . $this->slide->slide_order . ", title: " . $this->title;
        JToolBarHelper::title($title, 'generic.png');
    }

}

