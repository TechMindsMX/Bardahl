<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_buscaproducto
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
class ModBuscaProductoHelper{

    public function __construct(){
        $campos             =   array('id' => 'INT', 'title'=> 'ALNNUM', 'path' => 'ALNNUM');
        $this->inputVars    = JFactory::getApplication()->input->getArray($campos);

        parent::__construct();
    }

    public static function getList(){


        $db	= JFactory::getDbo();
        $query= $db->getQuery(true);

        $query  ->  select('id, title')
                ->  from('#__categories')
                ->  where('path'.' like '.$db->quote('productos/%'));

       $db->setQuery($query);
       $result=$db->loadObjectList('');


        return $result;

    }

}
?>