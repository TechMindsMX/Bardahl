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

class tree {
    var $_id;
    var $_counter;

    function getSeed($data, $seed)
    {
        $this->_counter++;
        if ($seed) {
            foreach($seed as $item) {
                $data[$item]['wrapnum'] = $this->_counter;
                $this->_new[] = $data[$item];
                if ($data[$item]['seed']) {
                    $this->getSeed($data, $data[$item]['seed']);
                    $data[$item] = null;
                }
            }
        }
        $this->_counter--;
    }

    function build($data)
    {
        $index = 0;
        $this->_new = null;
        $this->_counter = 0;

        foreach($data as $item) {
            $old[$item['id']] = $index;
            $data[$index]['treeid'] = $index;
            if ($data[$index]['parentid'] != -1) $data[$index]['parentid'] = $old[$item['parentid']];
            $index++;
        }

        foreach($data as $item)
        if ($item['parentid'] != -1) $data[$item['parentid']]['seed'][] = $item['treeid'];

        foreach($data as $item) {
            if ($item['parentid'] == -1) {
                $this->_new[] = $item;
                if ($item['seed']) $this->getSeed($data, $item['seed']);
            }
        }
        return $this->_new;
    }
}

?>