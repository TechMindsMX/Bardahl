<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

	class UniteElementsBaseBiz{
		
		protected $db;
		
		public function __construct(){
			
			$this->db = new UniteDBBiz();
		}
		
	}

?>