<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_visforms
 * @copyright	Copyright (C) vi-solutions, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JLoader::register('JHTMLVisforms', JPATH_ADMINISTRATOR . '/components/com_visforms/helpers/html/visforms.php');
JLoader::register('VisformsEditorHelper', JPATH_SITE . '/components/com_visforms/helpers/editor.php');

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$lang = JFactory::getLanguage();
$extension = 'com_visforms';
$base_dir = JPATH_SITE . '/components/com_visforms';
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, null, $language_tag, $reload);

$visforms = modVisformsHelper::getForm($params);
$menu_params = $params;
$formLink = "index.php?option=com_visforms&view=visforms&task=send&id=".$visforms->id;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$_SESSION['vis_send_once'.$params->get('catid')] = "1";

require JModuleHelper::getLayoutPath('mod_visforms');
