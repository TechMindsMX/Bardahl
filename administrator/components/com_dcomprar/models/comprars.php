<?php

/**
 * @version     1.0.1
 * @package     com_donde_comprar
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      ismael <aguilar_2001@hotmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Donde_comprar records.
 */
class DcomprarModelComprars extends JModelList {
    
    function __construct() {
        parent::__construct();
    }
    
    function getData(){
        return $this->_getList("select * from #__donde_comprar_");        
    }
    
    function introduce($post){
        $db=  JFactory::getDbo();
	    $db->setQuery( "insert into #__donde_comprar_ (tienda, direccion, telefono, lada, estado, url_mapa) "
	                   . "values ('" . $post["tienda"] . "', '" . $post["direccion"] . "'," . $post["telefono"] . ", " . $post["lada"] . ", '" . $post["estado"] . "', '" . $post["url_mapa"] . "')" );
        if ($db->query()){
            return 1;
        }else{
            JError::raiseWarning(100, $db->getErrorMsg());
            return 0;
        }
        
    }      

}
