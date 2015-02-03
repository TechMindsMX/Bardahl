<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.form.validation' );
jimport( 'joomla.html.html.bootstrap' );
?>
<script>
    function envio(){
        var identity           = '<?php echo $_POST['identity'] ?>';
        var kilometraje        = '<?php echo $_POST['kilometraje'] ?>';
        var email              = jQuery('.email').val();
        jQuery('#datapdf').attr('action', 'index.php?option=com_busqueda&view=pdf&format=pdf&kilometraje='+kilometraje+'&token='+identity+'&email='+email);
        jQuery( "#datapdf" ).submit();
    }
    jQuery(document).ready(function(){
        jQuery("#enlace").click(function(evento){
            var identity           = '<?php echo $_POST['identity'] ?>';
            var kilometraje           = '<?php echo $_POST['kilometraje'] ?>';
            evento.preventDefault();
            jQuery('#datapdf').attr('action', 'index.php?option=com_busqueda&view=pdf&format=pdf&kilometraje='+kilometraje+'&token='+identity);
            jQuery( "#datapdf" ).submit();
        });
    })
</script>
<?php
if ( is_null( $this->data ) ) {
	echo '	<div id="container" style="display: block"><p>No se encontraron resultados</p></div>';
} else {
	$data = $this->data;

	?>
    <?php if(isset($_POST['year'])){?>
        <form id="datapdf" method="post"></form>
        <div class="category-desc base-desc recomendados">
            <h1> Te recomendamos estos productos para tu:</h1>
            <div class="marca">
                <?php echo $_POST['marca']; ?>
            </div>
            <div class="modelo">
                <?php echo $_POST['modelo']; ?>
            </div>
            <div class="year">
               Año:<?php echo ' '.$_POST['year']; ?>
            </div>
            <div class="kilometraje">
                <?php echo $_POST['kilometraje']; ?> Kms
            </div>
            <div class="conocer">¿Quieres conocer más sobre las caracteristicas y productos recomendados para tu vehículo?</div>

            <form class="contacto" method="post">
                <div class="data">
                    <div class="Table">
                        <div class="Row">
                            <div class='Cel ejemplo'><a href="#" id="enlace" >Ver ejemplo</a></div>
                            <div class='Cel texto-correo'>Correo Electrónico: </div>
                            <div class='Cel input-correo'>
                                <input id="pdf_boton" class="email" type="email" name="email">
                            </div>
                            <div class='Cel pdf-botton'>
                                <input class="pdf_boton" type="button" value="Enviar" onclick="envio()">
                            </div>

                        </div>
                    </div>
                </div>
                <br>
            </form>
        </div>
     <?php } ?>
    <div>

    </div>
    <br>
    <?php
    if(isset($_GET['etiqueta'])){
    ?>
        <div class="busqueda-uso">
            <h1 class="module-title"><span><?php echo $_GET['etiqueta']; ?></span></h1>
            <p>Dentro de gama de productos contamos con lubricantes, aditivos para motor, aditivos para gasolina, líquidos de frenos, grasas automotrices, anticongelantes, productos especializados y cosméticos para diferentes tipos de vehículos.</p>
            <p>A continuación aparecen los productos que te recomendamos de acuerdo a tu selección.  En caso de preguntas o dudas específicas, envíanos un mensaje a través de nuestra sección de contacto y con gusto te asesoraremos.</p>
        </div>
    <?php } ?>

    <h3 class="module-title"><span>Productos Relacionados</span></h3>
	<div id="container" style="display: block">

		<?php
		foreach ( $data as $key => $value ) {
			$imagenes = $value->images;

           $newUrl = ''; # JRoute::_('index.php?option=com_content&view=article&catid='.$value->catid.'&id='.$value->id);

			echo '<div class="cat-article">
				<a href="'.$newUrl.'">
					<img class="article-catimg" src="' . $imagenes->image_fulltext . '">
						<span class="pleca-dorado"><div>' . $value->title . '</div></span>
				</a>
			</div>';


		}
		?>
	</div>

<?php
}