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

/**
 * Helper class for ContactForm
 *
 * @author Sebastien Chartier
 */
class CFPHelper {

    /**
     * Sends email for ContactForm.
     *
     * Message information is extract from Post vars.
     *
     * @return object
     */
    function sendmail() {
        jimport('joomla.mail.helper');

        $response->status = 1001;
        $response->message = "";

        if (JRequest::getString("error_message"))
            $response->message .= html_entity_decode (urldecode (JRequest::getString("error_message")));
        else
            $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_GENERIC_ERROR') . '</p>';

        if (!JRequest::checkToken()) {
            $respons->status = 9999;
            $response->message .= '<p>' . JText::_('JINVALID_TOKEN') . '</p>';
        }

        $debug = JRequest::getVar('debug');

        $mailto = "aguilar_2001@hotmail.com";//JRequest::getVar('mailto');
        $mailto = str_replace("#", "@", $mailto);

        if (!$mailto || !JMailHelper::isEmailAddress($mailto)) {
            $response->status = 1101;
            $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_MAILTO_MISSING') . '</p>';
        }

        $sender_email = JRequest::getVar('sender_email');
        if (!$sender_email || !JMailHelper::isEmailAddress($sender_email)) {
            $response->status = 1201;
            $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_SENDER_EMAIL_MISSING') . '</p>';
        }

        $message = stripslashes(JRequest::getVar('message'));
        if (!$message || $message == '') {
            $response->status = 1301;
            $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_MESSAGE_MISSING') . '</p>';
        }

        $sender_name = stripslashes(JRequest::getVar('sender_name'));
        if (!$sender_name || $sender_name == '') {
            $response->status = 1401;
            $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_SENDER_NAME_MISSING') . '</p>';
        }

        $subject = stripslashes(JRequest::getVar('subject'));
        if (!$subject || $subject == '') {
            $response->status = 1501;
            $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_SUBJECT_MISSING') . '</p>';
        }

        if (isset($captcha) && $captcha == true) {
            $ccheck = $mainframe->triggerEvent('onValidateForm');

            if (!$ccheck || (isset($ccheck) && !$ccheck[0])) {
                $response->message .= '<p>' . JText::_('PLG_CONTENT_CONTACTFORM_CAPTCHA_REQUIRE') . '</p>';
            }
        }

        if($response->status > 1001)
            return $response;

        $encoding = JRequest::getVar('encoding');
        $encoding || ($encoding = "UTF-8");

        // header injection test
        // An array of e-mail headers we do not want to allow as input

        $headers = array('Content-Type:',
            'MIME-Version:',
            'Content-Transfer-Encoding:',
            'bcc:',
            'cc:');

        // An array of the input fields to scan for injected headers

        $fields = array('mailto',
            'sender_name',
            'sender_email',
            'subject',
        );

        // iterate over variables and search for headers

        foreach ($fields as $field) {

            foreach ($headers as $header) {

                if (strpos(JRequest::getVar($field), $header) !== false) {

                    JError::raiseError(403, '');
                }
            }
        }

        unset($headers, $fields);

        $emailSubject = sprintf(JText::_('PLG_CONTENT_CONTACTFORM_EMAIL_SUBJECT'), $sender_name);

        // add header
        $emailBody = '
            <p><b>' . JText::_('PLG_CONTENT_CONTACTFORM_SUBJECT_LABEL') . '</b>: ' . JMailHelper::cleanBody($subject) . '</p>
            <p></p>
            <p><b>' . JText::_('PLG_CONTENT_CONTACTFORM_MESSAGE_LABEL') . ' : </b></p>
            <p>' . JMailHelper::cleanBody($message) . '</p>
            <p></p>
            <p>' . $sender_name . '
                <br />' . $sender_email . '</p>
            <p></p>
            <p></p>
            <p><small>This email was generated from a contact form at this URL: ' .
                $_SERVER['HTTP_REFERER'] . '</small></p>';

        $emailBody = mb_convert_encoding($emailBody, 'HTML-ENTITIES', $encoding);

        // send email
        $error_info = $this->_send_email($sender_name, $sender_email, $mailto, $emailSubject, $emailBody, true);

        if ($error_info == '') {
            $response->status = 1;
            if (JRequest::getString("success_message"))
                $response->message = html_entity_decode (urldecode(JRequest::getString("success_message")));
            else
                $response->message = JText::_('PLG_CONTENT_CONTACTFORM_SUCCESS');
        } else {
            $response->status = 1501;

            if($debug || JDEBUG)
                $response->message = $error_info;
        }

        return $response;
    }

    /**
     * Internal function to send email
     *
     * @param String $sender_name
     * @param String $sender_email
     * @param String $recipient
     * @param String $subject
     * @param String $body
     * @param bool $isHTML
     *
     * @return string   Returns the error message, if any!
     */
    function _send_email($sender_name, $sender_email, $recipient, $subject, $body, $isHTML=false) {

        // set sender
        $sender = array(
            $sender_email,
            $sender_name
        );

        // set mail data
        $mailer = & JFactory::getMailer();

        $mailer->setSender($sender);

        $mailer->addRecipient($recipient);

        $mailer->isHTML($isHTML);

        $mailer->setSubject($subject);

        $mailer->setBody($body);

        // send email
        ob_start();
        $send = & $mailer->Send();
        $error = ob_get_clean();

        if(ob_get_length()>0)
            ob_end_clean();

        return $error;
    }

}
