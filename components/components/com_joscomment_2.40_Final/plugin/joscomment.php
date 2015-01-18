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

if (defined('_PLUGIN_EXEC')) $GLOBALS['exclude_'] = false;
else {
    $GLOBALS['exclude_'] = true;
    $_MAMBOTS->registerFunction('onPrepareContent', 'pluginJosComment');
}

function pluginJosComment($published, &$row, &$params, $page = 0)
{
    global $mosConfig_live_site, $mosConfig_absolute_path, $exclude_;
    if (!$published) return true;

    $exclude = $exclude_;

    $GLOBALS['josComment_path'] = "/components/com_comment/joscomment";
    $GLOBALS['josComment_absolute_path'] = $mosConfig_absolute_path . $GLOBALS['josComment_path'];
    $GLOBALS['josComment_live_site'] = $mosConfig_live_site . $GLOBALS['josComment_path'];

    require_once($GLOBALS['josComment_absolute_path'] . '/utils.php');
    require_once($GLOBALS['josComment_absolute_path'] . '/board.php');

    $board = new board($GLOBALS['josComment_absolute_path'], $GLOBALS['josComment_live_site'], $row->sectionid, $row->catid, $exclude);

    if (!$exclude) {
        $board->setContentId($row->id);
        $board->execute();
        $row->text .= '<!-- joscomment -->';
		$row->text .= $board->htmlCode();
    } else unset($board);
}

?>
