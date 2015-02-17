<?php
defined('_JEXEC') or die('Restricted access');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento</title>
    <link type="text/css" href="/Bardahl/templates/t3_bs3_blank/css/custom.css" rel="stylesheet">
<style>
    body{
        background-color: #ffffff;
    }
#apDiv1 {
    background-color: #ffffff;
    width: 750px;
}

.titulo{
	text-align:right;
}
#bardahl{
	height: 21px;
	margin: 14px 194px;
	width: 555px;
	line-height: 10px;
	left: 15px;
}
#line{
	 margin: -20px auto;
    width: 154px;
}
#apDiv2 {
	position: absolute;
	left: 574px;
	top: 68px;
	width: 175px;
	height: 20px;
	z-index: 1;
	font-weight: bold;
	color: #000;
}
#apDiv1 .titulo a {
	color: #000;
	font-weight: bold;
}
#apDiv3 {
	 font-weight: bold;
    height: 167px;
    left: 12px;
    line-height: 7px;
    position: absolute;
    top: 84px;
    width: 270px;
    z-index: 2;
}
.normal {
	font-weight: normal;
}
#apDiv4 {
	position: absolute;
	top: 211px;
	width: 748px;
	height: 131px;
	z-index: 3;
	left: 12px;
	line-height: 15px;
}

#apDiv5 {
    background-color: #ffffff;
    color: #000000;
    height: 14px;
    left: -2px;
    position: absolute;
    top: 513px;
    z-index: 4;
}
.footer{
    background-color: #ffffff;
    color: #000000;
    height: 14px;
    left: -107px;
    position: absolute;
    top: 947px;
    z-index: 5;
}

.footer img{
    height: 25px;
    width: 1011px;
}
.fila1 {
    background-color: #e1e8f1;
}

.fila0 {
    background-color: lightblue;
}
.Table {
    background-color: white;
    display: table;
    margin: auto;
	width:100%;
}
.article-catimg, .article_image > img {
	display: block;
	height: 218px;
	margin: 0 auto;
}
.cat-article {
	background-color: #ffffff;
	display: inline-block;
	float: left;
	margin-bottom: 14px;
	margin-left: 31px;
	margin-right: 30px;
	overflow: hidden;
	width: 28%;
}
#info table{
    width: 750px;
}
.blod{
    font-weight: bold;
    display: inline-block;
}
.t3-sidebar .module-title, .module-title, div.titulo-dorado > h3, .texto-header > h3 {
    background-image: none;
}
.pleca-dorado {
    background-image: none;
}
.aceites-recomendados{
    height: 225px;
}
.otros-productos{
    height: 249px;
    margin: 28px auto;
}

.article-catimg, .article_image > img {
    height: 111px;
}
.cat-article {
    height: 148px;
    margin-bottom: 0;
    margin-left: 0;
    margin-right: 0;
    width: 140px;
}
.cat-article .pleca-dorado {
    font-size: 10px;
    height: auto;
    line-height: 1em;
    width: 107px;
}
.imgArticle{
    height: 128px;
}

    .title-productos{
        position: absolute;
    }
</style>
</head>
<body>
<div id="apDiv1">
  <div class="titulo">¿Tienes dudas? Ponte en contacto con nosotros <a href="mailto:webmaster@bardahl.com.mx">webmaster@bardahl.com.mx</a></div>
  <img id="bardahl" src="images/site/line_baner.jpg" alt="line"  style="position:absolute"  align="right" />
    <img id='line' src="images/logo-link.png" alt="bardahl"/>

  </div>
  <div id="apDiv2"><div class="titulo"><a href="http://www.bardahl.com.mx">www.bardahl.com.mx</a></div></div>
<div id="apDiv3">
  <p>Marca: <span class="normal"><?php echo $this->registro->Armadora ?></span></p>
  <p>Modelo: <span class="normal"><?php echo $this->registro->Modelo ?></span></p>
  <p>Año: <span class="normal"><?php echo $this->registro->A ?></span></p>
  <p>Kilomegraje: <span class="normal"><?php echo $this->kilometraje ?></span></p>
  <p>Características del Vehículo:</p>
</div>
<div id="apDiv4">
<div id="info" class="Table">
    <table>
        <tbody>
        <tr class="fila0">
            <td>
                <div class="blod">Estilo:</div><?php echo $this->registro->Estilo ?>
            </td>
            <td>
                <div class="blod">Tipo de Compustible:</div><?php echo $this->registro->Combustible ?>
            </td>
            <td>
                <div class="blod">Consumo en carretera:</div><?php echo $this->registro->carretera ?>
            </td>
        </tr>
        <tr class="fila1">
            <td>
                <div class="blod">Posición de Motor:</div><?php echo $this->registro->Tipomotor ?>
            </td>
            <td>
                <div class="blod">Transmisión:</div><?php echo $this->registro->Transmisi ?>
            </td>
            <td>
                <div class="blod">Consumo mixto:</div><?php echo $this->registro->Consumomezclado ?>
            </td>
        </tr>
        <tr class="fila0">
            <td>
                <div class="blod">Asientos:</div><?php echo $this->registro->Pasajeros ?>
            </td>
            <td>
                <div class="blod">Tipo de transmisión:</div><?php echo $this->registro->TipoTransmisi ?>
            </td>
            <td>
                <div class="blod">Consumo en ciudad:</div><?php echo $this->registro->ConsumoCiudad ?>
            </td>
        </tr>
        <tr class="Row fila1">
            <td>
                <div class="blod">Puertas:</div><?php echo $this->registro->Puertas ?>
            </td>
            <td>
                <div class="blod">Motor:</div><?php echo $this->registro->ConsumoCiudad ?>
            </td>
            <td>
                <div class="blod">Capacidad del Tanque:</div>
            </td>
        </tr>
        <tr class="fila0">
            <td>
                <div class="blod">Peso:</div><?php echo $this->registro->Peso ?>
            </td>
            <td>
                <div class="blod">Número de Cilíndros:</div><?php echo $this->registro->Cilindros ?>
            </td>
            <td>
            </td>
        </tr>
        <tr class="Row fila1">
            <td>
                <div class="blod">Largo:</div><?php echo $this->registro->Largo ?>   mm
            </td>
            <td>
                <div class="blod">Tipo de Motor:</div><?php echo $this->registro->Tipomotor ?>
            </td>
            <td>
            </td>
        </tr>
        <tr class="Row fila0">
            <td>
                <div class="blod">Ancho:</div><?php echo $this->registro->Ancho ?>
            </td>
            <td>
                <div class="blod">Caballos de Fuerza:</div><?php echo $this->registro->Caballaje ?>
            </td>
            <td>
            </td>
        </tr>
        <tr class="Row fila1">
            <td>
                <div class="blod">Alto:</div><?php echo $this->registro->alto ?>
            </td>
            <td>
                <div class="blod">RPM:</div><?php echo $this->registro->RPM ?>
            </td>
            <td>
            </td>
        </tr>
        <tr class="Row fila0">
            <td>
                <div class="blod">Distancia entre ejes:</div><?php echo $this->registro->Tipomotor ?>
            </td>
            <td>
                <div class="blod">Torque:</div>
            </td>
            <td>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="aceites-recomendados">
    <h3 class="module-title"><span>Aceites Lubricantes Recomendados:</span></h3>
	<div id="container" style="display: block">
		<?php

		foreach ( $this->data as $key => $value ) {
			$imagenes = $value->images;

          echo '
            <div class="cat-article">
					<div class="imgArticle"><img class="article-catimg" src="' . $imagenes->image_fulltext . '"></div>
						<span class="pleca-dorado">' . $value->title . '</span>
			</div>';
		}
		?>

	</div>
</div>

<div class="otros-productos">

    <div id="apDiv5"><h3 class="module-title"><span>Otros Productos Recomendados:</span></h3></div>
        <?php

        foreach ( $this->data2 as $key => $value ) {
            $imagenes = $value->images;

            echo '
            <div class="cat-article">
					<div class="imgArticle"><img class="article-catimg" src="' . $imagenes->image_fulltext . '"></div>
						<span class="pleca-dorado">' . $value->title . '</span>
			</div>';
        }
        ?>
    </div>
</div>
<div class="footer">
    <img src="images/site/line_baner.jpg" alt="line"    align="right" />
</div>
</body>
</html>
