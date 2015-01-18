<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

	class UniteBaseFrontClassBiz extends UniteBaseClassBiz{		
		
		const ACTION_ENQUEUE_SCRIPTS = "wp_enqueue_scripts";
		
		/**
		 * 
		 * main constructor		 
		 */
		public function __construct($mainFile,$t){
			
			parent::__construct($mainFile,$t);
			
			self::addAction(self::ACTION_ENQUEUE_SCRIPTS, "onAddScripts");
		}	
		
	}
?>