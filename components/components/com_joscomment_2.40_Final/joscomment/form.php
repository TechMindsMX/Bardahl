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
require_once('security.php');

class form extends support {
    var $_form;
    var $_template_path;
    var $_template_name;
    var $_captcha;

    function form($value)
    {
        $this->_form = $value;
    }

    function setTemplate_path($value)
    {
        $this->_template_path = $value;
    }

    function setTemplate_name($value)
    {
        $this->_template_name = $value;
    }

    function setCaptcha($value)
    {
        $this->_captcha = $value;
    }

    function onlyRegistered()
    {
        return '<div class="onlyregistered">' . _JOOMLACOMMENT_ONLYREGISTERED . '</div>';
    }

    function readOnly($username)
    {
        if ($username) return 'readonly="readonly"';
        else return '';
    }

    function emoticons()
    {
        if (!$this->_support_emoticons) return '';
        $html = "<div class='emoticoncontainer'>";
        $html .= "<div class='emoticonseparator'></div>";
        $index = 0;
        foreach ($GLOBALS["emoticon"] as $ubb => $icon) {
            $html .= "<span class='emoticonseparator'>";
            $html .= "<span class='emoticon'>";
            $html .= "<a href='javascript:emoticon(\"$ubb\")'>";
            $html .= "<img src='$this->_emoticons_path/$icon' border='0' alt='$i' />";
            $html .= "</a></span></span>";
            $index++;
            if ($index == 5) {
                $index = 0;
                $html .= "<div class='emoticonseparator'></div>";
            }
        }
        $html .= '</div>';
        return "<div>$html</div>";
    }

    function loadUBBIcons(&$ubbIconList, $absolute_path, $live_site)
    {
        require_once("$absolute_path/ubb_icons.php");
        foreach($ubbIcons as $name => $icon) {
            $ubbIconList[$name] = "$live_site/$icon";
        }
    }

    function UBBCodeButtons()
    {
        $absolute_path = "$this->_absolute_path/templates/$this->_template_name/images";
        $live_site = "$this->_template_path/$this->_template_name/images";
        $ubbIconList = array();
        $this->loadUBBIcons($ubbIconList, "$this->_absolute_path/images", "$this->_live_site/images");
        if (file_exists("$absolute_path/ubb_icons.php")) $this->loadUBBIcons($ubbIconList, $absolute_path, $live_site);
        $html = "<a href='javascript:insertUBBTag(\"b\")'><img src='" . $ubbIconList['bold'] . "' class='buttonBB' name='bb' alt='[b]' /></a>&nbsp;";
        $html .= "<a href='javascript:insertUBBTag(\"i\")'><img src='" . $ubbIconList['italicize'] . "' class='buttonBB' name='bi' alt='[i]' /></a>&nbsp;";
        $html .= "<a href='javascript:insertUBBTag(\"u\")'><img src='" . $ubbIconList['underline'] . "' class='buttonBB' name='bu' alt='[u]' /></a>&nbsp;";
        $html .= "<a href='javascript:insertUBBTag(\"url\")'><img src='" . $ubbIconList['url'] . "' class='buttonBB' name='burl' alt='[url]' /></a>&nbsp;";
        $html .= "<a href='javascript:insertUBBTag(\"quote\")'><img src='" . $ubbIconList['quote'] . "' class='buttonBB' name='bquote' alt='[quote]' /></a>&nbsp;";
        $html .= "<a href='javascript:insertUBBTag(\"code\")'><img src='" . $ubbIconList['code'] . "' class='buttonBB' name='bcode' alt='[code]' /></a>&nbsp;";
        $html .= "<a href='javascript:insertUBBTag(\"img\")'><img src='" . $ubbIconList['image'] . "' class='buttonBB' name='bimg' alt='[img]' /></a>&nbsp;";
        return $html;
    }

    function UBBCodeSelect()
    {
        $html = '';
        $html .= "<select name='menuColor' class='select' onchange='fontColor()'>";
        $html .= "<option>-" . _JOOMLACOMMENT_COLOR . "-</option>";
        $html .= "<option>" . _JOOMLACOMMENT_AQUA . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_BLACK . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_BLUE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_FUCHSIA . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_GRAY . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_GREEN . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_LIME . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_MAROON . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_NAVY . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_OLIVE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_PURPLE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_RED . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_SILVER . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_TEAL . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_WHITE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_YELLOW . "</option>";
        $html .= "</select>&nbsp;";
        $html .= "<select name='menuSize' class='select' onchange='fontSize()'>";
        $html .= "<option>-" . _JOOMLACOMMENT_SIZE . "-</option>";
        $html .= "<option>" . _JOOMLACOMMENT_TINY . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_SMALL . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_MEDIUM . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_LARGE . "</option>";
        $html .= "<option>" . _JOOMLACOMMENT_HUGE . "</option>";
        $html .= "</select>";
        return $html;
    }

    function htmlCode()
    {
        global $my;
        if (!$my->username && $this->_only_registered) return $this->onlyRegistered();

        $html = $this->_form;
        $html = ignoreBlock($html, 'UBBCode', (!$this->_support_UBBcode));
        $html = ignoreBlock($html, 'captcha', (!$this->_captcha));

        if ($this->_support_UBBcode) {
            $UBBCodeButtons = $this->UBBCodeButtons();
            $UBBCodeSelect = $this->UBBCodeSelect();
            $html = str_replace('{_UBBCODE}', _JOOMLACOMMENT_UBBCODE, $html);
            $html = str_replace('{UBBCodeButtons}', $UBBCodeButtons, $html);
            $html = str_replace('{UBBCodeSelect}', $UBBCodeSelect, $html);
        }

        $html = str_replace('{_WRITECOMMENT}', _JOOMLACOMMENT_WRITECOMMENT, $html);
        $html = str_replace('{_ENTERNAME}', _JOOMLACOMMENT_ENTERNAME, $html);
        $html = str_replace('{_ENTERTITLE}', _JOOMLACOMMENT_ENTERTITLE, $html);
        $html = str_replace('{_SENDFORM}', _JOOMLACOMMENT_SENDFORM, $html);

        $html = str_replace('{self}', 'index.php', $html);
        $html = str_replace('{id}', $this->_contentId, $html);
        $html = str_replace('{username}', $my->username, $html);
        $html = str_replace('{readonly}', $this->readOnly($my->username), $html);
        $html = str_replace('{emoticons}', $this->emoticons(), $html);

        $html = str_replace('{security_image}', "<div id='captcha'>" . insertCaptcha('security_refid') . '</div>', $html);
        $html .= '<h1 style="display: none; font-size: 10px;">Powered by JoomlaComment';
        $html .= 'Copyright (C) 2006 Frantisek Hliva. All rights reserved.';
		$html .= 'Homepage: <a href="http://cavo.co.nr/">http://cavo.co.nr/</a></h1>';
        return $html;
    }
}

?>
