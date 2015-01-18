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

function showMessage($msg)
{
    echo("<script type='text/javascript'>alert('$msg');</script>");
}

function insertToHead($html)
{
    global $mainframe;
    if (!strpos($mainframe->getHead(), $html))
        $mainframe->addCustomHeadTag($html);
}

function intUserType($userType)
{
    switch ($userType) {
        case 'Super Administrator':
            $result = 0;
            break;

        case 'Administrator':
            $result = 1;
            break;

        case 'Editor':
            $result = 2;
            break;

        case 'Registered':
            $result = 3;
            break;

        case 'Author':
            $result = 4;
            break;

        case 'Publisher':
            $result = 5;
            break;

        case 'Manager':
            $result = 6;
            break;
        default:
            $result = -1;
            break;
    }
    return $result;
}

function isCommentModerator($moderator, $usrname = '', $usrtype = '')
{
    global $my;
    $result = (in_array(intUserType($my->usertype), $moderator));
    $usertype = strtolower($my->usertype);
    if ($usrname && $usrtype)
        $result = ($result || (($my->username == $usrname) && ($usertype == strToLower($usrtype))));
    return $result;
}

function partialIP($ip)
{
    $quads = split('\.', $ip);
    $quads[3] = 'xxx';
    return join(".", $quads);
}

function replaceNL($text)
{
    return str_replace("\n", '\n', htmlspecialchars($text, ENT_QUOTES));
}

function ignoreBlock($source, $name, $ignore, $newStr = '')
{
    if ($ignore) {
        if ($newStr == '') $after_replace = '';
        else $after_replace = $newStr;
    } else $after_replace = '\\1';
    return eregi_replace("\{$name\}([^\[]+)\{/$name\}", $after_replace, $source);
}

function existsTable($name)
{
    global $database;
    $name = $database->replacePrefix($name);
    $database->setQuery("SHOW TABLES LIKE '$name';");
    return ($database->loadResult()) ? true : false;
}

function existsColumn($table, $name)
{
    global $database;
    $database->setQuery("SHOW COLUMNS FROM $table LIKE '$name';");
    return ($database->loadResult()) ? true : false;
}

function insertColumns($table, $columns)
{
    global $database;
    $query = '';
    foreach($columns as $index) {
        if ($query) $query .= ', ';
        $query .= 'ADD COLUMN ' . $index;
    }
    $database->setQuery("ALTER TABLE $table $query;");
    $database->Query();
}

function checkCompactibility()
{
    $columns = null;
    if (!existsColumn('#__comment', 'voting_yes'))
        $columns[] = "`voting_yes` INT(10) NOT NULL default '0'";
    if (!existsColumn('#__comment', 'voting_no'))
        $columns[] = "`voting_no` INT(10) NOT NULL default '0'";
    if (!existsColumn('#__comment', 'parentid'))
        $columns[] = "`parentid` INT(10) NOT NULL default '-1'";
    if ($columns) insertColumns('#__comment', $columns);
}

function checkDatabase()
{
    global $database;
    if (!existsTable('#__comment')) {
        $database->SetQuery("CREATE TABLE `#__comment` (
        `id` INT(10) NOT NULL auto_increment,
        `contentid` INT(10) NOT NULL default '0',
        `ip` VARCHAR(15) NOT NULL default '',
        `usertype` VARCHAR(25) NOT NULL default 'Unregistered',
        `date` DATETIME NOT NULL default '0000-00-00 00:00:00',
        `name` VARCHAR(30) NOT NULL default '',
        `title` VARCHAR(30) NOT NULL default '',
        `comment` TEXT NOT NULL,
        `published` TINYINT(1) NOT NULL default '0',
        `voting_yes` INT(10) NOT NULL default '0',
        `voting_no` INT(10) NOT NULL default '0',
        `parentid` INT(10) NOT NULL default '-1',
        PRIMARY KEY  (`id`));");
        $database->Query() or die(_JOOMLACOMMENT_SAVINGFAILED);
    } else checkCompactibility();
}

function decodeData($varName)
{
    return mosGetParam($_REQUEST, $varName, '', _MOS_ALLOWHTML);
}

function cdata($data)
{
    if ($data == '') return '';
    else return "<![CDATA[$data]]>";
}

function block($source, $name)
{
    $begin = '{' . $name . '}';
    $end = '{/' . $name . '}';
    $len = strlen($begin);
    $pos_begin = strpos($source, $begin);
    $pos_end = strpos($source, $end);
    return substr($source, $pos_begin + $len, $pos_end - ($pos_begin + $len));
}

function filter($html, $downward = false)
{
    if ($downward) {
        $html = str_replace('&#64;', '@', $html);
        $html = str_replace('&#92;', '\\', $html);
        $html = str_replace('&#34;', '"', $html);
    } else {
        $html = str_replace('@', '&#64;', stripslashes($html));
        $html = str_replace('\\', '&#92;', $html);
        $html = str_replace('"', '&#34;', $html);
    }
    return $html;
}

function buildTree($data)
{
    $tree = new tree();
    return $tree->build($data);
}

function wrapText($text, $width)
{
    return ($text) ? preg_replace("/([^\n\r ?&\.\/<>\"\\-]{" . $width . "})/i", " \\1\n", $text) : '';
}

?>
