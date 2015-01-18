
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

<div class="head-category">



    <h1>

        Fondos de Pantalla
    </h1>

    <div class="img-desc">
        <div class="img-left">
            <img src="/images/calendario/2015/<?php echo $_GET['img'] ?>">
        </div>
        <div class="img-rigth">
            <a href="/images/calendario/2015/1024/<?php echo $_GET['sJbD'] ?>">1024 x 768</a><br><br><br>
            <a href="/images/calendario/2015/1280/<?php echo $_GET['sJbD'] ?>">1280 x 1024</a><br><br><br>
            <a href="/images/calendario/2015/movil/<?php echo $_GET['sJbD'] ?>">Móvil</a>
        </div>
    </div>
</div>
<br><br><br>
