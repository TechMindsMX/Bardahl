<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.form.validation' );
jimport( 'joomla.html.html.bootstrap' );

if ( is_null( $this->data ) ) {
	echo '<p>No se encontraron resultados</p>';
} else {
	$data = $this->data;

	?>

	<div id="container" style="display: block">
		<?php
		foreach ( $data as $key => $value ) {
			$imagenes = $value->images;

			echo '<div class="cat-article">
				<a href="' . JRoute::_( "'index.php?option=com_content&view=article&id=' . $value->content_item_id.'" ) . '">
					<img class="article-catimg" src="' . $imagenes->image_fulltext . '">
						<span class="pleca-dorado"><div>' . $value->title . '</div></span>
				</a>
			</div>';


		}
		?>
	</div>

<?php
}