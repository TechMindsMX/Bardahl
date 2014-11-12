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
<div style="padding-left: 25%">
    <form style="" action="<?php echo JRoute::_('index.php');?>" method="post" name="adminForm">
        <br>
        <table style="text-align: center" border = "1">    
            <thead>
                <tr>
                    <th colspan="6"><h1 style="color: red">ESTAS SEGURO DE ELIMINAR ESTA COLUMNA</th> 
                </tr>
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
                $res=$this->data_delete;
                for($i=0; $i<count($res);$i++){        
                ?>
                <td><?php echo $res[$i]->tienda; ?></td>
                <td><?php echo $res[$i]->direccion; ?></td>
	            <td><?php echo $res[ $i ]->telefono; ?></td>
	            <td><?php echo $res[ $i ]->Lada; ?></td>
                <td><?php echo $res[$i]->estado; ?></td>
                <td><?php echo $res[$i]->url_mapa; ?>
                    <input type="hidden" name="id" value="<?php echo $res[$i]->id; ?>" />
                </td>            
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        <p style="padding-left: 25%"><br><button  type="submit"><?php echo JText::_('CONTINUAR'); ?></button>
	        <input type="hidden" name="option" value="com_dcomprar"/>
            <input type="hidden" name="task" value="eliminar"/>
            <?php
            echo JHtml::_('form.token');
            ?>
            </p>
    </form>
</div>
    


		
