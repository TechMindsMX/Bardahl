<?php
/**
 * vistools model for Visforms
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
 * vistools Model
 *
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 * @since        Joomla 1.6 
 */
class VisformsModelVistools extends JModelForm
{
	/*
	 * Constructor
	 *
	 * @return void
	 * @since Joomla 1.6
	 */
	function __construct()
	{
		parent::__construct();		
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		jimport('joomla.filesystem.file');

		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$id = $app->input->getInt('id', 0);
        $filename = $app->input->getString('file', '');
        $filename = base64_decode($filename);
		$explodeArray   = explode(':', $filename);
		$file            = end($explodeArray);

		$this->setState('extension.id', $id);
        $this->setState('filename', $file);
	}
    
    /**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		// Codemirror or Editor None should be enabled
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('#__extensions as a')
			->where(
				'(a.name =' . $db->quote('plg_editors_codemirror') .
				' AND a.enabled = 1) OR (a.name =' .
				$db->quote('plg_editors_none') .
				' AND a.enabled = 1)'
			);
		$db->setQuery($query);
		$state = $db->loadResult();

		if ((int) $state < 1)
		{
			$app->enqueueMessage(JText::_('COM_VISFORMS_ERROR_EDITOR_DISABLED'), 'warning');
		}

		// Get the form.
		$form = $this->loadForm('com_visforms.source', 'source', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
    
    /**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		$data = $this->getCss();

		$this->preprocessData('com_visforms.source', $data);

		return $data;
	}

	/**
	 * Method to get the css file
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function &getCss()
	{
        $app = JFactory::getApplication();
		$css = new StdClass;
		
		//$fileName	= $this->getState('filename');
		$csspath = JPath::clean(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_visforms' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'visforms.css');
		
		if (file_exists($csspath)) 
		{
			jimport('joomla.filesystem.file');
			$css->extension_id	= $this->getState('extension.id');
			$css->filename		= $this->getState('filename');
			$css->source = JFile::read($csspath);
		}
		else 
		{
			$app->enqueueMessage(JText::_('COM_VISFORMS_FILE_NOT_FOUND'), 'error');
		}

		return $css;
	}
	
	/**
	 * Method to retrieve the component Id
	 *
	 * @return int $id
	 * @since Joomla 1.6
	 *
	 */
	
	public function getComponentId() 
	{
		//Initialise Variables
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required field from the table.
		$query->select('a.extension_id');
		$query->from($db->quoteName('#__extensions').' AS a');

		// Filter by extension type.
		$query->where($db->quoteName('type').' = '.$db->quote('component') . ' and ' . $db->quoteName('name').' = '.$db->quote('visforms'));
		$db->setQuery($query);
		if (!$id = $db->loadResult())
		{
			return false;
		}
		
		return $id;
	
	}
	
	/**
	 * Method to store the source file contents.
	 *
	 * @param	array	$data The souce data to save.
	 *
	 * @return	boolean	True on success, false otherwise and internal error set.
	 * @since	1.6
	 */
	public function save($data)
	{
        jimport('joomla.filesystem.file');
		
		// Initialize some variables
		
		$fileName	= $this->getState('filename');
		$filePath	= JPath::clean(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_visforms' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $fileName);
		
		// Include the extension plugins for the save events.
		JPluginHelper::importPlugin('extension');
        
        $user = get_current_user();
		chown($filePath, $user);
		JPath::setPermissions($filePath, '0644');

		// Try to make the template file writable.
		if (!is_writable($filePath))
		{
			$app->enqueueMessage(JText::_('COM_VISFORMS_ERROR_SOURCE_FILE_NOT_WRITABLE'), 'warning');
			$app->enqueueMessage(JText::_('COM_VISFORMS_FILE_PERMISSIONS' . JPath::getPermissions($filePath)), 'warning');

			if (!JPath::isOwner($filePath))
			{
				$app->enqueueMessage(JText::_('COM_VISFORMS_CHECK_FILE_OWNERSHIP'), 'warning');
			}

			return false;
		}

		$return = JFile::write($filePath, $data['source']);  

        // Try to make the template file unwritable.
		if (JPath::isOwner($filePath) && !JPath::setPermissions($filePath, '0444'))
		{
			$app->enqueueMessage(JText::_('COM_VISFORMS_ERROR_SOURCE_FILE_NOT_UNWRITABLE'), 'error');

			return false;
		}
		elseif (!$return)
		{
			$app->enqueueMessage(JText::sprintf('COM_VISFORMS_ERROR_FAILED_TO_SAVE_FILENAME', $fileName), 'error');

			return false;
		}

		return true;
	}
	
}
