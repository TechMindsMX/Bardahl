<?php
/**
 * visfield model for Visforms
 *
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


/**
 * Visfield model class for Visforms
 *
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 *
 * @since        Joomla 1.6 
 */
class VisformsModelVisfield extends JModelAdmin
{
	
	/**
	 * Method to perform batch operations on an form or a set of forms.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of form ids.
	 * @param   array  $contexts  An array of form contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   11.1
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		$result = $this->batchCopy($commands, $pks, $contexts);
		if (is_array($result))
		{
			$pks = $result;
		}
		else
		{
			return false;
		}
			

		$done = true;
		
		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
	
	/**
	 * Batch copy form.
	 *
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of forms contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since	11.1
	 */
	protected function batchCopy($commands, $pks, $contexts)
	{
		
		$fid = $commands['form_id'];
		
		if (empty($fid)) {
			$this->setError((JText::_('COM_VISFORMS_ERROR_BATCH_NO_FORM_SELECTED')));
			return false;
		}
		$table = $this->getTable();
		$i = 0;

		// Check that the user has create permission for this form 
		$extension = JFactory::getApplication()->input->get('option', '');
		$user = JFactory::getUser();
		if (!$user->authorise('core.create', $extension . '.visform.' . $fid))
		{
			$this->setError(JText::_('COM_VISFORMS_FIELD_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$table->reset();

			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}
			
			$toFid = JArrayHelper::getValue($commands, 'form_id', $table->fid);
			
			if ($toFid == $table->fid) {
				
				// We copy into the same form so Alter the label & alias
				$data = $this->generateNewTitle('', $table->name, $table->label);
				$table->label = $data['0'];
				$table->name = $data['1'];
			}
			else
			{
				//alter formid
				$table->fid = $toFid;
			}

			// Reset the ID because we are making a copy
			$table->id = 0;
            // Set to unpublished
			$table->published = 0;
            // delete ordering to get the next ordering number
            $table->ordering = '';

			// Check the row.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}
			
			//Are Data saved for the table the copied fields belong to?
			//Then we have to create a datatable field 
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			//Select Ids of Fields of copied form in Table visfields		
			$query 
				->select('a.saveresult')
				->from('#__visforms AS a')
				->where('a.id = ' .$table->fid);
				
			$db->setQuery($query);
			$saveresult = $db->loadResult();
			if (isset($saveresult) && $saveresult === '1') 
			{
				$this->createDataTableField($table->fid, $table->id);
			}
			
			
			// Get the new item ID
			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$i]	= $newId;
			$i++;
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}
	
	public function save($data)
	{
        $app = JFactory::getApplication();
		//reset fields in group "defaultvalue" that are not part of the selected fieldtype (an its subfieldtypes) to their defaults before storage
		$form = $this->getForm();
		
		//get all fieldsets in fields group defaultvalue
		$groupFieldSets = $form->getFieldsets('defaultvalue');
		
		//go through all fieldsets
		foreach ($groupFieldSets as $name => $fieldSet) {
			//and find those that are not selected in the listbox typefield and t_typefield
			if ($name !== 'visf_' . $data['typefield']) { 
				foreach ($form->getFieldset($name) as $field) {
					$data['defaultvalue'][$field->fieldname] = $form->getFieldAttribute($field->fieldname, 'default', '', 'defaultvalue');
				}
			}
		}
		
		if (isset($data['defaultvalue']) && is_array($data['defaultvalue'])) {
				$registry = new JRegistry;
				$registry->loadArray($data['defaultvalue']);
				$data['defaultvalue'] = (string)$registry;
			}

		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy') 
        {
			list($label, $name) = $this->generateNewTitle('', $data['name'], $data['label']);
			$data['label']	= $label;
			$data['name']	= $name;
		}

		if (parent::save($data)) {

			return true;
		}

	return false;
	}
	
	/**Method to create a field in datatable
	 *
	 * @params string $fid form id
	 * @return boolean true
	 *
	 * @since Joomla 1.6
	 */
	 // Test if data must be saved in DB for this form
	
	public function createDataTableField($fid = Null, $id = Null) 
	{
		
		$tn = "#__visforms_".$fid;
	
		$dba	= JFactory::getDbo(); 

		$tableFields = $dba->getTableColumns($tn,false);
		$fieldname = "F" . $id;
		
		
		if (!isset( $tableFields[$fieldname] ))  
		{

			$query = "ALTER TABLE ".$tn." ADD F".$id." TEXT NULL";
			$dba->SetQuery($query);
			if (!$dba->execute()) 
			{
				echo JText::_( 'COM_VISFORMS_PROBLEM_WITH' )." (".$query.")";
				return false;
			}
		return true;
		}
	return true;
	}


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
		$form = $this->loadForm('com_visforms.visfield', 'visfield', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		$app=JFactory::getApplication();
		$fid = $app->input->getInt('fid', 0);
		$id = $app->input->getInt('id', 0);
		
		$user = JFactory::getUser();

		// Check for existing article.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_visforms.visform.'. $fid . '.visfield.'.(int) $id))
		|| ($id == 0 && !$user->authorise('core.edit.state', 'com_visforms.visform.'. $fid)))
		{
			// Disable fields for display.
			$form->setFieldAttribute('published', 'disabled', 'true');
		}
        $form->setFieldAttribute('ordering', 'disabled', 'true');
		
		return $form;
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the defaultvalue field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->defaultvalue);
			$item->defaultvalue = $registry->toArray();
		}

		return $item;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{	
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_visforms.edit.visfield.data', array());

		if (empty($data)) 
        {
			$data = $this->getItem();
		}

		return $data;
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
	public function getTable($type = 'Visfield', $prefix = 'VisformsTable', $config = array())
	{	
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 *
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'fid = '.JFactory::getApplication()->input->get('fid', 0);
		return $condition;
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

		// Check for existing field.
		if (!empty($record->id)  && !empty($record->fid)) 
        {
			return $user->authorise('core.edit.state', 'com_visforms.visform.' . (int) $record->fid . '.visfield.' .(int) $record->id);
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
		if (!empty($record->id)  && !empty($record->fid)) 
        {
			$user = JFactory::getUser();
			return $user->authorise('core.delete', 'com_visforms.visform.' . (int) $record->fid . '.visfield.' .(int) $record->id);
		}
		else
		{
			//use component settings
			return parent::canDelete($record);
		}
	}
	
	/**
	 * Method to change the label & name.
	 *
	 * @param   string   $name        The name.
	 * @param   string   $label        The label.
	 *
	 * @return	array  Contains the modified label and name.
	 *
	 * @since	11.1
	 */
	protected function generateNewTitle( $catid, $name, $label)
	{
		// Alter the label & name
		$table = $this->getTable();
		while ($table->load(array('name' => $name)))
		{

			$label = JString::increment($label);
			$name = JString::increment($name, 'dash');
		}

		return array($label, $name);
	}
}		
?>
