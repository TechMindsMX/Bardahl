<?php
//defined( '_JEXEC' ) or die( 'Restricted access' );
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

$emailDestination = "aguilar_2001@hotmail.com";
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


if($nombre != '' && $email != '' && $mensaje != ''){

    $sent = mail($emailDestination,$asunto,$cuerpo,$headers) or die ('No envio el Correo'); //ENVIAR!
    if($sent){
        $user_message = "Your email has been sent.";
    }else{
        $user_message = "There was a problem sending your email.";
    }
}
?>