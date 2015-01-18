<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class UniteShowbizModelItems extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'ordering', 'a.ordering',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'language', 'a.language'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);				
		
		// List state information.
		parent::populateState('a.slide_order', 'DESC');
                
		$this->setState('list.limit', 0);
	}

	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.published');
		
		return parent::getStoreId($id);
	}
	
	/**
	 * 
	 * get sliders array
	 */
	public function getArrSliders(){
		$arrSliders = HelperUniteShowbiz::getArrSliders();
		return($arrSliders);
	}
	
	/**
	 * 
	 * get slider id
	 */
	public function getSliderID(){
		$sliderID = JRequest::getCmd("id");
		if(empty($sliderID))
			UniteFunctionsShowbiz::throwError("Slider ID url argument not found (id)");
		return($sliderID);
	}
	
	protected function getListQuery()
	{
		
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__uniteshowbiz_slides AS a');
		
		
		//Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}
				
		$sliderID = $this->getSliderID();
				
		$query->where("a.slider_id='$sliderID'");

		
		$orderBy = '`slide_order` ASC';
		
		$query->order($orderBy);
		return $query;
	}
	
	
	/**
	 * get items rewrited, add slider title to slide properties
	 */
	public function getItems(){
		$items = parent::getItems();
//                var_dump($items);exit;
		if(empty($items))
			return($items);
			
		$arrSlidersAssoc = HelperUniteShowbiz::getArrSlidersAssoc();
		
		foreach ($items as $key=>$item){
			if(!isset($arrSlidersAssoc[$item->slider_id]))
				throw new Exception("Slider with id: {$item->slider_id} not found");
			
			$slider = $arrSlidersAssoc[$item->slider_id];			
			$items[$key]->slider_name = $slider["title"];
		}
		
		return($items);
	}
	
	
}
