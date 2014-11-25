<?php
/**
 * Hola mundo!
 * @license GNU/GPL, see LICENSE.php
 */
class modContacFormHelper
{ 
     function __construct() {
        parent::__construct();
    }
    
    function getData(){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        
        $query
            ->select($db->quoteName(array('id','tienda','direccion','telefono1','telefono2','estado','url_mapa')))
            ->from($db->quoteName('#__donde_comprar_'))
            ->order('tienda'.' ASC');
        
        $db->setQuery($query);
        
        $result= $db->loadObjectList();

        return $result;
    }   
}