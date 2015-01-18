<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

/**
 * 
 * get / update params in db
 *
 */
class ShowBizParams extends UniteElementsBaseBiz {

    /**
     * 
     * update settign in db
     */
    public function updateFieldInDB($name, $value) {

        $arrUpdate = array();
        $arrUpdate[$name] = $value;

        $arr = $this->db->fetch(GlobalsShowBiz::$table_settings);
        if (empty($arr)) { //insert to db
            $this->db->insert(GlobalsShowBiz::$table_settings, $arrUpdate);
        } else { //update db
            $id = $arr[0]["id"];
            $this->db->update(GlobalsShowBiz::$table_settings, $arrUpdate, array("id" => $id));
        }
    }

    /**
     * 
     * get field from db
     */
    public function getFieldFromDB($name) {

        $arr = $this->db->fetch(GlobalsShowBiz::$table_settings);

        //dmp("maxim");exit();

        if (empty($arr))
            return("");


        $arr = $arr[0];

        if (array_key_exists($name, $arr) == false)
            UniteFunctionsBiz::throwError("The settings db should cotnain field: $name");

        $value = $arr[$name];
        return($value);
    }

}

?>