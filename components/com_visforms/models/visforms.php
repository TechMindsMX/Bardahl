<?php
/**
 * Visforms model for Visforms
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
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport( 'joomla.utilities.arrayhelper' );

/**
 * Visforms modell
 *
 * @package		Joomla.Site
 * @subpackage	com_visforms
 * @since		1.6
 */
class VisformsModelVisforms extends JModelLegacy
{

	 /**
	 * The form id.
	 *
	 * @var    int
	 * @since  11.1
	 */
       private $_id;
         
    /**
      
     /**
	 * Input from request.
	 *
	 * @var    int
	 * @since  11.1
	 */
         private $input;
         
    /**
     
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModel
	 * @since   11.1
	 */
         public function __construct($config = array()) {
             $this->input = JFactory::getApplication()->input;
             if (isset($config['id']))
             {
                 $this->setId($config['id']);
             }
             else
             {
                $this->setId();
             }
             parent::__construct($config);
         }
         
         /**
	 * Method store the form id in _id.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
         public function setId($id = null) 
        {
             if (!isset($id))
             {
                $id = $this->input->getInt('id',  0);
             }
             $this->_id = $id;
         }
         
         /**
	 * Method to get the form dataset
	 *
	 * @return  object with form data
	 *
	 * @since   11.1
	 */
         public function getForm(){
             $query = ' SELECT * FROM #__visforms where id='.$this->_id ;				
             $this->_db->setQuery( $query );
             $form = $this->_db->loadObject();
             $registry = new JRegistry;
             //Convert receiptmailsettings field to an array
             $registry->loadString($form->emailreceiptsettings);
             $form->emailreceiptsettings = $registry->toArray();
             foreach ($form->emailreceiptsettings as $name => $value) 
             {
                //make names shorter and set all emailreceiptsettings as properties of form object               
                $form->$name = $value;   
             }
             $registry = new JRegistry;
             //Convert frontendsettings field to an array
             $registry->loadString($form->frontendsettings);
             $form->frontendsettings = $registry->toArray();
             foreach ($form->frontendsettings as $name => $value) 
             {
                //make names shorter and set all frontendsettings as properties of form object               
                $form->$name = $value;   
             }
             return $form;
         }
         
         /**
	 * Method to get the form fields from database
	 *
	 * @return  array of form fields
	 *
	 * @since   11.1
	 */
         public function getFields() {
            $query = ' SELECT * FROM #__visfields where fid='.$this->_id." and published=1 order by ordering asc" ;
            $fields = $this->_getList( $query );
		
            $n=count($fields );
            for ($i=0; $i < $n; $i++)
            { 
                    $registry = new JRegistry;
                    $registry->loadString($fields[$i]->defaultvalue);
                    $fields[$i]->defaultvalue = $registry->toArray();

                    foreach ($fields[$i]->defaultvalue as $name => $value) 
                    {
                            //make names shorter and set all default values as properties of field object
                            $prefix =  'f_' . $fields[$i]->typefield . '_';
                            if (strpos($name, $prefix) !== false) {
                                            $key = str_replace($prefix, "", $name);
                                            $fields[$i]->$key = $value;
                            }
                    }
                    
                    //Checkboxfield in Joomla 3 does not use field value properly. Checked box is always set to 1, we want it to be the field value so we fix it here
                    if (isset($fields[$i]->attribute_required) && ($fields[$i]->attribute_required == 'required' || $fields[$i]->attribute_required == '1' || $fields[$i]->attribute_required == true))
                    {
                        $fields[$i]->attribute_required = "required";
                        
                    }
                    if (isset($fields[$i]->attribute_readonly) && ($fields[$i]->attribute_readonly == "readonly" || $fields[$i]->attribute_readonly == "1" || $fields[$i]->attribute_readonly == true))
                    {
                        $fields[$i]->attribute_readonly == "readonly";
                    }

                    //delete defaultvalue array
                    unset($fields[$i]->defaultvalue);

                    //set some helpful parameters
                    //we have an HTMLEditor and have to check that it is not empty
                    if ($fields[$i]->typefield == 'textarea' && isset($fields[$i]->attribute_required) && $fields[$i]->attribute_required == 'required' && isset($fields[$i]->HTMLEditor) && $fields[$i]->HTMLEditor == '1' && (!(isset($fields[$i]->attribute_readonly)) || $fields[$i]->attribute_readonly != "readonly" )) 
                    {
                            $fields[$i]->textareaRequired = true;
                    }
                    //We have an HTMLEditor
                    if ($fields[$i]->typefield == 'textarea'  && isset($fields[$i]->HTMLEditor) && $fields[$i]->HTMLEditor == '1' && (!(isset($fields[$i]->attribute_readonly)) || ($fields[$i]->attribute_readonly != "readonly")))
                    {
                            $fields[$i]->hasHTMLEditor = true;
                    }

                    //Field is button
                    if ($fields[$i]->typefield == "submit" || $fields[$i]->typefield == "reset" || $fields[$i]->typefield == "image")
                    {
                            $fields[$i]->isButton = true;
                    }
		}
                return $fields;
         }
         
         /**
	 * Method to set $_POST values as default value of form field.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
         public function setDefaultsFromPost (&$fields) {
            $n=count($fields );
            for ($i=0; $i < $n; $i++)
            { 
                 //Form was send, but php validation failed. We use submitted values from post and show them as field values
                 //In truth this will not work for selects but they are handle seperatly anyway
                if (isset($fields[$i]->postValue))
                {
                    if (isset($fields[$i]->hasHTMLEditor) && $fields[$i]->hasHTMLEditor == true) 
                    {
                        $fields[$i]->initvalue = $fields[$i]->postValue;
                    }
                    else if (isset($fields[$i]->typefield) && $fields[$i]->typefield == "textarea")
                    {
                        $fields[$i]->initvalue = $fields[$i]->postValue;
                    }
                    else
                    {                    
                        $fields[$i]->attribute_value = $fields[$i]->postValue;
                    }
                }
            }
         }
         
         /**
	 * Method to get user input of form field from $_POST and attach it to field. Only if $_POST of field is set.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
         public function getDefaultsFromPost (&$fields){
            $n=count($fields );
            for ($i=0; $i < $n; $i++)
            { 
                 //Form was send, but php validation failed. We use submitted values from post and show them as field values
                 //In truth this will not work for selects but they are handle seperatly anyway
                if (isset($_POST[$fields[$i]->name]))
                {
                    if (isset($fields[$i]->hasHTMLEditor) && $fields[$i]->hasHTMLEditor == true) 
                    {
                        $fields[$i]->postValue = $this->input->post->get($fields[$i]->name, '', 'RAW');
                    }
                    else if ($fields[$i]->typefield == "select")
                    {
                        $fields[$i]->postValue = $this->input->post->get($fields[$i]->name, '', 'ARRAY');
                    }
                    else
                    {
                        $fields[$i]->postValue = $this->input->post->get($fields[$i]->name, '', 'STRING');                      
                    }
                }
            }
         }
	
	
	/**
	 * Method to add 1 to hits
	 * @return void
	 */
	function addHits()
	{
		$dba	= JFactory::getDbo();
		$visform = $this->getForm();
		
		if (isset($visform->id))
		{
			$query = " update #__visforms set hits = ".($visform->hits + 1). " where id = ".$visform->id;

			$dba->SetQuery($query);		
			$dba->execute();
		}
	}
	
	/**
	* Method to validate Post Data
	* @param  array $post Postdata
	* @param  array $fields Array of objects containing the data from the database
	* @return boolean true if valide
	*/
	
	function validatePostData() {
	
            $valid = true;
            $return = true;
            //to test correct display of allready submitted input after failed form validation (via php)
            //$valid = false;
            //$return = false;
            $fields = $this->getFields();
            $n = count($fields);
            for ($i=0; $i<$n; $i++) {
                $field=$fields[$i];
                //we validate form data, we only want values from fields that are submitted by $_POST
                if (isset($_POST[$field->name]))
                {
                     if (isset($field->hasHTMLEditor) && $field->hasHTMLEditor == true) 
                    {
                       $postValue = $field->postValue = $this->input->post->get($field->name, '', 'RAW');
                    }
                    else if ($field->typefield == "select")
                    {
                        $postValue = $field->postValue = $this->input->post->get($field->name, '', 'ARRAY');
                    }
                    else
                    {
                        $postValue = $field->postValue = $this->input->post->get($field->name, '', 'STRING');                      
                    }
                }

                if (isset($postValue))
                {
                    //Check that mandatory fields are not empty
                    if (isset($field->attribute_required) && $field->attribute_required == 'required') {
                            //if radios, selects and checkboxes are submitted by post, they have a value selected. Therefore they are not empty (if mandatory)
                            //these form field types can be excluded from check
                            if (($field->typefield !== 'select') && ($field->typefield !== 'checkbox') && ($field->typefield !== 'radio') && ($field->typefield !== "file")) {
                                    $regex = '/[^.*]/';
                                    $valid = $this->validateField ($field->label, $postValue, $regex, 'COM_VISFORMS_FIELD_REQUIRED');
                                    if (($return === true) && ($valid === false)) {
                                            $return = false;
                                    }
                            }
                            if (($field->typefield == 'select') && ($postValue[0] == ""))
                            {
                                $this->setError(JText::sprintf('COM_VISFORMS_FIELD_REQUIRED_RADIO_SELECT', $field->label));
                                if ($return === true) {
                                            $return = false;
                                    }
                            }
                    }

                    // Check that special text-fields that are not empty have right format
                    if (!($postValue == "")) { 
                            switch ($field->typefield) {
                                    case 'number' :
                                    //Check for numbers
                                        if (isset($field->validate_digits) && $field->validate_digits == true) 
                                        {
                                            $regex = '/^\d+$/';
                                            $valid = $this->validateField ($field->label, $postValue, $regex , 'COM_VISFORMS_FIELD_NOT_A_DIGIT');
                                        }
                                        else
                                        {
                                            $regex = '/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/';
                                             $valid = $this->validateField ($field->label, $postValue, $regex , 'COM_VISFORMS_FIELD_NOT_A_NUMBER');
                                        }
                                            if (($return === true) && ($valid === false)) {
                                                    $return = false;
                                            }
                                            break;
                                    case 'email':
                                            // Check for e-mail
                                            $regex = '/^([a-zA-Z0-9_\.\-\+%])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
                                            //use regex from validator plugin
                                            //$regex = "/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i";
                                            $valid = $this->validateField ($field->label, $postValue, $regex, 'COM_VISFORMS_FIELD_EMAIL_NOT_VALIDE');
                                            if (($return === true) && ($valid === false)) {
                                                    $return = false;
                                            }
                                            break;
                                    case 'url' :
                                            // Check for url
                                            $regex = '/^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i';
                                            //use regex from validation plugin
                                            //$regex = "/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i";
                                            $valid = $this->validateField ($field->label, $postValue, $regex, 'COM_VISFORMS_FIELD_NO_VALIDE_URL');
                                            if (($return === true) && ($valid === false)) {
                                                    $return = false;
                                            }
                                            break;
                                    case 'date' :
                                            //check for right date format
                                            $dformat = explode(";", $field->format);
                                            if (strpos($postValue, '.') > 0 ) 
                                            {
                                                    $valid = $this->validateDate($field->label, $postValue, '.', 'COM_VISFORMS_FIELD_DATE_FORMAT', $dformat[0]);
                                                    if ($valid === false) {
                                                            $_POST[$field->name] = "";
                                                    }
                                                    if (($return === true) && ($valid === false)) {
                                                            $return = false;
                                                    }
                                            }
                                            if (strpos($postValue, '/') > 0 ) 
                                            {
                                                    $valid = $this->validateDate($field->label, $postValue, '/', 'COM_VISFORMS_FIELD_DATE_FORMAT', $dformat[0]);
                                                    if ($valid === false) {
                                                            $_POST[$field->name] = "";
                                                    }
                                                    if (($return === true) && ($valid === false)) {
                                                            $return = false;
                                                    }
                                            }							
                                            if (strpos($postValue, '-') > 0 ) 
                                            {
                                                    $valid = $this->validateDate($field->label, $postValue, '-', 'COM_VISFORMS_FIELD_DATE_FORMAT', $dformat[0]);
                                                    if ($valid === false) {
                                                            $_POST[$field->name] = "";
                                                    }
                                                    if (($return === true) && ($valid === false)) {
                                                            $return = false;
                                                    }
                                            }
                                            if (strlen($postValue) != 10)
                                            {
                                                    $this->setError(JText::sprintf('COM_VISFORMS_FIELD_DATE_FORMAT', $field->label, $dformat[0]));
                                                    $return = false;
                                                    break;
                                            }
                                    break;
                                    default : 
                                    break;
                            }
                    }

                    //Check for right length of input
                    if (!($postValue == "")) 
                    {
                        $min = "0";
                        if ((isset($field->attribute_min)) && ($field->attribute_min != '')) 
                        {
                            $min = $field->attribute_min;
                            if ($min != '0') {

                                // Check min number value
                                if (is_numeric($postValue)) {
                                    $number = floatval($postValue);
                                    $minnumber = floatval($min);
                                    if ($number < $minnumber) {
                                            $this->setError(JText::sprintf('COM_VISFORMS_FIELD_MIN_VALUE', $field->label, $minnumber));
                                            $valid = false;
                                    }
                                    if (($return === true) && ($valid === false)) {
                                            $return = false;
                                    }
                                }								
                            }
                        }
                        if (isset($field->validate_minlength) && $field->validate_minlength != "")
                        {
                            $min = $field->validate_minlength;

                            if ($min != '0') 
                            {
                                // Check minlength
                                $regex = "/^[\\s\\S]{" . $min . ",}$/";
                                $valid = $this->validateField ($field->label, $postValue, $regex, 'COM_VISFORMS_FIELD_MIN_LENGTH', $min);
                                if (($return === true) && ($valid === false)) {
                                        $return = false;
                                }
                            }
                        }

                        $max = "-1";
                        if (isset($field->attribute_max) && $field->attribute_max != '') 
                        {
                            $max = $field->attribute_max;
                            if ($max != '-1') 
                            {

                                //check max number value
                                if (is_numeric($postValue)) {
                                        $number = floatval($postValue);
                                        $maxnumber = floatval($max);
                                    if ($number > $maxnumber) {
                                    $valid = false;
                                            $this->setError(JText::sprintf('COM_VISFORMS_FIELD_MAX_VALUE', $field->label, $maxnumber));
                                    }

                                    if (($return === true) && ($valid === false)) {
                                            $return = false;
                                    }
                                }
                            }
                        }
                        if (isset($field->attribute_maxlength) && $field->attribute_maxlength != "")
                        {
                            $max = $field->attribute_maxlength;
                            if ($max != '-1') 
                            {
                                // Check max-length
                                $regex = "/^[\\s\\S]{0," . $max . "}$/";
                                $valid = $this->validateField ($field->label, $postValue, $regex, 'COM_VISFORMS_FIELD_MAX_LENGTH', $max);
                                if (($return === true) && ($valid === false)) {
                                        $return = false;
                                }
                            }

                        }
                    }				
                } 
                else 
                {
                        //check for select, radios and checkboxes that must have a value selected
                        if (isset($field->attribute_required) && $field->attribute_required == 'required') {
                                switch($field->typefield) {
                                        case "select" :
                                                $this->setError(JText::sprintf('COM_VISFORMS_FIELD_REQUIRED_RADIO_SELECT', $field->label));
                                                $valid = false;
                                                break;
                                        case "radio" :
                                                $this->setError(JText::sprintf('COM_VISFORMS_FIELD_REQUIRED_RADIO_SELECT', $field->label));
                                                $valid = false;
                                                break;
                                        case "checkbox" :
                                                $this->setError(JText::sprintf('COM_VISFORMS_FIELD_REQUIRED_CHECKBOX', $field->label));
                                                $valid = false;
                                                break;
                                                //We have to look in $_FILES to see, if a file is selected
                                        case "file":
                                                if ((isset($_FILES[$field->name]['name']) === false) || (isset($_FILES[$field->name]['name']) && $_FILES[$field->name]['name'] ==''))
                                                {
                                                        $this->setError(JText::sprintf('COM_VISFORMS_FIELD_REQUIRED_UPLOAD', $field->label));
                                                        $valid = false;
                                                        break;
                                                }
                                                else
                                                {
                                                        //A file is selected, that is o.k. from mandantory point of view
                                                        break;
                                                }
                                        default : 
                                                break;
                                }

                                if (($return === true) && ($valid === false)) {
                                        $return = false;
                                }
                        } 
                }
            }
	return $return;
	}
	
	/* regex's used in formcheck.js
	required : /[^.*]/,
	alpha : /^[a-z ._-]+$/i,
	alphanum : /^[a-z0-9 ._-]+$/i,
	digit : /^[-+]?[0-9]+$/,
	nodigit : /^[^0-9]+$/,
	number : /^[-+]?\d*\.?\d+$/,
	email : /^([a-zA-Z0-9_\.\-\+%])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
	image : /.(jpg|jpeg|png|gif|bmp)$/i,
	phone : /^\+{0,1}[0-9 \(\)\.\-]+$/,
	phone_inter : /^\+{0,1}[0-9 \(\)\.\-]+$/,
	url : /^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i
	minlength : RegExp("^.{0,"+ ruleArgs[0] +"}$");
	*/
	
	/*
	* method to validate user input of field
	*
	* @param string $name Form field label
	* @param string $value user input from $_POST
	* @param string $regex regex to validate the value against
	* @param string $text error message
	* @param string $text length additional sprintf parameter, used for number validation
	*
	* @return boolean (true if input is valid)
	* @since Joomla 1.6
	*/	
	function validateField ($name, $value, $regex="", $text="", $length="") {
		if (!(preg_match($regex, $value) == true)) {
			$this->setError(JText::sprintf($text, $name, $length));
			return false;
		} else {
			return true;
		}
	}
	
	/*
	* method to validate format of date input
	* @param string $name Form field label
	* @param string $dateString date string from $_POST
	* @param string $delimiter delimiter in date string
	* @param string $text error message
	* @param string $dforamt date format string
	*
	* @return boolean (true if input is valid)
	* @since Joomla 1.6
	*/
	
	function validateDate ($name, $dateString, $delimiter, $text, $dformat) {
		(int)$day = 0;
		(int)$month = 0;
		(int)$year = 0;
		$checkdate = true;
		$date = explode($delimiter, $dateString);
		if (count($date) !== 3) 
		{
			$checkdate = false;
		} 
		else if (!(strpos($dformat, $delimiter) > 0))
		{
			$checkdate = false;
		}
		else
		{
			switch ($delimiter) {
				case  '.' :
				$day = (isset($date[0]) === true) ? (int)$date[0] : (int)0;
				$month = (isset($date[1]) === true) ? (int)$date[1] : (int)0;
				$year = (isset($date[2]) === true) ? (int)$date[2] : (int)0;
				break;
				case  '/' :
				$month = (isset($date[0]) === true) ? (int)$date[0] : (int)0;
				$day = (isset($date[1]) === true) ? (int)$date[1] : (int)0;
				$year = (isset($date[2]) === true) ? (int)$date[2] : (int)0;
				break;
				case  '-' :
				$year = (isset($date[0]) === true) ? (int)$date[0] : (int)0;
				$month = (isset($date[1]) === true) ? (int)$date[1] : (int)0;
				$day = (isset($date[2]) === true) ? (int)$date[2] : (int)0;
				break;
				default : 
				break;
			}
			$checkdate = checkdate($month, $day, $year);
		}
		if ($checkdate === false) {
			$this->setError(JText::sprintf($text, $name, $dformat));
			//ToDo Rausfinden wie wert entfernt werden kann und if SChleife für / - wiederholen wenn es funktionier
			return false;
		}
	}
	
	/**
	 * Method to save data user input
	 *
	 * @paran array $post user input from $_POST
	 * @return void
	 * @since Joomla 1.6
	 */
	function saveData()
	{		
		//Form and Field structure and info from db
		$visform = $this->getForm();
        $fields = $this->getFields();
        $this->getDefaultsFromPost ($fields);
        $visform->fields = $fields;
        $folder		= $visform->uploadpath;
        
        //time zone
        $config = JFactory::getConfig();
        $offset = $config->get('offset', 'UTC');
        if ($offset)
        {
            date_default_timezone_set($offset);
        }
                
		if ($this->uploadFiles($visform) === false)
        {
            return false;
        }
		
		if ($visform->saveresult == 1) 
		{	
            if ($this->storeData($visform) === false)
            {
                return false;
            }						
		}		
		
		/* ************************* */
		/*     Send Email Result     */
		/* ************************* */
		if ($visform->emailresult == 1) 
		{
			$this->sendResultMail($visform);			
		}		
		
		/* ************************** */
		/*     Send Email Receipt     */
		/* ************************** */
		if ($visform->emailreceipt == 1) 
		{	
            $this->sendReceiptMail($visform);
		}		
		return true;
	}
	
	
	/**
	  * Method to retrieve menu params
	  *
	  * @return array Array of objects containing the params from active menu
	  * @since Joomla 1.6
	  */
	
	function getMenuparams () 
	{
		$app = JFactory::getApplication();
		$menu_params = $app->getParams();
		$this->setState('menu_params', $menu_params);		
		return $menu_params;
	}
	
	/**
	 * Checks if the file can be uploaded
	 *
	 * @param array File information
	 * @param string An error message to be returned
	 *
	 * @return boolean
	 * @since Joomla 1.6
	 */
	public function canUpload($file, &$err, $maxfilesize, $allowedextensions)
	{

		if (empty($file['name'])) {
			$err = 'COM_VISFORMS_ERROR_UPLOAD_INPUT';
			return false;
		}

		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$err = 'COM_VISFORMS_ERROR_WARNFILENAME';
			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));
		$allowable = explode(',', $allowedextensions);		
		if ($format == '' || $format == false || (!in_array($format, $allowable)))
		{
			$err = 'COM_VISFORMS_ERROR_WARNFILETYPE';
			return false;
		}

		$maxSize = (int) ($maxfilesize  * 1024);
		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$err = 'COM_VISFORMS_ERROR_WARNFILETOOLARGE';
			return false;
		}

		$imginfo = null;

		$images = explode(',', "bmp,gif,jpg,jpeg,png");
		if (in_array($format, $images)) { // if its an image run it through getimagesize
			// if tmp_name is empty, then the file was bigger than the PHP limit
			if (!empty($file['tmp_name'])) {
				if (($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
					$err = 'COM_VISFORMS_ERROR_WARNINVALID_IMG';
					return false;
				}
			} else {
				$err = 'COM_VISFORMS_ERROR_WARNFILETOOLARGE';
				return false;
			}
		}

		$xss_check =  JFile::read($file['tmp_name'], false, 256);
		$html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if (stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$err = 'COM_VISFORMS_ERROR_WARNIEXSS';
				return false;
			}
		}
		return true;
	}
        
        /**
	 * Deletes linebreaks in MySQL Database
	 *
	 * @param id formId Id if submitted form
	 * @param array fields Formfields
	 *
	 * @return boolean
	 * @since Joomla 1.6
	 */
        public function cleanLineBreak ($formId, $fields)
        {
            $db = JFactory::getDbo();
            $id = $db->insertid();
            $query = $db->getQuery(true);
            $updatefields = array();
            for ($i = 0; $i<count($fields); $i++)
            {
                $updatefields[] = $db->quoteName('F' . $fields[$i]->id) . ' = replace (F' . $fields[$i]->id . ', CHAR(13,10), \' \')';
            }
            $conditions = array( $db->quoteName('id') . ' = ' .$id);
            $query->update($db->quoteName('#__visforms_' . $formId))->set($updatefields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
        }
        
        /**
         * Upload files
         * 
         * @param object $visform Form Object with attached field information
         */
        
        private function uploadFiles(&$visform)
        {
            // set some parameters
            $maxfilesize = $visform->maxfilesize;
            $allowedextensions = $visform->allowedextensions;
            //upload files
            $n=count($visform->fields );
            for ($i=0; $i < $n; $i++)
            {
                $field = $visform->fields[$i];
                
                //Request has an fileupload with values
                if ($field->typefield == 'file' && isset($_FILES[$field->name]['name']) && $_FILES[$field->name]['name'] !='')
                {
                    $file = $this->input->files->get($field->name);
                    $folder		= $visform->uploadpath;	
                    if (!file_exists ($folder))
                    {
                        JError::raiseWarning(100, JText::_('COM_VISFORMS_UPLOAD_DIRECTORY_DOES_NOT_EXIST'));
                        return false;
                    }
                    else
                    {                     

                        // Set FTP credentials, if given
                        JClientHelper::setCredentialsFromRequest('ftp');

                        // Make the filename safe
                        $file['name_org'] = $file['name'];
                        $file['name']	= JFile::makeSafe($file['name']);

                        // Check upload conditions
                        $err = null;
                        if (!$this->canUpload($file, $err, $maxfilesize, $allowedextensions))
                        {
                                // The file can't be upload
                                JError::raiseNotice(100, JText::sprintf($err, $file['name_org'], $maxfilesize));
                                return false;
                        }
                        else
                        {
                            //get a unique id to rename uploadfiles
                            $fileuid = uniqid('');

                            //rename file
                            $pathInf = pathinfo($file['name']);
                            $ext = $pathInf['extension'];
                            $file['new_name'] = basename($file['name'],".".$ext) . "_" . $fileuid . "." . $ext;
                            $file['new_name'] = strtolower($file['new_name']);

                            //get complete upload path with filename of renamed file
                            $filepath = JPath::clean(JPATH_SITE . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $file['new_name']);
                            $file['filepath'] = $filepath;
                            $file['filelink'] = JUri::base() . $folder . '/' . $file['new_name'];


                            //try to upload file
                            if (JFile::exists($file['filepath']))
                            {
                                // File exists
                                JError::raiseWarning(100, JText::sprintf('COM_VISFORMS_ERROR_FILE_EXISTS', $file['name_org']));
                                return false;
                            }
                            else
                            {
                                if (!JFile::upload($file['tmp_name'], $file['filepath']))
                                {
                                        // Error in upload
                                        JError::raiseWarning(100, JText::sprintf('COM_VISFORMS_ERROR_UNABLE_TO_UPLOAD_FILE', $file['name_org']));
                                        return false;
                                }
                            }
                        }
                    }
                    foreach ($file as $name => $value)
                    {
                        $visform->fields[$i]->file[$name] = $value;
                    }
                }
            }
            return true;
        }
        
         /**
         * store data in db
         * 
         * @param object $visform Form Object with attached field information
         */
        private function storeData($visform)
        {
            $folder	= $visform->uploadpath;	
            $dba	= JFactory::getDbo();
			$query = ' insert into #__visforms_'.$visform->id."(" ;
			
			$n=count($visform->fields );
			for ($i=0; $i < $n; $i++)
			{	
				$field = $visform->fields[$i];
				if (!(isset($field->isButton) && $field->isButton === true) && $field->typefield != 'fieldsep')
				{
					$query = $query."F".$field->id.",";
				}
			}

			$query = $query."created,ipaddress,published,articleid) values(";
     
			for ($i=0; $i < $n; $i++)
			{	
				$field = $visform->fields[$i];
				if (!(isset($field->isButton) && $field->isButton === true) && $field->typefield != 'fieldsep')
				{				
					if ($field->typefield == 'file' && isset($_FILES[$field->name]['name']) && $_FILES[$field->name]['name'] !='' )
					{ 
                        //save folder and filename
                        $data = new stdClass();
                        $data->folder = $folder;
                        $data->file = $field->file['new_name'];
                        $registry = new JRegistry($data);
                        $fieldValue = $registry->toString();

					} else if (isset($field->postValue))
					{
						$fieldValue = $field->postValue;
					} 
					else 
					{
						$fieldValue = '';
					}
					
					if (is_array ($fieldValue))	
					{
						$arrayVal = "";
						foreach($fieldValue as $selectValue){					
							$arrayVal = $arrayVal.$selectValue.",";
						}
						if (strcasecmp(substr($arrayVal, strlen($arrayVal) - strlen(",")),",") == 0)
						{
							$arrayVal = substr($arrayVal, 0,strlen($arrayVal) - strlen(","));
						}
						$fieldValue = $arrayVal;
					}
					
					$query = $query."'".addslashes($fieldValue)."',";
				}
			}
			
			$autopublish = "0";
			if($visform->autopublish == 1) 
			{
				$autopublish = "1";
			}
			
			$query = $query."'".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."',".$autopublish.",";

			$articleid = $this->input->get('articleid');
			if (isset($articleid) && ($articleid != ''))
			{
				$query = $query."'".$articleid."'";
			} else {
				$query = $query."null";
			}
			
			$query = $query.")";
			$dba->setQuery($query);
			
			if (!$dba->execute()) 
			{
				$errMsg = JText::_( 'COM_VISFORMS_PROBLEM_WITH' )." (".$query.")"."<br />". $dba->getErrorMsg();
                JError::raiseWarning($errMsg);

					return false;
				
			}
                        
            //Linebreaks confound data structure on export to excels. So we delete them in Database 
            $this->cleanLineBreak ($visform->id, $visform->fields);
            return true;
        }
        
        /**
         * Send Receipt Mail
         * @param object $visform Form Object with attached field information
         */
        private function sendReceiptMail($visform)
        {
            //we can only send a mail, if the form has a field of type email, that contains an email
            $isSendMail = false;
			$emailReceiptTo = '';
			
			$mail = JFactory::getMailer();
			$mail->CharSet = "utf-8";
            
            //Do some replacements in email text
            $fixedLinks = JHTMLVisforms::fixLinksInMail($visform->emailreceipttext);
			$mailBody = JHTMLVisforms::replacePlaceholder($visform, $fixedLinks);
			if ($visform->emailreceiptincformtitle == 1)
            {
                $mailBody = $mailBody."<br/>" .JText::_('COM_VISFORMS_FORM') . " : ".$visform->title."<br />";
            }
            if ($visform->emailreceiptinccreated == 1)
            {
                $mailBody = $mailBody.JText::_( 'COM_VISFORMS_REGISTERED_AT' )." ".date("Y-m-d H:i:s")."<br />";
            }

			$n=count($visform->fields );
            //Do we have an e-mail field with value? Then get to mail address to which to send the mail to
			for ($i=0; $i < $n; $i++)
			{	
				$field = $visform->fields[$i];
				
				if ($field->typefield == 'email')
				{					
                    if (isset($field->postValue))
                    {
                        $isSendMail = true;
                        $emailReceiptTo = $field->postValue;
                    }
				}
			}
			
            //Include user inputs if parameter is set to true
			if ($visform->emailreceiptincfield == 1) {				
				for ($i=0; $i < $n; $i++)
				{	
					$field = $visform->fields[$i];
					if (!(isset($field->isButton) && $field->isButton === true) && $field->typefield != 'fieldsep')
					{
							
						if ($field->typefield == 'file' && isset($_FILES[$field->name]['name']) && $_FILES[$field->name]['name'] !='' )
						{
                            if (isset($visform->emailrecipientincfilepath) && ($visform->emailrecipientincfilepath == true) && isset($field->file['filelink']))
                            {
                                $fieldValue = $field->file['filelink'];
                            }
                            else
                            {
                                $fieldValue = $field->file['name_org'];
                            }
						} 
						else if (isset($field->postValue))
						{
							$fieldValue = $field->postValue;
						}
						else 
						{
							$fieldValue = '';
						}
							
						if (is_array ($fieldValue))	
						{
							$arrayVal = "";
							foreach($fieldValue as $selectValue)
							{					
								$arrayVal = $arrayVal.$selectValue.",";
							}
							if (strcasecmp(substr($arrayVal, strlen($arrayVal) - strlen(",")),",") == 0)
							{
								$arrayVal = substr($arrayVal, 0,strlen($arrayVal) - strlen(","));
							}
							$fieldValue = $arrayVal;
						}
						
						$mailBody = $mailBody.$field->label . " : " . $fieldValue . "<br />";
					}
					
				}	
				
				$mailBody = $mailBody.JText::_( 'COM_VISFORMS_IP_ADDRESS' ) . " : " . $_SERVER['REMOTE_ADDR'] . "<br />";
				
			}
			
			//Attach filed to email
			if ($visform->emailreceiptincfile == 1)
			{
				for ($i=0; $i < $n; $i++) {
					$field = $visform->fields[$i];
					if ($field->typefield == 'file' && isset($_FILES[$field->name]['name']) && $_FILES[$field->name]['name'] !='' )
					{
						if ($field->file['filepath'] != '') 
						{
							$mail->addAttachment($field->file['filepath']);
						}
					} 
				}
			}
			
			//send the mail
			if (strcmp($emailReceiptTo,"") != 0 && $isSendMail == true)
			{
				$mail->addRecipient($emailReceiptTo);
						
				$mail->setSender( array( $visform->emailfrom, $visform->emailfromname ) );
				$mail->setSubject( $visform->emailreceiptsubject );
				$mail->setBody( $mailBody );
		
				$mail->IsHTML (true);
                $sent = $mail->Send();
			}
        }
        
		/**
         * Send Result Mail
         */
        private function sendResultMail($visform)
        {
            $mail = JFactory::getMailer();
			$mail->CharSet = "utf-8";
		
			$mailBody = JText::_('COM_VISFORMS_FORM') . " : ".$visform->title." [".$visform->name."]<br />";
			$mailBody = $mailBody.JText::_('COM_VISFORMS_REGISTERED_AT'). " ".date("Y-m-d H:i:s")."<br /><br />";
			$emailSender = "";

			$n=count($visform->fields );
            //Add user inputs to mail
			for ($i=0; $i < $n; $i++)
			{	
				$field = $visform->fields[$i];
				
				if ($field->typefield == 'email')
				{					
					if (isset($field->postValue))
					{
						$emailSender = $field->postValue;
					}
				}
			
				if (!(isset($field->isButton) && $field->isButton === true) && $field->typefield != 'fieldsep')
				{
					if ($field->typefield == 'file' && isset($_FILES[$field->name]['name']) && $_FILES[$field->name]['name'] !='' )
					{
                        if (isset($field->file['filelink']))
                        {
                            $fieldValue = $field->file['filelink'];
                        }
                        else
                        {
                            $fieldValue = $field->file['filepath'];
                        }
						//Attach file to email
						if ($field->file['filepath'] != "" && $visform->emailresultincfile == "1") 
						{
							$mail->addAttachment($field->file['filepath']);
						}

					}
					else if (isset($field->postValue))
					{
						$fieldValue = $field->postValue;
					} 
					else 
					{
						$fieldValue = '';
					}
					
					if (is_array ($fieldValue))	
					{
						$arrayVal = "";
						foreach($fieldValue as $selectValue){					
							$arrayVal = $arrayVal.$selectValue.",";
						}
						if (strcasecmp(substr($arrayVal, strlen($arrayVal) - strlen(",")),",") == 0)
						{
							$arrayVal = substr($arrayVal, 0,strlen($arrayVal) - strlen(","));
						}
						$fieldValue = $arrayVal;
					}
					
					if ($field->typefield == 'email') 
					{
						$fieldValue = '<a href="mailto:'.$fieldValue.'">'.$fieldValue.'</a>';
					} 
				
					$mailBody = $mailBody.$field->label . " : " . $fieldValue . "<br />";
				}
			}
			
			$mailBody = $mailBody.JText::_( 'COM_VISFORMS_IP_ADDRESS' ) . " : " . $_SERVER['REMOTE_ADDR'] . "<br />";
			
			$articleid = $this->input->get('articleid');
			if (isset($articleid) && ($articleid != ''))
			{
				$mailBody = $mailBody.JText::_( 'COM_VISFORMS_ARTICLE_ID' ) . " : " . $articleid . "<br />";
			}
			
			if (strcmp($visform->emailto,"") != 0)
			{
				$mail->addRecipient( explode(",", $visform->emailto) );
			}
			if (strcmp($visform->emailcc,"") != 0)
			{
				$mail->addCC( explode(",", $visform->emailcc) );
			}
			if (strcmp($visform->emailbcc,"") != 0)
			{
				$mail->addBCC( explode(",", $visform->emailbcc) );
			}
			
			if ($emailSender == "")
			{
			$emailSender = $visform->emailfrom;
			}
			
			$mail->setSender( array( $emailSender, $visform->emailfromname ) );
			$mail->setSubject( $visform->subject );
			$mail->setBody( $mailBody );

			$mail->IsHTML (true);			
			$sent = $mail->Send();
        }
}
