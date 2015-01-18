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

class menu extends support {
    var $_menu;
    var $_rss;

    function menu($value)
    {
        $this->_menu = $value;
    }

    function setRSS($value)
    {
        $this->_rss = $value;
    }

    function insertButton($text, $link, $icon = '')
    {
        if ($icon) $icon = "<img class='menuicon' src='$icon' alt='$icon' />";
        return "<td class='button'><a id='$text' href='$link'>$icon$text</a></td>";
    }

    function htmlCode()
    {
        $buttons .= $this->insertButton(_JOOMLACOMMENT_ADDNEW, 'javascript:addNew()');
        if (isCommentModerator($this->_moderator)) $buttons .= $this->insertButton(_JOOMLACOMMENT_DELETEALL, 'javascript:deleteAll()');
        $buttons .= $this->insertButton(_JOOMLACOMMENT_SEARCH, 'javascript:searchForm()');
        if ($this->_rss) $buttons .= $this->insertButton(_JOOMLACOMMENT_RSS, "index2.php?option=com_comment&no_html=1&command=rss&contentid=$this->_contentId");
        $html = str_replace('{_COMMENTS_2_4}', _JOOMLACOMMENT_COMMENTS_2_4, $this->_menu);
        $html = str_replace('{buttons}', $buttons, $html);
        return $html;
    }
}

?>