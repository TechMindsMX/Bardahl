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

require_once($mainframe->getPath('toolbar_html'));
require_once($mainframe->getPath('toolbar_default'));

switch ($task) {
    case "new":
        menucomment::FILE_MENU();
        break;

    case "edit":
        menucomment::FILE_MENU();
        break;

    case "language":
        menucomment::LANG_MENU();
        break;

    case "about":
        menucomment::ABOUT_MENU();
        break;

    case "settings":
        menucomment::CONFIG_MENU();
        break;

    default:
        menucomment::MENU_DEFAULT();
        break;
}

?>