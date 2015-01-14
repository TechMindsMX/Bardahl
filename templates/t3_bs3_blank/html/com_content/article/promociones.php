
<?php

/**

 * @package     Joomla.Site

 * @subpackage  com_content

 *

 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
jimport('bardahl.rating');
// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');
$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
$item = $this->item;
$imagenes = json_decode($item->images);
?>
<div class="promociones">
    <div class="img_prod">

        <?php if(isset($imagenes->image_intro) and $imagenes->image_intro<>'' ){
           echo "<img src=".$imagenes->image_intro.">";
        }
        ?>

    </div>
    <br>
    <br>
    <div class="col-md-7 texto-promociones">
        <?php echo $item->introtext; ?>
    </div>
</div>
