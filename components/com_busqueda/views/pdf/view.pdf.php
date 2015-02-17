<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('bardahl.createPDF');

class busquedaViewPdf extends JViewLegacy {

	function display($tpl = null)
	{
		$app 				= JFactory::getApplication();
		$data				= $app->input->getArray();
		$this->data = $this->get( 'Busquedamodulo' );

		$this->data2 = $this->get( 'Buscamodelos' );

		$datos = new busquedaModelPdf();

		$this->kilometraje	= $data['kilometraje'];
		$this->registro  				= $datos->getData($data['token']);

		// Check for errors.
		if (count($errors = $this->get('Errors'))){
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		$document = JFactory::getDocument();
		$document->setName('Recomendado');

	/*	$pdf = new PDF();
		$pdf->grabaDatos($this);
*/
		parent::display($tpl);
	}
}