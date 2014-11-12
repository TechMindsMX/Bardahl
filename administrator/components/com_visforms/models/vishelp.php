<?php
/**
 * vishelp model for Visforms
 *
 * @author       Aicha Vack
 * @see           visforms is extended and rivised adaptation of ckforms from cookex (http://www.cookex.eu) for Joomla 2.5
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport( 'joomla.application.component.modellist' );

/**
 * Vishelp model class for Visforms
 *
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 *
 * @since        Joomla 1.6 
 */
class VisformsModelVishelp extends JModelList
{
  public function __construct($config = array())
	{	
		parent::__construct($config);
	}
}