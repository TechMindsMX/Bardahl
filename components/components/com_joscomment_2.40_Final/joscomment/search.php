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

class search extends support {
    var $_search;
    var $_keyword;
    var $_phrase;
    var $_counter;
    var $_resultTemplate;

    function search($value)
    {
        $this->_search = $value;
    }

    function setKeyword($value)
    {
        $this->_keyword = addslashes(trim($value));
    }

    function setPhrase($value)
    {
        $this->_phrase = $value;
    }

    function anonymous($name)
    {
        if ($name == '') $name = _JOOMLACOMMENT_ANONYMOUS;
        return $name;
    }

    function searchMatch()
    {
        $result = ($this->_counter == 1) ? _JOOMLACOMMENT_SEARCHMATCH : _JOOMLACOMMENT_SEARCHMATCHES;
        return sprintf($result, $this->_counter);
    }

    function trimResult($html, $word, $size)
    {
        $html = str_replace("\n", '', $html);
        if ($word == '') return '';
        $p = strpos($html, $word);
        if ($p == 0) return substr($html, 0, $size);
        $len = strlen($html);
        $sublen = strlen($word);
        $size = ($size - $sublen) / 2;
        if ($size >= $len) $result = $html;
        else {
            if ($p < $size) $a = $p-1;
            else $a = $size;
            $c = $len - ($p + $sublen);
            if ($c < $size) $b = $c;
            else $b = $size;
            $b = $a + $b + $sublen;
            $result = substr($html, $p - $a, $b);
        }
        return $result;
    }

    function highlightWord($html, $maxSize = -1)
    {
        $html = stripslashes($html);
        if (($this->_phrase == 'any') Or ($this->_phrase == 'all')) {
            $words = split(' ', $this->_keyword);
            if ($maxSize != -1) $html = $this->trimResult($html, $words[0], $maxSize);
            foreach($words as $item) {
                if ($item != '')
                    $html = str_replace($item, "<span>$item</span>", $html);
            }
            return $html;
        } else {
            if ($maxSize != -1) $html = $this->trimResult($html, $this->_keyword, $maxSize);
            return str_replace($this->_keyword, "<span>$this->_keyword</span>", stripslashes($html));
        }
    }

    function addItem($item, $itemCSS)
    {
        $comment = $item['comment'];
        $title = $this->highlightWord($item['title']);
        $name = $this->highlightWord($this->anonymous($item['name']));
        $adress = 'javascript:goToPost(' . $item['contentid'] . ',' . $item['id'] . ')';
        $maxsize = 200;
        if ($maxsize != 0 && strlen($comment) > $maxsize)
            $comment = '...' . $this->highlightWord($comment, $maxsize) . '...';
        else $comment = $this->highlightWord($comment);
        $html = $this->_resultTemplate;
        $html = str_replace('{postclass}', 'sectiontableentry' . $itemCSS, $html);
        $html = str_replace('{title}', "<b>$title</b>", $html);
        $html = str_replace('{_JOOMLACOMMENT_BY}', _JOOMLACOMMENT_BY, $html);
        $html = str_replace('{name}', $name, $html);
        $html = str_replace('{adress}', $adress, $html);
        $html = str_replace('{preview}', $comment, $html);
        $html = str_replace('{date}', date($this->_date_format, strToTime($item['date'])), $html);
        return $html;
    }

    function find($terms)
    {
        global $database;
        $database->setQuery("SELECT * FROM #__comment WHERE $terms ORDER BY date DESC");
        $data = $database->loadAssocList();
        $html = '';
        $itemCSS = 1;
        $this->_counter = 0;
        if ($data == null) return '';
        foreach($data as $item) {
            $html .= $this->addItem($item, $itemCSS);
            $this->_counter++;
            $itemCSS++;
            if ($itemCSS == 3) $itemCSS = 1;
        }
        return $html;
    }

    function terms($list, $term)
    {
        $result = '';
        foreach($list as $item) {
            if ($result != '') $result .= ' OR ';
            $result .= $item . " $term ";
        }
        return $result;
    }

    function anyWords($list)
    {
        $result = '';
        if (!strpos($this->_keyword, ' ')) return $this->terms($list, "LIKE '%$this->_keyword%'");
        $words = split(' ', $this->_keyword);
        foreach($words as $item) {
            if ($item != '') {
                if ($result != '') $result .= ' OR ';
                $result .= $this->terms($list, "LIKE '%$item%'");
            }
        }
        return $result;
    }

    function allWords($list)
    {
        $result = '';
        if (!strpos($this->_keyword, ' ')) return $this->terms($list, "LIKE '%$this->_keyword%'");
        $words = split(' ', $this->_keyword);
        foreach($words as $item) {
            if ($item != '') {
                if ($result != '') $result .= ' AND ';
                $result .= '(' . $this->terms($list, "LIKE '%$item%'") . ')';
            }
        }
        return $result;
    }

    function exactPhrase($list)
    {
        return $this->terms($list, "LIKE '%$this->_keyword%'");
    }

    function htmlCode()
    {
        $html = $this->_search;
        if ($this->_keyword) {
            $list[] = 'name';
            $list[] = 'title';
            $list[] = 'comment';
            if ($this->_phrase == 'any') $terms = $this->anyWords($list);
            if ($this->_phrase == 'all') $terms = $this->allWords($list);
            if ($this->_phrase == 'exact') $terms = $this->exactPhrase($list);
            $this->_resultTemplate = block($html, 'searchresult');
            $results = $this->find($terms);
        } else $results = '';
        $html = str_replace('{resulttitle}', ($results) ? $this->searchMatch() : _JOOMLACOMMENT_NOSEARCHMATCH, $html);
        $html = ignoreBlock($html, 'searchresult', true, $results);
        return $html;
    }
}

?>
