
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
<div class="div-contacto">
    <div class="forma_contacto">
       <div class="header-contacto">
           <div class="form-contacto">
               ¿Quieres ponerte en contacto con nosotros? Mándanos un mensaje y nos pondremos en contacto contigo lo más pronto posible para resolver tus dudas o inquietudes.
           </div>
        <form method="post" class="contacto">
                <div class="data">
                    <table>
                        <tr>
                            <td><label id="label-form">Nombre:</label></td>
                            <td rowspan="8">
                                <div class="contact">
                                    <label id="contact-form">Mensaje:*</label>
                                    <br>
                                    <textarea id="contact-area" cols="30" rows="5" class="mensaje" name="mensaje" ></textarea></div></td>
                        </tr>
                        <tr>
                            <td><input id="idtext" type="text" class="nombre" name="nombre" /></td>
                        </tr>
                        <tr>
                            <td><label id="contact-form">Correo Electrónico:*</label></td>
                        </tr>
                        <tr>
                            <td><input id="idtext" type="text" class="email" name="email" /></td>
                        </tr>
                        <tr>
                            <td><label id="contact-form">Empresa:</label></td>
                        </tr>
                        <tr>
                            <td><input id="idtext" type="text" class="empresa" name="empresa" /></td>
                        </tr>
                        <tr>
                            <td><label id="contact-form">Teléfono:</label></td>
                        </tr>
                        <tr>
                            <td><input id="idtext" type="text" class="telefono" name="telefono" /></td>
                        </tr>
                    </table>
                </div>

                <div class="ultimo">
                    <img src="images/ajax.gif" class="ajaxgif hide" />
                    <div class="msg"></div>
                    <input type="button" class="contacto_boton" value="Enviar" onclick="envio()"/>
                </div>
        </form>
        </div>
        <script>

            function envio(){
                var nombre = jQuery(".nombre").val();
                var email = jQuery(".email").val();
                var validacion_email = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
                var mensaje = jQuery(".mensaje").val();
                var empresa=jQuery(".empresa").val();
                var telefono=jQuery(".telefono").val();
                if (nombre == "") {
                    alert('Ingresa tu nombre');
                    jQuery(".nombre").focus();
                    return false;
                }else if(email == "" || !validacion_email.test(email)){
                    alert('Te falta tu correo, o no es valido');
                    jQuery(".email").focus();
                    return false;
                }else if(mensaje == ""){
                    alert('Nos faltu tu mensaje');
                    jQuery(".mensaje").focus();
                    return false;
                }else {
                    jQuery('.ajaxgif').removeClass('hide');
                    var datos = 'nombre='+ nombre + '&email=' + email + '&mensaje=' + mensaje + '&empresa=' + empresa + '&telefono=' + telefono;
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
    <div class="col-md-7 texto-contacto">
        <?php echo $item->introtext; ?>
    </div>
</div>
