<?php 
/**
 * Visdatas default view for Visforms
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
 
 JHtml::_('bootstrap.tooltip');
 JHtml::_('behavior.multiselect');
 JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canEditState = $this->canDo->get('core.edit.state');
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
		if (pressbutton == 'visdatas.export') {
            //if datasets are check we submit id's of check datasets as array cid[]
            var form = document.getElementById('adminForm');
            stub = 'cb';
            var cid  = '';
	
            if (form) {
                var j = 0;
                for (var i = 0, n = form.elements.length; i < n; i++) {
                    var e = form.elements[i];
                    if (e.type == 'checkbox') {
                        if (e.id.indexOf(stub) == 0) {
                            if (e.checked == true)
                            {
                                cid += '&cid[' + j + ']=' + e.value;
                                j++;
                                e.checked = false;
                            }
                        }
                    }
                }
            }

 
            window.location = 'index.php?option=com_visforms&view=visdatas&fid=<?php echo JFactory::getApplication()->input->getInt('fid', -1);?>&task=visdatas.export' + cid; 
		}  else { 
			submitform( pressbutton );
		}
	}
</script>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
 
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<style type="text/css">
<!--
#element-box  {
	overflow:auto !important;
}
-->
</style>

<form action="<?php echo JRoute::_('index.php?option=com_visforms&view=visdatas&fid=' . JFactory::getApplication()->input->getInt( 'fid', -1 ) );?>" method="post" name="adminForm" id="adminForm" >

    
<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
	<div class="clr"> </div>

<table class="table table-striped" id="articleList">
<thead>
    <tr>
        <th width="3%" class="nowrap center">
                <?php echo JHtml::_('searchtools.sort', 'COM_VISFORMS_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>
        <th width="3%"  class="nowrap center">
                
            <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th width="3%">
                <?php echo JHtml::_('searchtools.sort', 'COM_VISFORMS_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>		
        </th>	

                <?php	$k = 0;
                $n=count( $this->fields );
                for ($i=0; $i < $n; $i++)
                {
                        $width = 30;
                        if ($n > 0) {
                                $width = floor(89/$n);
                        }
                        $rowField = $this->fields[$i];
                        if (!($rowField->showFieldInDataView === false))
                        {
                ?>
                        <th width="<?php echo $width ?>%" class="nowrap center">
                                <?php echo JHtml::_('searchtools.sort', $rowField->name, 'a.F'. $rowField->id, $listDirn, $listOrder); ?>
                        </th>
                <?php         
                        }
                }
                ?>
                <th width="4%" class="nowrap center">
                        <?php echo JHtml::_('searchtools.sort', 'COM_VISFORMS_IP', 'a.ipaddress', $listDirn, $listOrder); ?>
                </th>
                <th width="4%" class="nowrap center">
                        <?php echo JHtml::_('searchtools.sort', 'COM_VISFORMS_DATE', 'a.created', $listDirn, $listOrder); ?>
                </th>

			
	</tr>			
</thead>
	<?php foreach ($this->items as $i => $row) :
        $row->max_ordering = 0; //??
        $ordering   = ($listOrder == 'a.id');
	
                if ($canEditState)
                {
                    $published	= JHTML::_('jgrid.published', $row->published, $i, 'visdatas.', true );
                }
                else
                {
                    $published	= JHTML::_('jgrid.published', $row->published, $i, 'visdatas.', false );
                }
		$checked = JHTML::_('grid.id',   $i, $row->id );
		$link = JRoute::_( 'index.php?option=com_visforms&task=visdata.edit&fid='.JFactory::getApplication()->input->getInt( 'fid', -1 ).'&id='. $row->id );

		?>
        <tr class="row<?php echo $i % 2; ?>">
                    <td>
                            <?php echo "<a href=\"".$link."\">".$row->id."</a>"; ?>
                    </td>
                    <td>
                            <?php echo $checked; ?>
                    </td>   
                        
            <td align="center">
                <?php echo $published;?>
            </td>
<?php
	$z=count( $this->fields );
	for ($j=0; $j < $z; $j++)
	{
		$rowField = $this->fields[$j];
		if (!($rowField->showFieldInDataView === false))
		{
			$prop="F".$rowField->id;
			if (isset($row->$prop) == false)
			{
				$prop=$rowField->name;
			}
			if (isset($row->$prop))
			{
				$texte = $row->$prop;
			} else {
				$texte = "&nbsp;";
			}
			if ($rowField->typefield == 'email') 
                        {
				$linkfield = "mailto:".$texte;
                                echo "<td><a href=\"".$linkfield."\">".$texte."</a></td>";
			}
			else if ($rowField->typefield == 'file')
                        {
                            //info about uploaded files are stored in a JSON Object. Earlier versions just have a string.
                            $texte = JHTML::_('visforms.getUploadFileLink', $texte);
                            echo "<td>". $texte . "</td>";
                        }
                        else
			{
                            if (strlen($texte) > 255) {
				$texte = substr($texte,0,255)."...";
                            }
                            echo "<td>" . $texte . "</td>";
			}
 	
		}
	}
 ?>

			<td>
				<?php echo $row->ipaddress; ?>
			</td>
            <td>
				<?php echo $row->created; ?>
			</td>
			
		</tr>
		<?php endforeach; 
	?>
    
    <tfoot>
    <tr>
      <td colspan="<?php echo (count($this->fields) + 4); ?>"><?php echo $this->pagination->getListFooter(); ?></td>
    </tr>
  </tfoot>

</table> 

   

  
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
    </div>
</form>

<?php JHTML::_('visforms.creditsBackend'); ?>