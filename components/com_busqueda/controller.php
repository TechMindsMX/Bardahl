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

        $respuesta = array('success'=>true, 'score'=> $score);

        echo json_encode($respuesta);
    }
}