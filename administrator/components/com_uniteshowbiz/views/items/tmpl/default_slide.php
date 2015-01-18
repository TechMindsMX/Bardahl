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
?>

<form action="form_slides" method="post" name="adminForm" id="adminForm">

    <input type="hidden" name="sliderid" value="<?php echo $this->sliderID ?>">

    <div class="clr"> </div>
    <div id="loader_reloading" class="text_reloading_page" style="display:none">
        <?php echo JText::_("COM_UNITESHOWBIZ_RELOADING_PAGE") ?>...
    </div>
    <div id="items_list_wrapper" class="postbox box-slideslist">
        <h3>
            <span class='slideslist-title'>Slides List</span>
            <span id="saving_indicator" class='slideslist-loading'>Saving Order...</span>
        </h3>
        <div class="inside">
            <?php if (!empty($this->items)): ?>
                <ul id="list_slides" class="list_slides ui-sortable">
                    <?php
                    foreach ($this->items as $i => $item):
                        $numItem = $i + 1;
                        $html = $this->getSlideHtml($item, $numItem);
                        echo $html;
                    endforeach;
                    ?>
                </ul>
            <?php else: ?>
                <div class="inside">
                    No Slides Found.
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="clear"></div>

</form>
<script type="text/javascript">
    jQuery(document).ready(function() {

        ShowBizAdmin.initSlidesListViewGallery("<?php echo $this->sliderID ?>");

    });

</script>