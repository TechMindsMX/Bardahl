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
    <h3 class="module-title"><span>Productos Relacionados</span></h3>
	<div id="container" style="display: block">
		<?php
		foreach ( $data as $key => $value ) {
			$imagenes = $value->images;
            $url= JRoute::_( 'index.php?option=com_content&view=article&id='.$value->id.':'.$value->alias.'&catid='.$value->Itemid.'&Itemid=116');

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