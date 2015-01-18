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

require_once("$mosConfig_absolute_path/administrator/components/com_comment/library.comment.php");
require_once("$mosConfig_absolute_path/administrator/components/com_comment/class.config.comment.php");
require_once($mainframe->getPath('admin_html'));

$cid = josGetArrayInts( 'cid' ); 
$config = new config();

switch ($task) {
    case "new":
        editComment($option, 0);
        break;

    case "edit":
        editComment($option, $cid[0]);
        break;

    case "save":
        saveComment($option);
        break;

    case "remove":
        removeComments($cid, $option);
        break;

    case "publish":
        publishComment($cid, 1, $option);
        break;

    case "unpublish":
        publishComment($cid, 0, $option);
        break;

    case "about":
        HTML_comments::viewAbout();
        break;

    case "settings":
        $config->load();
        $config->execute($option);
        break;

    case "savesettings":
        $config->save($option);
        break;

    case "import":
        import($option);
        break;

    default:
        viewComments($option);
        break;
}

function viewComments($option)
{
    global $database, $mainframe;

    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', 10);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
    $search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
    $search = $database->getEscaped(trim(strtolower($search)));
    $where = array();
    if ($search) {
        $where[] = "LOWER(comment) LIKE '%$search%'";
    }
    $database->setQuery("SELECT count(*) FROM #__comment AS a" . (count($where) ? "\nWHERE " . implode(' AND ', $where) : ""));
    $total = $database->loadResult();
    echo $database->getErrorMsg();
    include_once("includes/pageNavigation.php");
    $pageNav = new mosPageNav($total, $limitstart, $limit);
    $database->setQuery("SELECT * FROM #__comment"
         . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "")
         . "\nORDER BY id DESC"
         . "\nLIMIT $pageNav->limitstart,$pageNav->limit"
        );
    $rows = $database->loadObjectList();
    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }
    $database->setQuery("SELECT title, id FROM #__content");
    $articles = $database->loadObjectList();
    foreach($articles as $article)
    $articleList[$article->id] = $article->title;
    unset($articles);
    HTML_comments::viewComments($option, $rows, $articleList, $search, $pageNav);
}

function publishComment($cid = null, $publish = 1, $option)
{
    global $database;
    if (!is_array($cid) || count($cid) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
    }
    $cids = implode(',', $cid);
    $database->setQuery("UPDATE #__comment SET published='$publish' WHERE id IN ($cids)");
    if (!$database->query()) {
        echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        exit();
    }
    mosRedirect("index2.php?option=$option");
}

function editComment($option, $uid)
{
    global $database;
    $row = new josComment($database);
    $row->load($uid);
    $contentitem[] = mosHTML::makeOption('0', 'Seleccionar Artículo de Contenido');
    $database->setQuery("SELECT id AS value, title AS text FROM #__content ORDER BY title");
    $contentitem = array_merge($contentitem, $database->loadObjectList());
    if (count($contentitem) < 1) {
        mosRedirect("index2.php?option=com_sections&scope=content", 'You must add content first.');
    }
    $clist = mosHTML::selectList($contentitem, 'contentid', 'class="inputbox" size="1"', 'value', 'text', intval($row->contentid));
    if ($uid) {
        $row->checkout($my->id);
    } else {
        $row->published = 0;
    }
    $publist = mosHTML::yesnoRadioList('published', 'class="inputbox"', $row->published);
    HTML_comments::editComment($option, $row, $clist, $olist, $publist);
}

function saveComment($option)
{
    global $database;
    $row = new josComment($database);
    if (!$row->bind($_POST)) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }
    $row->date = date("Y-m-d H:i:s");
    $row->ip = getenv('REMOTE_ADDR');
	$row->name = utf8_encode ($row->name);
	$row->title = utf8_encode ($row->title);
	$row->comment = utf8_encode ($row->comment);
    if (!$row->store()) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }
    $row->updateOrder("contentid='$row->contentid'");
    mosRedirect("index2.php?option=$option");
}

function removeComments($cid, $option)
{
    global $database;
    if (count($cid)) {
        $cids = implode(',', $cid);
        $database->setQuery("DELETE FROM #__comment WHERE id IN ($cids)");
        if (!$database->query()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
    }
    mosRedirect("index2.php?option=$option");
}

function import($option)
{
    global $database;
    $database->setQuery('SELECT * FROM #__akocomment ORDER BY id');
    $data = $database->loadAssocList();
    foreach($data as $item) {
		$database->setQuery("INSERT
		INTO #__comment(contentid,ip,date,name,title,comment,published)
	    VALUES(
        '" . $item['contentid'] . "',
        '" . $item['ip'] . "',
        '" . $item['date'] . "',
        '" . mysql_escape_string($item['name']) . "',
        '" . mysql_escape_string($item['title']) . "',
        '" . mysql_escape_string($item['comment']) . "',
        '" . $item['published'] . "')");
        $database->query() or die($database->getErrorMsg());
    }
    mosRedirect("index2.php?option=$option");
}

?>