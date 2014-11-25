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
$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
$item = $this->item;
$separator = explode('}',$item->introtext);
?>
<div class="table-responsive">
    <div class="texto-header">
        <h3>
            <?php echo $item->title; ?>
        </h3>
    </div>
    <br>
    <div class="conteiner-slider">
        <div class="slider-content">
            <?php echo $separator['0'].'}'; ?>
        </div>
    </div>
    <div class="col-md-7 texto-producto">
        <?php echo $separator['1']; ?>
    </div>
</div>