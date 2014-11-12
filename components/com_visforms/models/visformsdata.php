<?php
/**
 * Visformsdata model for Visforms
 *
 * @author       Aicha Vack
 * @see           visforms is extended and rivised adaptation of ckforms from cookex (http://www.cookex.eu) for Joomla 2.5
 * @package      Joomla.Site
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Visdata model class for Visforms
 *
 * @package      Joomla.Site
 * @subpackage   com_visforms
 *
 * @since        Joomla 1.6 
 */
class VisformsModelVisformsdata extends JModelList
{
	/*
	* visdata fields array
	*
	* @var array
	*/
	var $_datafields;
	
	/**
	* form id
	* @var int
	*/
	var $_id;
	
	/**
	* Single Form Dataset object
	* @var object
	*/
	var $_detail;
	
	/*
	 * Constructor
	 *
	 */
	function __construct()
	{
		
        $id = JFactory::getApplication()->input->getInt('id', -1);
		$this->setId($id);

		//get an array of fieldnames that can be used to sort data in datatable
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id', 'a.ipaddress', 'a.published',
			);
		}
		
		//get all form field id's from database
		$db	= JFactory::getDbo();
		$tn = "#__visforms_".$id;	
		$query = ' SELECT c.id from #__visfields as c where c.fid='.$id.' AND (c.frontdisplay is null or c.frontdisplay = 1) ';
		$db->setQuery( $query );
		$fields = $db->loadObjectList();
		
		//add field id's to filter_fields
		foreach ($fields as $field) {
			$config['filter_fields'][] = "a.F" . $field->id;
		}
		
		parent::__construct($config);	
	}
	
	/**
	 * Method to set the form identifier
	 *
	 * @param	int form identifier
	 * @return	void
	 * @since        Joomla 1.6
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id = $id;
	}
	
	/**
	 * Method to get the form identifier
	 *
	 * @param	int form identifier
	 * @return	int id
	 * @since        Joomla 1.6
	 */
	function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	 
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('a.id', 'asc');
        
        // Initialise variables.
		$app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $params = new JRegistry;
        $itemid = 0;
        if ($menu = $app->getMenu()->getActive())
        {
                $params->loadString($menu->params);
                $itemid = ($menu->id) ? $menu->id : 0;
        }
        else if ($menu = $app->getMenu()->getDefault($lang->getTag()))
        {
                $params->loadString($menu->params);
                $itemid = ($menu->id) ? $menu->id : 0;
        }
        $this->setState('params', $params);
        $this->setState('itemid', $itemid);

        $limit = $app->getUserStateFromRequest('com_visforms.visformsdata.list' . $itemid . '.limit', 'limit', $params->get('display_num'), 'uint');
		$this->setState('list.limit', $limit);
        $ordering = $app->getUserStateFromRequest($this->context. '.ordering', 'filter_order', $params->get('sortorder'), 'string');
        $this->setState('list.ordering', $ordering);
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		return parent::getStoreId($id);
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'*'
			)
		);
		$tn = "#__visforms_" . $this->_id;
		$query->from($tn . ' AS a');
		$query->where('(a.published = 1)');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.id');
        if (is_numeric($orderCol))
        {
            $orderCol = "a.F" . $orderCol;
        }
        $this->setState('list.ordering', $orderCol);
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}


	/**
	 * Method to retrieve the field list of a given form
	 * 
	 * @return array Array of objects containing the data from the database
	 * @since        Joomla 1.6
	 */
	function getDatafields()
	{
        $db	= JFactory::getDbO();
		// Lets load the data if it doesn't already exist
        //exclude all fieldtypes that should not be published in frontend (submits, resets, fieldseparator)
		if (empty( $this->_datafields ))
		{
			$query = ' SELECT * from #__visfields c where c.fid='.$this->_id." and (c.published = '1') and ".
                " !(c.typefield = 'reset') and !(c.typefield = 'submit') and !(c.typefield = 'image') and !(c.typefield = 'fieldsep') ORDER BY c.ordering asc";	
			$datafields = $this->_getList($query);
			$n = count($datafields);
			for ($i=0; $i < $n; $i++)
			{ 
				$registry = new JRegistry;
				$registry->loadString($datafields[$i]->defaultvalue);
				$datafields[$i]->defaultvalue = $registry->toArray();
				
				foreach ($datafields[$i]->defaultvalue as $name => $value) 
				{
					//make names shorter and set all defaultvalues as properties of field object
					$prefix =  'f_' . $datafields[$i]->typefield . '_';
					if (strpos($name, $prefix) !== false) {
							$key = str_replace($prefix, "", $name);
							$datafields[$i]->$key = $value;
					}
				}
				
				//delete defaultvalue array
				unset($datafields[$i]->defaultvalue);
			}
		}
		
		

		return $datafields;
	}
	
	/**
	* Method get the details of one dataset for a given form
	* @return object with data
	* @since        Joomla 1.6
	*/
	function getDetail()
	{
        $db	= JFactory::getDbO();
        $array = JFactory::getApplication()->input->get('cid', array(), 'ARRAY');
        JArrayHelper::toInteger($array);
        
		$id=(int)$array[0];
		if (is_numeric($id) == false) 
		{
			return null;
		}
		
		$query = ' SELECT * FROM #__visforms_'.$this->_id.
				'  WHERE id = '.$id;
		$db->setQuery( $query );
		$detail = $db->loadObject();
		
		return $detail;
	}

	
	/**
	* Method to get the form
	* @return object with data
	* @since        Joomla 1.6
	*/
	function getForm()
	{
        $db	= JFactory::getDbO();
		$query = ' SELECT * FROM #__visforms '.
				'  WHERE id = '.$this->_id;
		$db->setQuery( $query );
		$form = $db->loadObject();		
        $registry = new JRegistry;
        //Convert frontendsettings field to an array
        $registry->loadString($form->frontendsettings);
        $form->frontendsettings = $registry->toArray();
        foreach ($form->frontendsettings as $name => $value) 
        {
           //make names shorter and set all frontendsettings as properties of form object               
           $form->$name = $value;   
        }
		
		return $form;
	}
}
