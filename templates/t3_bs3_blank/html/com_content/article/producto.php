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
         getblog();
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
        <form method="post" class="contacto">
            <fieldset>
                <br/>
                <h3 class="module-title ">
                    <span>Comenta este Producto</span>
                </h3>
                <br/>
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
        <div class="Table" id="Table"></div>
        <script>
            function getblog(){
                var idarticle = 'article='+ <?php echo $item->id ?>;
                jQuery.ajax({
                        type: "POST",
                        url: "<?php echo JUri::base(); ?>index.php?option=com_busqueda&task=getBlog&format=raw",
                        data: idarticle,

                           success: function(data) {

                           var rows = jQuery.parseJSON(data);
                           document.getElementById('Table').innerHTML = '';
                           for (i = 0; i <= rows.length; i++) {

                                document.getElementById('Table').innerHTML += '' +
                                '<div class="Row fila'+i%2+'">' +
                                    '<div class="Cell nombre">'+rows[i].nombre+'</div>' +
                                    '<div class="Cell text">'+rows[i].mensaje+'</div></div>' +
                                '<br>';
                           }
                        },
                        error: function() {
                            jQuery('.ajaxgif').hide();
                            jQuery('.msg').text('Hubo un error!').addClass('msg_error').animate({ 'right' : '130px' }, 300);
                        }
                    });
                }

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
                    var datos = 'nombre='+ nombre + '&email=' + email + '&mensaje=' + mensaje+'&article='+ <?php echo $item->id ?>;
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo JUri::base(); ?>index.php?option=com_busqueda&task=blog&format=raw",
                        data: datos,
                        success: function() {
                            jQuery('.ajaxgif').hide();
                            jQuery('.msg').text('Mensaje enviado!').addClass('msg_ok').animate({ 'right' : '130px' }, 300);
                            getblog();
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