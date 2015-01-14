<?php
/**
 * Vistools view for Visforms
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

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * ckfield View
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class VisformsViewVistools extends JViewLegacy
{
	protected $form;
	protected $state;
    protected $id;
	
	
	/**
	 * display method of ckfield view
	 * @return void
	 **/
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
        $css = '.icon-visform {background:url(../administrator/components/com_visforms/images/visforms_logo_32.png) no-repeat;}'.
            ' [class^="icon-visform"] {display: block; float: left; height: 32px; line-height: 32px; width: 32px;}'.
           '  .visformbottom {	text-align: center;	padding-top: 15px;	color: #999;}';
   		$doc->addStyleDeclaration($css);
        $doc->addStyleSheet(JURI::root(true).'/administrator/components/com_visforms/css/visforms_min.css');
        JToolbarHelper::title(JText::_('COM_VISFORMS_EDIT_CSS_BUTTON_TEXT'), 'visform');
        
        
		JToolbarHelper::apply('vistools.apply');
		JToolbarHelper::save('vistools.save');
        
        $app            = JFactory::getApplication();
		$this->file     = $app->input->get('file');
        $this->state = $this->get('State');
        $this->fileName = $this->state->get('filename');
		$explodeArray   = explode('.', $this->fileName);
		$ext            = end($explodeArray);
        
        $this->form   = $this->get('Form');
        $this->form->setFieldAttribute('source', 'syntax', $ext);
        $this->css = $this->get('Css');
	
        $this->id = $this->state->get('extension.id');
			
		
					
		JToolbarHelper::cancel('vistools.cancel', 'Close');

		$app->input->set('hidemainmenu', 1);
		
		parent::display($tpl);
	}
}
