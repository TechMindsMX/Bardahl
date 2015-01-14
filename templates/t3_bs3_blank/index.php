<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=100" >
<!--[if gte IE 9]>
	<link rel="stylesheet" type="text/css" href="templates/t3_bs3_blank/css/ie_custom.css" />
<![endif]-->
<link href="//fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Maven+Pro:400normal,500normal,700normal,900normal|Open+Sans:400normal|Oswald:400normal|Droid+Sans:400normal|Lato:400normal|Ubuntu:400normal|Pacifico:400normal|Josefin+Slab:400normal|Raleway:400normal|Roboto:400normal|PT+Sans:400normal&subset=all">
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-57971202-1', 'auto');

	ga('send', 'pageview');
</script>

<?php
/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die;

//check if t3 plugin is existed
if (!defined('T3')) {
	if (JError::$legacy) {
		JError::setErrorHandling(E_ERROR, 'die');
		JError::raiseError(500, JText::_('T3_MISSING_T3_PLUGIN'));
		exit;
	} else {
		throw new Exception(JText::_('T3_MISSING_T3_PLUGIN'), 500);
	}
}

$t3app = T3::getApp($this);

// get configured layout
$layout = $t3app->getLayout();

$t3app->loadLayout($layout);