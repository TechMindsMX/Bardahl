<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('bardahl.rating');

class busquedaController extends JControllerLegacy {

	public function __construct(){
		$this->app			= JFactory::getApplication();
		$this->input_data	= $this->app->input;

        parent::__construct();
	}

    public function raty(){
        $filtro     = array('articleId' => 'STRING', 'score' => 'STRING');
        $data		= $this->input_data->getArray($filtro);
        $rating     = new rating();
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        $rating->insertRating($data);

        $score = $rating->getRating($data['articleId']);
        $data = $rating->getNumber($data['articleId']);

        $respuesta = array('success'=>true, 'score'=> $score, 'conteo' => $data);

        echo json_encode($respuesta);
    }

    public  function blog(){
        $filtro     = array('nombre' => 'STRING', 'email' => 'STRING', 'mensaje' => 'STRING', 'article' => 'STRING');
        $data		= $this->input_data->getArray($filtro);
        $blog     = new blog();
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        $blog->insertBlog($data);

        $respuesta = array('success'=>true);

        echo json_encode($respuesta);
    }

    public  function getBlog(){
        $filtro     = array('article' => 'STRING');
        $data		= $this->input_data->getArray($filtro);
        $getBlog    = new blog();
        $data       = $getBlog->getBlog($data);
        echo json_encode($data);
    }

    public function getModelo(){

        $filtro     = array('S2nw93' => 'STRING');
        $marca		= $this->input_data->getArray($filtro);
        $getd       = new busqueda();
        $data       = $getd->getModelo($marca);
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        echo json_encode($data);
    }

    public function getsubModelo(){

        $filtro     = array('modelo' => 'STRING', 'marca' => 'STRING');
        $marca		= $this->input_data->getArray($filtro);
        $getd       = new busqueda();
        $data       = $getd->getsubModelo($marca);
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        echo json_encode($data);
    }

    public function getdata(){

        $filtro     = array('modelo' => 'STRING','marca' => 'STRING', 'version' => 'STRING');
        $marca		= $this->input_data->getArray($filtro);
        $getd       = new busqueda();
        $data       = $getd->getdata($marca);
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        echo json_encode($data);
    }

    public function getdataId(){

        $filtro     = array('modelo' => 'STRING','marca' => 'STRING', 'version' => 'STRING');
        $marca		= $this->input_data->getArray($filtro);
        $getd       = new busqueda();
        $data       = $getd->getdataID($marca);
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        echo json_encode($data);
    }

    public function getId(){

        $filtro     = array('modelo' => 'STRING','marca' => 'STRING', 'version' => 'STRING', 'year' => 'STRING');
        $marca		= $this->input_data->getArray($filtro);
        $getd       = new busqueda();
        $data       = $getd->getItem($marca);
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        echo json_encode($data);
    }
}