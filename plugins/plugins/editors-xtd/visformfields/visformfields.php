<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.article
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Editor Article buton
 *
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.article
 * @since       1.5
 */
class PlgButtonVisformfields extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return array A four element array of (article_id, article_title, category_id, object)
	 */
	public function onDisplay($name)
	{
        $app = JFactory::getApplication();
        $o = $app->input->get('option');
        $v = $app->input->get('view');
        if ($o == 'com_visforms' && $v == 'visform')
        {
            $id = $app->input->getCmd('id', 0);
            /*
             * Javascript to insert the link
             * View element calls jSelectArticle when an article is clicked
             * jSelectArticle creates the link tag, sends it to the editor,
             * and closes the select frame.
             */
            $js = "
            function jSelectVisformfield(field)
            {
                var tag = '[' + field.toUpperCase() + ']';
                jInsertEditorText(tag, '" . $name . "');
                SqueezeBox.close();
            }";

            $doc = JFactory::getDocument();
            $doc->addScriptDeclaration($js);

            JHtml::_('behavior.modal');

            /*
             * Use the built-in element view to select the article.
             * Currently uses blank class.
             */
            $link = 'index.php?option=com_visforms&amp;view=visfields&amp;fid=' . $id . '&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

            $button = new JObject;
            $button->modal = true;
            $button->class = 'btn';
            $button->link = $link;
            $button->text = JText::_('PLG_VISFORMFIELDS_BUTTON_VISFORMFIELDS');
            $button->name = 'file-add';
            $button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

            return $button;
        }
    }
}
