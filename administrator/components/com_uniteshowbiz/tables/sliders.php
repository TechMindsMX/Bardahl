<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

class UniteShowbizTableSliders extends JTable {

    public function __construct(&$db) {
        parent::__construct(GlobalsUniteShowbiz::TABLE_SLIDERS, 'id', $db);
    }

    function bind($array, $ignore = '') {
        $array["params"] = UniteFunctionJoomlaBiz::encodeArrayToRegistry($array, "params");
        if (empty($array['alias'])) {
            $array['alias'] = $array['title'];
        }
        $array['alias'] = UniteFunctionJoomlaBiz::normalizeAlias($array['alias']);
        return parent::bind($array, $ignore);
    }

}
