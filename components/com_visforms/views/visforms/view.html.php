<?php
/**
 * Visforms view for Visforms
 *
 * @author       Aicha Vack
 * @see           visforms is extended and rivised adaptation of ckforms from cookex (http://www.cookex.eu) for Joomla 2.5
 * @package      Joomla.Site
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */
 
// no direct access
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.html.parameter');
//JHTML::_('behavior.tooltip');

/**
 * Visforms View class
 *
 * @package		Joomla.Site
 * @subpackage	com_visforms
 * @since		1.6
 */
class VisformsViewVisforms extends JViewLegacy
{
	function display($tpl = null)
	{	
		/* get params from menu */
		$menu_params = $this->get('menuparams');
		
		/* get data from database */
		$visforms = $this->get('Form');
                $fields = $this->get('Fields');
                if (isset($_POST)) 
                {
                    $this->getModel()->getDefaultsFromPost($fields);
                    $this->getModel()->setDefaultsFromPost($fields);
                }
                $visforms->fields = $fields;
	
		$this->assignRef( 'visforms',$visforms );
		$this->assignRef('menu_params', $menu_params);
		
		$formLink = "index.php?option=com_visforms&view=visforms&task=send&id=".$visforms->id;				
		$this->assignRef( 'formLink',$formLink );
	

		$document = JFactory::getDocument();
		
		// Set metadata Description and Keywords - we could use $this->document instead of $document
		
		if ($this->menu_params->get('menu-meta_description'))
		{
			$document->setDescription($this->menu_params->get('menu-meta_description'));
		}

		if ($this->menu_params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $this->menu_params->get('menu-meta_keywords'));
		}
		
				
		parent::display($tpl);
		
	}

}
?>
