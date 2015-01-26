<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$mensaje = $_POST['mensaje'];
if(isset($_POST['telefono'])){
    $telefono=$_POST['telefono'];
}
else{
    $telefono="";
}
if(isset($_POST['empresa'])){
    $empresa=$_POST['empresa'];
}else{
    $empresa="";
}

$dest = "webmaster@bardahl.com.mx";
$headers = "From: $nombre <$email>\r\n";
$headers .= "X-Mailer: PHP5\n";
$headers .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


$asunto = "Contacto";
$cuerpo = "Nombre: ".$nombre."<br>";
$cuerpo .= "Email: ".$email."<br>";
$cuerpo .= "Empresa: ".$empresa."<br>";
$cuerpo .= "Telefono: ".$telefono."<br>";
$cuerpo .= "Mensaje: ".$mensaje;
// Esta es una pequena validación, que solo envie el correo si todas las variables tiene algo de contenido:
if($nombre != '' && $email != '' && $mensaje != ''){
    mail($dest,$asunto,$cuerpo,$headers) or die ('No envio el Correo'); //ENVIAR!
}
?>