<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;
	//---------------------------------------------------------------------------------------------------------------------	
	
	if(!function_exists("dmp")){
		function dmp($str){
			echo "<div align='left'>";
			echo "<pre>";
			print_r($str);
			echo "</pre>";
			echo "</div>";
		}
	}
	

	
	
?>