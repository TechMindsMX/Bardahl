<?php
/**
 * Created by PhpStorm.
 * User: Lutek TIM
 * Date: 15/10/14
 * Time: 13:31
 */
defined('JPATH_PLATFORM') or die;

jimport('joomla.factory');

class rating {
    public function insertRating($data){
        $db         = JFactory::getDbo();
        $valores    = array($data['articleId'], $data['score']);
        $columnas   = array($db->quoteName('articleId'), $db->quoteName('score'));
        $query 	    = $db->getQuery(true);

        $query->insert($db->quoteName('calificacion_productos'))
              ->columns($columnas)
              ->values(implode(',',$valores));

        $db->setQuery($query);
        $db->execute();
    }

    public function getRating($articleId){
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
              ->from($db->quoteName('calificacion_productos'))
              ->where($db->quoteName('articleId').' = '.$articleId);

        $db->setQuery($query);
        $results = $db->loadObjectList();

        $total = 0;
        foreach ($results as $key => $value) {
            $total  = $total+$value->score;
        }
        $totalCalificaciones = count($results)==0?1:count($results);
        $result = $total/$totalCalificaciones;

        return $result;
    }
} 