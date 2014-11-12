<?php

/**
 * @version     1.0.1
 * @package     com_donde_comprar
 * @copyright   Copyright (C) 2014. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      ismael <aguilar_2001@hotmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

class DComprarController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
	    require_once JPATH_COMPONENT . '/helpers/dcomprar.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'comprars');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
    function __construct() {
        parent::__construct();
    }
          
    function guardar(){
        $res=$this->getModel("comprars")->introduce(JRequest::get("post"));
        if($res){
	        $this->setMessage( "Se ha Guardado Correctamente los datos" );
        }else{
            $this->setMessage("Error");
        }
	    $this->setRedirect( "index.php?option=com_dcomprar&view=comprars" );
    }
    
    function actualizar(){
        $res=  $this->getModel("editar")->actualiza(JRequest::get("post"));
        if($res){
	        $this->setMessage( "Se Actualizó los datos Correctamente" );
        }else{
            $this->setMessage("Error");
        }
	    $this->setRedirect( "index.php?option=com_dcomprar&view=comprars" );
    }

    function eliminar(){
        $res=  $this->getModel('eliminar')->delete(JRequest::get("post"));
        if($res){
            $this->setMessage("Se ha eliminado correctamente el registro");
        }else{
            $this->setMessage("Error");
        }
	    $this->setRedirect( "index.php?option=com_dcomprar&view=comprars" );
    }
}
