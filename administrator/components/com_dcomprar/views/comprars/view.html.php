<?php

/**
 * @version     1.0.1
 * @package     com_donde_comprar
 * @copyright   Copyright (C) 2014. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      ismael <aguilar_2001@hotmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Donde_comprar.
 */
class DcomprarViewComprars extends JViewLegacy {

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
        $this->assignRef("data", $this->getModel()->getData());

	    $this->addToolBar();
        
        parent::display($tpl);
    }

	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_INTEGRADO_MANAGER_INTEGRADO_EDIT'));
		JToolBarHelper::cancel();

		if (JFactory::getUser()->authorise('core.admin', 'com_dcomprar'))
		{
			JToolBarHelper::preferences('com_dcomprar');
		}

	}


}


