<?php
defined('_JEXEC') or die;

$data    =   new DcomprarModelComprars();
$res     =  $data->getList();

?>
<script>



    function cargar(tienda,direccion,tel,lada,estado,mapa){

        document.getElementById('estado').innerHTML=estado;
        document.getElementById('nombre').innerHTML=tienda;
        document.getElementById('direccion').innerHTML='Direccion:<br>'+direccion;
        document.getElementById('tel').innerHTML='<br>Tel: '+tel;
        document.getElementById('lada').innerHTML=' Lada sin costo: '+lada;


    }
    function cursor(){

    }
</script>


<h2>¿Donde Comprar?</h2>
<br>
<br>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla
<br>
<br><br>
<br>
<div id="conten_dc" >


   <div id="list"> <?php

    for($i=0; $i<10;$i++){
        ?>
        <div onmouseover="this.style.cursor='pointer'" onclick="cargar('<?php echo $res[$i]->tienda; ?>','<?php echo $res[$i]->direccion; ?>','<?php echo $res[$i]->telefono; ?>','<?php echo $res[$i]->Lada; ?>','<?php echo $res[$i]->estado; ?>','<?php echo $res[$i]->url_mapa; ?>');">+ <?php  echo $res[$i]->estado; ?></div>
    <?php
    }
       ?>
   </div><div id="list">
    <?php
    for($i=10; $i<20;$i++){
        ?>
        <div onmouseover="this.style.cursor='pointer'" onclick="cargar('<?php echo $res[$i]->tienda; ?>','<?php echo $res[$i]->direccion; ?>','<?php echo $res[$i]->telefono; ?>','<?php echo $res[$i]->Lada; ?>','<?php echo $res[$i]->estado; ?>','<?php echo $res[$i]->url_mapa; ?>');">+ <?php  echo $res[$i]->estado; ?></div>
    <?php
    }
    ?>
    </div><div id="list2">
        <?php
    for($i=20; $i<count($res)-1;$i++){
        ?>
        <div onmouseover="this.style.cursor='pointer'" onclick="cargar('<?php echo $res[$i]->tienda; ?>','<?php echo $res[$i]->direccion; ?>','<?php echo $res[$i]->telefono; ?>','<?php echo $res[$i]->Lada; ?>','<?php echo $res[$i]->estado; ?>','<?php echo $res[$i]->url_mapa; ?>');">+ <?php  echo $res[$i]->estado; ?></div>
    <?php
    }
    ?>
        </div>
    <hr>

<h2><div id="estado"></div></h2>
    <br>
    <div id="nombre"></div>
    <div id="direccion"></div>
    <div id="tel"></div>
    <div id="lada"></div>

</div>
<h3>¿No encuentras el producto? Llena este formulario:</h3>
<form action="index.php?com_email&pr=com_dcomprar" method="post">
<div id="contact_producto">
    <label>Nombre:*</label>
    <input type="text" id="input_text" name="nombre" >
</div>
<div id="contact_producto">
    <label>Email:*</label>
    <input type="text" id="input_text" name="email" >
</div>
<div id="contact_producto">
    <label>Producto:*</label>
    <input type="text" id="input_text" name="producto" >
</div>
<div>
    <input type="submit" id="btn_enviar" name="Enviar" value="Enviar" >
</div>
</form>

