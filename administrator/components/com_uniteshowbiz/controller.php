<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

class UniteShowbizController extends JControllerUniteBaseBiz {

    protected $default_view = GlobalsUniteShowbiz::VIEW_SLIDERS;
    protected $default_layout = GlobalsUniteShowbiz::LAYOUT_SLIDER;

    /**
     * show some image
     */
    public function showimage() {
        UniteFunctionJoomlaBiz::showImageFromRequest();
        exit();
    }


    /**
     *
     * display some view
     */
    public function display($cachable = false, $urlparams = false) {

        $isJoomla3 = UniteFunctionJoomlaBiz::isJoomla3();
        $urlAssets = GlobalsUniteShowbiz::$urlAssets;
        $document = JFactory::getDocument();
        //add custom scripts

        if ($isJoomla3) {
            JHtml::_('bootstrap.framework');
        } else {
            $document->addScript($urlAssets . "js/jquery.min.js");
        }
//add style
        $document->addStyleSheet($urlAssets . "css/style.css");
        $document->addStyleSheet($urlAssets . "css/admin.css");

//add jquery ui
        $document->addStyleSheet($urlAssets . "css/jui/new/jquery-ui-1.10.3.custom.css?ver=3.6.1");

//add codemirror
        $document->addStyleSheet($urlAssets . "codemirror/codemirror.css");
        $document->addScript($urlAssets . "codemirror/codemirror.js");
        $document->addScript($urlAssets . "codemirror/css.js");

//add custom scripts
        $document->addScript($urlAssets . "js/jquery-ui.min.js?ver=3.6.1");

        $document->addScript($urlAssets . "js/admin.js?ver=3.6.1");
        $document->addScript($urlAssets . "js/jquery.tipsy.js?ver=3.6.1");
        $document->addScript($urlAssets . "js/farbtastic/my-farbtastic.js?ver=3.6.1");
        $document->addScript($urlAssets . "js/codemirror/codemirror.js?ver=3.6.1");

        $document->addScript($urlAssets . "js/codemirror/css.js?ver=3.6.1");

        $document->addScript($urlAssets . "js/codemirror/xml.js?ver=3.6.1");
        $document->addScript($urlAssets . "js/codemirror/overlay.js?ver=3.6.1");
        $document->addScript($urlAssets . "js/showbiz_admin.js?ver=3.6.1");

//add custom plugin files
		$document->addStyleSheet($urlAssets . "css/settings.css");
		$document->addStyleSheet($urlAssets . "css/colors-fresh.css");
		$document->addScript($urlAssets . "js/showbiz_admin.js?ver=3.6.1");
		$document->addScript($urlAssets . "js/settings.js?ver=3.6.1");


//add ajax url:
        $currentView = JRequest::getCmd('view', $this->default_view);
        $ajaxUrl = UniteFunctionJoomlaBiz::getViewUrl($currentView, "ajax");
        $document->addScriptDeclaration("var g_urlAjax='$ajaxUrl';");

        parent::display();

        return $this;
    }

}