
<?php

/**

 * @package     Joomla.Site

 * @subpackage  com_content

 *

 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */


defined("_JEXEC") or die("Restricted access");


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

<div class="table-responsive">
    <div class="img_prod">
           <?php if ($imagenes->image_intro){ ?> <img src="<?php

            echo $imagenes->image_intro;


            ?>">
        <?php } ?>
    </div>
<br>
   <div class="texto-header">
       <h3 class="module-title landig-tab">
          <span> <?php echo $item->title; ?></span>
       </h3>
    </div>
    <br>
	<div class="col-md-7 texto-producto">

        <?php echo $item->introtext; ?>

    </div>

    <div class="relation back">
        <h3 class="module-title landig-tab">
            <span> Relacionados</span>
        </h3>
    </div>

</div>
<br><br><br>
