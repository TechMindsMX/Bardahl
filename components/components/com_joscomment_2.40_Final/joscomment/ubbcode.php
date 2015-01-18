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

require_once('support.php');

class ubbcode extends support {
    var $_comment;

    function ubbcode($value)
    {
        $this->_comment = $value;
    }

    function parseEmoticons($html)
    {
        foreach ($this->_emoticons as $ubb => $icon) {
            $html = str_replace($ubb, "<img src='" . $this->_emoticons_path . '/' . $icon . "' border='0' alt='$i' />", $html);
        }
        return $html;
    }

    function parseImgElement($html)
    {
        return preg_replace('/\[img\](.*?)\[\/img\]/i', '<img src=\'\\1\' alt=\'Posted image\' />', $html);
    }

    function parseQuoteElement($html)
    {
        $q1 = substr_count($html, "[/quote]");
        $q2 = substr_count($html, "[quote=");
        if ($q1 > $q2) $quotes = $q1;
        else $quotes = $q2;
        $patterns = array("/\[quote\](.+?)\[\/quote\]/is",
            "/\[quote=(.+?)\](.+?)\[\/quote\]/is");
        $replacements = array("<div class='quote'><div class='genmed'><b>" . _JOOMLACOMMENT_UBB_QUOTE . "</b></div><div class='quotebody'>\\1</div></div>",
            "<div class='quote'><div class='genmed'><b>\\1 " . _JOOMLACOMMENT_UBB_WROTE . "</b></div><div class='quotebody'>\\2</div></div>");
        while ($quotes > 0) {
            $html = preg_replace($patterns, $replacements, $html);
            $quotes--;
        }
        return $html;
    }

    function code_unprotect($val)
    {
        $val = str_replace("{ : }", ":", $val);
        $val = str_replace("{ ; }", ";", $val);
        $val = str_replace("{ [ }", "[", $val);
        $val = str_replace("{ ] }", "]", $val);
        $val = str_replace(array("\n\r", "\r\n"), "\r", $val);
        $val = str_replace("\r", '&#13;', $val);
		return filter($val, true);
    }

    function parseCodeElement($html)
    {
		if (preg_match_all('/\[code\](.+?)\[\/code\]/is', $html, $replacementI)) {
			foreach($replacementI[0] as $val) $html = str_replace($val, $this->code_unprotect($val), $html);
        }
        $pattern = array();
        $replacement = array();
        $pattern[] = "/\[code\](.+?)\[\/code\]/is";
        $replacement[] = "<div class='code'><div class='genmed'><b>" . _JOOMLACOMMENT_UBB_CODE . '</b></div><pre>\\1</pre></div>';
        return preg_replace($pattern, $replacement, $html);
    }

    function parseUBB($html, $hide)
    {
        $html = str_replace(']www.', ']http://www.', $html);
        $html = str_replace('=www.', '=http://www.', $html);
        $patterns = array('/\[b\](.*?)\[\/b\]/i',
            '/\[u\](.*?)\[\/u\]/i',
            '/\[i\](.*?)\[\/i\]/i',
            '/\[url=(.*?)\](.*?)\[\/url\]/i',
            '/\[url\](.*?)\[\/url\]/i',
            '#\[email\]([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#',
            '#\[email=([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\](.*?)\[/email\]#',
            '/\[font=(.*?)\](.*?)\[\/font\]/i',
            '/\[size=(.*?)\](.*?)\[\/size\]/i',
            '/\[color=(.*?)\](.*?)\[\/color\]/i');
        $replacements = array('<b>\\1</b>',
            '<u>\\1</u>',
            '<i>\\1</i>',
            '<a href=\'\\1\' title=\'Visit \\1\'>\\2</a>',
            '<a href=\'\\1\' title=\'Visit \\1\'>\\1</a>',
            '<a href=\'mailto:\\1\'>\\1</a>',
            '<a href=\'mailto:\\1\'>\\3</a>',
            '<span style=\'font-family: \\1\'>\\2</span>',
            '<span style=\'font-size: \\1\'>\\2</span>');
        if ($hide) $replacements[] = '\\2';
        else $replacements[] = '<span style=\'color: \\1\'>\\2</span>';
        $html = preg_replace($patterns, $replacements, $html);
        return $html;
    }

    function parse()
    {
        global $mosConfig_absolute_path;

		$html = $this->_comment;
        if ($this->_support_emoticons) $html = $this->parseEmoticons($html);
        if ($this->_support_pictures) $html = $this->parseImgElement($html);
        if ($this->_support_UBBcode) {
            $html = $this->parseUBB($html, $this->_hide);
            $html = $this->parseCodeElement($html);
            $html = $this->parseQuoteElement($html);
        }
        if ($this->_hide) $html = "<span class='hide'>$html</span>";
		return str_replace('&#13;', "\r", nl2br($html));
    }
}

?>
