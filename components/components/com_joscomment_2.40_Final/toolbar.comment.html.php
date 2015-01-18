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

require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

function existsAkoTable()
{
    global $database;
    $database->setQuery('');
}

class menucomment {
    function MENU_DEFAULT()
    {
        mosMenuBar::startTable();
        if (existsTable('#__akocomment'))
            mosMenuBar::custom('import', 'dbrestore.png', 'dbrestore.png', 'Ako Import', false);
        mosMenuBar::publishList();
        mosMenuBar::unpublishList();
        mosMenuBar::addNew();
        mosMenuBar::editList();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
    function CONFIG_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::save('savesettings');
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
    function FILE_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::save();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
    function ABOUT_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::back();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
}

?>