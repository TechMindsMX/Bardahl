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
global $mosConfig_absolute_path;

require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

function execPlugin()
{
    global $mosConfig_absolute_path;
	define('_PLUGIN_EXEC', 1);
    require_once("$mosConfig_absolute_path/mambots/content/joscomment.php");
    pluginJosComment(1, $row, $params);
}

function createFeed()
{
    global $database, $mosConfig_live_site, $mosConfig_absolute_path;
    require_once("$mosConfig_absolute_path/includes/feedcreator.class.php");
    $contentid = decodeData('contentid');
    $database->setQuery("SELECT * FROM #__content WHERE id='$contentid'");
    $content = null;
    $database->loadObject($content);
    $rss = new UniversalFeedCreator();
    $rss->useCached();
    $rss->title = $content->title;
    $rss->description = $content->title_alias;
    $rss->link = $mosConfig_live_site;
    $database->setQuery("SELECT *,UNIX_TIMESTAMP( date ) AS rss_date FROM #__comment WHERE contentid='$contentid' AND published='1' ORDER BY id ASC");
    $data = $database->loadAssocList();
    if ($data != null) {
        foreach($data as $item) {
            $rss_item = new FeedItem();
            $rss_item->author = utf8_decode($item['name']);
            $rss_item->title = utf8_decode($item['title']);
            $rss_item->link = sefRelToAbs("index.php?option=com_content&task=view&id=$contentid#josc" . $item['id']);
            $rss_item->description = utf8_decode($item['comment']);
            $rss_item->date = date('r', $item['rss_date']);
            $rss->addItem($rss_item);
        }
        $rss->saveFeed("RSS2.0", "feed.xml");
    }
}

$command = decodeData('command');

switch ($command) {
    case 'ajax_insert':
        execPlugin();
        break;
    case 'ajax_quote':
        execPlugin();
        break;
    case 'ajax_edit':
        execPlugin();
        break;
    case 'ajax_delete':
        execPlugin();
        break;
    case 'ajax_delete_all':
        execPlugin();
        break;
    case 'ajax_voting_yes':
        execPlugin();
        break;
    case 'ajax_voting_no':
        execPlugin();
        break;
    case 'ajax_search':
		execPlugin();
        break;
    case 'ajax_insert_search':
		execPlugin();
        break;
    case 'rss':
        createFeed();
        break;
    default:
        break;
}

?>
