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

$db			= & JFactory::getDBO();

$query = 'SELECT params ' 
			. ' FROM #__extensions '
			. ' WHERE element 	='.$db->Quote('icaptcha')
				.' AND type		='.$db->Quote('plugin')
				.' AND folder	='.$db->Quote('system')
			;
$db->setQuery($query);

$params 	= $db->loadResult();
$registry 	= new JRegistry();
$registry->loadString($params);
$params		= $registry;
		
include 'securimage.php';

$img = new securimage();


// Change some settings

$img->image_width		= (int) $params->get('captcha_systems-securImage2-width', 	150);
$img->image_height		= (int) $params->get('captcha_systems-securImage2-height', 	70);
$img->perturbation		= (float) $params->get('captcha_systems-securImage2-perturbation',	0.7); // 1.0 = high distortion, higher numbers = more distortion
if($params->get('captcha_systems-securImage2-length') == 'random'){
	$img->code_length 	= rand(3, 5);
}else{
	$img->code_length 	= $params->get('captcha_systems-securImage2-length',	4);
}
if($params->get('captcha_systems-securImage2-ttf') != '-1' ){
	if(is_readable(dirname(__FILE__).DS.$params->get('captcha_systems-securImage2-ttf',	'arial.ttf')) ){
		$img->ttf_file 			= dirname(__FILE__).DS.$params->get('captcha_systems-securImage2-ttf',	'arial.ttf');
	}
}
$img->image_bg_color	= new Securimage_Color("#".$params->get('captcha_systems-securImage2-bg_color',		'FFFFFF'));
$img->text_color		= new Securimage_Color("#".$params->get('captcha_systems-securImage2-text_color', 	'3D3D3D'));
$img->line_color		= new Securimage_Color("#".$params->get('captcha_systems-securImage2-line_color', 	'3D3D3D'));
$img->signature_color	= new Securimage_Color("#".$params->get('captcha_systems-securImage2-signature_color', 	'FFFFFF'));
$img->image_signature	= $params->get('captcha_systems-securImage2-image_signature', 	'');
$img->num_lines			= (int) $params->get('captcha_systems-securImage2-number_lines', 	8);

$img->text_angle_minimum	= $params->get('captcha_systems-securImage2-text_angle_minimum',	0);
$img->text_angle_maximum	= $params->get('captcha_systems-securImage2-text_angle_maximum',	0);
if($params->get('captcha_systems-securImage2-background') != '-1' ){
	if(is_readable(dirname(__FILE__).DS.'backgrounds'.DS.$params->get('captcha_systems-securImage2-background',	'letters-x.jpg'))){
		$img->bgimg 			= dirname(__FILE__).DS.'backgrounds'.DS.$params->get('captcha_systems-securImage2-background',	'letters-x.jpg');
	}
}

$img->show(); // alternate use:  $img->show('/path/to/background_image.jpg');

