<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;


$urlAssets = GlobalsUniteShowbiz::$urlAssets;

$numSliders = count($this->arrSliders);

if ($numSliders == 0) { //error output
    ?>
    <h2>Please add some slider before operating slides</h2>
    <?php
} else {
    ?>
    <div id="div_debug"></div>
    <div id="error_message" class="unite_error_message" style="display:none;"></div>
    <div id="success_message" class="unite_success_message" style="display:none;"></div>
    <?php if (count($this->items) >= 5): ?>
        <div class="slider_control">
            <a id="button_new_slide_top" class='button-primary float_left' href='javascript:void(0)' >New Slide</a>
            <div id="loader_add_slide_top" class="loader_round loader_near_button" style="display:none;"></div>
            <div id="loader_add_slide_top_message" class="success_message float_left mleft_10 mtop_5" style="display:none;"></div>
        </div>
        <div class="clear"></div>
        <div class="vert_sap"></div>
    <?php endif; ?>
    <div class="sliders_list_container">
        <?php echo $this->loadTemplate("slide"); ?>
    </div>
    <div class="vert_sap_medium"></div>
    <div class="slider_control">
        <a class='button-primary' id="button_new_slide" href='javascript:void(0)' >New Slide</a>

        <div id="loader_add_slide_bottom" class="loader_round loader_near_button" style="display:none;"></div>
        <div id="loader_add_slide_bottom_message" class="success_message float_left mtop_5" style="display:none;"></div>

        <span class="hor_sap"></span>
        <a class="button_close_slide button-primary" href='<?php echo Jroute::_("index.php?option=com_uniteshowbiz&view=sliders") ?>' >Close</a>
        <span class="hor_sap"></span>

        <a href="<?php echo JRoute::_("index.php?option=com_uniteshowbiz&view=slider&layout=edit&id=" . $this->sliderID) ?>" id="link_slider_settings">To Slider Settings</a>
    </div>
    <?php
}


HelperUniteShowbiz::includeView("slider/tmpl/dialog_preview_slider.php");
HelperUniteShowbiz::includeView("sliders/tmpl/footer.php");
?>



