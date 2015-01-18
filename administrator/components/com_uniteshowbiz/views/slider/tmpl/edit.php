<?php
/**
 * @package Unite Showbiz Slider for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Load submenu template, using element id 'submenu' as needed by behavior.switcher
$urlAssets = GlobalsUniteShowbiz::$urlAssets;

$sliderID = $this->slider->id;
$urlEditSlides = HelperUniteShowbiz::getViewUrl_Items($sliderID);
$viewTemplates =  HelperUniteShowbiz::getViewUrl_templates();
$viewTemplatesNav = HelperUniteShowbiz::getViewUrl_templatesNav();

?>

<?php
 	$this->outputParams->drawHeaderIncludes();
?>


<script type="text/javascript">
	var g_postTypesWithCats = "";
	var g_jsonTaxWithCats = "";
	
</script>

<input type="hidden" id="sliderid" value="<?php echo $sliderID ?>"></input>

<div class="wrap settings_wrap">
    <div id="div_debug" style="display:none;"></div>
    <div id="error_message" class="unite_error_message" style="display:none;"></div>
    <div id="success_message" class="unite_success_message" style="display:none;"></div>
    <!-- 
    <div class="title_line">
        <div id="icon-options-general" class="icon32"></div>				
        <h2>Edit Slider</h2>
        <?php BizOperations::putLinkHelp(GlobalsShowBiz::LINK_HELP_SLIDER); ?>			
    </div>		
 -->
    <div class="settings_panel">

        <div class="settings_panel_left">

				<?php $this->outputMain->draw(); ?>
				
			<div class="clear"></div>
	
            <div class="vert_sap_medium"></div>
            <?php if ($sliderID) { ?>
                <div id="slider_update_button_wrapper" class="slider_update_button_wrapper">
                    <a class='orangebutton' href='javascript:void(0)' id="button_save_slider" >Update Slider</a>
                    <div id="loader_update" class="loader_round" style="display:none;">updating...</div>
                    <div id="update_slider_success" class="success_message" class="display:none;"></div>
                </div>
                <a id="button_delete_slider" class='button-primary' href='javascript:void(0)' id="button_delete_slider" >Delete Slider</a>
                <a id="button_close_slider_edit" class='button-primary' href='<?php echo JRoute::_('index.php?option=com_uniteshowbiz&view=sliders'); ?>' >Close</a>
                <a href="<?php echo $urlEditSlides ?>" class="greenbutton" id="link_edit_slides">Edit Slides</a>
                <a href="javascript:void(0)" class="button-secondary prpos" id="button_preview_slider" title="Preview Slider">Preview Slider</a>
            <?php } else { ?>
                <a id="button_save_slider" class='button-primary' href='javascript:void(0)' >Create Slider</a>
                <span class="hor_sap"></span>
                <a id="button_cancel_save_slider" class='button-primary' href='<?php echo JRoute::_('index.php?option=com_uniteshowbiz&view=sliders'); ?>' >Close</a>
            <?php } ?>
        </div>
        <div class="settings_panel_right">
			<?php
				$this->outputParams->draw(); 
			?>
        </div>

        <div class="clear"></div>

    </div>

</div>

<?php
HelperUniteShowbiz::includeView("slider/tmpl/dialog_preview_slider.php");
HelperUniteShowbiz::includeView("sliders/tmpl/footer.php");
?>

<script type="text/javascript">
    var g_viewTemplates = '<?php echo $viewTemplates ?>';
    var g_viewTemplatesNav = '<?php echo $viewTemplatesNav ?>';

    jQuery(document).ready(function() {
<?php if ($sliderID) { ?>
            ShowBizAdmin.initEditSliderView();
<?php } else { ?>
            ShowBizAdmin.initAddSliderView();
<?php } ?>
    });

</script>



