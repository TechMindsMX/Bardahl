<?php


// no direct access
defined('_JEXEC') or die;

?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="adminForm">
    <table border="1">
        <thead>
            <tr>
                <th>Nombre de Tienda</th>
                <th>Direccion</th>
	            <th>Telefono</th>
	            <th>Lada</th>
                <th>Estado</th>
                <th>URL Google maps</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="tienda" /></td>
                <td><input type="text" name="direccion" /></td>
	            <td><input type="text" name="telefono"/></td>
	            <td><input type="text" name="lada"/></td>
                <td><input type="text" name="estado" /></td>
                <td><input type="text" name="url_mapa" /></td>
            </tr>
        </tbody>
    </table>

    <button class="button-div" type="submit"><?php echo JText::_('Send'); ?></button>
	<input type="hidden" name="option" value="com_dcomprar"/>
    <input type="hidden" name="task" value="guardar"/>
    <?php
    echo JHtml::_('form.token');
    ?>
</form>
<?php    