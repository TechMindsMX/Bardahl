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

$present= $this->item->presentaciones;

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
                    url :"<?php echo JUri::base(); ?>index.php?option=com_busqueda&task=raty&format=raw",
                    data: {"articleId" : <?php echo $item->id; ?>,
                        "score"     : score
                    },
                    type: 'post'
                });
                request.done(function(result){
                    var promedio    = parseFloat(result.score);
                    jQuery('#calif').raty({
                        readOnly: true,
                        path  : ruta,
                        score  : promedio
                    });
                    document.getElementById('number').innerHTML += '!Gracias por tu voto¡';
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
<div class="title-producto">
    <h1 class="module-title ">
        <?php echo $item->title;   ?>
    </h1>
</div>
<div class="table-responsive">
    <div class="img-prod">


        <img class="img-producto" src="<?php echo $imagenes->image_fulltext; ?>">
        <div class="div_icons_prod">
            <div class="title-uso">Tipos de uso:</div>
            <div class="img-uso">
                <?php
                foreach($item->iconos_de_uso as $value){
                    ?>
                    <img class="iconos_prod" src="<?php echo $value->image1; ?>" />&nbsp;
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="texto-header">
        <h1 class="module-title ">
            Información de Producto
        </h1>
    </div>
    <div class="col-md-5">

        <div class="colum-right">
            <div id="lbl_fusion2">
                <a target="_blank" href="images/hoja-de-seguridad/<?php echo $item->hoja_de_seguridad ?>">
                    <span class"titulios-apartados">Hoja de Seguridad</span>
                </a>
            </div>
            <div id="lbl_fusion2">
                <a target="_blank" href="images/ficha-tecnica/<?php echo $item->ficha_tecnica ?>">
                    <span class"titulios-apartados">Ficha Técnica</span>
                </a>
            </div>
            <div id="lbl_fusion2">
                <span class"titulios-apartados">Presentaciones </span>
            </div>
            <div class="presentaciones">
                <?php echo $present ?>
            </div>
            <div id="lbl_fusion2">
                <span class"titulios-apartados">Califica este producto</span>
            </div>
            <div id="raty" >
                <div id="calif"></div>
                <div id="number"></div>
            </div>
            <div id="lbl_fusion2">
                <span class"titulios-apartados"><a href="mailto:" style="color: #000000"> Recomienda a un amigo</a></span>
            </div>
        </div>
    </div>
    <div class="col-md-7 txt-producto">
        <?php  echo $item->introtext; ?>
    </div>

    <div class="productos-form">
        <div class="fb-comments" data-href="http://www.bardahl.com.mx" data-width="868" data-numposts="10" data-colorscheme="light"></div>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/es_MX/sdk.js#xfbml=1&appId=468581553288902&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

        </script>
    </div>
</div>