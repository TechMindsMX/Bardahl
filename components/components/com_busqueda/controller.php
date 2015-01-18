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
        $filtro     = array('nombre' => 'STRING', 'email' => 'STRING', 'mensaje' => 'STRING');
        $data		= $this->input_data->getArray($filtro);
        $blog     = new blog();
        $document   = JFactory::getDocument();

        $document->setMimeEncoding('application/json');

        $blog->insertBlog($data);

        $respuesta = array('success'=>true);

        echo json_encode($respuesta);
    }

    public  function getBlog(){
        $getBlog    = new blog();
        $data       = $getBlog->getBlog();
        echo json_encode($data);
    }
}