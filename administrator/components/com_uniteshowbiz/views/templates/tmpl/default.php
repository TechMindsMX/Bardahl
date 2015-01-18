<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;


JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$navigation = null;
$view = JRequest::getVar("navigation");
if ($view) {
    $navigation = '&navigation=1';
    //new item prefix
    $templatesPrefix = "Navigation Template";
    $templatesType = GlobalsShowBiz::TEMPLATE_TYPE_BUTTON;
    $standartOptionsName = "Navigation Options";
    $linkHelp = GlobalsShowBiz::LINK_HELP_TEMPLATES_NAVS;
    $showCustomOptions = false;
    $showClasses = false;
    //set buttons array
    $this->arrOriginalTemplates = BizOperations::getArrInitNavigationTemplates(true);
    $this->arrButtons = array();
    $this->arrButtons["showbiz_left_button_id"] = "Left Button ID";
    $this->arrButtons["showbiz_right_button_id"] = "Right Button ID";
    $this->arrButtons["showbiz_play_button_id"] = "Play Button ID";
} else {
    //new item prefix
    $templatesPrefix = "Item Template";
    $templatesType = GlobalsShowBiz::TEMPLATE_TYPE_ITEM;
    $showCustomOptions = true;
    $showClasses = true;
    $standartOptionsName = "Post Options";
    $linkHelp = GlobalsShowBiz::LINK_HELP_TEMPLATES_ITEMS;
}
$user = JFactory::getUser();
$userId = $user->get('id');

$n = count($this->items);
$filterID = JRequest::getVar("id");
?>
<div class='wrap'>

    <div class="title_line">
        <?php if (!empty($filterID)):
            ?>
            <div class='filter_text'>			
                Filtered results. <a id="button_show_all" class="button-secondary mleft_10" href="<?php echo JRoute::_('index.php?option=com_uniteshowbiz&view=templates' . $navigation); ?>">Show all</a>
            </div>
        <?php endif ?>

        <?php BizOperations::putLinkHelp($linkHelp) ?>

    </div>
    <?php if ($n <= 0): ?>
        No Templates Found
        <br>
    <?php else: ?>
        <form action="<?php echo JRoute::_('index.php?option=com_uniteshowbiz&view=templates'); ?>" method="post" name="adminForm" id="adminForm">
            <div class="clr"> </div>

            <table id="list_templates" class='wp-list-table widefat fixed unite_table_items'>
                <thead>
                    <tr>
                        <th width='25%'>
                            <?php echo JText::_('JGLOBAL_TITLE'); ?>
                        </th>
                        <th width="30%">
                            <?php echo JText::_('COM_UNITESHOWBIZ_CONTENT') ?>
                        </th>
                        <th width=''>
                            <?php echo JText::_('COM_UNITESHOWBIZ_OPERATIONS') ?>
                        </th>
                        <th width="10%">
                            <?php echo JText::_('COM_UNITESHOWBIZ_PREVIEW') ?>
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = count($this->items);
                    foreach ($this->items as $i => $slider) :
                        $templateID = $slider->id;

                        $urlSliderSettings = HelperUniteShowbiz::getViewUrl_Slider($templateID);
                        $urlEditSlides = HelperUniteShowbiz::getViewUrl_Items($templateID);
                        $title = $this->escape($slider->title);
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td><?php echo $title ?></td>
                            <td>
                                <div class="edit_html_button_wrapper">
                                    <a data-id="<?php echo $templateID ?>" data-title="<?php echo $title ?>" href='javascript:void(0)' class="button-secondary button_edit_content">Edit HTML</a>
                                    <span class="loader_round loader_edit_contents" style="display:none;"></span>
                                </div>
                                <a data-id="<?php echo $templateID ?>" href='javascript:void(0)' data-title="<?php echo $title ?>" class="button-secondary button_edit_css">Edit CSS</a>
                                <span class="loader_round loader_edit_css" style="display:none;"></span>
                            </td>
                            <td>
                                <a data-id="<?php echo $templateID ?>" data-title="<?php echo $title ?>" href='javascript:void(0)' class="button-secondary button_rename_template changemargin2 newlineheight">Rename</a>
                                <a data-id="<?php echo $templateID ?>" href='javascript:void(0)' class="button-secondary button_delete_template changemargin newlineheight">Delete</a>
                                <a data-id="<?php echo $templateID ?>" href='javascript:void(0)' class="button-secondary button_duplicate_template changemargin2 newlineheight">Duplicate</a>
                                <a data-id="<?php echo $templateID ?>" href='javascript:void(0)' class="button-secondary button_restore_template changemargin2 newlineheight">Restore</a>
                            </td>
                            <td>
                                <a data-id="<?php echo $templateID ?>" data-title="<?php echo $title ?>" href='javascript:void(0)' class="button-secondary button_preview_template">Preview</a>
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
    <?php endif ?>
    <br>
    <p>			
        <a id="button_create_template" class='button-primary' href='javascript:void(0)'>Create New Template</a>
    </p>
    <br>
</div>
<!--------------------Custom dialog---------------------------------> 
<!--Rename--> 
<div id="dialog_rename" class="dialog_rename_template" title="Rename Title" style="display:none;">

    <div class="mtop_15 mbottom_5">
        Enter new title:
    </div>
    <input type="text" id="template_title" >

</div>
<!--edit HTML-->
<div id="dialog_content" class="dialog_edit_content" title="Edit Template Html" style="display:none;">

    <div id="template_buttons_html" class="template_buttons">

        <b class="opt_title"><?php echo $standartOptionsName ?></b>
        <div class="divide8"></div>

        <?php
        foreach ($this->arrButtons as $name => $title):
            if ($name == "break") {
                echo "<br>";
                continue;
            }
            $buttonClass = "button-option";
            if (strpos($name, "showbiz_wc_") !== false)
                $buttonClass .= " button-woocommerce";
            ?>

            <a class="button-secondary <?php echo $buttonClass ?>" data-placeholder="<?php echo $name ?>" href="javascript:void(0)"><?php echo $title ?></a>
        <?php endforeach ?>

        <div class="clear"></div>
        <?php if ($showCustomOptions == true): ?>

            <hr>

            <div id="template_custom_options_wrapper" class="mtop_10">
                <b class="opt_title">Custom Options</b>
                <div class="divide8"></div>
                <?php foreach ($this->arrWildcards as $name => $title): ?>
                    <a id="template_button_<?php echo $name ?>" class="button-secondary button-option button-custom" data-placeholder="<?php echo $name ?>" href="javascript:void(0)"><?php echo $title ?></a>
                    <?php
                endforeach;
                ?>

                <a id="button_edit_custom_options" class="button-secondary" data-placeholder="" href="javascript:void(0)">Add / Edit</a>

            </div>

        <?php endif ?>

        <?php if ($showClasses == true): ?>
            <hr>

            <div id="template_classes" class="mtop_10">
                <b class="opt_title">Markup Shortcuts</b>
                <div class="divide8"></div>
                <?php
                foreach ($this->arrClasses as $class) {
                    $name = UniteFunctionsBiz::getVal($class, "name");
                    $description = UniteFunctionsBiz::getVal($class, "description");
                    $html = UniteFunctionsBiz::getVal($class, "html");
                    ?>
                    <a class="button-secondary button-class"  title='<?php echo $description ?>' data-html='<?php echo $html ?>' href="javascript:void(0)"><?php echo $name ?></a>
                    <?php
                }
                ?>
            </div>
        <?php endif ?>

    </div>

    <textarea id="textarea_content" class="textarea_content"></textarea>
</div>
<!--edit CSS-->
<div id="dialog_css" class="dialog_edit_css" title="Edit Template CSS" style="display:none; ">

    <div id="template_buttons_css" class="template_buttons">

        <a class="button-secondary" data-placeholder="itemid" href="javascript:void(0)">Item ID</a>

    </div>

    <textarea id="textarea_css" class="textarea_css"></textarea>
</div>

<!--Restore-->
<div id="dialog_restore" class="dialog_restore" title="Restore Template" style="display:none;">

    <div class="mtop_15 mbottom_5">
        Choose template to restore
    </div>

    <?php
    $select = UniteFunctionsBiz::getHTMLSelect($this->arrOriginalTemplates, "", "id='original_template'");
    echo $select;
    ?>
</div>

<!-- custom options dialog  -->

<?php if ($showCustomOptions == true): ?>

    <div id="dialog_add_wildcard" title="Edit Custom Options" style="display:none">
        <br>
        <b>Options List:</b>
        <br>
        <div class="list_custom_options_wrapper">
            <ul id="list_custom_options" class="list_custom_options">
                <?php
                foreach ($this->arrCustomOptions as $arr):
                    $name = $arr["name"];
                    $showName = $arr["title"] . " ({$name})";
                    $placeholder = ShowBizWildcards::PLACEHOLDER_PREFIX . $name;
                    ?>
                    <li>
                        <span class="option_name">
                            <?php echo $showName ?>						
                        </span>
                        <span class="option_operations">
                            <a class="button-secondary button_remove_option float_left" data-placeholder="<?php echo $placeholder ?>" data-optionname="<?php echo $name ?>">Remove</a>
                            <span class="loader_clean loader_remove_option float_left mleft_5 mtop_5" style="display:none;"></span>
                        </span>
                        <span class="clear_both"></span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="add_custom_options">
            <span>Option Title </span>
            <input size="20" type="text" id="new_option_title" value="">

            <span> &nbsp;&nbsp;

                Option Name
            </span>
            <input type="text" id="new_option_name" value="">
            <span>
                &nbsp;&nbsp;

                <input id="button_add_custom_option" type="button" class="button-secondary" value="Add Option" >
            </span>
            <span id="loader_button_add" class="loader_clean float_right mright_50" style="display:none;"></span>

            <div id="custom_options_error_message" style="display:none;" class="unite_error_message"></div>
        </div>
    </div>

<?php endif ?>
<!--Preview--> 
<div id="dialog_preview_sliders" class="dialog_preview_sliders" title="Preview Template" style="display:none;">
    <iframe id="frame_preview_slider" name="frame_preview_slider"></iframe>
</div>

<form id="form_preview" name="form_preview" action="" target="frame_preview_slider" method="post">
    <input type="hidden" name="client_action" value="preview_template">
    <input type="hidden" id="preview_templateid" name="templateid" value="">
</form>
<script type="text/javascript">
    var g_urlAjaxActions = "index.php?option=com_uniteshowbiz&view=templates&layout=ajax";
    jQuery(document).ready(function() {
        ShowBizAdmin.initTemplatesView("<?php echo $templatesType ?>", "<?php echo $templatesPrefix ?>");
    });
</script>
