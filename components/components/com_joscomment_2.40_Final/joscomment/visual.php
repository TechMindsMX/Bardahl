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

require_once('tree.php');
require_once('menu.php');

class visual extends properties {
    var $_parent_id = -1;

    function insertMenu()
    {
        $menu = new menu($this->_menu);
        $menu->setContentId($this->_contentId);
        $menu->setRSS($this->_rss);
        $menu->setModerator($this->_moderator);
        return $menu->htmlCode();
    }

    function insertSearch()
    {
        $html = $this->_search;
        $html = str_replace('{_JOOMLACOMMENT_SEARCH}', _JOOMLACOMMENT_SEARCH, $html);
        $html = str_replace('{_JOOMLACOMMENT_PROMPT_KEYWORD}', _JOOMLACOMMENT_PROMPT_KEYWORD, $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH_ANYWORDS}', _JOOMLACOMMENT_SEARCH_ANYWORDS, $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH_ALLWORDS}', _JOOMLACOMMENT_SEARCH_ALLWORDS, $html);
        $html = str_replace('{_JOOMLACOMMENT_SEARCH_PHRASE}', _JOOMLACOMMENT_SEARCH_PHRASE, $html);
        return $html;
    }

    function insertPost($item, $postCSS, $onlyBody = false)
    {
        $post = new post($this->_post);
        $post->setItem($item);
        $post->setCSS($postCSS);
        $post->setAjax($this->_ajax);
        $post->setTree($this->_tree);
        $post->setTree_indent($this->_tree_indent);
        $post->setDate_format($this->_date_format);
        $post->setIP_visible($this->_IP_visible);
        $post->setIP_partial($this->_IP_partial);
        $post->setIP_caption($this->_IP_caption);
        $post->setIP_usertypes($this->_IP_usertypes);
        $post->setContentId($this->_contentId);
        $post->setVoting_visible($this->_voting_visible);
        $post->setSupport_emoticons($this->_support_emoticons);
        $post->setSupport_UBBcode($this->_support_UBBcode);
        $post->setSupport_pictures($this->_support_pictures);
        $post->setEmoticons($this->_emoticons);
        $post->setEmoticons_path($this->_emoticons_path);
        $post->setOnly_registered($this->_only_registered);
        $post->setModerator($this->_moderator);
        $post->setAvatar($this->_avatar ? $this->_profiles[$item['name']]['avatar'] : false);
        $post->setUser_id($this->_profile ? $this->_profiles[$item['name']]['id'] : false);
        $post->setMaxLength_text($this->_maxlength_text);
        $post->setMaxLength_word($this->_maxlength_word);
        return $post->htmlCode($onlyBody);
    }

    function insertComments()
    {
        global $database;
        if ($this->_sort_downward) $sort = 'DESC';
        else $sort = 'ASC';
        $database->SetQuery("SELECT * FROM #__comment WHERE contentid='$this->_contentId' AND published='1' ORDER BY id $sort");
        $data = $database->loadAssocList();
        $postCSS = 1;
        if ($data != null) {
            if ($this->_tree) $data = buildTree($data);
            if ($data != null) {
                $html = '';
                foreach($data as $item) {
                    $html .= $this->insertPost($item, $postCSS);
                    $postCSS++;
                    if ($postCSS == 3) $postCSS = 1;
                }
            }
        }
       $html = utf8_decode($html);
 return "<div id='Comments'>$html</div>\n<script type='text/javascript'>var postCSS=$postCSS;</script>";
    }

    function insertForm()
    {
        $form = new form($this->_form);
        $form->setAbsolute_path($this->_absolute_path);
        $form->setLive_site($this->_live_site);
        $form->setOnly_registered($this->_only_registered);
        $form->setSupport_emoticons($this->_support_emoticons);
        $form->setSupport_UBBcode($this->_support_UBBcode);
        $form->setEmoticons($this->_emoticons);
        $form->setEmoticons_path($this->_emoticons_path);
        $form->setTemplate_path($this->_template_path);
        $form->setTemplate_name($this->_name);
        $form->setContentId($this->_contentId);
        $form->setCaptcha($this->_captcha);
        return $form->htmlCode();
    }

    function comments($number)
    {
        if ($number == 1) $comments = _JOOMLACOMMENT_COMMENTS_1;
        elseif ($number >= 2 && $number <= 4) $comments = _JOOMLACOMMENT_COMMENTS_2_4;
        else $comments = _JOOMLACOMMENT_COMMENTS;
        return $comments;
    }

    function insertCountButton()
    {
        global $database;
        $adress = sefRelToAbs("index.php?option=com_content&task=view&id=$this->_contentId");
        if ($this->_preview_visible) {
            $database->SetQuery("SELECT * FROM #__comment WHERE contentid='$this->_contentId' AND published='1' ORDER BY date DESC");
            $data = $database->loadAssocList();
            if ($data != null) {
                $html = "\n<div class='comment_preview_container'><div class='comment_preview' onclick=\"window.location='$adress'\">\n";
                $index = 0;
                foreach($data as $item) {
                    if ($index == $this->_preview_lines) break;
                    if ($item['title'] != '') $title = $item['title'];
                    else $title = $item['comment'];
                    if (strlen($title) > $this->_preview_length) $title = substr($title, 0, $this->_preview_length) . '...';
              //      $html .= '<div>' . $item['date'] . "&nbsp;<b>" . $title . "</b></div>\n";
                    $html .= '<div>' . date($this->_date_format,strtotime($item['date'])) . "&nbsp;<b>" . $title . "</b></div>\n";
                    $index++;
                }
                $html .= "</div></div>\n";
            }
        }
        $database->SetQuery("SELECT COUNT(*) FROM #__comment WHERE contentid='$this->_contentId' AND published='1'");
        $number = $database->loadResult();
        if (!$number) $number = 0;
        $html .= "\n<div class='write_comment'>\n";
        $html .= '  <a href="';
        $html .= $adress;
        $html .= '" class="readon">' . _JOOMLACOMMENT_WRITECOMMENT . " ($number " . $this->comments($number) . ") </a>\n";
        $html .= "</div>\n";
        return $html;
    }

    function htmlCode()
    {
        global $option, $task;

        $this->CSS();
        if (($option == 'com_content' && $task == 'view')) {
            insertJavaScript($this->_live_site);
            $this->parse();
            $html = "<div id='comment'>";
            $html .= $this->insertMenu();
            if ($this->_sort_downward) {
                $html .= $this->insertForm();
                $html .= $this->insertComments();
            } else {
                $html .= $this->insertComments();
                $html .= $this->insertForm();
            }
            $html .= "</div>";
            $html .= $this->jscriptInit();
        } else
        if ($this->_show_readon) {
            $html = $this->insertCountButton();
        } else
            nothing;
        return $html;
    }
}

?>