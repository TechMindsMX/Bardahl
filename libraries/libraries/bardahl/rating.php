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

    public function getNumber($articleId){
        $number = new stdClass();
        $number->uno=0;
        $number->dos=0;
        $number->tres=0;
        $number->cuatro=0;
        $number->cinco=0;
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('calificacion_productos'))
            ->where($db->quoteName('articleId').' = '.$articleId);

        $db->setQuery($query);
        $results = $db->loadObjectList();

        foreach ($results  as $key => $value) {

            switch ($value->score){
                case 1:
                    $number->uno=$number->uno+1;
                    break;
                case 2:
                    $number->dos=$number->dos+1;
                    break;
                case 3:
                    $number->tres=$number->tres+1;
                    break;
                case 4:
                    $number->cuatro=$number->cuatro+1;
                    break;
                case 5:
                    $number->cinco=$number->cinco+1;
                    break;
            }
        }
        return $number;
    }
}

class blog {
    public  function insertBlog($data){
        $db         = JFactory::getDbo();
        $valores    = array("'".$data['nombre']."'", "'".$data['email']."'","'".$data['mensaje']."'");
        $columnas   = array($db->quoteName('nombre'), $db->quoteName('correo'),$db->quoteName('mensaje'));
        $query 	    = $db->getQuery(true);

        $query->insert($db->quoteName('blog_productos'))
            ->columns($columnas)
            ->values(implode(',',$valores));

        $db->setQuery($query);
        $db->execute();
    }

    public function getBlog(){
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('blog_productos'))
            ->order($db->quoteName('id').' desc');



        $db->setQuery($query, 0, 10);
        $results = $db->loadObjectList();

        return $results;
    }

}