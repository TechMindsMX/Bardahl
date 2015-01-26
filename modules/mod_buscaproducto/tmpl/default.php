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
<script type="text/javascript" src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script>
    var marcaArray    = new Array();
    var modeloArray   = '';
    var modelos       = new Array();
    var marca         = '';
    var modelo        = '';
    <?php

            foreach ($marca as $key => $value) {
                echo 'marcaArray['.$key.'] = "'.$value.'";'."\n";
            }

    ?>

    var typeaheadSettings = {
        source: function () {
            return marcaArray;
        },
        minLength:1
    };



    jQuery('#modelo').change(function (){
        var valor = jQuery('#modelo option:selected').text();
        jQuery('#submodelo').html('');
        var peticion = jQuery.ajax({
            url: 'index.php?option=com_busqueda&task=getsubModelo&format=raw',
            type: 'POST',
            data: {modelo: valor},
            success: function (respuesta) {
                jQuery.each(respuesta, function(index, value){
                    jQuery('#submodelo').append('<option value="'+value.Version+'">'+value.Version+'</option>');
                });
            },
            error: function () {
                alert('Se ha producido un error');
            }

        });

    });

    jQuery('#submodelo').change(function (){
        var marca = jQuery('#marca').val();
        var valor = jQuery('#modelo option:selected').text();
        var submodelo = jQuery('#submodelo option:selected').text();
        jQuery.ajax({
            url: 'index.php?option=com_busqueda&task=getdata&format=raw',
            type: 'POST',
            data: {
                marca:      marca,
                modelo:     valor,
                version:  submodelo
            },
            success: function (data) {
                datos = JSON.stringify(data);
                console.log(datos);
                jQuery('#form_buscar').append("<input type='hidden' name='dataPdf' value='"+datos+"'>");
            },
            error: function () {
                alert('Se ha producido un error');
            }

        });
    });



    jQuery('#marca').change(function () {
        var valor = jQuery('#marca').val();
        jQuery('#modelo').html('');
        var peticion = jQuery.ajax({
            url: 'index.php?option=com_busqueda&task=getModelo&format=raw',
            type: 'POST',
            data: {S2nw93: valor},
            success: function (respuesta) {
            jQuery.each(respuesta, function(index, value){
                jQuery('#modelo').append('<option value="'+value.Modelo+'">'+value.Modelo+'</option>');
            });
            },
            error: function () {
                alert('Se ha producido un error');
            }
        });
    });

    jQuery(document).ready(function() {
        jQuery('.typeahead').typeahead(typeaheadSettings);
    });




</script>

<div id='div_buscar'>
    <form id="form_buscar"  name='form1' action='index.php?option=com_busqueda' method='post' enctype='multipart/form-data'
          xmlns="http://www.w3.org/1999/html">
        <label for="titulo">
            <h3> Encuentra tu Aceite</h3>
        </label><br>
        <label for="marca">
            Marca:
        </label><br>
            <input type='text' name='marca' value='' class="typeahead" autocomplete="off" id='marca' required/>
        <div id="display">
        </div><br>
        <label for="modelo">
            Modelo:
        </label><br>
        <select class="typeahead" id="modelo" name="modelo" >
            <optgroup label="Selecciona la Marca de tu auto..."/>
        </select><br>
        <label for="Version">
            Version:
        </label><br>
        <select class="typeahead" id="submodelo" name="submodelo">
            <optgroup label="Selecciona primero la Marca..."/>
        </select><br>

        <label for="year">
            Año:
        </label><br>
        <input type='text' name='year' value='' id='year' required/><br>
        <label for="kilometraje">
            Kilometraje:
        </label><br>
        <select name='kilometraje' id="kilometraje">
            <option value="0-50000">0 a 50,000 kms</option>
            <option value="50001-100000">50,001 a 100,000 kms</option>
            <option value="100001-150000">100,001 a 150,000 kms</option>
            <option value="150001-200000">150,001 a 200,000 kms</option>
            <option value="200001">más de 200,001 kms</option>
        </select>
        <br>
        <div class="suggestionList" id="suggestionsList"> &nbsp; </div>
        <p id="encuentra-producto" align="right">
            <input id="buscar" type='image' name='enviar'align="right" src="templates/t3_bs3_blank/images/site/btn_buscar.png" />
        </p>

    </form>
</div>
<div class="auto">
    <img id="auto"  src="templates/t3_bs3_blank/images/site/mustang.png">
</div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</div>
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

