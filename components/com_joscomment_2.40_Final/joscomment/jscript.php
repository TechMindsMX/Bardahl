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

function insertJavaScript($path)
{
	$html = "<script type='text/javascript'>";
    $html .= ' var _JOOMLACOMMENT_MSG_DELETE = \'' . htmlspecialchars(_JOOMLACOMMENT_MSG_DELETE, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_MSG_DELETEALL = \'' . htmlspecialchars(_JOOMLACOMMENT_MSG_DELETEALL, ENT_QUOTES) . '\';';
	$html .= ' var _JOOMLACOMMENT_WRITECOMMENT = \'' . htmlspecialchars(_JOOMLACOMMENT_WRITECOMMENT, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_SENDFORM = \'' . htmlspecialchars(_JOOMLACOMMENT_SENDFORM, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_EDITCOMMENT = \'' . htmlspecialchars(_JOOMLACOMMENT_EDITCOMMENT, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_EDIT = \'' . htmlspecialchars(_JOOMLACOMMENT_EDIT, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_FORMVALIDATE = \'' . htmlspecialchars(_JOOMLACOMMENT_FORMVALIDATE, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_ANONYMOUS = \'' . htmlspecialchars(_JOOMLACOMMENT_ANONYMOUS, ENT_QUOTES) . '\';';
    $html .= ' var _JOOMLACOMMENT_BEFORE_APPROVAL = \'' . htmlspecialchars(_JOOMLACOMMENT_BEFORE_APPROVAL, ENT_QUOTES) . '\';';
	$html .= "</script>";
    insertToHead($html);
    insertToHead("<script type='text/javascript' src='$path/jscripts/client.js'></script>");
}

?>
