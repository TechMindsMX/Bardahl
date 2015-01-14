<?php 
/**
 * Visfield field view for Visforms
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
 defined('_JEXEC') or die('Restricted access');  
    
	JHtml::_('behavior.formvalidation');
	JHtml::_('behavior.keepalive');?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'visfield.cancel') {
				Joomla.submitform(task, document.getElementById('item-form'));
		} else if (document.formvalidator.isValid(document.id('item-form'))) {
            //make sure the typefield has a selected value
            var ft = document.getElementById('jform_typefield');
            var idx = ft.selectedIndex;
            var sel = ft[idx].value;
            if (sel != 0) {
                Joomla.submitform(task, document.getElementById('item-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('COM_VISFORMS_TYPE_FIELD_REQUIRED'));?>');
            }
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_visforms&view=visfields&fid=' . $this->item->fid); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="form-inline form-inline-header">
        <?php
        echo $this->form->getControlGroup('label');
        echo $this->form->getControlGroup('name');
        ?>
    </div>
    <div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basicfieldinfo')); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'basicfieldinfo', JText::_('COM_VISFORMS_FIELD_BASIC_INFO')); ?>
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">
                        <?php foreach ($this->form->getFieldset('basicfieldinfo') as $field) 
                        { ?>
                            <?php if($field->fieldname != 'ordering')
                            { ?>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $field->input; ?>
                                    </div>
                                </div>
                            <?php 
                            } ?>
                        <?php 
                        } ?>
                    </div>
                    <div class="span6">
                        <?php  $groupFieldSets = $this->form->getFieldsets('defaultvalue'); ?>
                           <?php foreach ($groupFieldSets as $name => $fieldSet) : 
                               $fieldsetId = $name;
                           ?>						
                                <div id="<?php echo $name; ?>">
                               <?php //if (isset($fieldSet->label)) {echo '<legend>' . JText::_($fieldSet->label) . '</legend>';} ?>
                                   <?php foreach ($this->form->getFieldset($name) as $field) { ?>
                                   <?php 
                                       //get id attribute value from field 

                                       //include li in a div with field id to get levarage for javascript
                                   ?>


                                           <?php
                                           //if we have a Calender field we have to set default dateformat for Calender
                                           if ($field->fieldname === "f_date_attribute_value") {
                                               $dateformatfield = $this->form->getField('f_date_format', 'defaultvalue');
                                               if ($dateformatfield->value != "") {
                                                   // get dateformat for javascript	
                                                   $dformat = explode(";", $dateformatfield->value);
                                                   if (isset($dformat[1])) 
                                                    {
                                                        $this->form->setFieldAttribute("f_date_attribute_value", "format", $dformat[1], 'defaultvalue');
                                                   }							
                                               }
                                           }
                                           ?>
                                           <?php if ($field->fieldname == "f_radio_value" || $field->fieldname== "f_select_value") { 
                                               if ($field->fieldname == "f_radio_value") {
                                                   $fieldname = "f_radio";
                                               } else {$fieldname = "f_select"; } ?>
                                               <div class="control-group noclear">
                                                   <div class="control-label">
                                                    <?php echo $field->label; ?>
                                                </div>
                                                <div class="controls">
                                                    <?php echo $field->input; ?>
                                                    <label id="jform_defaultvalue_<?php echo $fieldname ?>_default-lbl" class="hasTooltip" title="" for="jform_defaultvalue_<?php echo $fieldname ?>_default" data-original-title="<strong><?php echo JText::_('COM_VISFORMS_DEFAULT'); ?></strong><br /><?php echo JText::_('COM_VISFORMS_RADIO_SELECT_DEFAULT_DESC'); ?>"><?php echo JText::_('COM_VISFORMS_DEFAULT'); ?></label>
                                                    <input id="jform_defaultvalue_<?php echo $fieldname ?>_default" type="checkbox" value="1" name="jform[defaultvalue][<?php echo $fieldname ?>_default]">
                                                </div>
                                               </div>
                                           <?php } else if ($field->fieldname == "f_radio_default" || $field->fieldname == "f_select_default") {?>
                                    <div class="pull-left"> <?php echo $field->label; ?> <?php echo $field->input; ?></div>
                                           <?php } else { ?>
                                            <div class="control-group">
                                                
                                                <div class="control-label">
                                                    <?php echo $field->label; ?>
                                                </div>
                                                <div class="controls">
                                                    <?php echo $field->input; ?>
                                                        <?php if ($field->fieldname === "f_radio_radio_label") { ?>
                                                            <input name="add" onclick="addValueToList('jform_defaultvalue_f_radio_list','jform_defaultvalue_f_radio_list_hidden','jform_defaultvalue_f_radio_value','jform_defaultvalue_f_radio_label','jform_defaultvalue_f_radio_default');" type="button" value="<?php echo JText::_('COM_VISFORMS_ADD'); ?>" />
                                                            &nbsp;<input onclick="resetValues('jform_defaultvalue_f_radio_value','jform_defaultvalue_f_radio_label','jform_defaultvalue_f_radio_default');" name="reset" type="button" value="<?php echo JText::_('COM_VISFORMS_RESET'); ?>" />
                                                        <?php } ?>
                                                        <?php if ($field->fieldname === "f_radio_list") { ?>
                                                            <input onclick="removeOptions('jform_defaultvalue_f_radio_list','jform_defaultvalue_f_radio_list_hidden','jform_defaultvalue_f_radio_value','jform_defaultvalue_f_radio_label','jform_defaultvalue_f_radio_default');" name="del" type="button" value="<?php echo JText::_('COM_VISFORMS_DEL'); ?>" />
                                                        <?php } ?>
                                                        <?php if ($field->fieldname === "f_select_select_label") { ?>
                                                            <input name="add" onclick="addValueToList('jform_defaultvalue_f_select_list','jform_defaultvalue_f_select_list_hidden','jform_defaultvalue_f_select_value','jform_defaultvalue_f_select_label','jform_defaultvalue_f_select_default');" type="button" value="<?php echo JText::_('COM_VISFORMS_ADD'); ?>"" />
                                                            &nbsp;<input onclick="resetValues('jform_defaultvalue_f_select_value','jform_defaultvalue_f_select_label','jform_defaultvalue_f_select_default');" name="reset" type="button" value="<?php echo JText::_('COM_VISFORMS_RESET'); ?>"" />
                                                        <?php } ?>
                                                        <?php if ($field->fieldname === "f_select_list") { ?>
                                                            <input onclick="removeOptions('jform_defaultvalue_f_select_list','jform_defaultvalue_f_select_list_hidden','jform_defaultvalue_f_select_value','jform_defaultvalue_f_select_label','jform_defaultvalue_f_select_default');" name="del" type="button" value="<?php echo JText::_('COM_VISFORMS_DEL'); ?>" />
                                                        <?php } ?>
                                                </div>
                                           </div>
                                    <?php } ?>
                                   <?php } ?>
                               </div>
                         <?php endforeach; ?>
                    </div>
                </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'visfield-advanced-detailso', JText::_('COM_VISFORMS_TAB_ADVANCED')); ?>
            <div class="row-fluid form-horizontal-desktop">
                <div class="span6">
                    <?php $fslayout = $this->form->getFieldset('visfield-layout-details'); ?>
                    <?php foreach ($fslayout as $field) { ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $field->label; ?>
                            </div>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="span6">
                    <?php $fsadvanced = $this->form->getFieldset('visfield-advanced-details'); ?>
                    <?php foreach ($fsadvanced as $field) { ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $field->label; ?>
                            </div>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>		
					
	<?php if ($this->canDo->get('core.admin'))
    {
        echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_VISFORMS_FIELDSET_FIELD_RULES', true)); 
        echo $this->form->getInput('rules'); 
        JHtml::_('bootstrap.endTab');
    }
     
     echo JHtml::_('bootstrap.endTabSet');
     ?>
		<input type="hidden" name="option" value="com_visforms" />
		<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="fid" value="<?php echo $this->fid; ?>" />
		<input type="hidden" name="ordering" value="<?php echo $this->item->ordering; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="visfields" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
 
</form>

 <?php JHTML::_('visforms.creditsBackend'); ?>