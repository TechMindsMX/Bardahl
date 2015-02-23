<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modelitem');

/**
 * Modelo de datos para busqueda de productos
 */
class busquedaModelbusqueda extends JModelItem {
    public function __construct(){
        $campos             = array('producto' => 'INT', 'modelo'=> 'ALNNUM', 'kilometraje' => 'INT', 'year' => 'INT', 'marca' => 'word', 'etiqueta'=>'word');
        $this->inputVars    = JFactory::getApplication()->input->getArray($campos);
        parent::__construct();
    }


    public function getBusquedatag(){
        $tag    = $this->inputVars['etiqueta'];
        $db		= JFactory::getDbo();
        $query = $db->getQuery( true );

        $query->select( $db->quoteName( 'id' ) )
              ->from( $db->quoteName( '#__tags' ) )
              ->where( $db->quoteName( 'alias' ) . ' = ' . $db->quote( $tag ) );

        $db->setQuery( $query );
        $queryTagId = $db->loadResult();

        if ( $db->getAffectedRows() === 0 ) {
            return null;
        }

        $query = $db->getQuery( true );
        $query->select( $db->quoteName( array('a.id', 'b.content_item_id' )) )
              ->from( $db->quoteName( '#__content', 'a' ) )
              ->join('INNER', $db->quoteName('#__contentitem_tag_map', 'b') . ' ON ('.$db->quoteName('content_item_id').' = '.$db->quoteName('id').' AND '. $db->quoteName('tag_id').' = '. $queryTagId .' )')
              ->where( $db->quoteName('state') . ' = 1');

        $db->setQuery($query);

        $results = $db->loadObjectList();

        foreach ( $results as $key => $value ) {

            $query 	= $db->getQuery(true);

            $query->select('*')
                  ->from($db->quoteName('#__content'))
                  ->where( $db->quoteName( 'id' ) . ' = ' . $db->quote( $value->content_item_id ) .' AND ' . $db->quoteName('state') . ' = 1');

            $db->setQuery($query);

            $result = $db->loadObject();

            self::getFieldsAttach($value);
            $result->images      = json_decode($result->images);

            $results[$key] = $result;
        }

        return $results;
    }
    public function getBusquedamodulo(){

        $array=explode('-', $this->inputVars['kilometraje']);
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
