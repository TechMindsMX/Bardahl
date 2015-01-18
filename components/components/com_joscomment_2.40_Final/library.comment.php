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

class element {
    function get($id)
    {
        return "var element = document.getElementById('$id');";
    }
    function visible($visible)
    {
        $result = '';
        if ($visible) {
            $result .= "element.style.visibility = 'visible';";
            $result .= "element.style.display = '';";
        } else {
            $result .= "element.style.visibility = 'hidden';";
            $result .= "element.style.display = 'none';";
        }
        return $result;
    }
}

class tabRow {
    var $caption;
    var $component;
    var $help;
    var $id;
    function visible($visible = true)
    {
        if ($this->id) {
            echo "<script type='text/javascript'>";
            echo element::get($this->id);
            echo element::visible($visible);
            echo "</script>";
        }
    }
    function htmlCode()
    {
        $cols = "<td align='left' valign='top'><b>$this->caption</b></td>";
        $colspan = ($this->help == false) ? " colspan='2'" : '';
        $cols .= "<td align='left' valign='top'$colspan>$this->component</td>";
        $cols .= ($this->help == false) ? '' : "<td align='left' valign='top' width='50%'>$this->help</td>";
        if ($this->id) $id = " id='$this->id'";
        return "<tr$id>$cols</tr>";
    }
}

class tabRows {
    var $rows = '';
    function addRow(&$row)
    {
        $this->rows .= $row->htmlCode();
    }
    function addTitle($title)
    {
        $this->rows .= "<tr><th colspan='3' class='title'>$title</th></tr>";
    }
    function addSeparator()
    {
        $this->rows .= "<tr><td colspan='3'><hr /></td></tr>";
    }
    function htmlCode()
    {
        return "<table class='adminlist' width='100%' cellpadding='4' cellspacing='2'>$this->rows</table>";
    }
}

class josComment extends mosDBTable {
    var $id = null;
    var $contentid = null;
    var $ip = null;
    var $usertype = null;
    var $date = null;
    var $name = null;
    var $title = null;
    var $comment = null;
    var $published = null;
    var $voting_yes = null;
    var $voting_no = null;
    var $parentid = null;

    function josComment(&$db)
    {
        $this->mosDBTable('#__comment', 'id', $db);
    }
}

function readOnly($readonly)
{
    return ($readonly) ? " readonly='readonly' " : '';
}

function input($tag_name, $tag_attribs, $value, $readonly = false)
{
    $readonly = readOnly($readonly);
    return "<input name='$tag_name' type='text' $tag_attribs value='$value' $readonly/>";
}

function textarea($tag_name, $tag_attribs, $value, $readonly = false)
{
    $readonly = readOnly($readonly);
    return "<textarea name='$tag_name' $tag_attribs $readonly>$value</textarea>";
}

function hidden($tag_name, $value = '')
{
    return "<input type='hidden' name='$tag_name' value='$value' />";
}

function button($tag_name, $value, $onClick)
{
    return "<input type='button' name='$tag_name' value='$value' onclick='$onClick' />";
}

class listBox {
    var $_tagName;
    var $_size;
    var $_multiple;
    var $items = array();
    function listBox($tagName, $size = 5)
    {
        $this->_tagName = $tagName . '[]';
        $this->_size = $size;
    }
    function multiple($value = true)
    {
        $this->_multiple = $value;
    }
    function add($value, $caption, $selected = false)
    {
        $item['caption'] = $caption;
        $item['selected'] = $selected;
        $this->items[$value] = $item;
    }
    function rename($oldCaption, $newCaption)
    {
        $this->items[array_search($oldCaption, $this->items)]['caption'] = $newCaption;
    }
    function htmlCode()
    {
        $multiple = $this->_multiple ? " multiple='multiple'" : '';
        $html = "<select size='$this->_size' name='$this->_tagName' class='inputbox'$multiple>";
        foreach ($this->items as $id => $option) {
            $html .= "<option value='$id' ";
            if ($option['selected']) $html .= "selected='selected'";
            $html .= ">" . ucfirst($option['caption']) . "</option>";
        }
        $html .= '</select>';
        return $html;
    }
}

class dbListBox extends listBox {
    var $selected;
    function dbListBox($tagName, $size = 5)
    {
        $this->listBox($tagName, $size);
        $this->selected = array();
    }
    function selected($values)
    {
        $this->selected = split(',', $values);
    }
    function loadFromDb($query, $optionDbColumn)
    {
        global $database;
        $database->setQuery($query);
        $items = $database->loadAssocList();
        foreach($items as $item) {
            $selected = in_array($item['id'], $this->selected);
            $this->add($item['id'], $item[$optionDbColumn], $selected);
        }
    }
}

function sections($tag_name, $values)
{
    $listBox = new dbListBox($tag_name);
    $listBox->multiple();
    $listBox->selected($values);
    $listBox->add(-1, 'Static Content', in_array(-1, $listBox->selected));
    $listBox->loadFromDb('SELECT id,title FROM #__sections ORDER BY title ASC', 'title');
    return $listBox->htmlCode();
}

function categories($tag_name, $values)
{
    $listBox = new dbListBox($tag_name);
    $listBox->multiple();
    $listBox->selected($values);
    $listBox->loadFromDb('SELECT id,title FROM #__categories WHERE section
REGEXP \'[1-9][0-9]*\' ORDER BY section, title ASC', 'title');
    return $listBox->htmlCode();
}

function usertypes($tag_name, $values = array(), $unregistered = true)
{
    $listBox = new dbListBox($tag_name);
    $listBox->multiple();
    $listBox->selected($values);
    if ($unregistered)
        $listBox->add(-1, 'unregistered', in_array(-1, $listBox->selected));
    $listBox->loadFromDb('SELECT id,name FROM #__usertypes ORDER BY name ASC', 'name');
    $listBox->rename('Superadministrator', 'Super Administrator');
    return $listBox->htmlCode();
}

function onClick($id, $onClick = '')
{
    echo "\n<script type='text/javascript'>";
    echo "document.getElementById('$id').onclick = function(event)\{$onClick};";
    echo "</script>";
}

function isPHP($fileName)
{
    if (strlen($fileName) >= 4) {
        if (strtolower(substr($fileName, -4, 4)) == '.php')
            return true;
    }
    return false;
}

function languageList($path)
{
    $folder = @dir($path);
    $darray = array();
    $darray[] = mosHTML::makeOption('auto', 'autodetect');
    if ($folder) {
        while ($file = $folder->read()) {
            if (isPHP($file))
                $darray[] = mosHTML::makeOption($file, substr($file, 0, strlen($file)-4));
        }
        $folder->close();
    }
    sort($darray);
    return $darray;
}

function folderList($path)
{
    $folder = @dir($path);
    $darray = array();
    if ($folder) {
        while ($file = $folder->read()) {
            if ($file != "." && $file != ".." && is_dir("$path/$file"))
                $darray[] = mosHTML::makeOption($file, $file);
        }
        $folder->close();
    }
    sort($darray);
    return $darray;
}

?>