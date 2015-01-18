<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('integradora.gettimone');

class busquedaViewbusqueda extends JViewLegacy {

	function display($tpl = null){
		$app 				= JFactory::getApplication();
		$data				= $app->input->getArray();

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

		parent::display($tpl);
	}
}
