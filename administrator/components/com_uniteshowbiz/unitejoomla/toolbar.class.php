<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;
	
	class JUniteToolBarHelperBiz{
		
		private static $addLoader = false;
		private static $loaderText = "";
		private static $addSuccessMessage = false;
		private static $successMessage = "";

		/**
		 * 
		 * get html of success message
		 */
		private static function getSuccessMessageHtml($buttonID,$text){
			$html = " 
				<div id='{$buttonID}_success_message' class='loader-success-message joomla3' style='display:none;'><span>$text</span></div>
			 ";
			return($html);
		}
		
		/**
		 * 
		 * get html of button loader
		 */
		private static function getLoaderHtml($buttonID,$loaderText){
			
			$class = "toolbar-loader";
			if(UniteFunctionJoomlaBiz::isJoomla3())
				$class = "toolbar-loader joomla3";
			
			$html = "
			<div id='{$buttonID}_loader' class='$class' style='display:none;'>
				<span class='loader-image'></span>
				<span class='loader-text'>$loaderText</span>
			</div>
			";
			
			return($html);
		}
		
		/**
		 * 
		 * add success message to button
		 */
		public static function addSuccessMessageToButton($message = ""){
			self::$addSuccessMessage = true;
			self::$successMessage = $message;
		}
		
		/**
		 * 
		 * add loader to the button 
		 */
		public static function addLoaderToButton($text = "Saving..."){
			self::$addLoader = true;
			self::$loaderText = $text;
		}
		
		
		/**
		 * 
		 * add custom button with loading etc.
		 */
		public static function addCustomButton($buttonID,$buttonText="Update",$buttonIcon="icon-32-apply"){
			
			if(UniteFunctionJoomlaBiz::isJoomla3()){
				
				$arrConvert = array();
				$buttonIcon = str_replace("icon-32", "icon", $buttonIcon);
				
				$html = "
					<button id=\"$buttonID\" class=\"btn btn-small\" onclick=\"Javascript:void(0)\" href=\"#\" style='xdisplay:none;' >
						<i class=\"$buttonIcon icon-white\"></i>
						$buttonText
					</button>
				";
			
				
			}else{	//joomla regular
				$html = "
				<a href=\"javascript:void(0);\" id=\"$buttonID\" class=\"toolbar\">
					<span class=\"$buttonIcon\"></span>
					$buttonText
				</a>
				";
			}
			
							
			//add loader
			if(self::$addLoader == true)
				$html .= self::getLoaderHtml($buttonID,self::$loaderText);
			
			if(self::$addSuccessMessage == true)
				$html .= self::getSuccessMessageHtml($buttonID, self::$successMessage);
							
			$bar = JToolBar::getInstance('toolbar');
			
			$bar->appendButton('Custom', $html, $buttonID);
			self::$addLoader = false;
			self::$addSuccessMessage = false;
		}
		
		/**
		 * 
		 * add button with loader and success message
		 */
		public static function addComboButton($buttonID,$buttonText,$loaderText,$successMessageText,$buttonIcon = "icon-32-apply"){
			self::addLoaderToButton($loaderText);
			self::addSuccessMessageToButton($successMessageText);
			self::addCustomButton($buttonID,$buttonText,$buttonIcon);
		}
		
	}

?>