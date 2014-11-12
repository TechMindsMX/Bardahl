<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

    $result=ModBuscaProductoHelper::getList();

require JModuleHelper::getLayoutPath('mod_buscaproducto', $params->get('layout', 'default'));











/*$frm = new html_form();
 
$formulario= $frm->openform("form1","post","index.php?option=com_busqueda","multipart/form-data");

$formulario .= $frm->openfieldset("<div id='cabecera'><br/>Encuentra tu producto</div>",550);

 

$formulario .= $frm->addInput("text","producto","Tipo de Producto: ")."<br>";
$formulario .= $frm->addInput("text","marca","Marca: ")."<br>";
$formulario .= $frm->addInput("text","modelo","Modelo: ")."<br>";
$formulario .= $frm->addInput("text","kilometraje","Kilometraje:")."<br>";

$formulario .= $frm->closefieldset();

 
$formulario .= $frm->addInput("submit","enviar","","Buscar");
$formulario .= $frm->addInput("reset","reset","","Reset");

$formulario .= $frm->closeform();



require JModuleHelper::getLayoutPath('mod_buscaproducto', $params->get('layout', 'default'));*/
 