<?php
/**
* @version		$Id: plgCkformsSpamCheck.php
* @package		visforms SpamCheck - check for possible spambots during register and login
* @author		vi-solutions, Aicha Vack the plugin is build on user-plugin spambotchek, originally written by Robert Kuster
* @copyright	Copyright (C) 2013 vi-solutions. All rights reserved.
* @license		GNU/GPL, see LICENSE.txt
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.plugin.plugin');
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'visforms'.DIRECTORY_SEPARATOR.'spambotcheck'.DIRECTORY_SEPARATOR.'spambotcheck'.DIRECTORY_SEPARATOR.'spambotcheckimpl.php');


class plgVisformsSpambotCheck extends JPlugin
{
	function plgVisformsSpambotCheck(& $subject, $config) {
		parent::__construct($subject, $config);
		
		//load the translation
		$this->loadLanguage();
	}
	
	
	/**
	 * Example store user method
	 *
	 * Method is called before user data is stored in the database
	 *
	 * @param 	array		holds the old user data (without new changes applied)
	 * @param 	boolean		true if a new user is stored
	 * 
	 * RKFIX - Check if this is a known spammer. If so:
	 * 			> prevent user registration
	 *			> notify the admins about the registration attempt via email
	 *			> show the the normal login notification to the user 
	 */
	public function onBeforeFormSubmit() 
    {
        jimport('joomla.application.component.model');
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_visforms/models', 'VisformsModel');
        $input=JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);
        $model = JModelLegacy::getInstance('Visforms', 'VisformsModel', array('ignore_request' => true, 'id' => $id));
        $fields = $model->getFields();
        $user = Array ();
        $user['email'] = "";
        //find the first e-mail field in form and consider it the recipient mail
        foreach($fields as $value)
        {
            if(isset($value->typefield) && $value->typefield == "email")
            {
                $emailField = $value->name;
                $user['email'] = $input->getString($emailField, '');
                break;
            }
        }
		
		//$this->params->set('current_action', 'Register');
		$spamString = "";
		if(plgVisformsSpambotCheck::_isSpammer($user, $spamString))
		{
			
			// Throw a message for the spammer and do nothing else. 
			
			$app = JFactory::getApplication();
			$app->enqueueMessage(sprintf (JText::_('PLG_VISFORMS_SPAMBOTCHECK_USER_LOGIN_SPAM_TXT')), "error");
			return 'spambot true';			
		}
		return 'spambot false';
	}
	
	/**
	 * Method check if the user specified is a spammer.
	 *
	 * @param 	array		holds the user data
	 * @param 	string		$spamstring hold the raw spam string 
	 * 
	 * @return boolean True if user is a spammer and False if he isn't. 
	 */
	function _isSpammer($user, &$spamString)
	{
        $fspamcheck = plgVisformsSpambotCheckImpl::checkSpambots($this->params, $user['email'], $_SERVER['REMOTE_ADDR']);
		if ($fspamcheck == false || strlen($fspamcheck) == 0 || strpos($fspamcheck, "SPAMBOT_TRUE") === false)	
		{
			// not a spammer
			$spamString = "";
			return false;
		}
		
		// if we get here we have to deal with a spammer		
		$spamString = $fspamcheck;
		return true;
	}
}