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

$rating = new rating();
$item = $this->item;
$imagenes = json_decode($item->images);
$score = $rating->getRating($item->id);
?>
<script type="text/javascript" src="libraries/bardahl/js/raty/jquery.raty.js"></script>
<script>
    var ruta = "<?php echo JUri::base(); ?>libraries/bardahl/js/raty/img/"

    jQuery(document).ready(function () {
        jQuery('#calif').raty({
            click: function(score) {
                var request = jQuery.ajax({
                    url :"index.php?option=com_busqueda&task=raty&format=raw",
                    data: {"articleId" : <?php echo $item->id; ?>,
                           "score"     : score
                    },
                    type: 'post'
                });

                request.done(function(result){
                    promedio = parseFloat(result.score);

                    jQuery('#calif').raty({
                        readOnly: true,
                        path  : ruta,
                        score  : promedio
                    });
                });

                request.fail(function (jqXHR, textStatus) {
                    alert('<?php echo JText::_("RATING_ERROR"); ?>');
                });
            },
            score  : parseFloat(<?php echo $score; ?>),
            path  : ruta
        });
    });
</script>
<h3 class="h3_categ"><?php echo $item->category_title;?></h3>
<div class="clearfix"></div>

<h2 class="prod_name"><?php echo $item->title; ?></h2>
<div class="clearfix">&nbsp;</div>

<div class="table-responsive">
    <div class="col-md-5">
        <div class="img_prod">
            <img src="<?php echo $imagenes->image_fulltext; ?>">
        </div>

        <div class="div_icons_prod">
            <?php
                foreach($item->iconos_de_uso as $value){
            ?>
                    <img class="iconos_prod" src="<?php echo $value->image1; ?>" />&nbsp;
            <?php
                }
            ?>
        </div>

        <div id="lbl_fusion2">
	        <a target="_blank" href="images/hoja_de_seguridad/<?php echo $item->hoja_de_seguridad ?>">
            <span class"titulios-apartados">Hoja de Seguridad</span>
            </a>
        </div>

        <div id="lbl_fusion2">
	        <a target="_blank" href="images/ficha_tecnica/<?php echo $item->ficha_tecnica ?>">
                <span class"titulios-apartados">Ficha Tecnica</span>
            </a>
        </div>

        <div id="lbl_fusion2">
            <span class"titulios-apartados">Califica este producto</span>
        </div>
        <div id="raty">
            <div id="calif"></div>
        </div>

        <div id="lbl_fusion2">
            <span class"titulios-apartados">Recomienda a un amigo</span>
        </div>
    </div>
	<div class="col-md-7 texto-producto">
        <?php echo $item->introtext; ?>
    </div>
</div>