<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class UniteShowbizViewItems extends JMasterViewUniteShowbiz {

    protected $items;
    protected $pagination;
    protected $arrSliders;
    protected $sliderID;

    public function display($tpl = null) {
        ShowbizSliderHelper::addSubmenu("sliders");

        $this->items = $this->get('Items');

        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $this->arrSliders = $this->get("ArrSliders");
        $this->sliderID = $this->get("SliderID");

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
     * additems toolbar
     */
    protected function addToolbar() {
        //$sliderTitle = $this->arrSliders[$this->sliderID]["title"];
        $arrSlider = HelperUniteShowbiz::getSlider($this->sliderID);
        $sliderTitle = $arrSlider["title"];

        $title = JText::_('COM_UNITESHOWBIZ') . " - " . $sliderTitle . " - ";
        $title .= "<small>[" . JText::_('COM_UNITESHOWBIZ_SLIDES') . "]</small>";

        JToolBarHelper::title($title, 'generic.png');
    }

    /**
     * 
     * get slide list item html
     */
    public function getSlideHtml($item, $numItem) {

        $sliderID = $item->slider_id;
        $slideid = $itemID = $item->id;
        $order = $item->slide_order;
        $img_file = null;
        //get params
        $params = new JRegistry();
        $params->loadString($item->params, "json");

        $urlRoot = JURI::root();

        //get image url's:
        $imageUrl = $params->get("slide_image");
        if ($imageUrl) {
            $image = UniteFunctionJoomlaBiz::getImageFilename($imageUrl);

            $thumbUrl = UniteFunctionJoomlaBiz::getImageOutputUrl($image, 200, 100, true);
            $imageUrl = $urlRoot . $image;
            $img_file = " (" . pathinfo($imageUrl, PATHINFO_BASENAME) . ")";
        }
//edit by Trung Turong			
        $itemTitle = $params->get("title", "Slide") . $img_file;

        $itemTitle = htmlspecialchars($itemTitle);

        $linkItem = HelperUniteShowbiz::getViewUrl_Item($sliderID, $itemID);

        $state = $params->get("state", "published");

        $imageAlt = stripslashes($params->get("title", "Slide"));

        ob_start();
        $linkEdit = UniteFunctionsBiz::getHtmlLink($linkItem, $itemTitle);

        $isJoomla3 = UniteFunctionJoomlaBiz::isJoomla3();
        ?>
        <li id="slidelist_item_<?php echo $itemID; ?>" class="ui-state-default">
            <span class="slide-col col-order">
                <span class="order-text"><?php echo $order ?></span>
                <div class="state_loader" style="display:none;"></div>
                <?php if ($state == "published"): ?>
                    <div class="icon_state state_published" data-slideid="<?php echo $slideid ?>" title="Unpublish Slide"></div>
                <?php else: ?>
                    <div class="icon_state state_unpublished" data-slideid="<?php echo $slideid ?>" title="Publish Slide"></div>
                <?php endif ?>

            </span>

            <span class="slide-col col-name">
                <?php echo $linkEdit ?>
                <a class='button_edit_slide greenbutton' href='<?php echo $linkItem ?>'>Edit Slide</a>
            </span>

            <span class="slide-col col-image">
                <?php if (!empty($imageUrl)): ?>
                    <div id="slide_image_<?php echo $slideid ?>" class="slide_image"  alt="<?php echo $imageAlt ?>" style="background-image:url('<?php echo $thumbUrl ?>')">
                        <input id="jform_params_image_<?php echo $slideid; ?>" type="hidden">
                        <a class="modal empty_slide_image" style="display: block; background: transparent"
                           rel="{handler: 'iframe', size: {x: 800, y: 500}}" 
                           href="index.php?option=com_media&view=images&tmpl=component&asset=com_uniteshowbiz&author=&fieldid=jform_params_image_<?php echo $slideid ?>&folder=" >

                        </a>
                    </div>
                <?php else: ?>

                    <div id="slide_image_<?php echo $slideid ?>" class="empty_slide_image">
                        <input id="jform_params_image_<?php echo $slideid; ?>" type="hidden">
                        <a class="modal empty_slide_image" style="display: block"
                           rel="{handler: 'iframe', size: {x: 800, y: 500}}" 
                           href="index.php?option=com_media&view=images&tmpl=component&asset=com_uniteshowbiz&author=&fieldid=jform_params_image_<?php echo $slideid ?>&folder=">
                            No image, click to set.
                        </a>

                    </div>

                <?php endif ?>
            </span>

            <span class="slide-col col-operations">
                <a id="button_delete_slide_<?php echo $slideid ?>" class='button-secondary button_delete_slide' href='javascript:void(0)'>Delete</a>
                <a id="button_duplicate_slide_<?php echo $slideid ?>" class='button-secondary button_duplicate_slide' href='javascript:void(0)'>Duplicate</a>
            </span>

            <span class="slide-col col-handle">
                <div class="col-handle-inside">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                </div>
            </span>	
            <div class="clear"></div>
        </li>
        <script>
            function jInsertFieldValue(value, id) {
                var old_id = document.id(id).value;
                var slideID = id.replace("jform_params_image_", "");
                if (old_id != id) {
                    var elem = document.id(id);
                    elem.value = value;
                    elem.fireEvent("change");
                    var data = {slider_id: <?php echo $sliderID ?>,
                        slide_id: slideID,
                        slide_image: value,
                    };
                    UniteAdminBiz.ajaxRequest("change_slide_image", data, function() {
                        location.reload(true);
                    });
                }
            }

        </script>
        <?php
        $content = ob_get_contents();
        ob_clean();
        ob_end_clean();

        return($content);
    }

}
