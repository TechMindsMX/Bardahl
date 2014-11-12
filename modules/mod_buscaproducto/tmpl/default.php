<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined ('_JEXEC') or die;

JHtml::_ ('bootstrap.tooltip');
$jinput = JFactory::getApplication ()->input;

?>
<form id='form_buscar' name='form1' action='index.php?option=com_busqueda' method='post' enctype='multipart/form-data'>
	<fieldset id='fieldset'>
		<label for="select_producto">
			Tipo de Producto
		</label><br>
		<select id="select_producto" name="producto">
			<option selected>
			</option>
			<?php
			for ($i = 0; $i < count ($result); $i++) {
				print_r ('<option value="' . $result[$i]->id . '">' . $result[$i]->title . '</option>');

			}?>
		</select>
		<br>
		<label for="marca">
			Marca:
		</label><br>
		<input type='text' name='marca' value='' id='marca'/><br>
		<label for="modelo">
			Modelo:
		</label><br>
		<input type='text' name='modelo' value='' id='modelo'/><br>
		<label for="kilometraje">
			Kilometraje:
		</label><br>
		<input type='text' name='kilometraje' value='' id='kilometraje'/><br>
	</fieldset>
	<p id="encuentra-producto">
		<input type='submit' name='enviar' value='Encuentra mi producto'/>
	</p>
</form>
