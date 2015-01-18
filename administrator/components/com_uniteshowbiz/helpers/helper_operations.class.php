<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;


class HelperUniteOperationsShowbiz {

    private $db;

    public function __construct() {
        $this->db = new UniteDBShowbiz();
    }

    /**
     * 
     * get slider raw data
     * @param $sliderID
     */
    private function getSliderRawData($sliderID) {
        $sliderID = (int) $sliderID;

        $rows = $this->db->fetch(GlobalsUniteShowbiz::TABLE_SLIDERS, "id=$sliderID");

        if (empty($rows))
            UniteFunctionsShowbiz::throwError("slider not found: $sliderID");

        $row = $rows[0];
        return($row);
    }

    /**
     * 
     * get slides from the database
     */
    private function getSlidesRawData($sliderID) {
        $sliderID = (int) $sliderID;
        $rows = $this->db->fetch(GlobalsUniteShowbiz::TABLE_SLIDES, "sliderid=" . $sliderID);

        return($rows);
    }

    /**
     * 
     * get slides short data
     */
    public function getSlidesShort($sliderID) {
        $rows = $this->getSlidesRawData($sliderID);

        $arrSlides = array();
        foreach ($rows as $index => $row) {
            $slideID = $row["id"];
            $title = $row["title"];
            $pathImage = $row["image"];
            $info = pathinfo($pathImage);
            $filename = UniteFunctionsShowbiz::getVal($info, "basename");
            $counter = $index + 1;
            $name = "Slide $counter";
            if (!empty($filename))
                $name .= " ($filename)";
            $arrSlides[$slideID] = $name;
        }

        return($arrSlides);
    }

    /**
     * get slider
     */
    public function getSlider($sliderID) {

        $row = $this->getSliderRawData($sliderID);

        $params = UniteFunctionJoomlaBiz::decodeRegistryToArray($row, "params");

        $paramsReg = new JRegistry();
        $paramsReg->loadArray($params);

        $row["params"] = $paramsReg;

        return($row);
    }

}

?>