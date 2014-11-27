<?php

/**

 * @package     Joomla.Site

 * @subpackage  mod_articles_news

 *

 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */



defined('_JEXEC') or die;

$item_heading = $params->get('item_heading', 'h4');
$imagenes=json_decode($item->images);

?>

<div class="latestnewstu_vida ">

    <a itemprop="url" href="<?php echo $item->link ?>">
        <img id="img-tu-vida" src="<?php echo $imagenes->image_intro ?>">
        <br>
        <div id="div-tu-vida">
            <span itemprop="name"><?php echo $item->title; ?></span>
        </div>
    </a>
</div>




<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :

    echo '<a class="readmore" href="'.$item->link.'">'.$item->linkText.'</a>';

endif; ?>

