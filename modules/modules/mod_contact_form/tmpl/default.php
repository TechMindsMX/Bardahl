<?php
// No permitir el acceso directo al archivo
defined('_JEXEC') or die;




?>



<!h1 style="text-align: center">Tiendas registradas</h1>
<br>
<br>
<br>
<table style="text-align: center; margin-left: 23%" >
 

    <?php
    
    for($i=0; $i<count($res);$i++){        
    ?>
   <tr > <td>+ <?php echo $res[$i]->tienda; ?></td></tr>
   
    
</tr>
<?php
}
?>
</tbody>
</table>
