<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @since		1.6
 */
class JFormFieldDateFormat extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'DateFormat';

	/**
	 * Method to get a list of categories that respects access controls and can be used for
	 * either category assignment or parent category assignment in edit screens.
	 * Use the parent element to indicate that the field will be used for assigning parent categories.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options = array();
		$options[0]       = JHTML::_('select.option',  'd.m.Y;%d.%m.%Y', 'DD.MM.YYYY');
		$options[1]         = JHTML::_('select.option',  'm/d/Y;%m/%d/%Y','MM/DD/YYYY');
		$options[2]         = JHTML::_('select.option',  'Y-m-d;%Y-%m-%d','YYYY-MM-DD');

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
