<?php
/**
 * ------------------------------------------------------------------------
 * Plugin ContactForm for Joomla! 1.7 - 2.5
 * ------------------------------------------------------------------------
 * @copyright   Copyright (C) 2011-2012 Chartiermedia.com - All Rights Reserved.
 * @license     GNU/GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @author:     Sebastien Chartier
 * @link:       http://www.chartiermedia.com
 * ------------------------------------------------------------------------
 *
 * @package	Joomla.Plugin
 * @subpackage  ContactForm
 * @version     1.12 (February 20, 2012)
 * @since	1.7
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the ContactFormForm
 * Used by the ContactForm Editor Button
 *
 * @package	Joomla.Plugin
 * @subpackage	plg_contactform
 * @since 1.0
 */
class ContactFormFormHtml
{
    protected $params;

    public function ContactFormFormHtml($params){
        $this->params = $params;
    }

	function view()
	{
		//JHTMLBehavior::formvalidation();
                $form =& JForm::getInstance('plg_contactform.form', dirname(__FILE__).'/../../forms/form.xml');

                if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

                $dispatcher = JDispatcher::getInstance();

                // CHANGE FOR A COPY OF POST + CUSTOM FIELDS
                // Trigger the form preparation event.
                $dispatcher->trigger('onContentPrepareForm', array($form, $this->params));
                // Trigger the data preparation event.
                $dispatcher->trigger('onContentPrepareData', array('plg_contactform.form', $this->params));

                // Load the data into the form after the plugins have operated.
                $form->bind($this->params);
                $config = JFactory::getConfig();

                $site_url = $config->get('site_url');
                if(trim($site_url) == '')
                {
                    $uri = JURI::getInstance();
                    $site_url = $uri->toString(array('scheme', 'host', 'port'));
                }

		include 'tmpl' . DS . 'default.php';
        }
}
