<?php

/**
 * @version     1.0.0
 * @package     com_dcomprar
 * @copyright   Copyright (C) 2014. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      ismael <aguilar_2001@hotmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Dcomprar records.
 */
class DcomprarModelComprars extends JModelList
{
    public function __construct(){
        $campos             =   array('' => 'INT', 'modelo'=> 'ALNNUM', 'kilometraje' => 'INT', 'marca' => 'word', 'etiqueta'=>'word');
        $this->inputVars    = JFactory::getApplication()->input->getArray($campos);

        parent::__construct();
    }



    public  function getList(){


        $db	= JFactory::getDbo();
        $query= $db->getQuery(true);

        $query  ->  select('*')
            ->  from('#__donde_comprar_');



        $db->setQuery($query);


        $result=$db->loadObjectList();

        return $result;

    }

}