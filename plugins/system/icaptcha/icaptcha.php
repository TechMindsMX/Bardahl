<?php
/**
 * @package		iCaptcha
 * @copyright	Copyright (C) 2006 - 2011 Ideal Extensions for Joomla. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.plugin.plugin');

/**
 * Plugin class for redirect handling.
 *
 * @package		Joomla
 * @subpackage	plgSystemRedirect
 */
class plgSystemIcaptcha extends JPlugin
{
	/**
	 * Object Constructor.
	 *
	 * @access	public
	 * @param	object	The object to observe -- event dispatcher.
	 * @param	object	The configuration object for the plugin.
	 * @return	void
	 * @since	1.0
	 */
	function __construct(&$subject, $config)
	{
		$this->session = JFactory::getSession();
		
		$lang =& JFactory::getLanguage();
		$lang->load('plg_system_icaptcha',JPATH_ADMINISTRATOR);
		$lang->load('plg_system_icaptcha');
		
		parent::__construct($subject, $config);
	}
	
	public function onSubmitContact($contact, $post) {
		return $this->onValidateForm($contact->params);
	}
	
	function onAfterDisplayForm($param=null,$returnType='html') {
		if(is_object($param)){
			$this->params->merge($param);
		}
		
		$html	='';
		$user	= JFactory::getUser();
		if($this->params->get('only_non_registered') == 1 AND $user->id ){
			return $html;
		}
		if($this->params->get('captcha_systems') == 'recaptcha'){
			$html = $this->displayRecaptchaForm();
		}else if($this->params->get('captcha_systems') == 'mathguard'){
			$html = $this->displayMathGuardForm();
		}else if($this->params->get('captcha_systems') == 'vouchsafe'){
			$html = $this->displayVouchSafe();
		}else if($this->params->get('captcha_systems') == 'mollom'){
			$html = $this->displayMollom();
		}else{
			$html = $this->displaySecurImage2Form();
		}
		$html = '<div class="captcha-container">'.$html.'</div>';
		//echo '<pre>'; print_r($this->params); echo '</pre>';
		
		if($returnType == 'html'){
			return $html;
		} 
		echo $html;
	}
	
	function onValidateForm($param,$returnType='html') {
		$user	= JFactory::getUser();
		if( ($this->params->get('only_non_registered') == 1 AND $user->id) OR defined('ICAPTCHA_RUN') ){
			return true;
		}
		// @todo Find out why this function is called twice on form submission
		// Avoid this function to be called twice
		define('ICAPTCHA_RUN',1);
		
		
		$lang =& JFactory::getLanguage();
		$lang->load('plg_system_icaptcha',JPATH_ADMINISTRATOR);
		
		if(is_object($param)){
			$this->params->merge($param);
		}
		//echo '<pre>'; print_r($args); echo '</pre>'; exit; 
		//echo $this->params->get('captcha_systems') ; exit;
		//echo 'Args: <pre>'; print_r(func_get_args()); echo '</pre>'; exit;
		$html	= '';
		
		if($this->params->get('captcha_systems') == 'recaptcha'){
			$html = $this->validateRecaptcha();
		}else if($this->params->get('captcha_systems') == 'mathguard'){
			$html = $this->validateMathGuard();
		}else if($this->params->get('captcha_systems') == 'vouchsafe'){
			$html = $this->validateVouchSafe();
		}else if($this->params->get('captcha_systems') == 'mollom'){
			$html = $this->validateMollom();
		}else{
			$html = $this->validateSecurImage2();
		}
		
				//echo '<pre>'; print_r($_SESSION); exit;
		if($this->params->get('returnType') == 'boolean' OR $returnType == 'boolean'){
			return $html;
		}else if(!$html){
			echo '<script>alert("'.JText::_('ICAPTCHA_WRONG_VALIDATION_CODE').'");history.back();</script>'; exit;
		}
		return $html;
	}
	
	
	function validateSecurImage2(){
		
		require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'securImage2'.DS.'securimage.php');
		$securimage = new Securimage();
		// the code was incorrect
//		echo '<pre>'; print_r($_SESSION); exit;
		if($securimage->check(JRequest::getVar('captcha_code','','POST')) == false) {
			return false;
		}
		return true;
	}
	
	function validateMathGuard(){
		require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'mathguard'.DS.'mathGuard.class.php');
		// the code was incorrect
		//Problem with the suffix
		if(mathGuardImproved::checkResult(JRequest::getVar('captcha_code','','POST'),$this->params->get('suffix')) == false) {
	//	if(mathGuardImproved::checkResult(JRequest::getVar('captcha_code','','POST'),	'') == false) {
			return false;
		}
		return true;
	}

	function validateRecaptcha(){
		if($this->params->get('captcha_systems-recaptcha-PriKey')){
			if(!defined('RECAPTCHA_API_SERVER')){
				require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'recaptchalib.php'); /**    reCAPTCHA Library   **/
			}
			$resp = recaptcha_check_answer ($this->params->get('captcha_systems-recaptcha-PriKey',''),
											$_SERVER["REMOTE_ADDR"],
											JRequest::getVar("recaptcha_challenge_field",null, "POST"),
											JRequest::getVar("recaptcha_response_field",null, "POST") );		
			if(!$resp->is_valid) {
				/*echo "\n recaptcha_challenge_field: ".JRequest::getString("recaptcha_challenge_field",null, "POST");
				echo "\n recaptcha_response_field: ".JRequest::getString("recaptcha_response_field",null, "POST");
				echo "\n";
				//print_r($_POST); exit;
				echo $resp->error;*/
				return false;
			}
		}
		return true;
	}
	
	function validateVouchSafe(){
		$challenge	= JRequest::getCmd("vouchsafe-challenge-id", JRequest::getCmd("vouchsafe-challenge-id", null, 'GET'), 'POST');
		$privatekey	= trim($this->params->get('captcha_systems-vouchsafe-PriKey')); // you got this from the signup page
		 
           
		if($privatekey AND $challenge){
			if(!function_exists('vouchsafe_check_answer')){
				/**    VouchSafe Library   **/
				require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'vouchsafe-lib.php'); 
			}
			// get user response
            $response = JRequest::getVar("vouchsafe-challenge-response", JRequest::getCmd("vouchsafe-challenge-response", null, 'GET'), 'POST');
            if ($response == null) {
                return false;
            }
			$serverToken =  JRequest::getVar("vouchsafe-server-token", JRequest::getCmd("vouchsafe-server-token", null, "GET"), "POST");
			$resp = vouchsafe_check_answer($privatekey, $challenge, $response, $serverToken);
			return $resp->is_valid;
		}
		return false;
	}
	
	function displaySecurImage2Form(){
		$urlPath = JURI::base().'plugins/system/icaptcha/captcha_systems/securImage2/';
		$doc =& JFactory::getDocument();
		jimport('joomla.error.profiler');
		$iCaptchaId='captcha_code_'.JProfiler::getmicrotime();
		
		
		$javascript	= '';
		if(!defined('MULTIPLE_ENGINE_CAPTCHA_INSTANCE'))
		{
			define('MULTIPLE_ENGINE_CAPTCHA_INSTANCE',1);
			JText::script('ICAPTCHA_WRONG_VALIDATION_CODE');
			
			//$doc->addScript(JURI::root().'plugins/system/icaptcha/captcha_systems/securImage2/securImage.js');
			/**/
			if(!class_exists('iBrowser')){
				require_once (JPATH_PLUGINS.'/system/icaptcha/helpers/browser.php');
			}
			$browser = new iBrowser();
			
			if($browser->getBrowser() == 'Internet Explorer' AND version_compare($browser->getVersion(), '9.0') <= 0){
				$doc->addScript(JURI::root().'plugins/system/icaptcha/captcha_systems/securImage2/securImage-ie8.js');
			}else{
				$doc->addScript(JURI::root().'plugins/system/icaptcha/captcha_systems/securImage2/securImage.js');
			}
			/**/
			$javascript	.= "
/* <![CDATA[ */
var icaptchaURI	= '".JURI::root()."';
/* ]]> */
			";
			
		}
		$reloadLink	= '';
		if($this->params->get('captcha_systems-securImage2-reloadbutton', 1)){
			$linkAttributes['onclick']	= 'updateCaptcha(); return false;';
			$linkAttributes['id']		= 'reloadImage';
			$image	= JHTML::_('image',$urlPath.'images/'.$this->params->get('captcha_systems-securImage2-reloadimage', 'sync.png'),JText::_('ICAPTCHA_RELOAD_IMAGE'),'border="0"');
			$reloadLink .= JHTML::_('link', JURI::current().'#'.JText::_('ICAPTCHA_RELOAD_IMAGE'),$image,$linkAttributes ); 
		}
		$html = '<br />';
		
			//I'm not sure why, but it  will not work with only one instance if I don't enter the url for the first time.
			$html .= '<img id="captcha'.$this->params->get('suffix','').'" name="captcha" src="'.$urlPath.'show.php?sid='.md5(uniqid(time())).'" alt="'.JText::_('ICAPTCHA_IMAGE_ALT_TEXT').'" />';
			$html .= $reloadLink; 
			$html .= '<br />';
			$html .= '<label for="'.$iCaptchaId.'" class="requiredField">'.JText::_('ICAPTCHA_VERIFICATION_CODE').': </label>';
			$html .= '<br />';
		
			$html .=  '<input type="text" 
							name="captcha_code" 
							id="'.$iCaptchaId.'" 
							class="inputbox captchacode required sicaptcha validate-sicaptcha" 
							size="12" maxlength="" />';
			$html .=  '<input type="hidden" name="captcha_code-validation" id="'.$iCaptchaId.'-validation"  />';
			$html .=  '<input type="hidden" name="captcha_code-icaptchaUseAjax" id="icaptchaUseAjax" 
							value="'.$this->params->get('captcha_systems-securImage2-ajaxcheck',0).'" />';
	
			$doc->addScriptDeclaration($javascript); //Conflicts with other extensions
			$html .= '<br />';
		
		return $html;
	}

	function displayMathGuardForm(){
		require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'mathguard'.DS.'mathGuard.class.php');
		
		$attributes		= array();
		$attributes['id']	= 'mathguard_answer'.$this->params->get('suffix');
		
		$html = '';
		$html .= '<label for="mathguard_answer'.$this->params->get('suffix').'" class="requiredField" id="label-mathguard_answer" >'
			.JText::_("ICAPTCHA_MATHGUARD_SECURITY_QUESTION").'</label>';
		$html .= '<br />';
		$html .= mathGuardImproved::returnQuestion($attributes,$this->params->get('suffix'));

		return $html;
	}
	

	function displayRecaptchaForm(){
		if($this->params->get('captcha_systems-recaptcha-PubKey')){
			$document	=& JFactory::getDocument();
			$document->addScript('https://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
			if(!defined('RECAPTCHA_API_SERVER')){
				require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'recaptchalib.php'); /**    reCAPTCHA Library   **/
			}
			
			$script		= 	"
window.addEvent('domready',function(){
	Recaptcha.create('".$this->params->get('captcha_systems-recaptcha-PubKey','')."',
		'icaptcha-recaptcha_div', 
		{	lang:'" . $this->params->get('captcha_systems-recaptcha-Lang','en') . "',
		  	 theme:'" . $this->params->get('captcha_systems-recaptcha-Theme','red') . "'
		});
});
";
			$document->addScriptDeclaration($script);
			
			/**   Generating CORE reCAPTCHA form   **/
			return '<div id="icaptcha-recaptcha_div"></div>'; 
		}else{
			return '<div id="icaptcha-recaptcha_div" ><strong>'.JText::_('PLG_SYSTEM_ICAPTCHA_RECAPTCHA_MISSING_PUBLIC_KEY').'</strong></div>';
		}
			
	}
	
	function displayVouchSafe(){
		$publickey = trim($this->params->get('captcha_systems-vouchsafe-PubKey')); // you got this from the signup page
		if($publickey){
			if(!function_exists('vouchsafe_check_answer')){
				/**    VouchSafe Library   **/
				require_once(JPATH_PLUGINS.DS.'system'.DS.'icaptcha'.DS.'captcha_systems'.DS.'vouchsafe-lib.php'); 
			}
	        return vouchsafe_get_html($publickey);
			 
		}else{
			return '<div id="icaptcha-vouchsafe" ><strong>'.JText::_('PLG_SYSTEM_ICAPTCHA_VOUCHSAFE_MISSING_PUBLIC_KEY').'</strong></div>';
		}
			
	}
	
}