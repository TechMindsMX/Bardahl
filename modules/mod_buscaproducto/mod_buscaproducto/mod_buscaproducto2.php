<?php
/** * @package
 * Joomla.Site *
 * @subpackage  mod_breadcrumbs *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * * @license     GNU General Public License version 2 or later; see LICENSE.txt */
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';
$result=ModBuscaProductoHelper::getAutos();
var_dump($result);exit;
require JModuleHelper::getLayoutPath('mod_buscaproducto',$params->get('layout', 'default'));