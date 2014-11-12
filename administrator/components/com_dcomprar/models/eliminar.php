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
class DcomprarModelEliminar extends JModelList {
    
    function __construct() {
        parent::__construct();
    }
    
    function getDataDelete($etr){        
        return $this->_getList("select * from #__donde_comprar_ where id='".$etr."'");
    }
        
    function delete($post){
    $db=  JFactory::getDbo();
    $db->setQuery("DELETE FROM #__donde_comprar_ WHERE #__donde_comprar_.id = ".$post["id"]);                                                             
    if ($db->query()){
        return 1;
    }else{
        JError::raiseWarning(100, $db->getErrorMsg());
        return 0;
    }

    return 1;
    }
    
    
}
