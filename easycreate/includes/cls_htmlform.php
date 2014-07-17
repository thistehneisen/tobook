<?php 
// +----------------------------------------------------------------------+
// | File name : CMS	                                          		  |
// |(AUTOMATED CUSTOM CMS LOGIC)					 	  |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              		  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ï¿½ 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Htmlform {
    public $method;
    public $action;
    public $formName;
    public $handleFile=false;
    public $class;
    public $id;
    public $preaction;
    public $formElements = array();
    public $form_title;
    public $form_error_message;


    public function renderForm() {

        if($this->handleFile)
            $enctype="enctype=multipart/form-data";
        
        $formData   =   '<form name="'.$this->name.'" id="jqCmsForm" method="'.$this->method.'" action="'.$this->action.'" class="form-horizontal'.$this->class.'" '.$enctype.' >';
        if($this->form_title) $formData .= '<span class="legend">'.$this->form_title.'</span>';
       // if($this->form_error_message)   '    <div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">×</button>  <h4>Error!</h4>'.$this->form_error_message.' </div>';
        for($loop=0;$loop<count($this->formElements);$loop++) {

            //pre html
            $formData   .=  $this->formElements[$loop]->prehtml;

            $formData   .=   '<div class="control-group">';

            //element
            if($this->formElements[$loop]->label) {
                $formData   .=   $this->addLabel($this->formElements[$loop]);
            }
            if($this->formElements[$loop]->type=="textbox") {
                $formData   .=   $this->addTextbox($this->formElements[$loop]);
            }else if($this->formElements[$loop]->type=="hidden")
                $formData   .=   $this->addHiddenField($this->formElements[$loop]);
            else if($this->formElements[$loop]->type=="textarea") {
                $formData   .=   $this->addTextarea($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="htmlEditor") {
                $formData   .= '<div class="controls">'. $this->addHtmlEditor($this->formElements[$loop]).'</div>';
            }
            else if($this->formElements[$loop]->type=="select") {
                $formData   .=   $this->addSelectBox($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="checkbox") {
                $formData   .=   $this->addCheckbox($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="radio") {
                $formData   .=   $this->addRadioButton($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="password") {
                $formData   .=   $this->addPasswordField($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="file") {
                $formData   .=   $this->addFileField($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="datepicker") {
                $formData   .=   $this->addDatepicker($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="autocomplete") {

                $formData   .=   $this->addAutoCompleteField($this->formElements[$loop]);
            }
            else if($this->formElements[$loop]->type=="disabled") {

                $formData   .=   $this->addDisabledTextboxField($this->formElements[$loop]);
            }
            $formData   .= '</div>';
            //post html
            $formData   .=   $this->formElements[$loop]->posthtml;

        }
        $formData   .=    $this->addSubmitButton($this->formElements[$loop]);
        $formData   .=    '</form>';
//      /  echo $formData;
return '<div class="feedbackform">'.$formData.'</div>';

    }

    public function getElements($sectionConfig) {

        foreach($sectionConfig->columns as  $key => $val) {

            if($val->editoptions) {

                $objFormElement = new Formelement();
                $objForm->formElements[]        =   $objFormElement->addElement($key,$val);

            }
        }
        echopre($objForm->formElements);
    }

    public function addElement(Formelement $objFormElement) {
        array_push($this->formElements,$objFormElement);
    }

    public function addLabel($labelElement) {

       // return '<label class="control-label" for="'.$labelElement->id.'">'.$labelElement->label.'</label>';
         return '<label class="control-label" for="'.$labelElement->id.'">'.$labelElement->label.'</label>';

    }
    public function addTextbox($textboxElement) {

        if(in_array('required',$textboxElement->validations)) {
            $validationClass="required";
            $mandatory='<span class="mandatory">*</span>';
        }
        if($textboxElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$textboxElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        //return '<div class="controls"><input type="text" id="'.$textboxElement->id.'" name="'.$textboxElement->name.'" value="'.$textboxElement->value.'" class="'.$validationClass.'">'.$mandatory.$hint.'</div>';
       return ' <input type="text" id="'.$textboxElement->id.'" name="'.$textboxElement->name.'" value="'.$textboxElement->value.'" class="'.$validationClass.' text_feild">'.$mandatory.$hint ;
        
    }
    public function addPasswordField($textboxElement) {

        if(in_array('required',$textboxElement->validations)) {
            $validationClass="required";
            $mandatory='<span class="mandatory">*</span>';
        }

        return '<div class="controls"><input type="password" id="'.$textboxElement->id.'" name="'.$textboxElement->name.'" value="'.$textboxElement->value.'" class="'.$validationClass.'"></div>';

    }
    public function addHiddenField($hiddenElement) {


        return '<input type="hidden" id="'.$hiddenElement->id.'" name="'.$hiddenElement->name.'" value="'.$hiddenElement->value.'">';

    }
    public function addTextarea($textareaElement) {
        if(in_array('required',$textareaElement->validations)) {
            $validationClass="required";
            $mandatory='<span class="mandatory">*</span>';
        }
        if($textareaElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$textareaElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        // echo $mandatory;
        return '<div class="controls"><textarea id="'.$textareaElement->id.'" name="'.$textareaElement->name.'" class="'.$validationClass.'" rows="7" cols="20">'.$textareaElement->value.'</textarea>'.$mandatory.$hint.'</div>';

    }
    public function addHtmlEditor($textareaElement) {
        //TODO
        //echopre($textareaElement);
        if(in_array('required',$textareaElement->validations))
            $validationClass="required";
        //$string  =   '<div class="controls"><textarea id="FCKeditor1" name="'.$textareaElement->name.'" class="htmleditor '.$validationClass.'">'.$textareaElement->value.'</textarea></div>';

        //'<script type="text/javascript" src="'.BASE_URL.'/lib/FCKeditor/fckeditor.js"></script>'
        //$string  =   '<div class="controls"><textarea id="mytext" name="mytext" class="htmleditor '.$validationClass.'">'.$textareaElement->value.'</textarea></div>';
        include_once "public/fckeditor/fckeditor.php" ;
        $sBasePath                              = "public/fckeditor/";
        $oFCKeditor 				= new FCKeditor($textareaElement->name) ;
        $oFCKeditor->Id				= $textareaElement->name;
        $oFCKeditor->BasePath			=  $sBasePath;
        $oFCKeditor->Value			=  stripslashes(trim($textareaElement->value));
        $oFCKeditor->Width  			= '740' ;
        $oFCKeditor->Height			= '400' ;
        $oFCKeditor->Create() ;
        //return $string;

    }
    public function addCheckbox($checkboxElement) {

        if($checkboxElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$checkboxElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        if(in_array('required',$checkboxElement->validations)) {
            $validationClass="required";
            $mandatory='<span class="mandatory">*</span>';
        }
        if($checkboxElement->default=="yes" && $checkboxElement->value=="")
            $checkedFlag    =   "checked=checked";
        else if($checkboxElement->value==1)
            $checkedFlag    =   "checked=checked";
        else
            $checkedFlag    =   "";
        return '<div class="controls"><input type="checkbox" id="'.$checkboxElement->id.'" name="'.$checkboxElement->name.'" value="1" '.$checkedFlag.'>'.$mandatory.$hint.'</div>';

    }

    public function addRadioButton($radioButtonElement) {

        if($radioButtonElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$radioButtonElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        if(in_array('required',$radioButtonElement->validations)) {
            $validationClass="required";
            $mandatory='<span class="mandatory">*</span>';
        }
        if($radioButtonElement->default=="yes" && $radioButtonElement->value=="")
            $checkedFlag    =   "checked=checked";
        else if($radioButtonElement->value==1)
            $checkedFlag    =   "checked=checked";
        else
            $checkedFlag    =   "";
        return '<div class="controls"><input type="radio" id="'.$radioButtonElement->id.'" name="'.$radioButtonElement->name.'" value="1" '.$checkedFlag.'>'.$mandatory.$hint.'</div>';

    }

    public function addSelectBox($selectBoxtElement) {

        if($selectBoxtElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$selectBoxtElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        if(in_array('required',$selectBoxtElement->validations)) {
            $validationClass="required";
            $mandatory='<span class="mandatory">*</span>';
        }
        if($selectBoxtElement->sourceType=="function") {
            $functionName   =   $selectBoxtElement->source;
            $options        =   call_user_func($functionName);

        }
        else {
            $sourceArray        =   $selectBoxtElement->source;
            $loop    =   0;
            foreach($sourceArray as $key    => $val) {
                $options[$loop]->value  =  $key;
                $options[$loop]->text   =  $val;
                $loop++;
            }

        }

        Logger::info($options);
        $html   =   '<select class="'.$validationClass.'" id="'.$selectBoxtElement->id.'" name="'.$selectBoxtElement->name.'">';
        $html   .=   '<option value="">Select</option>';
        foreach($options as $value) {

            $html   .=    '<option value="'.$value->value.'" ';
            $selected = false;
            $selectedOption =   $this->getSelectedOption($options,$selectBoxtElement->value);

            if($value->value==$selectedOption)
                $html   .=    ' selected="selected"';
            $html   .=    '>'.$value->text;
            $html   .=    '</option>';
        }
        $html   .=    '</select>';
        return '<div class="controls">'.$html.$mandatory.$hint.'</div>';

    }
    public function getSelectedOption($options,$selecctedValue) {

        $selecctedValueArray=explode("{valSep}", $selecctedValue);
        if(count($selecctedValueArray)>1) {
            $selecctedValue=$selecctedValueArray[1];
            foreach($options as $value) {


                if($value->value==$selecctedValue)
                    return $selecctedValue;
            }
            return 0;
        }
        else
            return $selecctedValue;
    }
    public function addFileField($fileElement) {
        if($fileElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$fileElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        if(in_array('required',$fileElement->validations)) {
            $mandatory='<span class="mandatory">*</span>';
            $validationClass="required";
        }
        $fileString =   '<div class="controls"><input type="file" id="'.$fileElement->id.'" name="'.$fileElement->name.'" class="'.$validationClass.'">'.$mandatory.$hint;
        if($fileElement->value!="") {
            $fileName       =   Cms::getImageName($fileElement->value);
            $filePath       =   BASE_URL. 'project/files/'.$fileName;
            if(!file_exists('project/files/'.$fileName))
                $filePath  =   BASE_URL. 'project/files/'."noImagePlaceholder.JPG";
            $fileString     .=  '<ul class="thumbnails">
                                    <li class="span3">
                                        <a href="#" class="thumbnail">
                                            <img src="'.$filePath.'" width="'.$width.'px" height="'.$height.'px">
                                        </a>
                                    </li>
                                </ul>';
        }
        $fileString        .=  '</div>';
        return $fileString;

    }
    public function addDatepicker($dateElement) {
        //TODO
        if($dateElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$dateElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        if(in_array('required',$dateElement->validations)) {
            $mandatory='<span class="mandatory">*</span>';
            $validationClass="required";
        }
        if($dateElement->type=="datepicker") {
            $date  =   $dateElement->value;
            if( $dateElement->value=="")
                $date = "";
            else {
                if($dateElement->dbFormat=="date") {
                    $timeArray=explode("-",$dateElement->value);

                    $time=mktime(0,0,0,$timeArray[1],$timeArray[2],$timeArray[0]);
                    $date= date($dateElement->displayFormat,$time);

                }
                if($dateElement->dbFormat=="time") {

                    $date = date($dateElement->displayFormat,$dateElement->value);
                }
                if($dateElement->dbFormat=="timestamp") {
                    list($date1,$time)=explode(" ", $dateElement->value);
                    list($year,$month,$day)=explode("-",$date1);
                    list($hour,$minute,$second)=explode(":",$time);

                    $time=mktime($hour,$minute,$second,$month,$day,$year);
                    $date= date($dateElement->displayFormat,$time);

                }
                if($dateElement->dbFormat=="datetime") {

                    list($date1,$time)=explode(" ",  $dateElement->value);
                    list($year,$month,$day)=explode("-",$date1);
                    list($hour,$minute,$second)=explode(":",$time);

                    $time=mktime($hour,$minute,$second,$month,$day,$year);
                    $date= date($dateElement->displayFormat,$time);

                }
            }
        }
        else
            $date  =   $dateElement->value;

        $dateString     =   '<div class="controls"><input name="'.$dateElement->name.'" id="'.$dateElement->id.'" type="text"  value="'.$date.'" placeholder="Date" class="textfield_date" >'.$mandatory.$hint;
        $dateString    .=  '</div>';
        return $dateString;

    }
    public function addAutoCompleteField($inputElement) {

        if(in_array('required',$inputElement->validations)) {
            $mandatory='<span class="mandatory">*</span>';
            $validationClass="required";
        }
        if($inputElement->hint)
            $hint   =   '<a href="#" class="tooltiplink" data-original-title="'.$inputElement->hint.'"><span class="help-icon"><img src="'.BASE_URL.'modules/cms/images/help_icon.png"><span></a>';
        $inputElement->value    =   explode("{valSep}",$inputElement->value);
        $textValue  =   $inputElement->value[0];
        $hiddenValue  =   $inputElement->value[1];

        $inputString     =   '<div class="controls"><input name="'.$inputElement->name.'" id="'.$inputElement->id.'" type="text"  value="'.$textValue.'"  class="textfield ui-autocomplete-input">'.$mandatory.$hint;
        // hidden field to hold selected value
        $inputString    .=  '<input type="hidden" id="selected_'.$inputElement->id.'" name="selected_'.$inputElement->name.'" value="'.$hiddenValue.":".$textValue.'" >';
        // for storing source url , used in cms.js file
        $inputString    .=  '<input type="hidden" id="source_'.$inputElement->id.'" name="source_'.$inputElement->name.'" value="'.$inputElement->source.'">';
        $inputString    .=  '</div>';
        return $inputString;

    }

    public function addDisabledTextboxField($textboxElement) {


        return '<div class="controls"><input type="text" id="'.$textboxElement->id.'" name="'.$textboxElement->name.'" value="'.$textboxElement->value.'" readonly=readonly ></div>';

    }
    public function addSubmitButton($submitElement) {

        return '<div class="controls popupeditpanel_ftr"> <input class="cancelButton btn popup_greybtn" type="button" value="Cancel" name="cancel">&nbsp;
            <input class="submitButton btn jqSubmitForm popup_orngbtn right" type="submit" value="Save" name="submit">
                   <div class="clear"></div></div>';

    }



}



class Formelement {
    public $type;
    public $name;
    public $value;
    public $default;
    public $sourceType;
    public $source;
    public $id;
    public $label;
    public $class;
    public $prehtml;
    public $posthtml;
    public $validations=array();
    public $hint;
    public $enumvalues;
    public $displayFormat;

    public function  __construct() {

    }
    public function addElement($element,$attributes) {

        $this->type     =   $attributes->editoptions->type;
        $this->name     =   $element;
        $this->id       =   $element;
        $elementArray   =   array();
        if($attributes->editoptions->hidden)            $elementArray['type']   =   $this->type;
        $elementArray['name']   =   $this->name;
        $elementArray['id']=$this->id;

        return $elementArray;
    }

}
class Formvalidation {
    public $field;
    public $successMessage;
    public $errorMessage;

    public function  __construct() {

    }
    public function validateForm($value,$name,$attributes) {


        for($loop=0;$loop<count($attributes->validations);$loop++) {

            if($attributes->validations[$loop]=="required")
                $this->checkRequired($value,$name);
            if($attributes->validations[$loop]=="email")
                $this->validateEmail($value);
            if($attributes->type=="file")
                $this->checkFileType($value);

        }

        return $this->errorMessage;
    }
    public function checkFileType($value) {
        $ext = pathinfo($value, PATHINFO_EXTENSION);
        $fileHandler = new Filehandler();
        $allowed_extensions = array("gif", "jpeg", "jpg", "png","bmp");
        if($value!="") {
            if (!in_array($ext, $allowed_extensions)) {
                return $this->errorMessage=" Inavalid file format";
            }
        }
        return true;

    }
    public function checkRequired($value,$name) {
        if($value=="")
            return $this->errorMessage=$name." Field is mandatory";
        return true;

    }
    public function validateEmail($email) {
        if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$', $email))
            return true;
        else
            return $this->errorMessage="Invalid email";
    }

}
?>