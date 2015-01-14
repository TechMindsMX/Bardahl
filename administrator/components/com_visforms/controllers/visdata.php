<?php

/**
 * visdata conroller for visforms
 * @author       Aicha Vack
 * @see           visforms is extended and rivised adaptation of ckforms from cookex (http://www.cookex.eu) for Joomla 2.5
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controllerform');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class VisformsControllerVisdata extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since	1.6
	 */
	function __construct($config = array())
	{

		parent::__construct($config);
	}
	
	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$fid = JFactory::getApplication()->input->getInt('fid', 0);
		$append = parent::getRedirectToItemAppend($recordId);
		//$append .= '&extension=' . $this->extension;
		$append .= '&fid=' . $fid;

		return $append;
	}
	
	/**
	 * Gets the URL arguments to append to a list redirect.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   11.1
	 */
	protected function getRedirectToListAppend()
	{
		$fid = JFactory::getApplication()->input->getIntr('fid', 0);
		$append = '';

		// Setup redirect info.
		if ($fid != 0)
		{
			$append .= '&fid=' . $fid;
		}
		parent::getRedirectToListAppend();

		return $append;
	}
}