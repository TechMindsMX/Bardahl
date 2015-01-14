<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.form.validation' );
jimport( 'joomla.html.html.bootstrap' );

if ( is_null( $this->data ) ) {
	echo '	<div id="container" style="display: block"><p>No se encontraron resultados</p></div>';
} else {
	$data = $this->data;

	?>
    <?php if(isset($_POST['year'])){?>
        <div class="category-desc base-desc recomendados">
            <h3> Te recomendamos estos productos para tu</h3>
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
                <?php echo $_POST['kilometraje']; ?>
            </div>
        </div>
     <?php } ?>
    <div>

    </div>
    <br>
    <?php
    if(isset($_GET['etiqueta'])){
        switch($_GET['etiqueta']){
            case 'automovil':
                $etiqueta='Automovil';
                break;
            case 'camion':
                $etiqueta='Camion';
                break;
            case 'trailer':
                $etiqueta='Trailer';
                break;
            case 'motocicleta':
                $etiqueta='Motocicleta';
                break;
            case 'lancha':
                $etiqueta='Lancha';
                break;
            case 'tractor':
                $etiqueta='Tractor';
                break;
        }
    ?>

        <div class="busqueda-uso">
            <h3 class="module-title"><span><?php echo $etiqueta; ?></span></h3>
            <p>Dentro de gama de productos contamos con lubricantes, aditivos para motor, aditivos para gasolina, líquidos de frenos, grasas automotrices, anticongelantes, productos especializados y cosméticos para diferentes tipos de vehículos.</p>
            <p>A continuación aparecen los productos que te recomendamos de acuerdo a tu selección.  En caso de preguntas o dudas específicas, envíanos un mensaje a través de nuestra sección de contacto y con gusto te asesoraremos.</p>
        </div>
    <?php } ?>

    <h3 class="module-title"><span>Productos Relacionados</span></h3>
	<div id="container" style="display: block">

		<?php
		foreach ( $data as $key => $value ) {
			$imagenes = $value->images;
            $value->path;
            $url= JRoute::_( $value->path.'/'.$value->id.'-'.$value->alias);

			echo '<div class="cat-article">
				<a href="'.$url.'">
					<img class="article-catimg" src="' . $imagenes->image_fulltext . '">
						<span class="pleca-dorado"><div>' . $value->title . '</div></span>
				</a>
			</div>';


		}
		?>
	</div>

<?php
}