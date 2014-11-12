<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controlleradmin');

/**
 * 
 */
class busquedaControllerbusqueda extends JControllerAdmin {

	public function getModel($name = 'busqueda', $prefix = 'busquedaModel')
	{
	        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
	        return $model;
	}	

}

