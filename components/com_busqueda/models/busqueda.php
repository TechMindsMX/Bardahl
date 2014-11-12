<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modelitem');

/**
 * Modelo de datos para busqueda de productos
 */
class busquedaModelbusqueda extends JModelItem {
    public function __construct(){
        $campos             =   array('producto' => 'INT', 'modelo'=> 'ALNNUM', 'kilometraje' => 'INT', 'marca' => 'word', 'etiqueta'=>'word');
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
        $respuesta = array();

        $db		= JFactory::getDbo();
        $query 	= $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('#__content'))
            ->where($db->quoteName('catid').' = '.$db->quote($this->inputVars['producto']));

        $db->setQuery($query);

        $results = $db->loadObjectList();

        foreach($results as $key => $value){
            $query 	= $db->getQuery(true);

            $query->select('b.id as Itemid')
                  ->from($db->quoteName('#__categories', 'a'))
                  ->join('INNER', $db->quoteName('#__menu','b').'ON ('.$db->quoteName('a.path') . ' = ' . $db->quoteName('b.path').')')
                  ->where($db->quoteName('a.id').' = '.$db->quote($value->catid));

            $db->setQuery($query);

            $result = $db->loadObject();

            self::getFieldsAttach($value);
            $value->Itemid  = $result->Itemid;
            $value->images  = json_decode($value->images);
            $value->urls    = json_decode($value->urls);

            if($value->Kilometraje == $this->inputVars['kilometraje']){
                $respuesta[] = $value;
            }elseif($this->inputVars['kilometraje'] === 0){
                $respuesta[] = $value;
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
