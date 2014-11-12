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

class ContactFormSendJson {

    public function ContactFormSendJson($params){}

    function view() {
        if(JRequest::getString('task') == 'send')
            $this->send();
    }

    function send() {
        include dirname(__FILE__) . '/../../../helper.php';
        $helper = new CFPHelper();
        $response = $helper->sendmail();
        echo json_encode($response);
        exit;
    }

}
