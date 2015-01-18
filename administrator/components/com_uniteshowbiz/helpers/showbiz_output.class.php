<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;



	class ShowBizOutput{
		
		private static $sliderSerial = 0;
		
		private $sliderHtmlID;
		private $slider;
		private $previewMode = false;	//admin preview mode
		private $previewTemplateMode = false;
		private $slidesNumIndex;
		private $template;
		private $initNavTemplateID;
		private $isJsToBody = false;
		private $isNoConflictMode = false;
		
		
		/**
		 * 
		 * put the slider on the html page.
		 * @param $data - mixed, can be ID ot Alias.
		 */
		public static function putSlider($sliderID,$putIn=""){
			$putIn = strtolower($putIn);
						
			if($putIn == "homepage"){		//filter by homepage
				if(is_front_page() == false)
					return(false);	
			}				
			else		//case filter by pages	
			if(!empty($putIn)){
				$arrPutInPages = array();
				$arrPagesTemp = explode(",", $putIn);
				foreach($arrPagesTemp as $page){
					if(is_numeric($page) || $page == "homepage")
						$arrPutInPages[] = $page;
				}
				if(!empty($arrPutInPages)){
					
					//get current page id
					$currentPageID = "";
					if(is_front_page() == true)
						$currentPageID = "homepage";
					else{
						global $post;
						if(isset($post->ID))
							$currentPageID = $post->ID;
					}
					
					//do the filter by pages
					if(array_search($currentPageID, $arrPutInPages) === false) 
						return(false);
				}
			}
			
			$output = new ShowBizOutput();
			
			$output->putSliderBase($sliderID);
			
			$slider = $output->getSlider();
			return($slider);
		}
		
		
		/**
		 * 
		 * set preview mode
		 */
		public function setPreviewMode($type = null,$navTemplateID = null){
			$this->previewMode = true;
			if($type == "template"){
				$this->previewTemplateMode = true;
				if(!empty($navTemplateID))
					$this->initNavTemplateID = $navTemplateID;
			}
		}
		
		
		/**
		 * 
		 * set some params for output.
		 */
		public function setOutputParams($isJsToBody, $isNoConflict){
			$this->isJsToBody = UniteFunctionsBiz::strToBool($isJsToBody);
			$this->isNoConflictMode = UniteFunctionsBiz::strToBool($isNoConflict);
		}
		
		
		/**
		 * 
		 * get the last slider after the output
		 */
		public function getSlider(){
			return($this->slider);
		}
		
		
		/**
		 * 
		 * put the slider slides
		 */
		private function putSlides(){
			
				$slides = $this->slider->getSlides(true);
				
				if(empty($slides))
					UniteFunctionsBiz::throwError("No Slides Found, Please add some slides");
				
				$templateHtml = $this->template->getContent();
				
				//$templateHtml = $this->getDemoTemplate();
				
				$this->slidesNumIndex = $this->slider->getSlidesNumbersByIDs(true);
				
				if(empty($slides)):
					?>
					<div class="no-slides-text">
						No items found, please add some items
					</div>
					<?php 
				endif;
				
				
				echo "<ul>";
				foreach($slides as $slide):
					
					$params = $slide->getParams();
					
					$text = $slide->getParam("slide_text","");
										
					$title = $slide->getParam("title","");
					$urlImage = $slide->getImageUrl();
				
					$link = $slide->getParam("link","");
					
					//get the html from the local or global template
					$templateID = $slide->getParam("template_id","0");
					if(!empty($templateID) && $templateID != "0" && $templateID != 0){
						$template = new ShowBizTemplate();
						$template->initById($templateID);
						$html = $template->getContent();
					}else
						$html = $templateHtml;
					
					$html = $slide->processTemplateHtml($html);
					
				?>
					<li>
						<?php 
							echo $html;
						?>
					</li>
					
				<?php 
				
				endforeach;
				echo "</ul>";
		}
		
		
		/**
		 * 
		 * put slider javascript
		 */
		private function putJS(){
			
			$params = $this->slider->getParams();			
			
			// number of visible items array:
			$visible1 = $this->slider->getParam("visible_items_1","4",ShowBizSlider::VALIDATE_NUMERIC);
			$visible2 = $this->slider->getParam("visible_items_2","3",ShowBizSlider::VALIDATE_NUMERIC);
			$visible3 = $this->slider->getParam("visible_items_3","2",ShowBizSlider::VALIDATE_NUMERIC);
			$visible4 = $this->slider->getParam("visible_items_4","1",ShowBizSlider::VALIDATE_NUMERIC);
			
			$arrVisible = "[{$visible1},{$visible2},{$visible3},{$visible4}]";
			
			//media height array
			$media1 = $this->slider->getParam("media_height_1","0",ShowBizSlider::VALIDATE_NUMERIC);
			$media2 = $this->slider->getParam("media_height_2","0",ShowBizSlider::VALIDATE_NUMERIC);
			$media3 = $this->slider->getParam("media_height_3","0",ShowBizSlider::VALIDATE_NUMERIC);
			$media4 = $this->slider->getParam("media_height_4","0",ShowBizSlider::VALIDATE_NUMERIC);
			
			$arrMedia = "[{$media1},{$media2},{$media3},{$media4}]";
			
			$rewindFromEnd = $this->slider->getParam("rewindFromEnd","off");
			
			//set several navigation mode params
			$carousel = "off";
			$drag = "off";
			$allEntry = "off";
			
			$navMode = $this->slider->getParam("navigation_mode","default");
			switch($navMode){
				case "drag":
					$drag = "on";
				break;
				case "all":
					$allEntry = "on";
				break;
				case "carousel":
					$carousel = "on";
					$rewindFromEnd = "off";
				break;
			}
			
			?>
			
			<script type="text/javascript">
			
			<?php if($this->isNoConflictMode):?>
				jQuery.noConflict();
			<?php endif;?>
				
				jQuery(document).ready(function() {
					
					if(jQuery('#<?php echo $this->sliderHtmlID?>').showbizpro == undefined)
						showbiz_showDoubleJqueryError('#<?php echo $this->sliderHtmlID?>');
					else
					jQuery('#<?php echo $this->sliderHtmlID?>').showbizpro({
						dragAndScroll:"<?php echo $drag?>",
						carousel:"<?php echo $carousel?>",
						allEntryAtOnce:"<?php echo $allEntry?>",
						closeOtherOverlays:"<?php echo $this->slider->getParam("closeOtherOverlays","off"); ?>",
						entrySizeOffset:<?php echo $this->slider->getParam("entrySizeOffset","0",ShowBizSlider::FORCE_NUMERIC);?>,
						heightOffsetBottom:<?php echo $this->slider->getParam("heightOffsetBottom","0",ShowBizSlider::FORCE_NUMERIC);?>,
						conteainerOffsetRight:<?php echo $this->slider->getParam("conteainerOffsetRight","0",ShowBizSlider::FORCE_NUMERIC);?>,
						visibleElementsArray:<?php echo $arrVisible?>,
						mediaMaxHeight:<?php echo $arrMedia?>,
						rewindFromEnd:"<?php echo $rewindFromEnd?>",
						autoPlay:"<?php echo $this->slider->getParam("autoPlay","off"); ?>",
						delay:"<?php echo $this->slider->getParam("delay","3000",ShowBizSlider::FORCE_NUMERIC); ?>",
						speed:"<?php echo $this->slider->getParam("speed","300",ShowBizSlider::FORCE_NUMERIC); ?>",
						easing:"<?php echo $this->slider->getParam("easing","Power1.easeOut"); ?>"
					});

					jQuery(".fancybox").fancybox();
				});

			</script>
			
			<?php			
		}
		
		
		/**
		 * 
		 * put inline error message in a box.
		 */
		private function putErrorMessage($message, $trace){
			?>
			<div style="width:800px;height:300px;margin-bottom:10px;border:1px solid black;overflow:auto;">
				<div style="padding-top:40px;color:red;font-size:16px;text-align:center;">
					ShowBiz Error: <?php echo $message?>					
				</div>
				<br>
				<?php
					if(!empty($trace)) 
						dmp($trace);
				?> 
			</div>
			<?php 
		}
		
		
		/**
		 * 
		 * modify slider settings for preview mode
		 */
		private function modifyPreviewModeSettings(){
			$params = $this->slider->getParams();
			$this->isJsToBody = false;
			
			$this->slider->setParams($params);
		}
		

		
		/**
		 * 
		 * put html slider on the html page.
		 * @param $data - mixed, can be ID ot Alias.
		 */
		public function putSliderBase($sliderID){
						
			global $showbizVersion;
			
			try{
				self::$sliderSerial++;
				
				$this->slider = new ShowBizSlider();
				
				//if it's put template mode, the sliderID is the templateID
				if($this->previewMode == true && $this->previewTemplateMode == true){
					$this->slider->initByHardcodedDemo();
					$this->slider->setTemplateID($sliderID);
				}
				else{					
					$this->slider->initByMixed($sliderID);
				}
				
				//modify settings for admin preview mode
				if($this->previewMode == true)
					$this->modifyPreviewModeSettings();
				
				$this->sliderHtmlID = "showbiz_".$sliderID."_".self::$sliderSerial;
				
				//get template html:
				$templateID = $this->slider->getParam("template_id");
				UniteFunctionsBiz::validateNumeric($templateID,"Slider should have item template assigned");
				
				$this->template = new ShowBizTemplate();
				$this->template->initById($templateID);

				//get css template:
				$templateCSS = $this->template->getCss();
				
				//$templateCSS = $this->getDemoCss();
				
				//set navigation params (template, custom, none)
				$navigationType =  $this->slider->getParam("navigation_type","template");
				$navigationParams = "";
								
				if($navigationType == "template"){
					$navigationParams = " data-left=\"#showbiz_left_{$sliderID}\" data-right=\"#showbiz_right_{$sliderID}\" ";
					$navigationParams .= "data-play=\"#showbiz_play_{$sliderID}\" ";
					
					//get navigation template html:				
					$navTemplateID = $this->slider->getParam("nav_template_id");
					if(!empty($this->initNavTemplateID))
						$navTemplateID = $this->initNavTemplateID;
						
					UniteFunctionsBiz::validateNumeric($navTemplateID,"Slider should have navigation template assigned");
					
					$templateNavigation = new ShowBizTemplate();
					$templateNavigation->initById($navTemplateID);
					
					$navigationHtml = $templateNavigation->getContent();
					//$navigationHtml = $this->getDemoNavigationHtml();
					
					$navigationHtml = str_replace("[showbiz_left_button_id]", "showbiz_left_".$sliderID, $navigationHtml);
					$navigationHtml = str_replace("[showbiz_right_button_id]", "showbiz_right_".$sliderID, $navigationHtml);
					$navigationHtml = str_replace("[showbiz_play_button_id]", "showbiz_play_".$sliderID, $navigationHtml);
					
					$navigationCss = $templateNavigation->getCss();
					
					//$navigationCss = $this->getDemoNavigationCss();
					$templateCSS .= "\n".$navigationCss;
					
					$navPosition = $this->slider->getParam("nav_position","top");
					 
				}
				else if($navigationType == "custom"){
					$leftButtonID = $this->slider->getParam("left_buttonid");
					$rightButtonID = $this->slider->getParam("right_buttonid");
					$navigationParams = " data-left=\"#{$leftButtonID}\" data-right=\"#{$rightButtonID}\" ";	
				}
								
				$templateCSS = str_replace("[itemid]", "#".$this->sliderHtmlID, $templateCSS);
				
				$containerStyle = "";
				
				//set position:
				$sliderPosition = $this->slider->getParam("position","center");
				switch($sliderPosition){
					case "center":
					default:
						$containerStyle .= "margin:0px auto;";
					break;
					case "left":
						$containerStyle .= "float:left;";
					break;
					case "right":
						$containerStyle .= "float:right;";
					break;
				}
				
				//set margin:
				if($sliderPosition != "center"){
					$containerStyle .= "margin-left:".$this->slider->getParam("margin_left","0")."px;";
					$containerStyle .= "margin-right:".$this->slider->getParam("margin_right","0")."px;";
				}
				
				$containerStyle .= "margin-top:".$this->slider->getParam("margin_top","0")."px;";
				$containerStyle .= "margin-bottom:".$this->slider->getParam("margin_bottom","0")."px;";
				
				$clearBoth = $this->slider->getParam("clear_both","false");
				
				$htmlBeforeSlider = "";
				
				//put js to body handle
				if($this->isJsToBody == "true"){
					
					//include showbiz js
					$urlIncludeJS = GlobalsUniteShowbiz::$urlItemPlugin . "js/jquery.themepunch.plugins.min.js";
					$htmlBeforeSlider .= "<script type='text/javascript' src='$urlIncludeJS'></script>"."\n";
					
				    $urlIncludeJS = GlobalsUniteShowbiz::$urlItemPlugin . "js/jquery.themepunch.showbizpro.min.js";					
					$htmlBeforeSlider .= "<script type='text/javascript' src='$urlIncludeJS'></script>"."\n";
					
					//include fancybox js
					if(GlobalsShowBiz::INCLUDE_FANCYBOX){
						$urlIncludeFancybox = GlobalsUniteShowbiz::$urlItemPlugin. "fancybox/jquery.fancybox.pack.js";				
						$htmlBeforeSlider .= "<script type='text/javascript' src='$urlIncludeFancybox'></script>"."\n";
						
						$urlIncludeFancybox = GlobalsUniteShowbiz::$urlItemPlugin . "fancybox/helpers/jquery.fancybox-media.js"."\n";					
						$htmlBeforeSlider .= "<script type='text/javascript' src='$urlIncludeFancybox'></script>"."\n";
					}
				}
			
			ob_start();
			
				?>
				
			<!-- START SHOWBIZ <?php echo GlobalsUniteShowbiz::$version?> -->	
			
			<?php echo $htmlBeforeSlider?>
			
			<?php if(!empty($templateCSS)): ?>
			<style type="text/css">
				<?php echo $templateCSS ?> 
			</style>
			<?php endif?>
			
			<div id="<?php echo $this->sliderHtmlID?>" class="showbiz-container" style="<?php echo $containerStyle?>">
				
				<?php if($navigationType == "template" && $navPosition == "top"): ?>
					<!-- start navigation -->
					<?php echo $navigationHtml?>
					<!--  end navigation -->
				<?php endif?>
				
				<div class="showbiz" <?php echo $navigationParams?>>
					<div class="overflowholder">
					
						<?php $this->putSlides() ?>
					
						<div class="sbclear"></div>
					</div> 
					<div class="sbclear"></div>
				</div>
				
				<?php if($navigationType == "template" && $navPosition == "bottom"): ?>
					<!-- start navigation -->
					<?php echo $navigationHtml?>
					<!--  end navigation -->
				<?php endif?>
				
			</div>
			
			<?php if($clearBoth == "true"):?>
				<div style="clear:both"></div>
			<?php endif?>
			
			<?php $this->putJS() ?>
				
			<!-- END SHOWBIZ -->
			
			<?php 

			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			echo $content;
			
			}catch(Exception $e){
				
				$debugMode = $this->slider->getParam("debug_mode","false");
				
				$content = ob_get_contents();
				
				$message = $e->getMessage();
				
				$trace = "";
				
				if($debugMode == "true"){
					ob_clean();ob_end_clean();	
					$trace = $e->getTraceAsString();
					$trace .= $content;
				}
								
				$this->putErrorMessage($message,$trace);
				
			}
		}


		/**
		 * 
		 * get demo template code
		 */
		private function getDemoTemplate(){
			ob_start();
			
			?>
			
				<div class="mediaholder">
					<div class="mediaholder_innerwrap">
						<img alt="" src="[showbiz_image]">

						<div class="hovercover">
							
							<a href="[showbiz_link]">
								<div class="linkicon notalone"><i class="icon-link"></i></div>
							</a>
							
							<a class="fancybox" rel="group" href="[showbiz_image]"><div class="lupeicon notalone"><i class="icon-search"></i></div></a>
						</div>	
					</div>
				</div>
				
				<div class="detailholder">
					<div class="showbiz-title"><a href="#">[showbiz_title]</a></div>
					<div class="showbiz-description">[showbiz_text]</div>
				</div>
				
			<?php

			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			return($content);
		}
		
		
		/**
		 * 
		 * get demo css
		 */
		private function getDemoCss(){
			ob_start();
			
			?>
			[itemid].showbiz-container{
				max-width:1210px; 
				min-width:300px;
			}
			
			[itemid] .showbiz-title{
				margin-top:10px;
				text-align:center;
			}
			
			[itemid] .showbiz-title,
			[itemid] .showbiz-title a,
			[itemid] .showbiz-title a:visited,
			[itemid] .showbiz-title a:hover	{
					color:#555; 
					font-family: 'Open Sans', sans-serif; 
					font-size:14px; 
					text-transform:uppercase;  
					text-decoration: none; 
					font-weight:700;
			}
			
			[itemid] .showbiz-description{
				margin-top:10px;
				text-align:center;
				font-size:13px; 
				line-height:22px; 
				color:#777; 
				font-family: 'Open Sans', sans-serif;	
			}
			
			<?php
			
			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();	
			return($content);
		}
		
		
		/**
		 * 
		 * get demo navigation html
		 */
		private function getDemoNavigationHtml(){
			ob_start();
			
			?>
			
			<div class="showbiz-navigation center sb-nav-grey">
				<div id="[showbiz_left_button_id]" class="sb-navigation-left"><i class="icon-left-open"></i></div>
				<div id="[showbiz_right_button_id]" class="sb-navigation-right"><i class="icon-right-open"></i></div>
				<div class="sbclear"></div>
			</div> 
				
			<?php

			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			return($content);
			
		}
		
		/**
		 * 
		 * get demo navigation html
		 */
		private function getDemoNavigationCss(){
			ob_start();
			
			?>
			
			[itemid] .showbiz-navigation{
				margin-bottom:10px;
			}
				
			<?php

			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			return($content);
			
		}
		
		
		
	}

?>