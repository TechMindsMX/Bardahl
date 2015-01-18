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

require_once('template.php');

class properties extends template {
    var $_ajax;
    var $_only_registered;
    var $_language;
    var $_moderator = array();
    var $_exclude_sections = array();
    var $_exclude_categories = array();
    var $_template;
    var $_emoticon_pack;
    var $_tree;
    var $_tree_indent;
    var $_sort_downward;
    var $_support_emoticons;
    var $_support_UBBcode;
    var $_support_pictures;
    var $_censorship_enable;
    var $_censorship_case_sensitive;
    var $_censorship_words;
    var $_censorship_usertypes;
    var $_IP_visible;
    var $_IP_partial;
    var $_IP_caption;
    var $_IP_usertypes;
    var $_preview_visible;
    var $_preview_length;
    var $_preview_lines;
    var $_voting_visible;
    var $_notify_admin;
    var $_notify_email;
    var $_rss;
    var $_date_format;
    var $_captcha;
    var $_autopublish;
    var $_ban;
    var $_avatar;
    var $_profile;
    var $_profiles;
    var $_maxlength_text;
    var $_maxlength_word;
    var $_show_readon;

    function properties($absolutePath, $liveSite, $sectionId, $categoryId, &$exclude)
    {
        global $my;
        require("$absolutePath/../config.comment.php");
        $this->_ajax = $josc_ajax;
        $this->_only_registered = $josc_only_registered;
        $this->_language = $josc_language;
        $this->_moderator = explode(',', $josc_moderator);
        $this->_exclude_sections = explode(',', $josc_exclude_sections);
        if ($exclude && in_array((($sectionId == 0) ? -1 : $sectionId), $this->_exclude_sections)) {
            $exclude = true;
            return;
        }
        $this->_exclude_categories = explode(',', $josc_exclude_categories);
        if ($exclude && in_array($categoryId, $this->_exclude_categories)) {
            $exclude = true;
            return;
        }
        $this->_template = $josc_template;
        $this->_emoticon_pack = $josc_emoticon_pack;
        $this->_tree = $josc_tree;
        $this->_tree_indent = $josc_tree_indent;
        $this->_sort_downward = ($this->_tree) ? 0 : $josc_sort_downward;
        $this->_support_emoticons = $josc_support_emoticons;
        $this->_support_UBBcode = $josc_support_UBBcode;
	$this->_support_pictures = $josc_support_pictures;
        $this->_censorship_enable = $josc_censorship_enable && in_array(intUserType($my->usertype), explode(',', $josc_censorship_usertypes));;
        $this->_censorship_case_sensitive = $josc_censorship_case_sensitive;
        $this->_censorship_words = explode(',', $josc_censorship_words);
        $this->_IP_visible = $josc_IP_visible && in_array(intUserType($my->usertype), explode(',', $josc_IP_usertypes));
        $this->_IP_partial = $josc_IP_partial;
        $this->_IP_caption = $josc_IP_caption;
        $this->_IP_usertypes = explode(',', $josc_IP_usertypes);
        $this->_preview_visible = $josc_preview_visible;
        $this->_preview_length = $josc_preview_length;
        $this->_preview_lines = $josc_preview_lines;
        $this->_voting_visible = $josc_voting_visible;
        $this->_notify_admin = $josc_notify_admin;
        $this->_notify_email = $josc_notify_email;
        $this->_rss = $josc_rss;
        $this->_date_format = $josc_date_format;
        $this->_captcha = $josc_captcha;
        $this->_autopublish = $josc_autopublish;
        $this->_ban = $josc_ban;
        $cb = existsTable('#__comprofiler');
        $this->_profile = $josc_support_profiles && $cb;
        $this->_avatar = $josc_support_avatars && $cb;
		$this->_maxlength_text = $josc_maxlength_text;
        $this->_maxlength_word = $josc_maxlength_word;
        $this->template($this->_template);
        $this->_absolute_path = $absolutePath;
        $this->_live_site = $liveSite;
        $this->_template_path = $liveSite . '/templates/';
        $this->_emoticons_path = $liveSite . "/emoticons/$this->_emoticon_pack/images";
        $this->loadLanguage($GLOBALS['josComment_absolute_path'], $this->_language);
        $this->loadEmoticons("$absolutePath/emoticons/$this->_emoticon_pack/index.php");
        $this->_show_readon = $josc_show_readon;
#  $this->loadProfiles()
        $exclude = false;
    }
    function jscriptInit()
    {
        $html = "<script type='text/javascript'>";
        $html .= "var ajaxEnabled=$this->_ajax;";
        $html .= "if (!http) ajaxEnabled=false;";
        $html .= "var sortDownward='$this->_sort_downward';";
        $captchaEnabled = $this->_captcha ? 'true' : 'false';
        $html .= "var captchaEnabled=$captchaEnabled;";
        $html .= "var liveSite='$this->_live_site';";
        $html .= "var autopublish='$this->_autopublish';";
        $html .= "</script>";
        return $html;
    }
    function loadLanguage($path, $language)
    {
        $path .= '/language/';
        if ($language == 'auto') $language = $path . $GLOBALS['mosConfig_lang'] . '.php';
        else $language = $path . $language;
        if (!file_exists($language)) $language = $path . 'english.php';
        require($language);
    }
    function loadEmoticons($fileName)
    {
        require_once($fileName);
        $this->_emoticons = $GLOBALS['emoticon'];
    }
function loadProfiles()
    {
        global $database;
        $database->setQuery('SELECT #__users.username, #__comprofiler.user_id,
        #__comprofiler.avatar FROM #__users, #__comprofiler
        WHERE #__users.id = #__comprofiler.user_id');
        $userList = $database->loadAssocList();
        $this->_profiles = array();
        //add this line
        if(count($userList)){
        //add the above line
        foreach ($userList as $item) {
            if ($this->_avatar) $this->_profiles[$item['username']]['avatar'] = $item['avatar'];
            else $this->_profiles[$item['username']]['avatar'] = false;
            if ($this->_profile) $this->_profiles[$item['username']]['id'] = $item['user_id'];
            else $this->_profiles[$item['username']]['id'] = false;
        }
        //add this curly bracket
        }
        //add the above curly bracket
        unset($userList);
    }
 
}

?>