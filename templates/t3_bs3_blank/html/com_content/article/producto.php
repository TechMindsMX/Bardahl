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
$present= $this->presentaciones;
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
<div class="clearfix"></div>
<div class="texto-header">
    <h3>Información del Producto</h3>
</div>
<div class="clearfix">&nbsp;</div>
<div class="table-responsive">
    <div class="col-md-5">
        <div class="img-prod">
            <br/>
            <br/>
            <div class="texto-header">
                <h3><?php echo $item->title;   ?></h3>
            </div>
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
        <div class="colum-right">
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
            </div>
            <div id="lbl_fusion2">
                <span class"titulios-apartados">Recomienda a un amigo</span>
            </div>
        </div>
    </div>
    <div class="col-md-7 txt-producto">
        <?php  echo $item->introtext; ?>
    </div>
    <div class="productos-form">
        <form method="post" class="contacto">
            <fieldset>
                <div class="texto-form">
                    <h3>Comenta este Producto</h3>
                </div>
                <div class="data">
                    <table>
                        <tr>
                            <td>
                                <label id="label-form">Nombre:</label>
                            </td>
                            <td>
                                <input id="idtext" type="text" class="nombre" name="nombre" />
                            </td>

                            <td>
                                <label id="label-form">Email:</label>
                            </td>
                            <td>
                               <input  id="idtext" type="text" class="email" name="email" />
                            </td>

                        </tr>

                    </table>
                </div>
                <br/>
                <div class="contact-textarea"><textarea id="contacto-area" cols="30" rows="5" class="mensaje" name="mensaje" ></textarea></div>
                <div class="ultimo">
                    <img src="images/ajax.gif" class="ajaxgif hide" />
                    <div class="msg"></div>
                    <input type="button" class="boton_envio" value="Enviar" onclick="envio()"/>
                </div>
            </fieldset>
        </form>
        <script>

            function envio(){
                var nombre = jQuery(".nombre").val();
                var email = jQuery(".email").val();
                var validacion_email = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
                var mensaje = jQuery(".mensaje").val();
                if (nombre == "") {
                    jQuery(".nombre").focus();
                    return false;
                }else if(email == "" || !validacion_email.test(email)){
                    jQuery(".email").focus();
                    return false;
                }else if(mensaje == ""){
                    jQuery(".mensaje").focus();
                    return false;
                }else{
                    jQuery('.ajaxgif').removeClass('hide');
                    var datos = 'nombre='+ nombre + '&email=' + email + '&mensaje=' + mensaje;
                    jQuery.ajax({
                        type: "POST",
                        url: "libraries/bardahl/proceso.php",
                        data: datos,
                        success: function() {
                            jQuery('.ajaxgif').hide();
                            jQuery('.msg').text('Mensaje enviado!').addClass('msg_ok').animate({ 'right' : '130px' }, 300);
                        },
                        error: function() {
                            jQuery('.ajaxgif').hide();
                            jQuery('.msg').text('Hubo un error!').addClass('msg_error').animate({ 'right' : '130px' }, 300);
                        }
                    });
                    return false;
                }
            }
        </script>
    </div>
</div>