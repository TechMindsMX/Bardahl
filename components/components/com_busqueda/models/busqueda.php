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
		$query->select( $db->quoteName( 'content_item_id' ) )
		      ->from( $db->quoteName( '#__contentitem_tag_map' ) )
		      ->where( $db->quoteName( 'tag_id' ) . ' = ' . $queryTagId );

        $db->setQuery($query);

        $results = $db->loadObjectList();

		foreach ( $results as $key => $value ) {

            $query 	= $db->getQuery(true);

            $query->select('introtext, title, Images')
                  ->from($db->quoteName('#__content'))
	            ->where( $db->quoteName( 'id' ) . ' = ' . $db->quote( $value->content_item_id ) );

            $db->setQuery($query);

            $result = $db->loadObject();

            self::getFieldsAttach($value);
            $value->title       = $result->title;
            $value->introtext   = $result->introtext;
            $value->images      = json_decode($result->Images);
        }

        return $results;
	}

    public function getBusquedamodulo(){
        $year=$this->inputVars['year'];

        $primero = array(
            '47',
            '48',
            '49',
            '50',
            '51',
            '52',
            '53',
            '54'
        );

        $segundo = array(
            '47',
            '48',
            '49',
            '50',
            '51',
            '52',
            '53',
            '54'
        );

         $tercero =array(
             '47',
             '48',
             '49'
         );

        $respuesta = array();

        if(in_array($year, range(1970, 1988))){
            $arreglo = $primero;
        }
        if(in_array($year, range(1989, 2004))){
            $arreglo = $segundo;
        }
        if(in_array($year, range(2005, 2015))) {
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
                $query->select('b.id as Itemid')
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
            ->join('INNER', $db->quoteName('#__fieldsattach', 'tableb').'ON ('.$db->quoteName('tablea.fieldsid') . ' = ' . $db->quoteName('tableb.id').')')
	        ->where( $db->quoteName( 'tablea.articleid' ) . ' = ' . $db->quote( $objeto->content_item_id ) );
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
