<?php
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
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="adminForm">
    <table border = "1">    
        <thead>
            <tr>
            <th>Nombre de Tienda</th>
            <th>Dirección</th>
            <th>1er. Telefono</th>
            <th>2do. Telefono</th>
            <th>Estado de la Republica</th>
            <th>URL mapa Google</th>            
            </tr>
            </thead>
            <tbody>
        <tr>
            <?php   
            $res=$this->datawhere;
            for($i=0; $i<count($res);$i++){        
            ?>
            <td><input type="text" name="tienda" value="<?php echo $res[$i]->tienda; ?>"/></td>
            <td><input type="text" name="direccion" value="<?php echo $res[$i]->direccion; ?>"/></td>
	        <td><input type="text" name="telefono1" value="<?php echo $res[ $i ]->telefono; ?>"/></td>
	        <td><input type="text" name="telefono2" value="<?php echo $res[ $i ]->Lada; ?>"/></td>
            <td><input type="text" name="estado" value="<?php echo $res[$i]->estado; ?>"/></td>
            <td><input type="text" name="url_mapa" value="<?php echo $res[$i]->url_mapa; ?>" />
                <input type="hidden" name="id" value="<?php echo $res[$i]->id; ?>" />
            </td>            
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <button class="button-div" type="submit"><?php echo JText::_('Actualizar'); ?></button>
	<input type="hidden" name="option" value="com_dcomprar"/>
        <input type="hidden" name="task" value="actualizar"/>
        <?php
        echo JHtml::_('form.token');
        ?>
</form>
    


		
