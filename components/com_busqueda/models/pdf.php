<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modelitem');

/**
 * Modelo de datos para busqueda de productos
 */
class busquedaModelPdf extends JModelItem {
    public function __construct(){
        $campos             = array('token'=> 'NUM');
        $this->inputVars    = JFactory::getApplication()->input->getArray($campos);
        parent::__construct();
    }

    public function getData($token){

        $token         = explode('-', $this->inputVars['token']);
        $db		= JFactory::getDbo();
        $query 	= $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('tbl_name'))
            ->where($db->quoteName('id').' = '.$db->quote($token[0]));
        $db->setQuery($query);
        $results = $db->loadObject();
        return $results;
    }
    public function getBusquedamodulo(){

        $array=explode('-', $_GET['kilometraje']);
        $compare    =$array[0];

        $primero = array(
            '47',
            '48',
        );

        $segundo = array(
            '108',
            '52',
            '51',
        );

        $tercero =array(
            '53',
            '54'
        );

        $respuesta = array();

        if($compare == 0){
            $arreglo = $primero;
        }
        if($compare > 50000 or $array < 100000){
            $arreglo = $segundo;
        }
        if($compare > 100000) {
            $arreglo = $tercero;
        }
        foreach ($arreglo as $key => $value) {
            $db		= JFactory::getDbo();
            $query 	= $db->getQuery(true);
            $query->select('*')
                ->from($db->quoteName('#__content'))
                ->where($db->quoteName('id').' = '.$db->quote($value));
            $db->setQuery($query);
            $results[] = $db->loadObject();
        }

        foreach($results as $key => $value){
            if($value<>NULL){
                $query 	= $db->getQuery(true);
                $query->select('*')
                    ->from($db->quoteName('#__categories', 'a'))
                    ->join('INNER', $db->quoteName('#__menu','b').'ON ('.$db->quoteName('a.path') . ' = ' . $db->quoteName('b.path').')')
                    ->where($db->quoteName('a.id').' = '.$db->quote($value->catid));
                $db->setQuery($query);
                $result = $db->loadObject();

                self::getFieldsAttach($value);
                $valor=new stdClass();
                # $valor->path    = $result->path;
                $valor->id      = $value->id;
                $valor->alias   = $value->alias;
                $valor->title   = $value->title;
                $valor->Itemid  = @$result->Itemid;
                $valor->images  = json_decode($value->images);
                $valor->urls    = json_decode($value->urls);
                $respuesta[] = $valor;
            }
        }
        return $respuesta;
    }

    public function getBuscamodelos(){

        $array=explode('-', $_GET['kilometraje']);
        $compare    =$array[0];

        $arreglo = array(
            '125',
            '89',
            '86',
            '85',
            '72'
        );

        foreach ($arreglo as $key => $value) {
            $db		= JFactory::getDbo();
            $query 	= $db->getQuery(true);
            $query->select('*')
                ->from($db->quoteName('#__content'))
                ->where($db->quoteName('id').' = '.$db->quote($value));
            $db->setQuery($query);
            $results[] = $db->loadObject();
        }

        foreach($results as $key => $value){
            if($value<>NULL){
                $query 	= $db->getQuery(true);
                $query->select('*')
                    ->from($db->quoteName('#__categories', 'a'))
                    ->join('INNER', $db->quoteName('#__menu','b').'ON ('.$db->quoteName('a.path') . ' = ' . $db->quoteName('b.path').')')
                    ->where($db->quoteName('a.id').' = '.$db->quote($value->catid));
                $db->setQuery($query);
                $result = $db->loadObject();

                self::getFieldsAttach($value);
                $valor=new stdClass();
                $valor->id      = $value->id;
                $valor->alias   = $value->alias;
                $valor->title   = $value->title;
                $valor->Itemid  = @$result->Itemid;
                $valor->images  = json_decode($value->images);
                $valor->urls    = json_decode($value->urls);
                $respuesta[] = $valor;
            }
        }
        return $respuesta;
    }


    public function getFieldsAttach($objeto){
        $db		= JFactory::getDbo();
        $query 	= $db->getQuery(true);
        $query->select('tableb.title, tablea.value')
            ->from($db->quoteName('#__fieldsattach_values', 'tablea'))
            ->join('INNER', $db->quoteName('#__fieldsattach', 'tableb').'ON ('.$db->quoteName('tablea.fieldsid') . ' = ' . $db->quoteName('tableb.id').')');
	       # ->where( $db->quoteName( 'tablea.articleid' ) . ' = ' . $db->quote( $objeto->content_item_id ) );
        $db->setQuery($query);

        $results = $db->loadObjectList();


        foreach($results as $key => $value){
            $propiedad  = $value->title;
            $valor      = $value->value;
            $objeto->$propiedad = $valor;
        }
    }
}
?>
