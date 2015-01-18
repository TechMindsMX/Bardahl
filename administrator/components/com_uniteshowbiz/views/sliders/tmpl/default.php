<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;
?>

<?php
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$user = JFactory::getUser();
$userId = $user->get('id');

$table = new UniteAdminTableBiz($this->state);
$table->addFilter(UniteAdminTableBiz::FILTER_TYPE_PUBLISHED);

if (UniteFunctionJoomlaBiz::isJoomla3())
    $checkAllFunction = "Joomla.checkAll(this)";
?>
<div id="div_debug" style="display:none;"></div>
<div id="error_message" class="unite_error_message" style="display:none;"></div>
<div id="success_message" class="unite_success_message" style="display:none;"></div>
<div class="title_line">
    <div id="icon-options-general" class="icon32"></div>				

    <?php BizOperations::putLinkHelp(GlobalsShowBiz::LINK_HELP_SLIDER); ?>			
</div>
<form action="<?php echo JRoute::_('index.php?option=com_uniteshowbiz&view=sliders'); ?>" method="post" name="adminForm" id="adminForm">

    <div class="clr"> </div>

    <table class="adminlist unite-table">
        <thead>
            <tr>
                <th width="3%">
                    <?php echo JText::_("ID"); ?>
                </th>
                <th>
                    <?php echo JText::_("COM_UNITESHOWBIZ_NAME"); ?>
                </th>
                <th>
                    <?php echo JText::_("COM_UNITESHOWBIZ_ALIAS"); ?>

                </th>
                <th width="455">
                    <?php echo JText::_("COM_UNITESHOWBIZ_ACTIONS"); ?>

                </th>								

                <th width="8%">
                    <?php echo JText::_('COM_UNITESHOWBIZ_PREVIEW') ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="5">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $n = count($this->items);
            foreach ($this->items as $i => $slider) :
                $sliderID = $slider->id;
                $urlEditSlides = HelperUniteShowbiz::getViewUrl_Items($sliderID);
                $urlSliderSettings = HelperUniteShowbiz::getViewUrl_Slider($sliderID);
                $title = $this->escape($slider->title);
                ?>

                <tr class="row<?php echo $i % 2; ?>">

                    <td align="center">
                        <?php echo $slider->id; ?>
                    </td>  
                    <td>             
                        <a href="<?php echo $urlSliderSettings ?>"><?php echo $title ?></a>
                    </td>
                    <td>
                        <?php echo $slider->alias; ?>
                    </td>
                    <td align="center">
                        <a class="greenbutton newlineheight" href="<?php echo $urlSliderSettings ?>"><?php echo JText::_('COM_UNITESHOWBIZ_EDIT_SLIDER') ?></a>
                        <a class="greenbutton newlineheight" href="<?php echo $urlEditSlides ?>"><?php echo JText::_('COM_UNITESHOWBIZ_EDIT_SLIDES') ?></a>

                        <div class="clearme"></div>						
                        <a id="button_delete_<?php echo $sliderID ?>" href="javascript:void(0)" class="button-secondary button_delete_slider changemargin newlineheight">Delete</a>
                        <div class="clearme"></div>
                        <a id="button_duplicate_<?php echo $sliderID ?>" href="javascript:void(0)" class="button-secondary button_duplicate_slider changemargin2 newlineheight">Duplicate</a>
                    </td>
                    <td class="preview">
                        <div data-title="<?php echo $title ?>" title="<?php echo JText::_("COM_UNITESHOWBIZ_PREVIEW") . " " . $title ?>" class="button_slider_preview" id="button_preview_<?php echo $sliderID ?>"></div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        ShowBizAdmin.initSlidersListView();
    });
</script>
<p>			
    <a href="<?php echo JRoute::_("index.php?option=com_uniteshowbiz&view=slider&layout=edit") ?>" class="button-primary"><?php echo JText::_("COM_UNITESHOWBIZ_CREATE_SLIDER") ?></a>
</p>

<?php
HelperUniteShowbiz::includeView("slider/tmpl/dialog_preview_slider.php");
HelperUniteShowbiz::includeView("sliders/tmpl/footer.php");
?>
