<?php

/**
 * @package		Asynchronous Social Buttons - Plugin for Joomla!
 * @author		DeConf - http://www.deconf.com
 * @copyright	Copyright (c) 2010 - 2013 DeConf.com
 * @license		GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgContentAsyncSocialButtons extends JPlugin {

 function plgContentAsyncSocialButtons(&$subject, $params) { 
	
	parent::__construct($subject, $params); 
    
	$mode = $this->params->def('mode', 1);
	
	jimport('joomla.environment.browser');
    
    $browser = JBrowser::getInstance();
    
    $browserType = $browser->getBrowser();

    JHTML::stylesheet('plugins/content/asyncsocialbuttons/asyncsocialbuttons/asyncsocialbuttons.css',false);
	
 }

  function PageURL() {
	
	$pageURL = JURI::current();

	return $pageURL;
 
  } 
 
  function abutt_insert(){
	
	$abutt='<div id="socialbut"><div id="asyncsocialbuttons"></div></div>';

	return $abutt;
	
  }
 
 function onAfterRender(){

	if( JRequest::getVar( 'view' ) == 'article' ){
	
		JHtml::_('jquery.framework');
	
	}	
	return true;
 
 }
 function onContentBeforeDisplay( $context, &$article, &$params ) {
	$app = JFactory::getApplication();
	if ( $app->isAdmin()){
		return;
	}
	
	global $mainframe;
	$document = JFactory::getDocument();
	$title = $document->title;
	if( JRequest::getVar( 'view' ) == 'article' ){
		$permalink = $this->PageURL();
		$buttonstate = $this->params->get('atwitter').$this->params->get('agoogle').$this->params->get('alinkedin').$this->params->get('abuffer').$this->params->get('afacebook');
		$article->text.="<script type='text/javascript'>
		
		function get_social(permalink,title,buttonstate){
			var url = '".JURI::root()."plugins/content/asyncsocialbuttons/asyncsocialbuttons/share.php';
			jQuery('#asyncsocialbuttons').load(url,{permalink:permalink,title:title,buttonstate:buttonstate});
		}
		
		jQuery(window).bind('load', function() {
			get_social('$permalink','$title','$buttonstate');
		});

		</script>";
		
		if ($this->params->get('aposition')=='1')
			$article->text.=$this->abutt_insert();
		else
			$article->text=$this->abutt_insert().$article->text;
	}	
 }
 
}