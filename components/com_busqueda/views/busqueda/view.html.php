<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class busquedaViewbusqueda extends JViewLegacy {

	function display($tpl = null){


		$this->aviso		 = '';
		$app 				 = JFactory::getApplication();
		$data				 = $app->input->getArray();

		if(isset($_GET['back'])){
			$data			= $_SESSION['varPost'];
			$this->aviso	= 'Se ha enviado un correo a su direccion con informaicon de su automovil..';
		}
		$this->varPost	= (object) $data;
        if(isset($data['etiqueta'])){
	        $this->data = $this->get( 'Busquedatag' );
        }else{
	        $this->data = $this->get( 'Busquedamodulo' );
        }
		// Check for errors.
        if (count($errors = $this->get('Errors'))){
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
        }
		$_SESSION['varPost'] = $data;
		parent::display($tpl);
		return true;
	}
}
