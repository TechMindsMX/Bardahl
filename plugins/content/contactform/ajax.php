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

if (!defined('_JEXEC')) {
    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);
    define('JPATH_BASE', dirname(dirname(dirname(dirname(__FILE__)))));

    require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
    require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
    JDEBUG ? $_PROFILER->mark('afterLoad') : null;

    $mainframe = & JFactory::getApplication('site');

    $mainframe->initialise();

    JPluginHelper::importPlugin('system');
    JDEBUG ? $_PROFILER->mark('afterInitialise') : null;

    $mainframe->triggerEvent('onAfterInitialise');
}

$lang = JFactory::getLanguage();
$lang->load('plg_content_contactform', JPATH_BASE.DS.'administrator');

$sview = JRequest::getString('contactform');
$slayout = JRequest::getString('layout', 'view');
$sformat = JRequest::getString('format', 'html');

$fview = dirname(__FILE__) . '/adm/views/' . $sview . '/view.' . $sformat . '.php';
if ($sview && file_exists($fview)) {
    include_once $fview;
    $cname = 'ContactForm' . ucfirst($sview) . ucfirst($sformat);

    $plugin = JPluginHelper::getPlugin('content', 'contactform');
    $params = new JRegistry($plugin->params);
    $view = new $cname($params);

    $view->$slayout();
} else if ($sformat == 'json') {
    $response->status = 0;
    $response->message = JText::_('PLG_CONTENT_CONTACTFORM_GENERIC_ERROR');
    echo json_encode($response);
    exit;
} else {
    $mainframe->redirect(JURI::base(), JText::_('PLG_CONTENT_CONTACTFORM_GENERIC_ERROR'), 'error');
}
