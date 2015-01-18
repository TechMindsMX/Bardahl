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
require_once('support.php');
require_once('ubbcode.php');

class post extends support {
    var $_post;
    var $_item;
    var $_css;
    var $_tree;
    var $_tree_indent;
    var $_IP_visible;
    var $_IP_partial;
    var $_IP_caption;
    var $_IP_usertypes;
    var $_voting_visible;
    var $_avatar;
    var $_user_id;
    var $_maxlength_text;
    var $_maxlength_word;

    function post($value)
    {
        $this->_post = $value;
    }

    function setItem($value)
    {
        $this->_item = $value;
    }

    function setCSS($value)
    {
        $this->_css = $value;
    }

    function setTree($value)
    {
        $this->_tree = $value;
    }

    function setTree_indent($value)
    {
        $this->_tree_indent = $value;
    }

    function setIP_visible($value)
    {
        $this->_IP_visible = $value;
    }

    function setIP_partial($value)
    {
        $this->_IP_partial = $value;
    }

    function setIP_caption($value)
    {
        $this->_IP_caption = $value;
    }

    function setIP_usertypes($value)
    {
        $this->_IP_usertypes = $value;
    }

    function setVoting_visible($value)
    {
        $this->_voting_visible = $value;
    }

    function setAvatar($value)
    {
        $this->_avatar = $value;
    }

    function setUser_id($value)
    {
        $this->_user_id = $value;
    }

    function setMaxlength_text($value)
    {
        $this->_maxlength_text = $value;
    }

    function setMaxlength_word($value)
    {
        $this->_maxlength_word = $value;
    }

    function highlightAdmin($usertype)
    {
        if (strpos($usertype, 'Administrator'))
            $usertype = "<span class='administrator'>$usertype</span>";
        return $usertype;
    }

    function anonymous($name)
    {
        if ($name == '') $name = _JOOMLACOMMENT_ANONYMOUS;
        return $name;
    }

    function space($title)
    {
        if ($title == '') return '';
        return ' - ';
    }

    function IP($ip, $usertype, $visible, $partial, $caption)
    {
        if (in_array(intUserType($usertype), $this->_IP_usertypes) && $visible) {
            if ($partial) $ip = partialIP($ip);
            return $caption . $ip;
        } else return $this->highlightAdmin($usertype);
    }

    function linkQuote($id)
    {
        return "<a href = 'javascript:quote($id)'>" . _JOOMLACOMMENT_QUOTE . "</a>";
    }

    function linkPost($id)
    {
        return "<a href='javascript:reply($id)'>" . _JOOMLACOMMENT_REPLY . '</a>';
    }

    function linkEdit($id)
    {
        return "<a href='javascript:editComment($id)'>" . _JOOMLACOMMENT_EDIT . '</a>';
    }

    function linkDelete($id)
    {
        return "<a href='javascript:deleteComment($id)'>" . _JOOMLACOMMENT_DELETE . '</a>';
    }

    function voting_cell($mode, $num, $id)
    {
        return "<td><a id='$mode$id' class='voting_$mode' href='javascript:voting($id,\"$mode\")'>$num</a></td>";
    }

    function voting($voting_no, $voting_yes, $id, $contentId)
    {
        $html = '';
        if ($this->_voting_visible) {
            if ($voting_yes == '') {
                $voting_yes = 0;
                $voting_no = 0;
            }
            $html .= "<table cellspacing='0' cellpadding='0' border='0'>";
            $html .= "<tr>" . $this->voting_cell('yes', $voting_yes, $id);
            $html .= "<td>&nbsp;</td>";
            $html .= $this->voting_cell('no', $voting_no, $id) . "</tr>";
            $html .= "</table>";
        }
        $this->_hide = (($voting_no + 1) > (($voting_yes + 1) * 2));
        return $html;
    }

    function parseUBBCode($html)
    {
        $ubbcode = new ubbcode($html);
        $ubbcode->setSupport_emoticons($this->_support_emoticons);
        $ubbcode->setSupport_UBBcode($this->_support_UBBcode);
        $ubbcode->setSupport_pictures($this->_support_pictures);
        $ubbcode->setHide($this->_hide);
        $ubbcode->setEmoticons($this->_emoticons);
        $ubbcode->setEmoticons_path($this->_emoticons_path);
        return $ubbcode->parse();
    }

    function envelope($html, $id, $wrapnum)
    {
        $wrapnum = ($this->_tree) ? $wrapnum : 0;
        $result = "<table class='postcontainer' id='post$id' width='100%' cellpadding='0' cellspacing='0' style='padding-left: $wrapnum;'>";
        $result .= "<tr><td><a name='josc$id'></a>$html</td></tr>";
        $result .= "</table>";
        return $result;
    }

    function setMaxLength($text)
    {
        if ($this->_maxlength_word != -1) $text = wrapText($text, $this->_maxlength_word);
        if (($this->_maxlength_text != -1) && (strlen($text) > $this->_maxlength_text))
            $text = substr($text, 0, $this->_maxlength_text) . '...';
        return $text;
    }

    function profileLink($s, $id)
    {
        global $mosConfig_live_site;
        return $id ? "<a href='$mosConfig_live_site/index.php?option=com_comprofiler&task=userProfile&user=$id'>$s</a>" : $s;
    }

    function htmlCode()
    {
        global $my, $mosConfig_live_site;
        $id = $this->_item['id'];
        $name = filter($this->anonymous($this->_item['name']));
        $title = filter($this->_item['title']);
        $comment = filter($this->_item['comment']);
        $usertype = $this->_item['usertype'];
        $ip = $this->_item['ip'];
        $date = date($this->_date_format, strToTime($this->_item['date']));
        $edit = '';
        if ($this->_tree) $edit = $this->linkPost($id);
        if ($this->_support_UBBcode) {
            if ($edit) $edit .= ' | ';
            $edit .= $this->linkQuote($id);
        }
        if ($my->username && isCommentModerator($this->_moderator, $name, $usertype)) {
            if ($edit) $edit .= ' | ';
            $edit .= $this->linkEdit($id);
            $edit .= ' | ' . $this->linkDelete($id);
        }
        $voting = $this->voting($this->_item['voting_no'], $this->_item['voting_yes'], $id, $this->_contentId);
        $comment = $this->parseUBBCode($comment);
        $comment = $this->setMaxLength($comment);
        $html = $this->_post;
        $html = ignoreBlock($html, 'footer', ((!$my->username && $this->_only_registered) || !$this->_ajax || ($edit == '')));
        $html = ignoreBlock($html, 'avatar', !$this->_avatar);
        if ($this->_avatar) {
            $path = "$mosConfig_live_site/images/comprofiler/$this->_avatar";
            $html = str_replace('{avatar_picture}', $this->profileLink("<img class='avatar' src='$path' alt='avatar' />", $this->_user_id), $html);
        }
        $html = str_replace('{postclass}', 'sectiontableentry' . $this->_css, $html);
        $html = str_replace('{username}', $this->profileLink($name, $this->_user_id), $html);
        $html = str_replace('{space}', $this->space($title), $html);
        $html = str_replace('{title}', $title, $html);
        $html = str_replace('{usertype}', $this->IP($ip, $usertype, $this->_IP_visible, $this->_IP_partial, $this->_IP_caption), $html);
        $html = str_replace('{date}', $date, $html);
        $html = str_replace('{content}', $comment, $html);
        $html = str_replace('{editbuttons}', $edit, $html);
        $html = str_replace('{voting}', $voting, $html);
        return $this->envelope($html, $id, ($this->_item['wrapnum'] * $this->_tree_indent) . 'px');
    }
}

?>
