<?php/** * @package     Joomla.Site * @subpackage  mod_breadcrumbs * * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved. * @license     GNU General Public License version 2 or later; see LICENSE.txt */defined ('_JEXEC') or die;JHtml::_ ('bootstrap.tooltip');$jinput = JFactory::getApplication ()->input;?><div id='div_buscar'>    <img id="back_producto" src="templates/t3_bs3_blank/images/site/back_bproducto.png"><form id="form_buscar"  name='form1' action='index.php?option=com_busqueda' method='post' enctype='multipart/form-data'      xmlns="http://www.w3.org/1999/html">        <label for="titulo">          <h3> Encuentra tu Aceite</h3>        </label><br>		<label for="marca">			Marca:		</label><br>		<input type='text' name='marca' value='' id='marca'/><br>		<label for="modelo">			Modelo:		</label><br>		<input type='text' name='modelo' value='' id='modelo'/><br>        <label for="year">            Año:        </label><br>        <input type='text' name='year' value='' id='year'/><br>    <label for="kilometraje">			Kilometraje:		</label><br>		<input type='text' name='kilometraje' value='' id='kilometraje'/><br>	</fieldset>	<p id="encuentra-producto" align="right">		<input id="buscar" type='image' name='enviar'align="right" src="templates/t3_bs3_blank/images/site/btn_buscar.png" />	</p>    <img id="auto" src="templates/t3_bs3_blank/images/site/mustang.png"></form></div>