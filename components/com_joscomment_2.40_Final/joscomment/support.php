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

class support {
    var $_ajax;
    var $_absolute_path;
    var $_live_site;
    var $_only_registered;
    var $_support_emoticons;
    var $_support_UBBcode;
    var $_support_pictures;
    var $_hide;
    var $_emoticons;
    var $_emoticons_path;
    var $_contentId;
    var $_moderator;
    var $_show_readon;
    var $_date_format;

    function setAjax($value)
    {
        $this->_ajax = $value;
    }

    function setAbsolute_path($value)
    {
        $this->_absolute_path = $value;
    }

    function setLive_site($value)
    {
        $this->_live_site = $value;
    }

    function setOnly_registered($value)
    {
        $this->_only_registered = $value;
    }

    function setSupport_emoticons($value)
    {
        $this->_support_emoticons = $value;
    }

    function setSupport_UBBcode($value)
    {
        $this->_support_UBBcode = $value;
    }

    function setSupport_pictures($value)
    {
        $this->_support_pictures = $value;
    }

    function setHide($value)
    {
        $this->_hide = $value;
    }

    function setEmoticons($value)
    {
        $this->_emoticons = $value;
    }

    function setEmoticons_path($value)
    {
        $this->_emoticons_path = $value;
    }

	function setContentId($value)
    {
        $this->_contentId = $value;
    }

	function setModerator($value)
    {
        $this->_moderator = $value;
    }
    
    function setReadon($value)
    {
        $this->_show_readon= $value;
    }
    
    function setDate_format($value)
    {
        $this->_date_format = $value;
    }
}

?>
