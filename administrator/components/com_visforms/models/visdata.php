<?php

/**
 * Visdata model for visforms
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

class VisformsModelVisdata extends JModelAdmin
{
    /**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{	
		// Get the form.
		$form = $this->loadForm('com_visforms.visdata', 'visdata', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}   
        
         /**
	 * Method to get the visdatas model
	 *
	 * @return array Array of objects containing the data from the database
	 * @since	1.6
	 */
    public function getVisdatasModel()
    {
        $model = JModelLegacy::getInstance('Visdatas', 'VisformsModel', array('ignore_request' => true));
        if (!$model)
        {
            //todo throw an error
            return false;
        }
        else
        {
            return $model;
        }
    }
    
    /**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Visdata', $prefix = 'VisformsTable', $config = array())
	{	
		return JTable::getInstance($type, $prefix, $config);
	}
    
    /**
	 * Method to test whether a record state can be changed.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
	 *
	 * @since   11.1
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
        $fid = JFactory::getApplication()->input->getInt('fid', -1);

		// Check form settings.
		if ($fid != -1) 
        {
			return $user->authorise('core.edit.state', 'com_visforms.visform.' . (int) $fid);
		}
		// Default to component settings 
		else {
			return parent::canEditState($record);
		}
	}
	
    /**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
	 *
	 * @since   11.1
	 */
	protected function canDelete($record)
	{
        $fid = JFactory::getApplication()->input->getInt('fid', -1);
        $user = JFactory::getUser();

		// Check form settings.
		if ($fid != -1) 
        {
			return $user->authorise('core.delete.data', 'com_visforms.visform.' . (int) $fid);
		}
		else
		{
			//use component settings
            return $user->authorise('core.delete.data', 'com_visforms');
		}
	}
}