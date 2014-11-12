;<?php
/**
 * @version     1.0.1
 * @package     com_donde_comprar
 * @copyright   Copyright (C) 2014. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      ismael <aguilar_2001@hotmail.com> - http://
 */

// no direct access
defined('_JEXEC') or die;
?>
<h1 style="text-align: center">Tiendas registradas</h1>
<h2 style="text-align: center">Registros almacenados en la base de datos </h2>
<br>
<br>
<br>
<table style="text-align: center; margin-left: 23%" border = "1">
    <thead style="background-color: #0077b3">
    <tr>
    <th>Nombre de Tienda</th>
    <th>Dirección</th>
	    <th>Telefono</th>
	    <th>Lada</th>
    <th>Estado de la Republica</th>
    <th>URL mapa Google</th>
    <th>Editar</th>
    <th>Eliminar</th>
    </tr>
    </thead>
    <tbody>
<tr >
    <?php
    $res=$this->data;

    for($i=0; $i<count($res);$i++){        
    ?>
    <td><?php echo $res[$i]->tienda; ?></td>
    <td><?php echo $res[$i]->direccion; ?></td>
	<td><?php echo $res[ $i ]->telefono; ?></td>
	<td><?php echo $res[ $i ]->Lada; ?></td>
    <td><?php echo $res[$i]->estado; ?></td>
    <td><?php echo $res[$i]->url_mapa; ?></td>
	<td><a href="index.php?option=com_dcomprar&view=Editar&etr=<?php echo $res[ $i ]->id; ?>">Editar</a></td>
	<td><a href="index.php?option=com_dcomprar&view=Eliminar&etr=<?php echo $res[ $i ]->id; ?>">Eliminar</a></td>
</tr>
<?php
}
?>
</tbody>
</table>
<h3 style="margin-left: 43%"><a href="index.php?option=com_dcomprar&view=Nuevo">Ingresar nuevo Registro</a>

	<h3>

		
