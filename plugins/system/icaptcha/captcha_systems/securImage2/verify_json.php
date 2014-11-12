<?php
error_reporting(0);

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', '../../../../../' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );


$app	= &JFactory::getApplication('site');
// Instantiate the application.
$app = JFactory::getApplication('site');

$config		=& JFactory::getConfig();
/**
 * @var int	Will set error report to maximum if global settings is set to maximum, otherwise set error reporting to none this will avoid problems with Joom!Fish and some SEF extensions
 */
$error_reporting_level	= $config->getValue('config.error_reporting');
if($error_reporting_level != 6143){
	$error_reporting_level = 0;
}
error_reporting($error_reporting_level);

// Initialise the application.
//$app->initialise();

$session   = &JFactory::getSession();
include 'securimage.php';
$captcha = new securimage();


$code	= trim(strtolower($captcha->getCode()));
if (JRequest::getVar('captcha_code') != '' AND $code != '' AND strtolower(JRequest::getVar('captcha_code')) == $code) {
	$return	= 'success';
}else{
	$return = 'failed';
}
header('Content-type: application/json'); 
echo json_encode( array('action'=>$return)); exit();