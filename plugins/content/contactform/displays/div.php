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

// No direct access.
defined('_JEXEC') or die;

class CFP_Div
{
    private $_inited = false;
    private $_params = NULL;

    protected function _init()
    {
        if($this->_inited) return true;
        $path = JURI::base().'plugins/content/contactform/';

        JHTML::stylesheet($path . 'displays/div/div.css');

        JHTML::_('behavior.formvalidation');

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration(
                'function cfp_send(f, disable_send) {
                   var form = $(f);
                   var debug = form.getElementById(\'debug\');

                   form.getElement(\'input[type=submit]\').disabled=true;

                   if (document.formvalidator.isValid(f)) {
                        // this code will send a data object via a GET request and alert the retrieved data.
                        new Request.JSON({
                            url: \''.JURI::base().'plugins/content/contactform/ajax.php?contactform=send&format=json\',
                            data: form,
                            onSuccess: function(response){
                                form.getElements(\'.cfp_desc\').each(function(el){
                                    el.setStyle(\'display\', \'none\');
                                });

                                form.getElement(\'input[type=submit]\').set(\'value\', \''.JText::_('PLG_CONTENT_CONTACTFORM_SENT').'\');
                                alert(response.message);
                            },
                            onError: function(text, error){
                                alert(text);
                            }
                        }).send();
                   }
                   else {
                      var msg = \'All fields are required.\';

                      if(form.getElementById(\'sender_name\').hasClass(\'invalid\')){
                        form.getElement(\'.cfp_sender_name_desc\').setStyle(\'display\', \'inline\');
                      }
                      if(form.getElementById(\'sender_email\').hasClass(\'invalid\')){
                        form.getElement(\'.cfp_sender_email_desc\').setStyle(\'display\', \'inline\');
                      }
                      if(form.getElementById(\'subject\').hasClass(\'invalid\')){
                        form.getElement(\'.cfp_subject_desc\').setStyle(\'display\', \'inline\');
                      }
                      if(form.getElementById(\'message\').hasClass(\'invalid\')){
                        form.getElement(\'.cfp_message_desc\').setStyle(\'display\', \'inline\');
                      }

                      alert(msg);
                   }
                   if(disable_send != 1)
                       form.getElement(\'input[type=submit]\').disabled = false;

                   return false;
                }'
                );

        $this->_inited = true;
    }

    /**
     *
     * @param JObject $params
     *    Required Parameters:
     *      - mailto
     *
     *    Optional Parameters:
     *      - sender_name
     *      - sender_email
     *      - subject
     *      - message
     *      - sender_name_size
     *      - sender_email_size
     *      - subject_size
     *      - message_cols
     *      - message_rows
     *      - encoding
     *      - debug
     *
     * @return string
     */
    public function showContactForm($params)
    {
        $this->_params = $params;

        $rand = rand(1, 100000);
        $formid = 'cfp_contact_form_'.$rand;
        $statusdiv = 'cfp_status_'.$rand;

        $this->_inited || $this->_init();

        $out = '
                <div class="cfp_contact_form">';

        if($params->get('debug', 0))
        {
            $out .= '<p class="debug_warning" style="color:#f00;">'. JText::_('PLG_CONTENT_CONTACTFORM_DEBUG_WARING') . '</p>';
        }

        $out .= '
                    <form id="'.$formid.'" method="post" action="#" onSubmit="cfp_send(this, '.$params->get('disable_send', '0').'); return false;">';
        if($params->get('debug', 0))
        {
            $out .= '
                        <div class="cfp_field">
                            <div class="cfp_label">
                                <label for="mailto">'. JText::_('PLG_CONTENT_CONTACTFORM_MAILTO_LABEL') .'</label>
                            </div>
                            <div class="cfp_input">
                                <input class="inputbox required validate-email" type="text" id="mailto" name="mailto" value="" size="'.$params->get('mailto_size', 40).'" maxlength="128" />
                            </div>
                        </div>';
        }
            $out .= '
                        <div class="cfp_field">
                            <div class="cfp_label">
                                <label for="sender_name">'. JText::_('PLG_CONTENT_CONTACTFORM_YOURNAME_LABEL') .'</label> <span class="cfp_desc cfp_sender_name_desc"> *** '. JText::_('PLG_CONTENT_CONTACTFORM_YOURNAME_DESC') .'</span>
                            </div>
                            <div class="cfp_input">
                                <input class="inputbox required" type="text" id="sender_name" name="sender_name" value="'.htmlspecialchars($params->get('sender_name', '')).'" size="'.$params->get('sender_name_size', 40).'" maxlength="35" />
                            </div>
                        </div>
                        <div class="cfp_field">
                            <div class="cfp_label">
                                <label for="sender_email">'. JText::_('PLG_CONTENT_CONTACTFORM_YOUREMAIL_LABEL') .'</label> <span class="cfp_desc cfp_sender_email_desc"> *** '. JText::_('PLG_CONTENT_CONTACTFORM_YOUREMAIL_DESC') .'</span>
                            </div>
                            <div class="cfp_input">
                                <input class="inputbox required validate-email" type="text" id="sender_email" name="sender_email" size="'.$params->get('sender_email_size', 40).'" maxlength="128" value="'.htmlspecialchars($params->get('sender_email','')).'" />
                            </div>
                        </div>
                        <div class="cfp_field">
                            <div class="cfp_label">
                                <label for="subject">'. JText::_('PLG_CONTENT_CONTACTFORM_SUBJECT_LABEL') .'</label> <span class="cfp_desc cfp_subject_desc"> *** '. JText::_('PLG_CONTENT_CONTACTFORM_SUBJECT_DESC') .'</span>
                            </div>
                            <div class="cfp_input">
                                <input class="inputbox required" data-validators="required" type="text" id="subject" name="subject" size="'.$params->get('subject_size', 40).'" value="'. htmlspecialchars($params->get('subject', '')).'" />
                            </div>
                        </div>
                        <div class="cfp_field">
                            <div class="cfp_label">
                                <label for="message">'. JText::_('PLG_CONTENT_CONTACTFORM_MESSAGE_LABEL') .'</label> <span class="cfp_desc cfp_message_desc"> *** '. JText::_('PLG_CONTENT_CONTACTFORM_MESSAGE_DESC') .'</span>
                            </div>
                            <div class="cfp_input">
                                <textarea id="message" class="inputbox required" name="message" rows="'.$params->get('message_rows', 14).'" cols="'.$params->get('message_cols', 55).'">'. htmlspecialchars($params->get('message', '')) .'</textarea>
                            </div>
                        </div>
                        <input type="hidden" id="encoding" name="encoding" value="'.$params->get('encoding', 'UTF-8').'" />

                        <input type="hidden" id="success_message" name="success_message" value="'.urlencode(html_entity_decode($params->get('success_message'))).'" />

                        <input type="hidden" id="error_message" name="error_message" value="'.urlencode(html_entity_decode($params->get('error_message'))).'" />

                        <input type="hidden" id="task" name="task" value="send" />'.

                        $params->get('captcha', '') .

                        JHtml::_('form.token');

                        if($params->get('debug', 0))
                        {
                            $out .= '
                                <input type="hidden" name="debug" id="debug" value="1" />';
                        }
                        else
                        {
                            $out .= '
                                <input type="hidden" id="mailto" name="mailto" value="'.$params->get('mailto', '').'" class="required" />';
                        }

                        $out .= '
                        <div class="cfp_field">
                            <div class="cfp_submit">
                                <input type="submit" id="submit" name="submit" class="button" value="'. JText::_('PLG_CONTENT_CONTACTFORM_SEND') .'"/>
                            </div>
                        </div>
                    </form>
                </div>
                <script type="text/javascript">
                <!--
                // <![CDATA[
                    $(\''.$formid.'\').getElement(\'input[type=submit]\').disabled = false;
                // ]]>
                -->
                </script>
                ';

        return $out;
    }
}
