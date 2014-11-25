<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
//defined ('_JEXEC') or die;
JHtml::_ ('bootstrap.tooltip');
$jinput = JFactory::getApplication ()->input;
?>
<div id='div_buscar'>
    <form id="form_buscar"  name='form1' action='index.php?option=com_busqueda' method='post' enctype='multipart/form-data'
          xmlns="http://www.w3.org/1999/html">
        <label for="titulo">
            <h3> Encuentra tu Aceite</h3>
        </label><br>
        <label for="marca">
            Marca:
        </label><br>
        <input type='text' name='marca' value='' id='marca'/><br>
        <label for="modelo">
            Modelo:
        </label><br>
        <input type='text' name='modelo' value='' id='modelo'/><br>
        <label for="year">
            Año:
        </label><br>
        <input type='text' name='year' value='' id='year'/><br>
        <label for="kilometraje">
            Kilometraje:
        </label><br>
        <input type='text' name='kilometraje' value='' id='kilometraje'/><br>
        </fieldset>
        <p id="encuentra-producto" align="right">
            <input id="buscar" type='image' name='enviar'align="right" src="templates/t3_bs3_blank/images/site/btn_buscar.png" />
        </p>
        <img id="auto" src="templates/t3_bs3_blank/images/site/mustang.png">
    </form>
</div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div class="redes">
    <div id="facebook">
        <div class="fb-like" data-href="http://bardahl.com.mx/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
    </div>
    <div id="twitter">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://bardahl.com.mx/">Tweet</a>
        <script>
            !function(d,s,id){
                var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
                if(!d.getElementById(id)){
                    js=d.createElement(s);
                    js.id=id;
                    js.src=p+'://platform.twitter.com/widgets.js';
                    fjs.parentNode.insertBefore(js,fjs);
                }
            }(document, 'script', 'twitter-wjs');</script>
    </div>
    <div id="google">
        <!-- Inserta esta etiqueta donde quieras que aparezca Botón +1. -->
        <div class="g-plusone" data-size="medium" data-href="http://bardahl.com.mx/"></div>
        <!-- Inserta esta etiqueta después de la última etiqueta de Botón +1. -->
        <script type="text/javascript">
            window.___gcfg = {lang: 'es-419'};
            (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/platform.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
        </script>
    </div>
</div>
