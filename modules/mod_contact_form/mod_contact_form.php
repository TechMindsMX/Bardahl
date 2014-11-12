<?php
/**
 * Hola mundo!
 * @license GNU/GPL, see LICENSE.php
 */

 defined( '_JEXEC' ) or die( 'Restricted access' );

 require_once( dirname(__FILE__).'/helper.php' );

$res= modContacFormHelper::getData();

 require( JModuleHelper::getLayoutPath( 'mod_contact_form' ) );
