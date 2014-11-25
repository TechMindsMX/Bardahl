<?php
/**
 * Visforms default view for Visforms
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
defined('_JEXEC') or die('Restricted access'); 
jimport( 'joomla.utilities.arrayhelper' );

JHTMLVisforms::includeScriptsOnlyOnce();
	
if ($this->visforms->published != '1') {return;}

	$nbFields=count($this->visforms->fields );
	//get some infos to look whether it's neccessary to add Javascript or special HTML-Code or not
	//variables are set to true if they are true for at least one field
	$required = false;
	$upload = false;
	$textareaRequired = false;
        $hasHTMLEditor = false;
	
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->visforms->fields[$i];
		//set the controll variables
		if (isset($field->attribute_required) && ($field->attribute_required == "required")) 
		{
			$required = true;
		}
		if (isset($field->typefield) && $field->typefield == "file")
		{
			$upload = true;
		}
		if (isset($field->textareaRequired) && $field->textareaRequired === true) 
		{
			//we have some work to do to use Javascript to validate that the textarea has content
			$textareaRequired = true;
		}
                if (isset($field->hasHTMLEditor) && $field->hasHTMLEditor == true)
                {
                    $hasHTMLEditor = true;
                }
		
		
		JHTML::_('visforms.getFieldAttributeArray', $field);
                JHTML::_('visforms.getFieldValidateArray', $field);
                JHTML::_('visforms.getFieldCustomErrorMsgArray', $field);
	}
?>

<div class="visforms-form<?php echo $this->menu_params->get( 'pageclass_sfx' ); ?>" id="visformcontainer">
<?php 
if ($this->menu_params->get('show_page_heading') == 1) 
	{ 
		if (!$this->menu_params->get('page_heading') == "") { ?>
			<h1><?php echo $this->menu_params->get('page_heading'); ?></h1>
	<?php 
	}
	else 
	{ ?>
		<h1><?php echo $this->visforms->title; ?></h1>
	<?php
	}
}?>

<script type="text/javascript">


jQuery(document).ready( function(){
    <?php 
	
	if ($textareaRequired == true ||$hasHTMLEditor == true)
	{ 
            //we need an editor and create a simple tinyMCE editor 
            VisformsEditorHelper::initEditor();
?>
            // Create a simple plugin
            tinymce.create('tinymce.plugins.TestPlugin', {
                TestPlugin : function(ed, url) {
                    //add function that will update content of tinyMCE on change (is only called, when user clicks outside editor        
                     ed.on ("change", function(ed) {
                          updateText(ed);
                      });
                     //add function that will update content of tinyMCE on submit
                     ed.on("submit", function(ed)
                     {
                          return updateText(ed);
                     });
                }
            });

            // Register plugin using the add method
            tinymce.PluginManager.add('test', tinymce.plugins.TestPlugin);

            //copy content of editor into a textarea field and validate content of that textarea
            function updateText(ed) {
                //get id of textarea which belongs to the editor
                //var edId = ed.target.id;
                var inputId = ed.target.id;
                //copy editor content into textarea
                tinyMCE.triggerSave();
                <?php if ($textareaRequired == true) { 
                //validate content of textarea
                echo 'return jQuery("#" + inputId).valid();';
                 } ?>
            };
<?php 
	}
?>    
        var validator = jQuery(document).ready(function() {
            jQuery('#visform<?php echo $this->visforms->id; ?>').validate({
                wrapper: "p",
                //absolutly necessary when working with tinymce!
                ignore: "",
                rules: {
<?php
                    //insert rules that we cannot put into html attributes because they are no valid attributs or valid attribute values
                    for ($i=0;$i < $nbFields; $i++)
                    { 
                        $field = $this->visforms->fields[$i];
                        if(isset($field->validateArray))
                        {
                            echo "\"". $field->name . "\" : {";
                            foreach ($field->validateArray as $n => $v)
                            {
                               echo $n . ": " . $v . ","; 
                            }
                            echo "},";
                        }
?>
<?php
                    }
                    //recaptcha code comes from google api. Because we use the joomla recaptcha plugin we cannot change much field attribute values... and have to include a rule for the captcha
                    if (isset($this->visforms->captcha) && ($this->visforms->captcha == 2))
                    {
                        echo 'recaptcha_response_field : { required : true},';
                    }
?>
                },
                messages: {
<?php
                    //Include custom error messages
                    for ($i=0;$i < $nbFields; $i++)
                    { 
                        $field = $this->visforms->fields[$i];
                        //Custom Error Messages for date fields
                        if (isset($field->typefield) && $field->typefield == "date" && !(isset($field->customErrorMsgArray)))
                        {
                            if(isset($field->dateFormatJs))
                            {
                                switch ($field->dateFormatJs)
                                {
                                    case "%d.%m.%Y":
                                    echo "\"". $field->name . "\" : { dateDMY: \"" . JText::_('COM_VISFORMS_ENTER_VALID_DATE')  . "\" },";
                                    break;
                                    case "%m/%d/%Y":
                                    echo "\"". $field->name . "\" : { dateMDY: \"" . JText::_('COM_VISFORMS_ENTER_VALID_DATE')  . "\" },";
                                    break;
                                    case "%Y-%m-%d":
                                    echo "\"". $field->name . "\" : { dateYMD: \"" . JText::_('COM_VISFORMS_ENTER_VALID_DATE')  . "\" },";
                                    break;
                                }
                            }
                        }
                        //Custom Error Messages for Selects
                        if (isset($field->typefield) && $field->typefield == "select" && (isset($field->customErrorMsgArray)))
                        {
                            echo "\"" . $field->name . "[]\": {";
                            foreach ($field->customErrorMsgArray as $n => $v)
                            {
                               echo  $n . ": \"" . $v . "\","; 
                            }
                            echo "},";
                        }
                        //Custom Error Messages for 'normal' fields
                        if(isset($field->customErrorMsgArray))
                        {
                            echo "\"" . $field->name . "\": {";
                            foreach ($field->customErrorMsgArray as $n => $v)
                            {
                               echo  $n . ": \"" . $v . "\","; 
                            }
                            echo "},";
                        }
                        //Custom Captcha Error Message
                        if(isset($this->visforms->captchacustomerror) && $this->visforms->captchacustomerror != "")
                        {
                            echo "\"recaptcha_response_field\": {"; 
                            echo  "required" . ": \"" . $this->visforms->captchacustomerror . "\","; 
                            echo "},";
                        }
                    }
?>
                },
                errorPlacement: function (error, element){
                    error.appendTo('div.fc-tbx' + element.attr("id"));
                    error.addClass("errorcontainer");
                },

            });
                
        });
        jQuery.extend(jQuery.validator.messages, {
                required: '<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_REQUIRED' )); ?>',
		remote: "Please fix this field.",
		email: '<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VALID_EMAIL' )); ?>',
		url: '<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VALID_URL' )); ?>',
		date: '<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VALID_DATE' )); ?>',
		dateISO: "Please enter a valid date (ISO).",
		number: '<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VALID_NUMBER' )); ?>',
		digits: '<?php echo addslashes(JText::_( 'COM_VISMORMS_ENTER_VALID_DIGIT' )); ?>',
		creditcard: "Please enter a valid credit card number.",
		equalTo: '<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_CONFIRM' )); ?>',
		maxlength: jQuery.validator.format('<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VAILD_MAXLENGTH' )); ?>'),
		minlength: jQuery.validator.format('<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VAILD_MINLENGTH' )); ?>'),
		rangelength: jQuery.validator.format('<?php echo addslashes(JText::_( 'COM_VISMORMS_ENTER_VAILD_LENGTH' )); ?>'),
		range: jQuery.validator.format('<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VAILD_RANGE' )); ?>'),
		max: jQuery.validator.format('<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VAILD_MAX_VALUE' )); ?>'),
		min: jQuery.validator.format('<?php echo addslashes(JText::_( 'COM_VISFORMS_ENTER_VAILD_MIN_VALUE' )); ?>')
        });
        jQuery.validator.addMethod("dateDMY", function(value, element) {
            var check = false;
            var re = /^(0[1-9]|[12][0-9]|3[01])[\.](0[1-9]|1[012])[\.]\d{4}$/;
            if( re.test(value)) {
                    var adata = value.split('.');
                    var day = parseInt(adata[0],10);
                    var month = parseInt(adata[1],10);
                    var year = parseInt(adata[2],10);
                    if (day == 31 && (month == 4 || month == 6 || month == 9 || month == 11)) {
                        check = false; // 31st of a month with 30 days
                        } else if (day >= 30 && month == 2) {
                        check = false; // February 30th or 31st
                        } else if (month == 2 && day == 29 && ! (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0))) {
                        check = false; // February 29th outside a leap year
                        } else {
                        check = true; // Valid date
                        }
                    }
            return this.optional(element) || check;
        }, <?php echo (isset($field->customerror) && $field->customerror != "") ? "\"" . $field->customerror. "\"" : "\"" . JText::_('COM_VISFORMS_ENTER_VALID_DATE')  . "\""; ?>);
        jQuery.validator.addMethod("dateMDY", function(value, element) {
            var check = false;
            var re = /^(0[1-9]|1[012])[\/](0[1-9]|[12][0-9]|3[01])[\/]\d{4}$/;
            if( re.test(value)) {
                    var adata = value.split('/');
                    var month = parseInt(adata[0],10);
                    var day = parseInt(adata[1],10);
                    var year = parseInt(adata[2],10);
                    if (day == 31 && (month == 4 || month == 6 || month == 9 || month == 11)) {
                        check = false; // 31st of a month with 30 days
                        } else if (day >= 30 && month == 2) {
                        check = false; // February 30th or 31st
                        } else if (month == 2 && day == 29 && ! (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0))) {
                        check = false; // February 29th outside a leap year
                        } else {
                        check = true; // Valid date
                        }
                    }
            return this.optional(element) || check;
        }, <?php echo (isset($field->customerror) && $field->customerror != "") ? "\"" . $field->customerror. "\"" : "\"" . JText::_('COM_VISFORMS_ENTER_VALID_DATE')  . "\""; ?>);
        jQuery.validator.addMethod("dateYMD", function(value, element) {
            var check = false;
            var re = /^\d{4}[\-](0[1-9]|1[012])[\-](0[1-9]|[12][0-9]|3[01])$/;
            if( re.test(value)) {
                    var adata = value.split('-');
                    var year = parseInt(adata[0],10);
                    var month = parseInt(adata[1],10);
                    var day = parseInt(adata[2],10);
                    if (day == 31 && (month == 4 || month == 6 || month == 9 || month == 11)) {
                        check = false; // 31st of a month with 30 days
                        } else if (day >= 30 && month == 2) {
                        check = false; // February 30th or 31st
                        } else if (month == 2 && day == 29 && ! (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0))) {
                        check = false; // February 29th outside a leap year
                        } else {
                        check = true; // Valid date
                        }
                    }
            return this.optional(element) || check;
        }, <?php echo (isset($field->customerror) && $field->customerror != "") ? "\"" . $field->customerror. "\"" : "\"" . JText::_('COM_VISFORMS_ENTER_VALID_DATE')  . "\""; ?>);

        jQuery('.captcharefresh<?php echo $this->visforms->id; ?>').bind({
            'click' : function() {
                if (jQuery('#captchacode<?php echo $this->visforms->id; ?>')) 
                {
                    jQuery('#captchacode<?php echo $this->visforms->id; ?>').attr('src', 'index.php?option=com_visforms&task=captcha&sid=' + Math.random());
                }
            }
        });	
});

</script>
  <?php if (strcmp ( $this->visforms->description , "" ) != 0) { ?>
	<div class="category-desc"><?php echo $this->visforms->description; ?></div>
  <?php } ?>



        <form action="<?php echo JRoute::_($this->formLink); ?>" method="post" name="visform" id="visform<?php echo $this->visforms->id; ?>" class="visform <?php echo $this->visforms->formCSSclass; ?>"<?php if($upload == true) { ?> enctype="multipart/form-data"<?php } ?>>
		<fieldset>
		
<?php 
	//Explantion for * if at least one field is requiered at the top of the form
	if ($required == true && $this->visforms->required == 'top')
	{
?>
	<p class="vis_mandatory"><?php echo JText::_( 'COM_VISFORMS_REQUIRED' ); ?> *</p>
<?php } ?>
        
<?php
 
	//first hidden fields at the top of the form
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->visforms->fields[$i];
		if ($field->typefield == "hidden")
		{
?>
        <input name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" type="hidden" value="<?php if (isset($field->filluid) && ($field->filluid == "1")) {echo uniqid($field->attribute_value,true);} else {echo $field->attribute_value;} ?>" />
<?php
		}
	}

	//then inputs, textareas, selects and fieldseparators
	for ($i=0;$i < $nbFields; $i++)
	{ 
            $field = $this->visforms->fields[$i];
            if ($field->typefield != "hidden" && !isset($field->isButton))
            {	
?>   
            <div>
<?php
            //Create a div with the right class where we can put the validation errors into
            $idError = 'field' . $field->id;

            if ($field->typefield == 'radio') {
                    $idError = 'field' . $field->id . '_0';
            }
?>  
            <div class="fc-tbx<?php echo $idError ?>"></div>
<?php		
            //show a label except for fieldseparator
            if ($field->typefield != "fieldsep")
            {
?>
                <label class="<?php echo($field->typefield == "radio" && isset($field->display) && $field->display == 'LST') ? " visCSSlabel100 " : " visCSSlabel "; ?><?php if ($field->custominfo != "" && $field->typefield == "textarea") echo " visCSSbot5"; ?> <?php echo $field->labelCSSclass; ?>" id="<?php echo $field->name."lbl"; ?>" <?php if ($field->typefield != "radio") { echo 'for="field' . $field->id .'"';}?>> 
<?php 			
                 echo JHTML::_('visforms.createTip', $field);
?>
<?php 
                if (isset($field->attribute_required) && ($field->attribute_required == 'required')) 
                { 
        ?>
                     &nbsp;<span class="vis_mandatory">*</span>

        <?php
                }
?>       
                </label>
<?php       } 
?>
		
<?php        //We inclose textareas with HTML-Editor that are not readonly in a div
            if (isset($field->hasHTMLEditor) && $field->hasHTMLEditor)
            {
                 echo '<div class="editor">';
            }
            
            //we try to create allmost all HTML-Elements with on statement
            //doesn't work for selects and radios and calendars
            if (($field->htmlTag != 'select') && (!isset($field->attribute_type) || ($field->attribute_type != 'radio' && $field->attribute_type != 'date')))
            {

                echo '<' . $field->htmlTag . ' ';

                if (!empty($field->attributeArray)) 
                {
                        //add all attributes
                        echo JArrayHelper::toString($field->attributeArray, '=',' ', true);
                } 

                if ($field->htmlTag == 'input'  || $field->htmlTag == 'hr') 
                { 
                    echo '/>';
                }

                else 
                { 
                    echo '>';
                    echo $field->initvalue;
                    echo'</' . $field->htmlTag . '>';
                }
             }

            //field is a textarea with html Editor we have to close the div
            if (isset($field->hasHTMLEditor) && $field->hasHTMLEditor) 
            {
              echo '</div>';
            }
	
            //field is a select we get the listbox
            if (isset($field->htmlTag) && $field->htmlTag == 'select') 
            {
                    echo JHTML::_('visforms.getSelect', $field);
            }

            //field is a radio list
            if (isset($field->attribute_type) && $field->attribute_type == 'radio') 
            {
                    echo JHTML::_('visforms.getRadioList', $field);	
            }
            
            //field is a date list
            if (isset($field->attribute_type) && $field->attribute_type == 'date') 
            {
                    echo JHTML::_('visforms.getDateControl', $field);
            }
	
?>

    <p class="visCSSclear"><!-- --></p>
                </div>

<?php
	}   	
  
	if (($field->customtext != '') && (!isset($field->isButton) || $field->isButton != true)) {
 ?>
 		<div class="visCustomText "><?php echo $field->customtext; ?></div>
<?php
	}	
	
    }

	//Explantion for * if at least one field is requiered above captcha
	if ($required == true && $this->visforms->required == 'captcha')
	{
?>
	<p class="vis_mandatory"><?php echo JText::_( 'COM_VISFORMS_REQUIRED' ); ?> *</p>
<?php 
    }
    if (isset($this->visforms->captcha) && ($this->visforms->captcha == 1 || $this->visforms->captcha == 2))
	{
             echo JHTML::_('visforms.getCaptchaHtml', $this->visforms);
	} 

	//Explantion for * if at least one field is requiered above submit
	if ($required == true && $this->visforms->required == 'bottom')
	{
?>
	<div class="vis_mandatory visCSSbot10 visCSStop10"><?php echo JText::_( 'COM_VISFORMS_REQUIRED' ); ?> *</div>
<?php   
        } 
?>
    
    <div class="visBtnCon">
	<?php 
	//all button on the bottom of the form
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->visforms->fields[$i];
		//$isButton = ($field->typefield == "submit" || $field->typefield == "reset" || $field->typefield == "image");
		if (isset($field->isButton) && $field->isButton === true)
		{
			if ($field->customtext != '')  
			{
 ?>
				<div class="visCustomText "><?php echo $field->customtext; ?></div>
<?php
			}
			echo '<' . $field->htmlTag . ' ';
		?>
<?php 
		if (!empty($field->attributeArray)) 
		{
			//add all attributes
			echo JArrayHelper::toString($field->attributeArray, '=',' ', true);
		} 
		echo '/>&nbsp;';

			
	?>
    		
<?php 		
		   
		}
	}


?>
	</div>
    </fieldset>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>

<?php if ($this->visforms->poweredby == '1') { ?>
	<?php JHTML::_('visforms.creditsFrontend'); ?>
<?php } ?>

</div>
