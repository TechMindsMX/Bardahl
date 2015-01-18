<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$sliderID = $this->slide->slider_id;
?>

<div id="div_debug"></div>
<div id="error_message" class="unite_error_message" style="display:none;"></div>
<div id="success_message" class="unite_success_message" style="display:none;"></div>
<div class="wrap settings_wrap">
    <div id="icon-options-general" class="icon32"></div>

    <div id="slide_params_holder">
        <form name="form_slide_params" id="form_slide_params">		
            <?php echo $this->loadTemplate("slide"); ?>

            <input type="hidden" id="image_url" name="image_url" value="<?php echo $this->params->get("image") ?>" />
        </form>
    </div>

    <div class="vert_sap_medium"></div>
    <div class="slider_control">
        <div class="slide_update_button_wrapper">
            <a href="javascript:void(0)" id="button_save_slide" class="orangebutton">Update Slide</a>
            <div id="loader_update" class="loader_round" style="display:none;">updating...</div>
            <div id="update_slide_success" class="success_message" class="display:none;"></div>
        </div>
        <a id="button_close_slide" href="<?php echo JRoute::_("index.php?option=com_uniteshowbiz&view=items&id=" . $sliderID) ?>" class="button-primary">Close</a>
    </div>
</div>

<div class="vert_sap"></div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        ShowBizAdmin.initEditSlideView(<?php echo $this->slide->id ?>);
    });
</script>
<?php
HelperUniteShowbiz::includeView("slider/tmpl/dialog_preview_slider.php");
HelperUniteShowbiz::includeView("sliders/tmpl/footer.php");
?>
