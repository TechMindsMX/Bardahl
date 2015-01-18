<?php defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/**
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */

require_once('utils.php');

class template {
    var $_live_site;
    var $_absolute_path;
    var $_template_path = '';
    var $_name = '';
    var $_title = '';
    var $_post = '';
    var $_search = '';
    var $_searchResults = '';
    var $_form = '';

    function template($name)
    {
        $this->_name = $name;
    }

    function loadFromFile()
    {
        $fileName = $this->_absolute_path .'/templates/'. $this->_name . '/index.html';
        if (file_exists($fileName)) {
            $file = fopen ($fileName, 'r');
            $template = fread ($file, filesize($fileName));
            fclose($file);
            return $template;
        } else die ('!JoomlaComment template not found: ' . $this->_name);
    }

    function CSS()
    {
        insertToHead('<link rel="stylesheet" href="' . $this->_live_site . '/templates/' . $this->_name . '/css/template_css.css" type="text/css" />');
    }

    function parse()
    {
        $template = $this->loadFromFile();
        $this->_menu = block($template, 'menu');
        $this->_search = block($template, 'search');
        $this->_searchResults = block($template, 'searchresults');
        $this->_post = block($template, 'post');
        $this->_form = block($template, 'form');
    }
}

?>
