<?php 
/**
 * Visdata detail view for Visforms
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
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_visforms&view=visdatas&fid=' . JFactory::getApplication()->input->getInt( 'fid', -1 ) . '&id=' . $this->item->id );?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <div class="form-horizontal">
	<div class="row-fluid">
			<div class="span12">
				<fieldset class="adminform">
	<legend><?php echo JText::_('COM_VISFORMS_DATA_DETAIL'); ?></legend>
    <div class="control-group">
        <div class="control-label">
            <label id="id-lbl" title="" for="id"><?php echo JText::_( 'COM_VISFORMS_ID' ); ?>:
        </div>
        <div class="controls">
            <?php echo $this->item->id; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <label id="date-lbl" title="" for="date"><?php echo JText::_( 'COM_VISFORMS_DATE' ); ?>: 
        </div>
        <div class="controls">
            <?php echo $this->item->created; ?>
        </div>
    </div>
        <!-- <ul class="adminformlist">
            <li>
                <label id="id-lbl" title="" for="id"><?php echo JText::_( 'COM_VISFORMS_ID' ); ?>: 
                </label>
                 <input disabled="disabled" class="inputbox" type="text" value="<?php echo $this->item->id; ?>" name="id" /> 
                <p class="detail"><?php echo $this->item->id; ?></p>
            </li>
             <li>
                 <label id="date-lbl" title="" for="date"><?php echo JText::_( 'COM_VISFORMS_DATE' ); ?>: 
                 </label>
                 <input disabled="disabled" class="inputbox" type="text" value="<?php echo $this->item->created; ?>" name="date" />
                 <p class="detail"><?php echo $this->item->created; ?></p>
             </li> -->
              
	<?php	$k = 0;
		$n=count( $this->fields );
		for ($i=0; $i < $n; $i++)
		{
			$rowField = $this->fields[$i];
			if (!($rowField->showFieldInDataView === false))
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
					$texte = "&nbsp;";
				}
                                if ($rowField->typefield == 'email') 
                                {
                                        $linkfield = "mailto:".$texte;
                                        $texte = "<a href=\"".$linkfield."\">".$texte."</a>";
                                }
                                else if ($rowField->typefield == 'file')
                                {
                                    //info about uploaded files are stored in a JSON Object. Earlier versions just have a string.
                                    $texte = JHTML::_('visforms.getUploadFileLink', $texte);
                                }
							
 	?>
        <div class="control-group">
        <div class="control-label">
            <label id="F<?php echo $rowField->id; ?>-lbl" title="" for="F<?php echo $rowField->id; ?>"><?php echo  $rowField->label; ?>: 
        </div>
        <div class="controls">
            <?php echo  $texte; ?>
        </div>
    </div>
        <!-- <li>
            <label id="F<?php echo $rowField->id; ?>-lbl" title="" for="F<?php echo $rowField->id; ?>"><?php echo  $rowField->label; ?>: 
            </label>             
            <input disabled="disabled" class="inputbox" type="text" value="<?php echo  $texte; ?>" name="F<?php echo $rowField->id; ?>" />
            <p class="detail"><?php echo  $texte; ?></p>
        </li> -->
            
	<?php	
			}
		}
 	?>
        <div class="control-group">
        <div class="control-label">
            <label id="ip-lbl" title="" for="ip"><?php echo JText::_( 'COM_VISFORMS_IP' ); ?>:
        </div>
        <div class="controls">
            <?php echo $this->item->ipaddress; ?>
        </div>
    </div>
        <!-- <li><label id="ip-lbl" title="" for="ip"><?php echo JText::_( 'COM_VISFORMS_IP' ); ?>: 
            </label>
            <input disabled="disabled" class="inputbox" type="text" value="<?php echo $this->item->ipaddress; ?>" name="ip" />
            <p class="detail"><?php echo $this->item->ipaddress; ?></p>
	</li>
        </ul>
        </fieldset> -->
        </div>
    </div>
    </div>


<input type="hidden" name="task" value="" />


</form>
    </div>
    </div>
<?php JHTML::_('visforms.creditsBackend'); ?>
