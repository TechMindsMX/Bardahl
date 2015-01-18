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
require_once('strutils.php');
require_once('properties.php');
require_once('jscript.php');
require_once('post.php');
require_once('form.php');
require_once('visual.php');
require_once('security.php');
require_once('search.php');

class board extends visual {
    var $_contentId;
    var $_command;
    var $_tname;
    var $_ttitle;
    var $_tcomment;
    var $_comment_id;
    var $_content_id;
    var $_search_keyword;
    var $_search_phrase;
    var $_charset;

    function board($absolutePath, $liveSite, $sectionId, $categoryId, &$exclude)
    {
        $this->properties($absolutePath, $liveSite, $sectionId, $categoryId, $exclude);
    }

    function setContentId($value)
    {
        $this->_contentId = $value;
    }

    function voting($item, $mode)
    {
        global $database;

        $t = time()-3 * 86400;
        $database->SetQuery("DELETE FROM #__comment_voting WHERE time<'$t'");
        $database->Query();
        $database->SetQuery("SELECT COUNT(*) FROM #__comment_voting WHERE id='" . $item['id'] . "' AND ip='" . $_SERVER['REMOTE_ADDR'] . "'");
        $exists = $database->loadResult();
        if (!$exists) {
            $item["voting_$mode"]++;
            $database->SetQuery("
			UPDATE #__comment SET
        	voting_$mode='" . $item["voting_$mode"] . "'
        	WHERE id=$this->_comment_id");
            $database->Query() or die('Database error: voting(1)!');
            $database->SetQuery("INSERT INTO #__comment_voting(id,ip,time)
    		VALUES(
      		'" . $item['id'] . "',
      		'" . $_SERVER['REMOTE_ADDR'] . "',
      		'" . time() . "')");
            $database->Query() or die("Database error: voting(2)!");
        }
        header('Content-Type: text/xml; charset=utf-8');
        $xml = '<?xml version="1.0" standalone="yes"?><voting><id>' . $item['id'] . '</id><yes>' . $item["voting_yes"] . '</yes><no>' . $item["voting_no"] . '</no></voting>';
        exit($xml);
    }

    function getNewPost($sort, &$data)
    {
        global $database;
        $database->SetQuery("SELECT * FROM #__comment WHERE contentid='$this->_contentId' AND published='1' ORDER BY id $sort");
        $data = $database->loadAssocList() or die('Database error: insertNewPost!');
    }
    	
    function notifyAdmin($name, $title, $comment, $contentid)
    {
	  # funkce upravena - pridan parametr $contentid
        # pridana hlavicka zpravy zabezpecujici spravne prepnuti mail klienta pro spravne zobrazeni diakritiky
        # formatovani zpravy zmeneno aby vyhovelo html a pridan primy odkaz do clanku s komentarem 
        # 10.08.2006 Roman Kolbabek

        global $mosConfig_live_site;
        $articlelink = $mosConfig_live_site.'/index.php?option=com_content&task=view&id='.$contentid;

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: ' . $this->_notify_email;

        $message = '<p>A user has posted a new comment to an content item at '.$mosConfig_live_site.':</p>';
        $message .= '<p><b>Name: </b>'.$name.'<br />';
	  $message .= '<b>Title: </b>'.$title.'<br />';
        $message .= '<b>Text: </b>'.$comment.'<br />';
        $message .= '<b>Article: </b><a href="'.$articlelink.'">'.$articlelink.'</a></p>';

        
        $message .= "<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>";
        @mail($this->_notify_email, 'New comment item', $message, $headers);
    }

    function isBlocked($ip)
    {
        if ($this->_ban != '') {
            $ipList = split(',', $this->_ban);
            foreach($ipList as $item) {
                if (trim($item) == $ip) return true;
            }
        }
        return false;
    }

    function censorText($text)
    {
        if ($this->_censorship_enable && is_array($this->_censorship_words)) {
            if ($this->_censorship_case_sensitive) $replace = str_replace;
            else $replace = str_ireplace;
            foreach($this->_censorship_words as $word) {
                $word = trim($word);
                if (strpos($word, '=')) {
                    $word = explode('=', $word);
                    $text = $replace(trim($word[0]), trim($word[1]), $text);
                } else $text = $replace($word, str_fill(strlen($word), '*'), $text);
            }
        }
        return $text;
    }

    function insertNewPost($ajax = false)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->isBlocked($ip)) return false;
        global $database, $my;

        $tname = $this->_tname;
        if ($my->username) {
            $usertype = $my->usertype;
            $tname = $my->username;
        } else $usertype = 'Unregistered';
        $name = $this->censorText(mysql_escape_string(strip_tags($tname)));
        $title = $this->censorText(mysql_escape_string(strip_tags($this->_ttitle)));
        $comment = $this->censorText(mysql_escape_string(strip_tags($this->_tcomment)));
        $published = $this->_autopublish;
        $parent_id = $this->_parent_id;
        $database->SetQuery("
            INSERT INTO #__comment(contentid,ip,usertype,date,name,title,comment,published,voting_yes,voting_no,parentid)
            VALUES(
            '$this->_content_id',
            '$ip',
            '$usertype',
            now(),
            '$name',
            '$title',
            '$comment',
            '$published',
            '0',
            '0',
            '$parent_id'
            )");
        $database->Query() or die(_JOOMLACOMMENT_SAVINGFAILED);
        if ($this->_notify_admin) $this->notifyAdmin($name, $title, $comment, $this->_content_id);
        if ($ajax) {
            if ($this->_tree) {
                $this->getNewPost('ASC', $data);
                $id = $data[sizeOf($data)-1]['id'];
                $data = buildTree($data);
                $after = -1;
                foreach($data as $item) {
                    if ($item['id'] == $id) {
                        $item['after'] = $after;
                        $item['view'] = $published;
                        return $item;
                    }
                    $after = $item['id'];
                }
            } else $this->getNewPost('DESC LIMIT 1', $data);
            $data[0]['view'] = $published;
            return $data[0];
        } else return $published;
    }

    function editPost()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->isBlocked($ip)) return false;
        global $database;

        $database->SetQuery("SELECT * FROM #__comment WHERE id='$this->_comment_id'");
        $item = $database->loadAssocList();
        if (isCommentModerator($this->_moderator, $item[0]['name'], $item[0]['usertype'])) {
            $title = $this->censorText(mysql_escape_string(strip_tags($this->_ttitle)));
            $comment = $this->censorText(mysql_escape_string(strip_tags($this->_tcomment)));
            $database->SetQuery("
            UPDATE #__comment SET
            date=now(),
            title='$title',
            comment='$comment'
            WHERE id=$this->_comment_id");
            $database->Query() or die(_JOOMLACOMMENT_EDITINGFAILED);
            $database->SetQuery("SELECT * FROM #__comment WHERE id='$this->_comment_id' AND published='1' LIMIT 1");
            $data = $database->loadAssocList() or die('Database error: editPost!');
            $data[0]['view'] = 1;
            return $data[0];
        }
    }

    function modify($event = false)
    {
        global $my;
        if (!$event) {
            if (!$my->username && $this->_only_registered)
                showMessage(_JOOMLACOMMENT_ONLYREGISTERED);
            else if (!($this->_captcha && !captchaResult())) {
                $published = $this->insertNewPost();
                unset($this->_tcomment);
                mosRedirect(sefRelToAbs("index.php?option=com_content&task=view&id=$this->_content_id"), $published ? '':_JOOMLACOMMENT_BEFORE_APPROVAL);
            }
            mosRedirect(sefRelToAbs("index.php?option=com_content&task=view&id=$this->_content_id"));
        }
        header('Content-Type: text/xml; charset=utf-8');
        if (!($this->_captcha && !captchaResult())) {
            $this->setContentId($this->_content_id);
            $item = $this->$event(true);
            if (!$item) exit();
            $this->parse();
            $xml = '<?xml version="1.0" standalone="yes"?>';
            $xml .= '<post>';
            $xml .= '<id>' . $item['id'] . '</id>';
            if ($this->_tree) $xml .= '<after>' . $item['after'] . '</after>';
            $html = cdata($this->insertPost($item, ''));
            $xml .= "<body>$html</body>";
            if ($this->_captcha) {
                $captcha = cdata(insertCaptcha('security_refid'));
                $xml .= "<captcha>$captcha</captcha>";
            }
            $xml .= '<published>' . $item['view'] . '</published>';
            $xml .= '</post>';
            exit($xml);
        } else if ($this->_captcha) {
            $captcha = cdata(insertCaptcha('security_refid'));
            $xml = '<?xml version="1.0" standalone="yes"?>';
            $xml .= '<post>';
            $xml .= '<id>captcha</id>';
            $xml .= "<captcha>$captcha</captcha>";
            $xml .= '</post>';
            exit($xml);
        } else exit;
    }

    function deletePost($id = -1)
    {
        global $database;
        $id = ($id == -1) ? "WHERE contentid='$this->_content_id'" : "WHERE id='$id'";
        $database->SetQuery("DELETE FROM #__comment $id");
        $database->Query() or die(_JOOMLACOMMENT_DELETINGFAILED);
    }

    function search()
    {
        $this->parse();
        $search = new search($this->_searchResults);
        $search->setKeyword($this->_search_keyword);
        $search->setPhrase($this->_search_phrase);
        $search->setDate_format($this->_date_format);
        return $search->htmlCode();
    }

    function filterAll($item)
    {
        $item['name'] = filter($item['name']);
        $item['title'] = filter($item['title']);
        $item['comment'] = filter($item['comment']);
        return $item;
    }

    function decodeURI()
    {
        $this->_command = decodeData('command');
        $this->_tname = decodeData('tname');
        $this->_ttitle = decodeData('ttitle');
        $this->_tcomment = decodeData('tcomment');
        $this->_comment_id = decodeData('comment_id');
        $this->_content_id = decodeData('content_id');
        $this->_search_keyword = decodeData('search_keyword');
        $this->_search_phrase = decodeData('search_phrase');
        $this->_parent_id = decodeData('parent_id');
        if ($this->_parent_id == '') $this->_parent_id = '-1';
    }

    function execute()
    {
        global $database;
        $this->decodeURI();
        if ($this->_command) {
            $database->SetQuery("SELECT * FROM #__comment WHERE id='$this->_comment_id' LIMIT 1");
            $item = $database->loadAssocList();
            if (isCommentModerator($this->_moderator, $item[0]['name'], $item[0]['usertype'])) {
                if ($this->_command == 'ajax_delete') {
                    $this->deletePost($this->_comment_id);
                    exit;
                }
                if ($this->_command == 'ajax_delete_all') {
                    $this->deletePost();
                    exit;
                }
                if ($this->_command == 'ajax_edit') $this->modify(editPost);
            }
            if ($this->_command == 'ajax_insert') $this->modify(insertNewPost);
            if ($this->_command == 'ajax_quote') {
                $item = $this->filterAll($item[0]);
                header('Content-Type: text/xml; charset=utf-8');
                $xml = '<?xml version="1.0" standalone="yes"?><post><name>' . cdata($item['name']) . '</name><title>' . cdata($item['title']) . '</title><comment>' . cdata($item['comment']) . '</comment></post>';
                exit($xml);
            }
            if ($this->_command == 'ajax_voting_yes') $this->voting($item[0], 'yes');
            if ($this->_command == 'ajax_voting_no') $this->voting($item[0], 'no');
            if ($this->_command == 'ajax_insert_search') {
                $this->parse();
                header('Content-Type: text/xml; charset=utf-8');
                exit($this->insertSearch());
            }
            if ($this->_command == 'ajax_search') {
                header('Content-Type: text/xml; charset=utf-8');
                exit($this->search());
            }
        } else if ($this->_tcomment) $this->modify(false);
    }
}

?>
