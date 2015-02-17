<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class busquedaViewPdf extends JViewLegacy {

	function display($tpl = null)
	{
		$app 				= JFactory::getApplication();
		$data				= $app->input->getArray();

			$this->data  = $this->get( 'Busquedamodulo' );
			$this->data2 = $this->get('Buscamodelos');

		// Check for errors.

		if (count($errors = $this->get('Errors'))){
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		parent::display($tpl);
	}
}