<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_visforms
 * @copyright	Copyright (C) vi-solutions, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

//require_once JPATH_SITE.'/components/com_visforms/helpers/route.php';

//jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_visforms/models', 'VisformsModel');

abstract class modVisformsHelper
{
	/**
	 * Method to retrieve form and field data structure from db
	 *
	 * @return object containing the data from the database
	 * @since		1.6
	 */
	public static function getForm(&$params)
	{

		$id = $params->get('catid', 0);

		// Get an instance of the generic visforms model
		$model = JModelLegacy::getInstance('Visforms', 'VisformsModel', array('ignore_request' => true, 'id' => $id));		
		$visforms = $model->getForm();
                $fields = $model->getFields();
                if (isset($_POST)) 
                {
                    $model->getDefaultsFromPost($fields);
                    $model->setDefaultsFromPost($fields);
                }
                $visforms->fields = $fields;

		return $visforms;
	}
}