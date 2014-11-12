<?php

/** PHPExcel_IOFactory */
require_once 'Classes/PHPExcel/IOFactory.php';

define( '_JEXEC', 1 );
define( 'JPATH_BASE', '../../' );
require_once( JPATH_BASE . '/includes/defines.php' );
require_once( JPATH_BASE . '/includes/framework.php' );

/* Create the Application */
$app = JFactory::getApplication( 'site' );


class ProductUpload
{

	public $objPHPExcel;
	public $csvFile;
	public $filename;

	function __construct () {
		/** Error reporting */
		error_reporting (E_ALL);
		ini_set ('display_errors',
				 TRUE);
		ini_set ('display_startup_errors',
				 TRUE);

		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		$this->objPHPExcel = new PHPExcel();

//		$this->objPHPExcel->getProperties()->setCreator("Ricardo Lyon")
//			->setLastModifiedBy("Ricardo Lyon")
//			->setTitle("Office 2007 XLSX Test Document")
//			->setSubject("Office 2007 XLSX Test Document")
//			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
//			->setKeywords("office 2007 openxml php")
//			->setCategory("Test result file");

		$this->setExcelFile ();
	}

	public function setExcelFile () {
		$this->csvFile = str_replace ('.xlsx',
									  '.csv',
									  $_FILES['file-upload']['name']);
		$this->filename = $_FILES['file-upload']['tmp_name'];
	}

	public function excel2Csv ($objPHPExcel) {

		$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
		$objWriter->save ($this->csvFile);

		return $objWriter;
	}

	public function readFromExcel () {
		$objReader = PHPExcel_IOFactory::createReaderForFile ($this->filename);
		$objReader->setReadDataOnly (true);
		$objPHPExcel = $objReader->load (($this->filename));

		return $objPHPExcel;
	}

	public function readFromCSV () {

		$file = fopen ($this->csvFile,
					   'r');
		while (($data = fgetcsv ($file,
								 50000,
								 ',',
								 '"')) !== false) {
			$arrayRead[] = $data;
		};
		fclose ($file);

		return $arrayRead;
	}
}

$upload = new ProductUpload();

$read = $upload->readFromExcel ();
$upload->excel2Csv ($read);

$array = $upload->readFromCSV ();

$data = new ProcessData($array);

$db = new to_db();


class to_db
{
	public $affected_rows;
	protected $dbpdo;
	protected $dbname = 'friks_bardahl_db';
	protected $hostname = 'localhost';
	protected $username = 'root';
	protected $password = '';

	function __construct () {
		try {
			$this->dbpdo = new PDO("mysql:host=$this->hostname;dbname=$this->dbname",
								   $this->username,
								   $this->password);
			echo "PDO connection object created";
		}
		catch (PDOException $e) {
			echo $e->getMessage ();
		}
	}

	function __destruct () {
		$this->dbpdo = null;
	}

	public function insertData () {
		$stmt = $this->dbpdo->prepare ("INSERT INTO a_pruebas(iconos_uso, presentaciones, hoja_seguridad, ficha_tecnica) VALUES(:iconos_uso,:presentaciones,:hoja_seguridad,:ficha_tecnica)");
		$stmt->execute (array ('iconos_uso' => $this->data, 'presentaciones' => $this->data, 'hoja_seguridad' => $this->data, ':ficha_tecnica' => $this->data));
		$this->affected_rows = $stmt->rowCount ();
	}

}

/**
 * @property array headers
 * @property array data
 * @property array assocArray
 */
class ProcessData extends to_db
{
	public $row;

	/**
	 * @param $array
	 */
	function __construct ($array) {

		try {
			is_array ($array);
		}
		catch (Exception $e) {
			die('Datos incorrectos: ' . $e->getMessage ());
		}

		$this->headers = array_shift( $array );
		$this->data = $array;

		foreach ($array as $this->row) {
			$this->processRow ();
		}

	}

	public function processRow () {


		$this->assocArray = array_combine( $this->headers,
		                                   $this->row );

		$article   = new articleJoomla;
		$articleId = $article->create( $this->assocArray );

		$fields = new fieldsattach( $articleId );
		$result = $fields->create( $this->assocArray );


	}
}


class articleJoomla {

	/**
	 * @param $data
	 *
	 * @return bool
	 */
	public function create( $data ) {

		$jarticle        = new stdClass();
		$jarticle->title = $data['Nombre'];
		$jarticle->alias = JFilterOutput::stringURLSafe( $data['Nombre'] );

		$jarticle->introtext = '<h3>¿Qué es?</h3><p>' . $data['¿Qué es?'] . '</p><h3>¿Para qué tipo de vehículo se recomienda?</h3><p>' . $data['¿Para qué tipo de vehículo se recomienda?'] . '</p><h3>Beneficios</h3><p>' . $data['Beneficios'] . '</p><h3>Recomendaciones de Uso</h3><p>' . $data['Recomendaciones de Uso'] . '</p>';

		$jarticle->state = 1;
		$jarticle->catid = 1;

		$jarticle->created    = JFactory::getDate()->toSQL();
		$jarticle->created_by = JFactory::getUser()->id;

		$jarticle->access   = 1;
		$jarticle->metadata = '';
		$jarticle->language = '*';

		$jarticle->images = '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"images\/icon\/'.$data['Nombre de img de producto'].'","float_fulltext":"","image_fulltext_alt":"'.$data['Nombre'].'","image_fulltext_caption":""}';

		$table = JTable::getInstance( 'content',
		                              'JTable' );
		$data  = (array) $jarticle;

		// Bind data
		if ( ! $table->bind( $data ) ) {
			//Handle the errors here however you like (log, display error message, etc.)
			die( 'error en el bind del articulo' );

			return false;
		}

		// Check the data.
		if ( ! $table->check() ) {
			//Handle the errors here however you like (log, display error message, etc.)
			die( 'error en el check del articulo' );

			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			//Handle the errors here however you like (log, display error message, etc.)
			die('error en el store del articulo');
			return false;
		} else {
			return $this->getLastArticleId();
		}
	}

	public function getLastArticleId() {
		$db = JFactory::getDbo();

		$query = $db->getQuery( true );
		$query->select( 'MAX(' . $db->quoteName( 'id' ) . ')' )
		      ->from( $db->quoteName( '#__content' ) );
		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	}

}

class fieldsattach {
	/* Campos de tabla fieldsattach
		1,SKU
		2,"tipo Producto"
		3,Kilometraje
		4,presentaciones
		5,"iconos de uso"
		6,"hoja de seguridad"
		7,"ficha tecnica"
	*/

	/* Campos tabla fieldsattach_values
		1. articleid
		2. fieldsid
		3. value
	*/

	public $sku;
	public $tipo_producto;
	public $ficha_tecnica;
	public $hoja_seguridad;
	public $kilometraje;
	public $presentaciones;
	public $iconos_uso;

	function __construct( $articleId ) {
		$this->sku = $articleId;
	}

	public function create( $data ) {

		$arrayByKey[4] = $data['Presentaciónes'];
		$arrayByKey[6] = $data['Hoja de Seguridad'];
		$arrayByKey[7] = $data['Ficha Técnica'];

		foreach ( $arrayByKey as $key => $value ) {
			$valores[] = array ( $this->sku, $key, $value );
		}


		foreach ($valores as $key => $value) {

				$profile = new stdClass();
				$profile->articleid = $value[0];
				$profile->fieldsid   = $value[1];
				$profile->value     = $value[2];

			$result = JFactory::getDbo()->insertObject('#__fieldsattach_values', $profile);

		}


		$iconos_uso = $data['Iconos de uso'];

		$iconos_uso = str_replace( ',',
		                           '',
		                           strtolower( $iconos_uso ) );
		$iconos_uso = str_replace( ' y camioneta',
		                           ' ',
		                           $iconos_uso );
		$iconos_uso = str_replace( '.',
		                           '',
		                           $iconos_uso );
		$iconos_uso = str_replace( 'ó',
		                           'o',
		                           $iconos_uso );

		$iconos_uso = explode( ' ',
		                       $iconos_uso );

		$iconos_uso = array_filter( $iconos_uso );

		foreach ( $iconos_uso as $icono ) {
			$objIconosUso                 = new stdClass();
			$objIconosUso->articleid      = $valores[0][0];
			$objIconosUso->fieldsattachid = 5;
			$objIconosUso->catid          = '';
			$objIconosUso->title          = '';
			$objIconosUso->image1         = 'images/' . $icono . '-producto.png';
			$objIconosUso->image2         = '';
			$objIconosUso->image3         = '';
			$objIconosUso->description    = '';
			$objIconosUso->ordering       = 0;
			$objIconosUso->published      = 1;

			$result = JFactory::getDbo()->insertObject( '#__fieldsattach_images',
			                                            $objIconosUso );

		}

		var_dump( $objIconosUso );

	}
}









