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

function com_install()
{
    global $mosConfig_absolute_path, $database;
    $adminDir = dirname(__FILE__);
    require_once("$mosConfig_absolute_path/components/com_comment/joscomment/utils.php");

    checkDatabase();

    if (!is_writable("$mosConfig_absolute_path/mambots/content/")) {
        die("Ha ocurrido un error en la instalación de !JoomlaComment</h2>
       <p>El directorio \"$mosConfig_absolute_path/mambots/content/\" no puede ser escrito</p>
       <p>Por favor:</p>
       <ul>
       <li>Desinstala !JoomlaComment</li>
       <li>Modifica los permisos del directorio</li>
       <li>Reinstala el componente</li>
       </ul>
       <br/>Gracias.</p>);");
    }

    @rename($adminDir . "/plugin/joscomment.php", "$mosConfig_absolute_path/mambots/content/joscomment.php");
    @rename($adminDir . "/plugin/joscomment.xml", "$mosConfig_absolute_path/mambots/content/joscomment.xml");

    $database->setQuery("INSERT INTO `#__mambots` (`name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES ('!JoomlaComment', 'joscomment', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '');");
    $database->query();

    $database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/edit.png' WHERE admin_menu_link='option=com_comment&task=comments'");
	$database->query();
    $database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_comment&task=settings'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/credits.png' WHERE admin_menu_link='option=com_comment&task=about'");
	$database->query();
}

?>
