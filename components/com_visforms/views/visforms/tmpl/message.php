<?php 
/**
 * Visforms message view for Visforms
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
defined('_JEXEC') or die('Restricted access'); ?>

<div class="visforms-form<?php echo $this->menu_params->get( 'pageclass_sfx' ); ?>">
<?php if ($this->menu_params->get('show_page_heading') == 1) { 
		if (!$this->menu_params->get('page_heading') == "") { ?>
			<h1><?php echo $this->menu_params->get('page_heading'); ?></h1>
	<?php }
	else { ?>
		<h1><?php echo $this->visforms->title; ?></h1>
	<?php
	}
}?>

<?php if (strcmp ( $this->visforms->textresult , "" ) != 0) { ?>
<div class="item-page">

	<?php echo JHTMLVisforms::replacePlaceholder($this->visforms, $this->visforms->textresult); ?>
</div>
<?php } ?>
</div>
