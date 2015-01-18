<?php
/**
 * @Plugin "ContactUs Form"
 * @version 3.1.1
 * @author EmmeAlfa
 * @authorUrl http://www.emmealfa.it
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.plugin.plugin');
JHtml::_('behavior.formvalidation');

class plgContentContactusform extends JPlugin {

	function plgContentContactusform ( &$subject, $params ) {
		parent::__construct( $subject, $params );
 	}

	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{

		$html ="";
		$captcha_is_valid = true;
		$jv = (int) substr(JVERSION,0,1);
		
		$req_subject = ( $this->params->get('req_subject','1') ) ? ' required' : '' ;  
		$req_name 	 = ( $this->params->get('req_name','1')    ) ? ' required' : '' ;   		
		$emailLanguage 	 = $this->params->get('email_language')  ;  

		
		// If captcha enabled, call the plugin and create a dispatcher (based on Joomla version)
		if ( $this->params->get('captcha') ) {		
			JPluginHelper::importPlugin('captcha');
			switch ($jv) {	
				case 2:		
					$dispatcher = JDispatcher::getInstance();
					break;
				case 3:			
					$dispatcher = JEventDispatcher::getInstance();
					break;
			}
		}
				
		// Search in the article text the plugin code and exit if not found
		$regex = "%\{contactus mailto=([^\{]*)\}%is";
		preg_match_all( $regex, $row->text, $matches );
		$count = count( $matches[0] );
		if ( !$count )  return true;

		$lang =& JFactory::getLanguage();  
		$siteLanguage = $lang->getTag();
		
		// Get the email text for the site admin in his preferred language as in plugin parameter
		$lang->load('com_contact', JPATH_SITE , $emailLanguage, true); 
		$enquryText = JText::_( 'COM_CONTACT_ENQUIRY_TEXT');

		// Load the language file in the current site language		
		$lang->load('com_contact', JPATH_SITE , $siteLanguage , true );  
		$lang->load('plg_captcha_recaptcha', JPATH_ADMINISTRATOR , $siteLanguage   , true); 		

		// Get the post variables
		$post = JFactory::getApplication()->input->post;				
		$cufaction = $post->get('cufaction',null);

		// Check if there are data coming from the submitted form...
		if ($cufaction=="sendmail") {
			// Captcha is enabled in the parameters? If so check in the post data if it is valid
			if ( $this->params->get('captcha') ) {
				$res = $dispatcher->trigger('onCheckAnswer',$post->get('recaptcha_response_field') );	
				$captcha_is_valid = ($res[0]) ? true : false ;										
			}
				
			//Captcha is valid?	Session token is valid?		
			if ($captcha_is_valid || !JSession::checkToken() ) {
				//YES -> Call the send mail routine and show the thanks messange
				plgContentContactusform::_sendemail($post,$enquryText);
				$html .= '<div class="plg_contactus_main_div" id="plg_contactus_'.$row->id.'" >';
				$html .=  '<div id="thank_message">';
				$html .=  JText::_( 'COM_CONTACT_EMAIL_THANKS');
				$html .=  '</div></div>';	
			} else {
				//NO -> Show a captcha error message
				$html .= '<div class="plg_contactus_main_div" id="plg_contactus_'.$row->id.'" >';
				$html .=  '<div id="thank_message">';
				$html .=  JText::_( 'PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL');
				$html .=  '</div></div>';				
			}

		} else {
		// ...otherwise it shows the form
		$html .= '<div class="plg_contactus_main_div" id="plg_contactus_'.$row->id.'" >';
		$html .=  '<form action="'. $_SERVER["REQUEST_URI"] .'" method="post" name="emailForm" id="emailForm" class="form-validate">';
		$html .=  '<div id="write_us_div">';
		$html .=  '<fieldset id="write_us_fieldset">';
		$html .=  '<legend>'. JText::_( 'COM_CONTACT_EMAIL_FORM' ).'</legend>';
		$html .=  '<label for="contact_name">';
		$html .=  '&nbsp;Nombre:*';
		$html .=  '</label>';
		$html .=  '<br />';
		$html .=  '<input type="text" name="name" id="contact_name" size="30" class="inputbox '.$req_name.'" value="" />';
		$html .=  '<br />';
		$html .=  '<label id="contact_emailmsg" for="contact_email">';
		$html .=  '&nbsp;Email:*';
		$html .=  '</label>';
		$html .=  '<br />';
		$html .=  '<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox" maxlength="100" />';
		$html .=  '<br />';
		$html .=  '<label for="contact_subject">';
		$html .=  '&nbsp;Empresa:';
		$html .=  '</label>';
		$html .=  '<br />';
		$html .=  '<input type="text" name="subject" id="contact_subject" size="30" class="inputbox'.$req_subject.'" value="" />';
		$html .=  '<br /><br />';
                $html .=  '<label for="contact_phone">';
		$html .=  '&nbsp;Telefono:';
		$html .=  '</label>';
		$html .=  '<br />';
		$html .=  '<input type="text" name="subject" id="contact_subject" size="30" class="inputbox'.$req_subject.'" value="" />';
		$html .=  '<br /><br />';
		$html .=  '<label id="contact_textmsg" for="contact_text">';
		$html .=  '&nbsp;'. JText::_( 'COM_CONTACT_CONTACT_ENTER_MESSAGE_LABEL' ).':';
		$html .=  '</label>';
		$html .=  '<br />';
		$html .=  '<textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>';
		$html .=  '<br />';
		$html .=  '<br />';
		if ($this->params->get('captcha')) {
			// $html .=  '<div id="dynamic_recaptcha_1"></div>';
			$dispatcher->trigger('onInit','CUF_CAPTCHA');
			$captcha_html =  $dispatcher->trigger('onDisplay','CUF_CAPTCHA','CUF_CAPTCHA',null);
			$html .= $captcha_html[0];
			$html .=  '<br />';		
		}		
		$html .=  '<input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />';
		$html .=  '<label for="contact_email_copy">';
		$html .=   JText::_( 'COM_CONTACT_CONTACT_EMAIL_A_COPY_LABEL' )  ;
		$html .=  '</label>';
		$html .=  '<br />';
		$html .=  '<br />';		
		$html .=  '<button class="button validate" type="submit">'. JText::_('COM_CONTACT_CONTACT_SEND')  .'</button>';
		$html .=  '</fieldset>	';
		$html .=  '</div>';
		$html .=  '<input type="hidden" name="recipient" value="'.$matches[1][0].'" />';		
		$html .=  '<input type="hidden" name="cufaction" value="sendmail" />';
		$html .=   JHTML::_( 'form.token' );
		$html .=  '</form>';
		$html .=  '<br />';
		$html .=  '</div>';
		}
		
		// Replace the form in place of the plugin tag 
		$found = $matches[0][0];
		$row->text = str_replace( $found  ,$html , $row->text );
	}
	
	function _sendemail($post,$enquryText) {
		$owner_email = 	$post->get('recipient',null,'string');
		$sender = 		$post->get('email',null,'string');	
		$name = 		$post->get('name',null,'string');			
		$subject = 		$post->get('subject',null,'string');	
		$text = 		$post->get('text',null,'string');			
		$email_copy = 	$post->get('email_copy',false,'boolean');	

		$body =  		str_replace('%s',JURI::current(), JText::_( 'COM_CONTACT_ENQUIRY_TEXT'))."\n".$name."  <".$sender.">\n\n".$text;		
		$owner_email = 	str_replace( '#'  , '@' , $owner_email );	
		$owner_email = 	str_replace( '"'  , '' , $owner_email );
        $recipient = explode(";",$owner_email); 		
		
		if ($email_copy ) { 	
			$app		= JFactory::getApplication();		
			$mailfrom	= $app->getCfg('mailfrom');
			$fromname	= $app->getCfg('fromname');
			$sitename	= $app->getCfg('sitename');
			
			$copytext		= JText::sprintf('COM_CONTACT_COPYTEXT_OF', $fromname, $sitename);
			$copytext		.= "\r\n\r\n".$body;
			$copysubject	= JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

			$mail = JFactory::getMailer();
			$mail->addRecipient($sender);
			$mail->addReplyTo(array($sender, $name));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($copysubject);
			$mail->setBody($copytext);
			$sent = $mail->Send();
			}
		
		$body =  str_replace('%s',JURI::current(), $enquryText)."\n\n".$name."  <".$sender.">\n\n".$text;
		
		$mailer = JFactory::getMailer();
		foreach ($recipient as $r) $mailer->addRecipient($r);
		$mailer->setSender($sender);
		$mailer->setSubject($subject);
		$mailer->isHTML(false);
		$mailer->setBody($body);
		$send = $mailer->Send();
		
		$mailer = null;

	}
	
}