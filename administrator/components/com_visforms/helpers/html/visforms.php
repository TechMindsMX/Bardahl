<?php
/**
 * JHTMLHelper for Visforms
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
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Utility class for creating HTML Grids
 *
 * @static
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 * @since   1.5.5
 */
class JHTMLVisforms
{

    /**
     * Method to displays the credits in backend
     *
     * @return  void
     * @since   1.0.6
     */
    public static function creditsBackend()
    {

      ?>
              <div class="visformbottom span12" style="text-align: center;">
                      Visforms Version <?php echo self::getVersion(); ?>, &copy; 2012 Copyright by <a href="http://vi-solutions.de" target="_blank" class="smallgrey">vi-solutions</a>, all rights reserved. 
                      visForms is Free Software released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html"target="_blank" class="smallgrey">GNU/GPL License</a>. 
              </div>
      <?php
      }
      
      /**
        * Method to display credits in frontend
        *
        * @return  void
        *
        * @since   11.1
        */
	public static function creditsFrontend() {
        ?>
            <div id="vispoweredby"><a href="http://vi-solutions.de" target="_blank"><?php echo JText::_( 'COM_VISFORMS_POWERED_BY' ); ?></a></div>
	<?php 
        }
        
        /**
	 * Method to get the version number of installed version of visforms.
	 *
	 * @return  string  version number
	 *
	 * @since   11.1
	 */
	public static function getVersion() {
		$xml_file = JPath::clean(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'visforms.xml');
		$installed_version = '1.0.0';
		if(file_exists($xml_file))
			{   if(version_compare(JVERSION,'3.0.0','ge')) 
             {
                $xml = JFactory::getXML($xml_file);
                $installed_version = $xml->version;
             }
            else 
            {
				$xml = JFactory::getXMLParser('simple');
                $xml->loadFile($xml_file);
                $version_tag = $xml->document->getElementByPath('version');
				$installed_version = $version_tag->data();
            }
        }
		return $installed_version;
	}
        
    /**
	 * Method to create html code for field of type select according to field settings
	 *
	 * @param   object  $fields  Object of form fields
	 *
	 * @return  string  html code for select field
	 *
	 * @since   11.1
	 */
	public static function getSelect($field) 
	{
            //No Options for select given
            if (!(isset($field->list_hidden)) || $field->list_hidden == "")
            {   
                return false;
            }
            //split options into an array
            $opt = explode("[-]", $field->list_hidden);
            $k=count($opt);
            $html = "";
            $options = array();
            $checked = array();
            //$postValueArray = JRequest::getVar($field->name, '', 'post', 'array');
			$postValueArray = JFactory::getApplication()->input->post->get($field->name, array(), 'ARRAY');
            $invalidPost = true;
		
            //SelectOption are an array $post; each checked option in form is an array element. All array elements should contain a value
            $count = count($postValueArray);
            for ($i = 0; $i < $count; $i++)
            {
                //somebody has messed the post we need to use the default of the select
                if ($postValueArray[$i] != "")
                {
                        $invalidPost = false;
                }

            }

            //Has select no default value? Then we need a supplementary 'default' option for selects that are not "multiple" or have a height < 1. Otherwise the first option can not be selected properly.

            if (((!(isset($field->attribute_multiple)) || ($field->attribute_multiple != 'multiple' && $field->attribute_multiple != '1' && $field->attribute_multiple != true)) && (!isset($field->attribute_size) || ($field->attribute_size == '' || $field->attribute_size <= 1)) && (!(isset($field->list_hidden))|| strpos($field->list_hidden,' [default]') == false))) {
                   $options[] = JHTML::_('select.option', '', JText::_('CHOOSE_A_VALUE'));
            }
            for ($j=0;$j < $k; $j++)
            {	
                if (strpos($opt[$j], '==') > 0)
                {
                    //split options into key/value pairs
                    $val = explode("==", $opt[$j]);
                    $key = explode("||", $val[1]);
                    //Is option an default option? (selected)
                    $ipos = strpos ($key[1],' [default]');

                    //Set Option to selected if set by post 
                    if (in_array($key[0], $postValueArray))
                    {
                        $checked[] = $key[0];
                    } 

                    //set option to selected acording to field default 
                    else if ($ipos != false && $invalidPost) 
                    {
                        $checked[] = $key[0];
                    }

                    //we have to remove the [default]
                    if ($ipos != false)
                    {
                        $key[1] = substr($key[1],0,$ipos);
                    }

                    $options[] = JHTML::_('select.option', $key[0], $key[1]);	
                }
            }
            $html .= JHTML::_('select.genericlist', $options, $field->name . '[]', array('id'=>'field' . $field->id,'list.attr'=>$field->attributeArray, 'list.select'=>$checked));
            return $html;
	}
        
	/**
	 * Method to create html for field of type radio list according to field settings
	 *
	 * @param   object  $fields  Object of form fields
	 *
	 * @return  string  html code for radio
	 *
	 * @since   11.1
	 */
	public static function getRadioList($field) 
	{
        //No Options for radio given
        if (!(isset($field->list_hidden)) || $field->list_hidden == "")
        {   
            return false;
        }
        $html = "";
		$labelClass = "";
		$opt = explode("[-]", $field->list_hidden);
		$k=count($opt);
        $postvalue = JFactory::getApplication()->input->post->getString($field->name);
		$invalidPost = false;

        if ($postvalue == "")
        {
            $invalidPost = true;
        }
		if (isset($field->display) && $field->display == 'LST')
        {
            //Show radios as a list; Wrap them in an div
            $html .= '<div class="visCSSclear '.$field->fieldCSSclass.'">';
        }
		else 
        {
            $html .= '<p class="visCSStop0 visCSSmargLeft visCSSrbinl '.$field->fieldCSSclass.'">';
        }
		for ($j=0; $j < $k; $j++)
		{	
			$checked = "";
			//is any option set?
			if (strpos($opt[$j], '==') > 0)
			{
				
				$val = explode("==", $opt[$j]);
				$key = explode("||", $val[1]);
				$ipos = strpos ($key[1],' [default]');
 
                if ($postvalue == $key[0]) 
				{
					$checked = 'checked="checked" ';
				} 
                                else if ($ipos != false && $invalidPost)
				{
					$checked = 'checked="checked" ';
				}
				//we have to remove the [default]
				if ($ipos != false)
				{
					$key[1] = substr($key[1],0,$ipos);
				}
                $inputAttributes = (!empty($field->attributeArray)) ? JArrayHelper::toString($field->attributeArray, '=',' ', true) : '';
                
				if (isset($field->display) && $field->display == 'LST')
					{
					if($j!=0)
					{
						$html .= '<br />';
					}
                    $labelClass = 'visCSSbot5 visCSSrllst ';
                    $html .= '<label class="'.  $labelClass . $field->labelCSSclass . '" id="' . $field->name . 'lbl_' . $j .'" for="field' . $field->id . '_' . $j .'">' . $key[1] . '</label>';
                    $html .= '<input id="field' . $field->id . '_' . $j . '" name="' . $field->name .'" value="' . $key[0] .'" ' . $checked  . $inputAttributes . ' />';
				}
				else 
				{
					$labelClass = 'visCSStop10 visCSSright20 visCSSrlinl ';
                    $html .= '<input id="field' . $field->id . '_' . $j . '" name="' . $field->name .'" value="' . $key[0] .'" ' . $checked  . $inputAttributes . ' />';
                    $html .= '<label class="'.  $labelClass . $field->labelCSSclass . '" id="' . $field->name . 'lbl_' . $j .'" for="field' . $field->id . '_' . $j .'">' . $key[1] . '</label>';
				} 
			}
		} 
		if (isset($field->display) && $field->display == 'LST') 
		{
			$html .= '</div>';
		}
		else 
		{
			$html .= '</p>';
		}
		return $html;
			
	}
	
     /**
	 * Method to create html code for tooltips when using ## as seperator between title and tiptext (backwards compatibility)
	 *
	 * @param   object  $fields  Object of form fields
	 *
	 * @return  string  html code for tooltip or field label
	 *
	 * @since   11.1
	 */
	public static function createTip($field) 
	{
		$tip = array();
		$html = "";
			
		//Show Helptext in Tooltip
		if (isset($field->custominfo) && $field->custominfo != "") {
			$tip = explode('##', $field->custominfo, 2);
		}
		
		if ($tip) {
			if (!isset($tip[1])) 
            {
                //tip has no title seperated by ##
				$html = JHTML::_('tooltip', $tip[0],'','',$field->label);
			}
			else
			{
                //tip has text and title 
				$html = JHTML::_('tooltip', $tip[1], $tip[0], '',$field->label);
			}
		 }
		 else
		 {
			//return field label as text
            $html = $field->label;
		 }
		 return $html;
	}
    
    /**
	 * Method to create html code for captcha tooltips
	 *
	 * @param   object  $form  Visforms form object
	 *
	 * @return  string  html code for tooltip or field label
	 *
	 * @since   11.1
	 */
	public static function createCaptchaTip($form) 
	{
		$html = "";
			
		//Show Helptext in Tooltip
        

        if (isset($form->captchacustominfo) && $form->captchacustominfo != "")
        {
            $html = JHTML::_('tooltip', $form->captchacustominfo,'','','Captcha <span class="vis_mandatory">*</span>'); 
        }
		 else
		 {
			//return field label as text
            $html = 'Captcha <span class="vis_mandatory">*</span>';
		 }
		 return $html;
	}
        
    /**
	 * Method to create html code to generate the captcha
	 *
	 * @param   object  $fields  Object of form fields
	 *
	 * @return  string  html code for radio
	 *
	 * @since   11.1
	 */
        public static function getCaptchaHtml ($form, $clear = false){
            $html = "";
            if (isset($form->captcha))
            {
                $html .= '<div class="captchaCont">';
                //Create a div with the right class where we can put the validation errors into
                $html .= '<div class="fc-tbxrecaptcha_response_field"></div>';
                $html .= '<label class ="visCSSlabel" id="captcha-lbl" for="recaptcha_response_field">' . self::createCaptchaTip($form) . '</label>';
                if ($clear)
                {
                    $html .= '<div class="clr"> </div>';
                }
                if ($form->captcha == 1) 
                {
                    if ($clear==false) { $html .= '<div class="visCSSmargLeft">';}
                    $html .= '<img id="captchacode' . $form->id . '" class="captchacode" src="' . JRoute::_('index.php?option=com_visforms&task=captcha&sid=c4ce9d9bffcf8ba3357da92fd49c2457&id=' . $form->id) . '" align="absmiddle"> &nbsp; ';          
                    $html .= '<img alt="' . JText::_( 'COM_VISFORMS_REFRESH_CAPTCHA' ) . '" class="captcharefresh' . $form->id . '" src="' . JURI::root(true) . '/components/com_visforms/captcha/images/refresh.gif' . '" align="absmiddle"> &nbsp;';
                    $html .= '<input class="visCSStop10" required="required" type="text" id="recaptcha_response_field" name="recaptcha_response_field" />'; 
                    if (!$clear) { $html .= '</div>'; }
                }
                else if ($form->captcha == 2) 
                {
                    JPluginHelper::importPlugin('captcha');
                    $dispatcher = JDispatcher::getInstance();
                    $dispatcher->trigger('onInit','dynamic_recaptcha_1');
                    $html .= '<div id="dynamic_recaptcha_1"></div>';
                }
                $html .= '</div>';
            }
            
            return $html;
        }
        
        
	
    /**
	 * Method to create an array of attributes for form field
	 *
	 * @param   object  $field  form field object
	 *
	 * @return  object  void
	 *
	 * @since   11.1
	 */
	public static function getFieldAttributeArray(&$field) 
	{
		$field->attributeArray = array();
		//attributes are stored in xml-definition-fields with name that ends on _attribute_attributename (i.e. _attribute_checked).
		//each form field is represented by a fieldset in xml-definition file 
		//each form field should have in xml-definition file a field with name that ends on _attribute_class. default " " or class-Attribute values for form field 
		foreach ($field as $name => $value) 
		{
                    if ($field->typefield == "date")
                    {
                        JHTML::_('visforms.addDateFormatsToField', $field);
                        JHTML::_('visforms.getDateFieldValue', $field);
                    }

			if (!is_array($value))
			{
				if (strpos($name, 'attribute_') !== false) 
				{
					if ($value) {
						$name = str_replace('attribute_', "", $name);
						if ($name == "class") 
						{
							if (isset($field->noborder) && $field->noborder == "1") 
							{
								$value .= " visNoBorder ";
							}
							$value =  $value . $field->fieldCSSclass; 
                                                        //we have a tinymce field that is required and not readonly
                                                        //has it's own class
                                                        if ((isset($field->textareaRequired) && $field->textareaRequired === true) || (isset($field->hasHTMLEditor) && $field->hasHTMLEditor == true)) 
                                                        {
                                                                $value = "mce_editable";
                                                        }
						}
                                                $field->attributeArray[$name] = $value;
					}
				}
				if ($name == 'name')
				{
					if ($field->typefield != "fieldsep")
					{
						$field->attributeArray['name'] = $value;
					}
				}
				
				if ($name == 'id')
				{
					$value = 'field' . $value;
					$field->attributeArray['id'] = $value;
				}	
			}
		}
		
		//special Attributes
		if (isset($field->isButton) && $field->isButton === true && $field->typefield != "image")
		{
			$field->attributeArray['value'] = $field->label;
		}
        
        //add baseurl to src attribute
        if (isset($field->isButton) && $field->isButton === true && $field->typefield == "image")
		{
			$field->attributeArray['src'] = JUri::base() . $field->attributeArray['src'];
		}
		
		if (isset($field->hasHTMLEditor) && $field->hasHTMLEditor) 
		{ 
			//set some special attaribute for the textarea that is linked to the editor
			$field->attributeArray['style'] = "width: 97%; height: 200px;";
		}
		
		//no id, name and value Attribute for radios in attribute Array
		if ($field->typefield == 'radio')
		{
			unset($field->attributeArray['id']);
			unset($field->attributeArray['name']);
			unset($field->attributeArray['value']);
		}
		
		//no id and name Attribute for selects in attributeArray
		if ($field->typefield == 'select')
		{
			unset($field->attributeArray['id']);
			unset($field->attributeArray['name']);
		}
        
        //no id, name, type, value Attribute for dates in attributeArray
		if ($field->typefield == 'date')
		{
			unset($field->attributeArray['id']);
			unset($field->attributeArray['name']);
            unset($field->attributeArray['value']);
            unset($field->attributeArray['type']);
		}
		
		//make sure we have cols and rows for textarea
		if ($field->typefield == "textarea")
		{
			if (!isset($field->attributeArray['cols']) || $field->attributeArray['cols'] == "")
			{
				$field->attributeArray['cols'] = "10";
			}
			if (!isset($field->attributeArray['rows']) || $field->attributeArray['rows'] == "")
			{
				$field->attributeArray['rows'] = "20";
			}
		}
        
        //use fillfieldwith values if set
        if (($field->typefield == "text") && (isset($field->fillwith) && $field->fillwith != "")) 
        {
            $user = JFactory::getUser();
            $userId = $user->get('id');
            if($userId != 0)
            {
                if($field->fillwith == 1)
                {
                    $field->attributeArray['value'] = $user->get('name');
                }
                if($field->fillwith == 2)
                {
                    $field->attributeArray['value'] = $user->get('username');
                }
            }
        }
        if (($field->typefield == "email") && (isset($field->fillwith) && $field->fillwith != "")) 
        {
            $user = JFactory::getUser();
            $userId = $user->get('id');
            if($userId != 0)
            {
                if($field->fillwith === 'usermail')
                {
                    $field->attributeArray['value'] = $user->get('email');
                }                
            }
        }
        
	}
        
    /**
	 * Method to create an array of validate rules for a form field
     * The rules are used for form field validation with jQuery validator plugin
     * If there are html attributes like "required" or type="email" the validator creates a rule from this attributes
     * Not all validation rules corresponds to html attributes. Those rules we have to create seprately
	 *
	 * @param   object  $field  Form field object
	 *
	 * @return  object  $field  void
	 *
	 * @since   11.1
	 */
        public static function getFieldValidateArray(&$field) 
	{
		//validation rules are stored in xml-definition-fields with name that ends on _validate_rulename (i.e. _validate_minlength).
		//each form field is represented by a fieldset in xml-definition file 
		foreach ($field as $name => $value) 
		{

			if (!is_array($value))
			{
				if (strpos($name, 'validate') !== false) 
				{
					if ($value) {
						$name = str_replace('validate_', "", $name);
                                                $field->validateArray[$name] = $value;
					}
				}
			}
		}
                if (isset($field->typefield) && $field->typefield == "date")
                {
                    if(isset($field->dateFormatJs))
                    {
                        switch ($field->dateFormatJs)
                        {
                            case "%d.%m.%Y":
                            $field->validateArray['dateDMY'] = 'true';
                                break;
                            case "%m/%d/%Y":
                                $field->validateArray['dateMDY'] = 'true';
                            break;
                            case "%Y-%m-%d":
                                $field->validateArray['dateYMD'] = 'true';
                                break;
                        }
                    }
                }
	}
        
    /**
	 * Method to create an array of custom error messages for a form field and attach it as field property
     * The messages are use in jQuery validator plugin
     * 
	 *
	 * @param   object  $fields  Object of form fields
	 *
	 * @return  string  void
	 *
	 * @since   11.1
	 */
        public static function getFieldCustomErrorMsgArray(&$field) 
	{
		//validation rules are stored in xml-definition-fields with name that ends on _validate_rulename (i.e. _validate_minlength).
		//each form field is represented by a fieldset in xml-definition file 
		if(isset($field->customerror) && $field->customerror != "")
		{
                    foreach ($field as $name => $value) 
                    {
                        $attributes = array("maxlength", "min", "max", "required");
                        $types = array("email", "url", "date", "number");
			if (!is_array($value))
			{
                            if ($value)
                            {
				if (strpos($name, 'validate') !== false) 
				{
                                    $name = str_replace('validate_', "", $name);
                                    $field->customErrorMsgArray[$name] = $field->customerror;
				}
                                if (strpos($name, 'attribute_') !== false) 
				{
                                    $name = str_replace('attribute_', "", $name);
                                    if (in_array($name, $attributes)) 
                                    {
                                          $field->customErrorMsgArray[$name] = $field->customerror;
                                    }
				}

                                    $name = $field->typefield;
                                    if (in_array($name, $types)) {
                                        $field->customErrorMsgArray[$name] = $field->customerror;
                                    }
                            }
			}
                    }
		}
	}
        
        /**
	 * Method to add date format property to date field
	 *
	 * @param   object  $field  form field object
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
        public static function addDateFormatsToField(&$field) {
            if (isset($field->format)) 
            {
                // get dateformat for php and for javascript	
		$dformat = explode(";", $field->format);
                if (count($dformat) == 2) 
                {
                    $field->dateFormatPhp = $dformat[0];
                    $field->dateFormatJs = $dformat[1];
                }
            }
        }
	
    /**
	 * Method to set default value from field settings for date field
	 *
	 * @param   object  $field  form field object
	 *
	 * @return  object  $field  form field object
	 *
	 * @since   11.1
	 */
	public static function getDateFieldValue(&$field)
	{
		if ($field->attribute_type == "date") 
		{			
			//check if we have to use now as defaultdate
			if (strcmp($field->attribute_value,'') == 0 && (isset($field->daydate) && strcmp($field->daydate,'1') == 0)) 
			{
				$field->attribute_value = JHTML::_('date', 'now', $field->dateFormatPhp);
			}
		}
	}
        
    /**
	 * Method to create HTML for date control
	 *
	 * @param   object  $field  form field object
	 *
	 * @return  string  HTML for date control
	 *
	 * @since   11.1
	 */
     public static function getDateControl($field) {
         //Joomla date control accepts only the attribute value "readonly" not 1 or true to detect a readonly date field. We support the other values as well
         if (isset($field->attributeArray['readonly']) && ($field->attributeArray['readonly'] == 1 || $field->attributeArray['readonly'] === true)) 
         {
             $field->attributeArray['readonly'] = "readonly";
         }
        $date = JHTML::calendar($field->attribute_value, $field->name, 'field' . $field->id, $field->dateFormatJs, $field->attributeArray);
        return $date;
     }
        
    /**
     * Method to extract uploadfile Link from JRegistry Object and create link HTML
     * in earlier versions of visforms link information was not stored
     * 
     *
     * @param   object  $registryString  JRegistry Object that contains link and file name information
     *
     * @return  string  ancor tag (HTML) or filename (if no link information is available)
     *
     * @since   11.1
     */
    public static function getUploadFileLink ($registryString) 
    {
         //info about uploaded files are stored in a JSON Object. Earlier versions just have a string.
        $registry = new JRegistry;
        $registry->loadString($registryString);
        $fileInfo = $registry->toArray();
        if (isset($fileInfo['folder']))
        {
            //return link
            return '<a href="' . JUri::root()  . $fileInfo['folder'] . '/' . $fileInfo['file'] . '" target="_blank">'. JUri::root()  . $fileInfo['folder'] . '/' . $fileInfo['file'] . '</a>';
        }   
        else 
        {
            return basename($registryString);
        }
    }
        
    /**
	 * Method to extract upload filename from JRegistry Object
	 *
	 * @param   object  $registryString  JRegistry Object that contains link and file name information
	 *
	 * @return  string  filename
	 *
	 * @since   11.1
	 */
    public static function getUploadFileName ($registryString)
    {
        //info about uploaded files are stored in a JSON Object. Earlier versions just have a string.
        $registry = new JRegistry;
        $registry->loadString($registryString);
        $fileInfo = $registry->toArray();
        if (isset($fileInfo['file']))
        {
            return $fileInfo['file'];
        }
        else 
        {
            return basename($registryString);
        }
    }
    
    /**
	 * Method to load visforms specific css and javascripts files; Load them only once 
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
    public static function includeScriptsOnlyOnce () 
    {
        /* Add css and js links*/
        $doc = JFactory::getDocument();
        //initialize some control vars
        $cssAlreadyIncluded = false;
        $val = false;
        $jQueryAlreadyIncluded = false;
        $header = $doc->getHeadData();
        //We use addStyleSheet to include css file. If already included they are stored in this array
        if (isset($header['styleSheets'])) {
            foreach ($header['styleSheets'] as $key => $value) {
               if (strpos($key, '/media/com_visforms/css/visforms.css') !== false)
               {
                   $cssAlreadyIncluded = true;
               }
            }
        }
        //we use addCustomTag to load jQuery library and depending scripts. If already included the are stored in this array
        if (isset($header['custom'])) {
            foreach ($header['custom'] as $value) {
               if (strpos($value, '/media/com_visforms/js/jquery.validate.min.js') !== false)
               {
                   $jQueryAlreadyIncluded = true;
               }
            }
        }
        //we load all three jQuery scripts unless all three are already included
        if (!$jQueryAlreadyIncluded)
        {
            $doc->addCustomTag('<script type="text/javascript" src="'.JURI::root(true).'/media/com_visforms/js/jquery.validate.min.js"></script>');
        }
        //we include the css script only if it is not already included
        if (!$cssAlreadyIncluded)
        {
            $doc->addStyleSheet(JURI::root(true).'/media/com_visforms/css/visforms.css');
        }
    }
    
    public static function replacePlaceholder($form, &$text='')
    {
        $fieldValue = ' ';

        if($text != '')
        {
            //enclose pattern in '' and // as delimeter
            $pattern = '/\[[A-Z0-9]{1}[A-Z0-9\-]*]/';
            if (preg_match_all($pattern, $text, $matches))
            {
                //found matches are store in the $matches[0] array
                foreach($matches[0] as $match)
                {
                    $str = trim($match, '\[]');
                    $field = JString::strtolower($str);
                    $fieldValue = " ";
                    if (isset($field) && ($field != "") && (is_array($form->fields)))
                    {
                        foreach ($form->fields as $ffield)
                        {
                            //Match is a real form field
                            if ($field == $ffield->name)
                            {
                                //get field type
                                $fieldtype = $ffield->typefield;
                                //we use the fieldtype to get user input from request 
                                //(basically we have to distinguish between selects, that are stored in an array, upload fields, html editor fields and the rest which can be captured as a string)
                                if($fieldtype == "select")
                                {
                                    $fieldValue = JFactory::getApplication()->input->post->get($field, '', 'ARRAY');
                                    //If we deal with select that allows multiselect, we convert it into a string
                                    if (is_array($fieldValue))
                                    {
                                        $vstr = implode(" " . JText::_('COM_VISFORMS_AND') . " ", $fieldValue);
                                        $fieldValue = $vstr;
                                    }
                                }
                                else if ($fieldtype == 'textarea')
                                {
                                    if (isset($ffield->hasHTMLEditor) && $ffield->hasHTMLEditor == true)
                                    {
                                        $fieldValue = JFactory::getApplication()->input->post->get($field, '', 'RAW');
                                    }
                                    else
                                    {
                                        $fieldValue = JFactory::getApplication()->input->post->get($field, '', 'STRING');
                                    }
                                }
                                else if ($fieldtype == "file")
                                {
                                    if (isset($form->emailrecipientincfilepath) && ($form->emailrecipientincfilepath == true) && isset($ffield->file['filelink']))
                                    {
                                        $fieldValue = $ffield->file['filelink'];
                                    }
                                    else if (isset($_FILES[$ffield->name]['name']) && $_FILES[$ffield->name]['name'] !='')
                                    {
                                        $fieldValue = $_FILES[$ffield->name]['name'];
                                    }
                                }
                                else
                                {
                                    $fieldValue = JFactory::getApplication()->input->post->get($field, '', 'STRING');
                                }
                            }
                        }
                    }
                    
                    //replace the match
                    $newText = preg_replace('\''. preg_quote($match) . '\'', $fieldValue, $text);
                    $text = $newText;
                }
            }
        }
        return $text;
    }
    
    public static function fixLinksInMail(&$text)
    {
        $urlPattern = '/^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i';
        $aPattern = '/<[ ]*a[^>]+href=[("\')]([^("\')]*)/';
        $imgPattern = '/<[ ]*img[^>]+src=[("\')]([^("\')]*)/';
        if (preg_match_all($aPattern, $text, $hrefs))
        {
            foreach($hrefs[1] as $href)
            {
                if(!(preg_match($urlPattern, $href) == 1))
                {
                    //we deal with an intern Url without Root path
                    $link = JURI::base() . $href;
                    $newText = preg_replace('\'' . preg_quote($href ) . '\'', $link, $text);
                    $text = $newText;
                }
            }
        }
        if (preg_match_all($imgPattern, $text, $srcs))
        {
            jimport('joomla.filesystem.file');
            jimport('joomla.filesystem.folder');
            foreach($srcs[1] as $src)
            {
                if(JFile::exists($src))
                {
                    //we deal with a local img
                    if (!(preg_match('\'' . preg_quote(Juri::base()) . '\'', $src) == 1))
                    {
                        //we deal with an intern Url without base Uri
                        $link = Juri::base() . $src;
                        $newText = preg_replace('\'' . preg_quote($src ) . '\'', $link, $text);
                        $text = $newText;
                    }
                }
            }
        }
        
        return $text;
    }
}
?>