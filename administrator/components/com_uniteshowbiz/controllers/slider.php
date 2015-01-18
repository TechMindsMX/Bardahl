<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class UniteShowbizControllerSlider extends JControllerForm {
	
	/**
	 * 
	 * bypass direct saving restrictions
	 */
	private function holdID(){		
		$context = "$this->option.edit.$this->context";
		$recordId = JRequest::getInt("id");
		$this->holdEditId($context, $recordId);
		
	}
	
	public function save($key=null, $urlVar=null){
		$this->holdID();
		return parent::save($key,$urlVar);
	}
	
	public function cancel($key=null){
		$this->holdID();
		return parent::cancel($key);
	}
	
	
}

?>