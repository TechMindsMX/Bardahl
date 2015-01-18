<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;
	
	jimport('joomla.application.component.view');
	
	 
	class JMasterViewUniteShowbiz extends JMasterViewUniteBaseBiz{
		
		private $userTemplate;
		
		/**
		 * 
		 * overwrite constructor function.
		 * do ajax operations.
		 * @param unknown_type $config
		 */
		public function __construct($config = array()){
			parent::__construct($config);
			
			if($this->_layout == "ajax"){
				$actions = new ActionsUniteShowbiz();
				$actions->operate();
				exit();
			}
		}
		
		
		/**
		 * 
		 * get some master template path
		 */
		private function getMasterTemplatePath($filename){
			$filepath = dirname(__FILE__)."/tpl/$filename";
			return($filepath);
		}
		
		/**
		 * 
		 * display master template (master.php from tpl folder) 
		 */
		private function displayMasterTemplate(){
			
			
			//each view has self controls
			UniteControlsBiz::emptyControls();
			
			if(isset($this->form))
				UniteControlsBiz::loadControlsFromForm($this->form);
			
			$filepath = dirname(__FILE__)."/tpl/master.php";
			
			if(!is_file($filepath))
				UniteFunctionsShowbiz::throwError("master template not found: $filepath");
			
			$arrControls = UniteControlsBiz::getArrayForJsOutput();
			$jsonControls = json_encode($arrControls);
			
			//prepare content
			ob_start();
			require $filepath;				
			$output = ob_get_contents();
			ob_end_clean();
			
			//output content
			echo $output;
		}
		
		
		
		/**
		 * 
		 * replace the display function by display master function
		 * and all the files will go via the master.
		 */
		public function display($tpl = null){
			
			//displa user template inside the master
			$this->userTemplate = $tpl;
			
			$this->displayMasterTemplate();
		}
		
	}
	
?>