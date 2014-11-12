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

jimport('joomla.plugin.plugin');
jimport('joomla.session.session');

class plgContentContactform extends JPlugin {

    function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onContentPrepare($context, &$row, &$params, $page = 0) {
        if (is_object($row)) {
            return $this->_replaceTags($row->text, $params);
        }
        return $this->_replaceTags($row, $params);
    }

    protected function _replaceTags(&$text, &$params) {
        /*
         * Check for presence of {contactform=off} which is explicits disables this
         * bot for the item.
         */
        if (JString::strpos($text, '{contactform=off}') !== false) {
            $text = JString::str_ireplace('{contactform=off}', '', $text);
            return true;
        }

        // Simple performance check to determine whether bot should process further.
        if (JString::strpos($text, '{contactform ') === false) {
            return true;
        }

        JHtml::_('behavior.keepalive');

        /*
         * Search for contactform tags and replace them with contact froms
         * NOTE: Tags inserted inside <textarea> tags are not processed
         */
        $prepattern = '/(<textarea(?:(?!<\/textarea>).)*<\/textarea>)/is';
        $parts = preg_split($prepattern, $text, null, PREG_SPLIT_DELIM_CAPTURE);

        $pattern = '/{contactform\s([^\}]*)(?|(?:\/\})|(?:\}((?:(?!\{\/contactform\}).)*)\{\/contactform\}))/i';
        for ($i = 0; $i < count($parts); $i += 2) {
            while (preg_match($pattern, $parts[$i], $regs, PREG_OFFSET_CAPTURE)) {
                $sparamstmp = trim($regs[1][0]);
                $paramstmp = JUtility::parseAttributes($sparamstmp);

                // Merge default params with tag params
                $formparams = new JObject($this->params->toArray());
                $formparams->setProperties($paramstmp);

                $mailto = $formparams->get('mailto');
                if (!$mailto || !strlen($mailto)) {
                    $formparams->set('message', JText::_('PLG_CONTENT_CONTACTFORM_MAILTO_MISSING'));
                    $formparams->set('label', 'WARNING: contactform tag must have a mailto attribute');
                } else if (isset($regs[2][0])) {
                    $formparams->set('message', $regs[2][0]);
                }

                $mediabox_width = $formparams->get('mediabox_width', 0);
                if ($mediabox_width && (substr($mediabox_width, -2) == 'px')) {
                    $mediabox_width = substr($mediabox_width, 0, strlen($mediabox_width) - 2);
                    $formparams->set('mediabox_width', $mediabox_width);
                }

                if ($formparams->get("captcha", 1)) {
                    $extraFields = JFactory::getApplication()->triggerEvent('onAfterDisplayForm');

                    $formparams->set('captcha', implode('<br />\n', $extraFields));
                } else {
                    $formparams->set("captcha", '');
                }

                $formparams->set('label', html_entity_decode($formparams->get('label', JText::_('PLG_CONTENT_CONTACTFORM_TEXT_FOR_MEDIABOX'))));

                $display = $formparams->get('display', 0);
                if ($display && file_exists(dirname(__FILE__) . '/displays/' . $display . '.php')) {
                    require_once dirname(__FILE__) . '/displays/' . $display . '.php';
                    $display = 'CFP_' . ucfirst($display);

                    $display = new $display();
                } else {
                    require_once dirname(__FILE__) . '/displays/div.php';
                    $display = new CFP_Div();
                }
                // Check to see if mail text is different from mail addy
                $replacement = $display->showContactForm($formparams);

                // Replace the found address with the contact form
                $parts[$i] = substr_replace($parts[$i], $replacement, $regs[0][1], strlen($regs[0][0]));
            }
        }

        $text = implode('', $parts);

        return true;
    }

}