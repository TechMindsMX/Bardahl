<?php
/**
 * Vistools editcss view for Visforms
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

//no direct access
 defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_('index.php?option=com_visforms&view=vistools&layout=default&id='. $this->id . '&file=' . $this->file);?>" method="post" name="adminForm" id="adminForm">
<div class="clr"></div>
						<div class="editor-border">
							<?php echo $this->form->getInput('source'); ?>
						</div>
						<input type="hidden" name="task" value="" />
						<?php echo JHtml::_('form.token'); ?>
						<?php echo $this->form->getInput('extension_id'); ?>
						<?php echo $this->form->getInput('filename'); ?>
</form>


<?php JHTML::_('visforms.creditsBackend'); ?>