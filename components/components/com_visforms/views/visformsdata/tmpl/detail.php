<?php 
/**
 * Visformsdata detail view for Visforms
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

//no direct access
defined('_JEXEC') or die('Restricted access'); 

    $document = JFactory::getDocument();
    $document->addCustomTag('<link type="text/css" href="' . JURI::root(true) . '/media/com_visforms/css/visforms.css" rel="stylesheet">');
?>
<div class="visforms-form<?php echo $this->menu_params->get( 'pageclass_sfx' ); ?>">
<?php if ($this->menu_params->get('show_page_heading') == 1) { 
		if (!$this->menu_params->get('page_heading') == "") { ?>
			<h1><?php echo $this->menu_params->get('page_heading'); ?></h1>
	<?php }
	else { if (isset($this->form->fronttitle) == false || strcmp ($this->form->fronttitle, "") == 0)
		{
			echo '<h1>' . $this->form->title . '</h1>';
			} else {
			echo '<h1>' . $this->form->fronttitle . '</h1>'; 
		}
	}
}
	
	$linkback = "index.php?option=com_visforms&view=visformsdata&layout=data&Itemid=". $this->itemid ."&id=". $this->id;	
?>


<a href="<?php echo JRoute::_($linkback); ?>">
<?php echo JText::_( 'COM_VISFORMS_BACK_TO_LIST' ); ?></a>
 
<table class="visdatatable">
         
<?php	$k = 0;
	$n=count( $this->fields );
	for ($i=0; $i < $n; $i++)
	{
		$rowField = $this->fields[$i];

		if (isset($rowField->frontdisplay) && ($rowField->frontdisplay == 1 || $rowField->frontdisplay == 3))
		{			
			$prop="F".$rowField->id;
			if (isset($this->item->$prop) == false)
			{
				$prop=$rowField->name;
			}
			if (isset($this->item->$prop))
			{
				$texte = $this->item->$prop;
			} else {
				$texte = "";
			}
?>
    
<tr>
	<td class="visfrontlabel">
    
        <?php echo  $rowField->label; ?> :
    
    </td>
    <td>
<?php             
			if ($rowField->typefield == 'email') {
				$texte = '<a href="mailto:'.$texte.'">'.$texte.'</a>';
			}
			
			if ($rowField->typefield == 'file' && isset($texte))
			{
                            //info about uploaded files are stored in a JSON Object. Earlier versions just have a string.
                            if (isset($rowField->showlink) && $rowField->showlink == true)
                            {
                                //show the link
                                $texte = JHTML::_('visforms.getUploadFileLink', $texte);
                            }
                            else
                            {
                                //show the filename
                                $texte = JHTML::_('visforms.getUploadFileName', $texte);
                                $texte = basename($texte);
                            }
			}
		echo  $texte; 
                if ($rowField->typefield == 'file' && isset($row->$prop))
			{
                             //info about uploaded files are stored in a JSON Object. Earlier versions just have a string.
                            if (isset($rowField->showlink) && $rowField->showlink == true)
                            {
                                $texte = JHTML::_('visforms.getUploadFileLink', $texte);
                            }
                            else
                            {
                                $texte = JHTML::_('visforms.getUploadFileName', $texte);
                            }
			}
 ?>
    </td>
</tr>
            
<?php	
			}
		}

		if ($this->form->displayip == '1') 
    	{
?>
        <tr>
            <td class="visfrontlabel">
                <label for="title">
                   <?php echo JText::_( 'COM_VISFORMS_IP_ADDRESS' ); ?>
                </label>
            </td>
            <td>
                <?php echo $this->item->ipaddress; ?>
            </td>
        </tr>
<?php } ?>
        
</table>        


<?php if ($this->form->poweredby == '1') { ?>
	<div id="vispoweredby"><a href="http://vi-solutions.de" target="_blank"><?php echo JText::_( 'COM_VISFORMS_POWERED_BY' ); ?></a></div>
<?php } ?>
</div>