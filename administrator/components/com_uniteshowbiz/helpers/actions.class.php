<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

class ActionsUniteShowbiz {

    private $data = array();
    private $action = "";

    /**
     * 
     * operate actions
     */
    public function operate() {

        $this->action = UniteFunctionsShowbiz::getPostVariable("action");
        if (empty($this->action))
            $this->action = UniteFunctionsShowbiz::getPostVariable("client_action");

        $this->data = UniteFunctionsShowbiz::getPostVariable("data", array());
        $templates = new ShowBizTemplate();
        $wildcards = new ShowBizWildcards();
        $slider = new ShowBizSlider();
        $slide = new BizSlide();
        $operations = new BizOperations();

        try {

            switch ($this->action) {
//                get template content
                case "get_template_content":
                    $content = $templates->getContentFromData($this->data);
                    $arrData = array("content" => $content);
                    UniteFunctionsShowbiz::ajaxResponseData($arrData);
                    break;
//                update template content 
                case "update_template_content":
                    $templates->updateContentFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Content Updated Successfully");
                    break;
//              get template css
                case "get_template_css":
                    $css = $templates->getCssFromData($this->data);
                    $arrData = array("css" => $css);
                    UniteFunctionsShowbiz::ajaxResponseData($arrData);
                    break;
//                update template css
                case "update_template_css":
                    $templates->updateCssFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Css Updated Successfully");
                    break;
//                update template name
                case "update_template_title":
                    $templates->updateTitleFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Title Updated Successfully");
                    break;
//                create template
                case "create_template":
                    $templates->addFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Template Added Successfully");
                    break;
//                duplicate a template
                case "duplicate_template":
                    $templates->duplicateFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Template Duplicated Successfully");
                    break;
//                delete template
                case "delete_template":
                    $templates->deleteFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Template Deleted Successfully");
                    break;
//                restore template
                case "restore_original_template":
                    $templates->restoreOriginalFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Template Restored");
                    break;
//                add wildcard
                case "add_wildcard":
                    $response = $wildcards->addFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Added successfully", $response);
                    break;
//                remove wildcard
                case "remove_wildcard":
                    $response = $wildcards->removeFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Removed successfully", $response);
                    break;
                default:
                    UniteFunctionsShowbiz::ajaxResponseError("ajax action not found: " . $this->action);
                    break;

                case "update_slider":
                    $sliderID = $slider->updateSliderFromOptions($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Slider updated");
                    ;
                    break;
                case "create_slider":
                    $newSliderID = $slider->createSliderFromOptions($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccessRedirect(
                            "The slider successfully created", JUri::base() . 'index.php?option=com_uniteshowbiz&view=slider&layout=edit&id=' . $newSliderID);
                    break;
                case "delete_slider":
                    $slider->deleteSliderFromData($this->data);

                    UniteFunctionsShowbiz::ajaxResponseSuccessRedirect(
                            "The slider deleted", JUri::base() . 'index.php?option=com_uniteshowbiz');
                    break;
                case "duplicate_slider":
                    $slider->duplicateSliderFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccessRedirect(
                            "The duplicate successfully, refreshing page...", JUri::base() . 'index.php?option=com_uniteshowbiz');
                    break;
//                for slide
                case "add_slide":
                    $slider->createSlideFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccessRedirect(
                            "Slide Created, refreshing...", JUri::base() . "index.php?option=com_uniteshowbiz&view=items&id=" . $this->data['sliderid']);
                    break;
                case "update_slide":
                    $slide->updateSlideFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Slide updated");
                    break;
                case "update_slides_order":
                    $slider->updateSlidesOrderFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccess("Order updated successfully");
                    break;
                case "duplicate_slide":
                    $sliderID = $slider->duplicateSlideFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccessRedirect(
                            "Slide Duplicated Successfully", JUri::base() . "index.php?option=com_uniteshowbiz&view=items&id=" . $this->data['sliderID']);
                    break;
                case "toggle_slide_state":
                    $currentState = $slide->toggleSlideStatFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseData(array("state" => $currentState));
                    break;
                case "delete_slide":
                    $slide->deleteSlideFromData($this->data);
                    UniteFunctionsShowbiz::ajaxResponseSuccessRedirect(
                            "Slide Deleted Successfully,  refreshing page...", JUri::base() . "index.php?option=com_uniteshowbiz&view=items&id=" . $this->data['sliderID']);
                    break;
                case "change_slide_image":
                    $slide->updateSlideImageFromData($this->data);

                    UniteFunctionsShowbiz::ajaxResponseSuccess("Image Changed");
                    break;
                case "preview_template":
                    $templateID = UniteFunctionsBiz::getPostGetVariable("templateid");
                    $operations->previewTemplateOutput($templateID);
                    break;
                case "preview_slider":
                    $sliderID = UniteFunctionsBiz::getPostVariable("sliderid");
                    $operations->previewOutput($sliderID);
                    break;
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            UniteFunctionsShowbiz::ajaxResponseError($message);
        }
        exit();
    }

}

