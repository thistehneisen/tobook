<?php

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add form items						                          |
// +----------------------------------------------------------------------+
error_reporting(0);
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
//include "../includes/globalfunctions.php";
include "../includes/cls_htmlform.php";
//include "../language/english_lng_user.php";

echo "<script>";
foreach($customform as $key=>$val){
    echo 'var '.$key.'="'.$val.'";';
    //print_r($val);exit;
}
echo "</script>";
$pageType 		= $_GET['page'];
$menuname 		= $_GET['menuname'];
$currentPage 		= $_SESSION['siteDetails']['currentpage'];

$fieldlist = array();//for storing the types of inputs that can be created by the user
$fieldlist["1"] = "Text Box";
$fieldlist["2"] = "Text Area";
$fieldlist["3"] = "Select Box";
$fieldlist["4"] = "Checkbox";
$fieldlist["5"] = "Radiobutton";


$menuType 		= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['settings']['menutype'];
$formData 		= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]; 
$pageHeading            = $formData['heading'];
$formEmail 		= $formData['email'];
$formElements           = $formData['formelements'];
$appForm                = $_GET['menuname'];
$formElements           =  $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements']; 
$objForm = new Htmlform();
$objForm->method	= "POST";
$objForm->action  	= "mailto:$formEmail";
$formOutPut = "";
if(sizeof($formElements) > 0) {

    $outArray = array();
    $loop = 0 ;
    foreach($formElements as $key=>$items) {

        $objFormElement		    = new Formelement();
        $objFormElement->type	    = $items['type'];
        $objFormElement->name       = $items['name'];
        $objFormElement->id         = $items['name'];
        $objFormElement->label      = $items['display'];
        $objFormElement->value      = $items['value'];
        $objFormElement->posthtml   = '&nbsp;&nbsp;<a href="javascript:deleteFormElement('.$items['name'].')">Delete</a>&nbsp;&nbsp;';
        $objForm->addElement($objFormElement);
        $outArray[$loop] = array();
        $outArray[$loop][0]= $objFormElement->label;

        $outArray[$loop][1]=$objForm->addTextbox($objFormElement);
        $outArray[$loop][2]= $objFormElement->name;
        $loop++;

        /* 	*/
    }
    $formOutPut	= $objForm->renderForm();

    //echopre($formOutPut);

}


?> 
<script type="text/javascript">
    var out = new Array();

<?php if(count($outArray)>1 ) {?>
    out= <?php echo json_encode($outArray ); ?>;
    var jArrayCount= <?php echo count($outArray ); ?>;
    <?php } ?>


</script> 
<script>
    var digitsandchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    var digitscharsandspace = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789";
    var digits = "0123456789";
    var output = "";
    var firsttdwidth = 20;
    var secondtdwidth = 2;
    var thirdtdwidth = 78;


    var citems = new Array();
    var sitems = new Array();
    var ritems = new Array();
    var pages = '<?php echo $pages; ?>';	// the names of pages for cheking against duplicate file names
    var siteLanguageOption = '<?php echo $siteLanguageOption; ?>';

    function checkMail(email){
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
            eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
            eFlag = false;
        }
        else
        {
            var dot=arr[1].split('.');
            if(dot.length < 2)
            {
                eFlag = false;
            }
            else
            {
                if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                {
                    eFlag = false;
                }

                for(i=1;i < dot.length;i++)
                {
                    if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1 || dot[i].length > 4)
                    {
                        eFlag = false;
                    }
                }
            }
        }
        return eFlag;
    }
    /*
     *creates page name by  removing the spaces in the page display name
     */


    /*
     *Removes the spaces in a string and return a string (E.g. 'my file name' is converted to 'myfilename')
     */
    function trimSpaces(str){
        var parts = str.split(" ");
        var res = "";
        for(i=0;i<parts.length;i++){
            if(parts[i] !=" "){
                res += parts[i];
            }
        }
        return res;
    }

    /*
     *Validates the form for creating the custom form
     *checks if a page with the same name already exists
     *checks whether all fields are entered
     */

    /*
     *Toggles the display of entry fields for creating different input elements like textbox,
     *textarea, selectbox, checkbox and radiobutton
     *It is called on the change of dropdown list 'ddlFields'
     */
    function displayOptions(val){
        var frm = document.frmexternalcustomformeditor;
        if(val == 1){
            document.getElementById("textbox").style.display = "";
            document.getElementById("textarea").style.display = "none";
            document.getElementById("selectbox").style.display = "none";
            document.getElementById("checkbox").style.display = "none";
            document.getElementById("radiobutton").style.display = "none";
        }else if(val == 2){
            document.getElementById("textbox").style.display = "none";
            document.getElementById("textarea").style.display = "";
            document.getElementById("selectbox").style.display = "none";
            document.getElementById("checkbox").style.display = "none";
            document.getElementById("radiobutton").style.display = "none";
        }else if(val == 3){
            document.getElementById("textbox").style.display = "none";
            document.getElementById("textarea").style.display = "none";
            document.getElementById("selectbox").style.display = "";
            document.getElementById("checkbox").style.display = "none";
            document.getElementById("radiobutton").style.display = "none";
        }else if(val == 4){
            document.getElementById("textbox").style.display = "none";
            document.getElementById("textarea").style.display = "none";
            document.getElementById("selectbox").style.display = "none";
            document.getElementById("checkbox").style.display = "";
            document.getElementById("radiobutton").style.display = "none";
        }else if(val == 5){
            document.getElementById("textbox").style.display = "none";
            document.getElementById("textarea").style.display = "none";
            document.getElementById("selectbox").style.display = "none";
            document.getElementById("checkbox").style.display = "none";
            document.getElementById("radiobutton").style.display = "";
        }
    }


    /*
     *Checks whether a string (strstring) contains characters other than a set of allowed characters (allowedchars)
     */
    function validchars(strstring,allowedchars){
        var ch;
        for (i = 1;  i < strstring.length;i++){
            ch = strstring.charAt(i);
            for (j = 0;  j < allowedchars.length;  j++){
                if (ch == allowedchars.charAt(j)){
                    break;
                }
            }
            if (j == allowedchars.length){
                return false;
                break;
            }
        }
        return true;
    }

    /*
     *returns input text for a textbox with given name, value, size and maxlength
     *will be something like '<input type="text" name="firstname" value="" size="20" maxlength = "40">'
     */
    function getTextBox(tbname, tbval, tbsize, tbmaxlen){ 

        var textboxtext = '<input class="textbox_style3" type = text name='+ tbname +' value = "' + tbval + '" ';
        if(!isNaN(tbsize)){
            textboxtext += ' size='+tbsize;
        }else{
            textboxtext += ' size=15';
        }
        if(!isNaN(tbmaxlen)){
            textboxtext += ' maxlength='+tbmaxlen;
        }else{
            textboxtext += ' maxlength=50';
        }
        textboxtext += ' >';
        return textboxtext;
    }

    /*
     *returns input text for a textarea with given name, value, rows and cols
     *will be something like '<textarea name="address" rows="5" cols= "40">Some address</textarea>'
     */
    function getTextArea(taname,tavalue,tarows,tacols){

        var textareatext = '<textarea class="textarea_style3" name='+ taname ;
        if(!isNaN(tarows)){
            textareatext += ' rows='+tarows;
        }
        if(!isNaN(tacols)){
            textareatext += ' cols ='+tacols;
        }
        textareatext += ' >';
        textareatext += tavalue;
        textareatext += '</textarea>';
        return textareatext;
    }

    /*
     *gets the textbox text into a variable 'tbox' using the current values of 'txtTextboxName', 'txtTextboxValue' etc
     *and adds it to the array 'out'. The div by name 'output' is re-rendered with the value of 'out',
     *displaying all the input elements including the newly addred textbox
     */
     
    function addTextBox(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var tbsize = parseInt(frm.txtTextboxSize.value);
        var tbmaxlen = parseInt(frm.txtTextboxMaxLength.value);
        var tbox = "";
        if(frm.txtDisplayText.value == ""){ 
            msg += "*" + CUSTOMFRM_TEXT_DISPALYTEXT + "\n";
        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtDisplayText.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_VALID_DISPALYTEXT + "\n";
            }
        }
        if(frm.txtTextboxName.value == ""){
            msg += "*" + CUSTOMFRM_TEXTNAME + "\n";
        }else{
            if(!validchars(frm.txtTextboxName.value,digitsandchars)){
                msg += "*" + CUSTOMFRM_VALID_TEXTNAME + "\n";
            }
        }

        if(frm.txtTextboxValue.value != ""){
            if(siteLanguageOption =='english'){
                if(!validchars(frm.txtTextboxValue.value,digitscharsandspace)){
                    msg += "*" + CUSTOMFRM_TEXTVALUE + "\n";
                }
            }
        }

        if(frm.txtTextboxSize.value.length >0){
            if(isNaN(tbsize) || tbsize<=0 || !validchars(tbsize,digits)){//if(isNaN(tbsize) || tbsize<=0 || !validchars(tbsize,"0123456789") ){
                msg += "*" + CUSTOMFRM_POSVALUE + "\n";
                frm.txtTextboxSize.value = "";
            }else{
                frm.txtTextboxSize.value = tbsize;
            }
        }

        if(frm.txtTextboxMaxLength.value.length >0){
            if(isNaN(tbmaxlen) || tbmaxlen<=0 || !validchars(tbmaxlen,digits)){
                msg += "*" + CUSTOMFRM_POSMAXVALUE + "\n";
                frm.txtTextboxMaxLength.value = "";
            }else{
                frm.txtTextboxMaxLength.value = tbmaxlen;
            }
        }
        if(msg != ""){
            msg = CUSTOMFRM_CONTINUE + "\n" +msg;
            alert(msg);
        }else{
            if (document.getElementById){
                tbox =  getTextBox(frm.txtTextboxName.value,frm.txtTextboxValue.value,tbsize,tbmaxlen);
                var newarr = new Array(2);
                newarr[0] = frm.txtDisplayText.value;
                newarr[1] = tbox;
                newarr[2] = frm.txtTextboxName.value;
                var present = false;
                for(i=0;i<out.length;i++){
                    if(frm.txtTextboxName.value == out[i][2]){
                        present = true;
                        break;
                    }
                }
                if(!present){

                    var arrField = new Array();
                    arrField['type']	= 'textbox';
                    arrField['display']	= frm.txtDisplayText.value;
                    arrField['name']	= frm.txtTextboxName.value;
                    arrField['value']	= frm.txtTextboxValue.value;
                    arrField['size']	= frm.txtTextboxSize.value;
                    arrField['maxlenght']	= frm.txtTextboxMaxLength.value;



                    out.push(newarr);
                    frm.txtDisplayText.value = "";
                    frm.txtTextboxName.value = "";
                    frm.txtTextboxSize.value = "";
                    frm.txtTextboxMaxLength.value = "";
                    frm.txtTextboxValue.value = "";
                    var outp = getOutput(out,false);
                    x = document.getElementById("output");
                    x.innerHTML = '';
                    x.innerHTML = outp;

                    addItemtoSession(arrField);

                }else{
                    alert(CUSTOMFRM_TEXT_UNIQUE);
                }


            }

        }

    }


    /*
     *gets the textarea text into a variable 'tarea' using the current values of 'txtTextareaName', 'txtTextareaValue' etc
     *and adds it to the array 'out'. The div by name 'output' is re-rendered with the value of 'out',
     *displaying all the input elements including the newly addred textarea
     */
    function addTextArea(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var tarows = parseInt(frm.txtTextareaRows.value);
        var tacols = parseInt(frm.txtTextareaColumns.value);
        var tbox = "";
        if(frm.txtTextAreaDisplayText.value == ""){
            msg += "*" + CUSTOMFRM_TEXTAREA + "\n";
        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtTextAreaDisplayText.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_DISPLAY_TEXTAREA + "\n";
            }
        }
        
        if(frm.txtTextareaName.value == ""){
            msg += "*" + CUSTOMFRM_TEXTAREANAME + "\n";
        }else{
            if(!validchars(frm.txtTextareaName.value,digitsandchars)){
                msg += "*" + CUSTOMFRM_VALID_TEXTAREANAME + "\n";
            }
        }

        if(frm.txtTextareaRows.value.length >0){
            if(isNaN(tarows) || tarows<=0 || !validchars(tarows,digits)){
                msg += "*" + CUSTOMFRM_POS_TEXTAREAROW + "\n";
                frm.txtTextareaRows.value = "";
            }else{
                frm.txtTextareaRows.value = tarows;
            }
        }

        if(frm.txtTextareaColumns.value.length >0){
            if(isNaN(tacols) || tacols<=0 || !validchars(tacols,digits)){
                msg += "*"+ CUSTOMFRM_POS_TEXTARECOL + "\n";
                frm.txtTextareaColumns.value = "";
            }else{
                frm.txtTextareaColumns.value = tacols;
            }
        }
        if(msg != ""){
            msg = CUSTOMFRM_POS_TEXTARECOL + "\n" +msg;
            alert(msg);
        }else{
            if (document.getElementById){
                tarea =  getTextArea(frm.txtTextareaName.value,frm.txtTextareaValue.value,tarows,tacols);
                var newarr = new Array(2);
                newarr[0] = frm.txtTextAreaDisplayText.value;
                newarr[1] = tarea;
                newarr[2] = frm.txtTextareaName.value;
                var present = false;
                for(i=0;i<out.length;i++){
                    if(frm.txtTextareaName.value == out[i][2]){
                        present = true;
                        break;
                    }
                }
                if(!present){
                    var arrField = new Array();
                    arrField['type']	= 'textarea';
                    arrField['display']	= frm.txtTextAreaDisplayText.value;
                    arrField['name']	= frm.txtTextareaName.value;
                    arrField['value']	=  frm.txtTextareaValue.value;
                    arrField['rows']	=  frm.txtTextareaRows.value;
                    arrField['columns']	=  frm.txtTextareaColumns.value;



                    out.push(newarr);
                    frm.txtTextareaRows.value = '';
                    frm.txtTextareaColumns.value= '';
                    frm.txtTextAreaDisplayText.value= '';
                    frm.txtTextareaName.value = '';
                    frm.txtTextareaValue.value = '';
                    var outp = getOutput(out,false);
                    x = document.getElementById("output");
                    x.innerHTML = '';
                    x.innerHTML = outp;

                    addItemtoSession(arrField);

                }else{
                    alert(CUSTOMFRM_TEXTAREA_UNIQUE);
                }
            }

        }

    }

    /*
     *Returns a table row (<tr></tr>) with the given display text and input field.
     *Used to generate both the intermediate display form (in the div 'output') and the final output form.
     *If finalout is false then intermediate, else final output.
     *If not final out, links for Delete, Move Up, Move down also are created.
     */
    function getOutTR(inputnametext,inputfield,finalout,arraycount,currentelement){
        var outtr = '';
        outtr += '<div class="editor_form_items_row"><div class="editor_form_items_col1"> <label for="'+ inputnametext +' " class="control-label">'+ inputnametext + '</label></div>';

        outtr += '<div class="editor_form_items_col2">' + inputfield;
        if(!finalout){
            outtr += getManipulationLinks(arraycount,currentelement);

        }

        outtr += '  </div></div>';
        return outtr;
    }


    /*
     *Create the Move Up, Move Down, Delete links for each of the form elements created intermediately
     *and displayed in the div called 'output'
     */
    function getManipulationLinks(arraycount,currentelement){
        var res = '';
        if(arraycount <= 1){
            res += '&nbsp;&nbsp;<a href="javascript:deleteElement('+ currentelement +')">Delete</a>&nbsp;&nbsp;';//adding delete link
        }else{
            //adding Move Up link
            if(currentelement > 0 ){
                res += '&nbsp;&nbsp;<a href="javascript:moveUp('+ currentelement +')">Move Up</a>&nbsp;&nbsp;';//adding moveup link
            }
            //adding Move Down link
            if(currentelement < arraycount-1){
                res += '&nbsp;&nbsp;<a href="javascript:moveDown('+ currentelement +')">Move Down</a>&nbsp;&nbsp;';//adding movedown link
            }
            res += '&nbsp;&nbsp;<a href="javascript:deleteElement('+ currentelement +')">Delete</a>&nbsp;&nbsp;';//adding delete link
        }
        return res;
    }

    /*
     *Moves Up the form element created intermediately
     *and displayed in the div called 'output', on clicking the corresponding 'Move Up' link
     */
    function moveUp(id){
        var newout = new Array();
        newout.push(out[id-1]);
        newout.push(out[id]);
        out[id-1] = newout.pop();
        out[id] = newout.pop();
        var outp = getOutput(out,false);
        x = document.getElementById("output");
        x.innerHTML = '';
        x.innerHTML = outp;
    }

    /*
     *Moves Down the form element created intermediately
     *and displayed in the div called 'output', on clicking the corresponding 'Move Down' link
     */
    function moveDown(id){
        var newout = new Array();
        newout.push(out[id+1]);
        newout.push(out[id]);
        out[id+1] = newout.pop();
        out[id] = newout.pop();
        var outp = getOutput(out,false);
        x = document.getElementById("output");
        x.innerHTML = '';
        x.innerHTML = outp;
    }

    /*
     *Delete the form element created intermediately
     *and displayed in the div called 'output', on clicking the corresponding 'Delete' link
     */
    function deleteElement(id){
        var frm = document.frmexternalcustomformeditor;
        var newout = new Array();
        for(i=0;i<out.length;i++){
            if(id != i){
                newout.push(out[i]);
            }
        }
        out = newout;
        var outp = getOutput(out,false);
        x = document.getElementById("output");
        x.innerHTML = '';
        x.innerHTML = outp;
    }

    /*
     *Returns the complete output form with the given display texts and input fields.
     *Used to generate both the intermediate display form (in the div 'output') and the final output form.
     *If finalout is false then intermediate, else final output.
     */
    function getOutput(inputarray,finalout){ 
        var frm = document.frmexternalcustomformeditor;
        var outtext = '';
        if(finalout){
            outtext += '<div class="customformouter"><form name=frmCustom method="POST" action="mailto:'+ frm.txtEmailAddress.value +'" >';
            //outtext += '<input type="hidden" name="mailfrom" value="test@test.com">';
        }else{
            outtext += '<fieldset><legend>Output</legend>';
            if(frm.txtPageHeading.value !=""){
                outtext += '<br><center>'+frm.txtPageHeading.value+'</center><br>';
            }

        }
        //  outtext += '<table width=100% cellpadding=2 cellspacing=2 border=0>';
        for(i=0;i<inputarray.length;i++){
            outtext += getOutTR(inputarray[i][0],inputarray[i][1],finalout,inputarray.length,i);

        }
        

        if(finalout){
            outtext += '<input class="popup_orngbtn right" type="submit" value="Submit Form">';
            outtext += '<div class="clear"></div></form></div>';
        }else{
            outtext += '</fieldset>';
        }
		outtext += '<div class="clear"></div></div>';

        return outtext;
    }

    /*
     *Adds a select box item to the selectbox temporarily displayed in the preview area
     *The new item is added to the array 'sitems' and it is displayed in a div called 'sboutput'
     */
    function addSBItem(){
        
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var sboxitem = "";
        var itemdisplayfield = frm.txtSBDisplayField.value;
        var itemactualvalue = frm.txtSBActualValue.value;
        var itemselected;
        if(frm.chkSBSelected.checked == true){
            itemselected = true;
        }else{
            itemselected = false;
        }
        if(frm.txtSBDisplayField.value == ""){
            msg += "*" + CUSTOMFRM_DISPLAYTEXT + "\n";

        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtSBDisplayField.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_VALID_TEXT_SELECT + "\n";
            }
        }

        if(itemactualvalue == ""){
            itemactualvalue = itemdisplayfield;
        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtSBActualValue.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_VALID_VALUE_SELECT + "\n";
            }
        }
        sboxitem = '<option value="' + itemactualvalue + '"';
        if(itemselected){
            sboxitem += ' SELECTED ';
        }
        sboxitem += '>' + itemdisplayfield + '</option>';
        var newarr = new Array(2);
        newarr[0] = itemdisplayfield;
        newarr[1] = sboxitem;
        newarr[2] = itemactualvalue;
        if(sbItemPresent(sitems,newarr)){
            msg += "*" + CUSTOMFRM_ITEMPRESENT + "\n";
        }
        if(msg !=""){
            msg = CUSTOMFRM_CONTINUE + "\n" +msg;
            alert(msg);
            frm.txtSBDisplayField.focus(); // since we have only Item Display Field as manadatoy field
            return false;
        }else{
            sitems.push(newarr);
            showSBItems();
            frm.txtSBDisplayField.value = '';
            frm.txtSBActualValue.value = '';
            frm.chkSBSelected.checked = false;


        }
    }

    /*
     *Returns a select box text with the items input.
     */
    function getSB(arritems,multi,sbname){
        var frm = document.frmexternalcustomformeditor;
        var sbout = '';
        if(sbname == ''){
            sbname ='dummySBname29890980293';
        }
        sbout += '<select class="select_style3" name="'+ sbname +'" ';
        if(multi){
            sbout += ' multiple ';
        }
        sbout += ' >';
        for(i=0;i<arritems.length;i++){
            sbout += arritems[i][1];
        }
        sbout += '</select>';
        return sbout;
    }

    /*
     *Shows the temporarily created select box items in the div called 'sboutput'
     */
    function showSBItems(){
        var frm = document.frmexternalcustomformeditor;
        document.getElementById("sbvalues").style.display = "";
        var sbout = getSB(sitems,frm.chkMultiSelectBox.checked,'');

        y = document.getElementById("sboutput");
        y.innerHTML = '';
        y.innerHTML = sbout;
    }

    /*
     *Checks whether a given select box item is present in the current main array
     */
    function sbItemPresent(mainarray,newitem){
        var frm = document.frmexternalcustomformeditor;
        if(mainarray.length == 0){
            return false;
        }else{
            for(i=0;i<mainarray.length;i++){
                if((newitem[0] == mainarray[i][0]) || (newitem[2] == mainarray[i][2]) ){
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    /*
     *gets the selectbox text into a variable 'sbox' using the current values of 'txtSelectBoxName', and array 'sitems'
     *and adds it to the array 'out'. The div by name 'output' is re-rendered with the value of 'out',
     *displaying all the input elements including the newly addred selectbox
     */
    function addSelectBox(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var sbox = "";
        if(frm.txtSelectBoxDisplayText.value == ""){
            msg += "*" + CUSTOMFRM_SELECT_DISPLAYTEXT + "\n";
        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtSelectBoxDisplayText.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_SELECT_DISPLAYTEXT_VALID + "\n";
            }
        }
        if(frm.txtSelectBoxName.value == ""){
            msg += "*" + CUSTOMFRM_SELECTBOX + "\n";
        }else{
            if(!validchars(frm.txtSelectBoxName.value,digitsandchars)){
                msg += "*" + CUSTOMFRM_VALID_SELECTBOX + "\n";
            }
        }

        if(sitems.length == 0){
            msg += "*" + CUSTOMFRM_SELECTBOX_ITEMS + "\n";
        }
        if(msg != ""){
            msg = CUSTOMFRM_CONTINUE + "\n" +msg;
            alert(msg);
        }else{
            if (document.getElementById){
                sbox = getSB(sitems,frm.chkMultiSelectBox.checked,frm.txtSelectBoxName.value);
                var newarr = new Array(2);
                newarr[0] = frm.txtSelectBoxDisplayText.value;
                newarr[1] = sbox;
                newarr[2] = frm.txtSelectBoxName.value;
                var present = false;
                for(i=0;i<out.length;i++){
                    if(frm.txtSelectBoxName.value == out[i][2]){
                        present = true;
                        break;
                    }
                }
                if(!present){
                    var arrField = new Array();

                    arrField['type']	= 'selectbox';
                    arrField['name']	= frm.txtSelectBoxName.value;
                    arrField['display']	=frm.txtSelectBoxDisplayText.value;
                    arrField['multiselect'] = frm.chkMultiSelectBox.value



                    var arrayCnt = 0;
                    var optnValues  = new Array();
                    var values = $.map(frm.dummySBname29890980293, function(e) {
                        optnValues[arrayCnt]  = new Array();
                        optnValues[arrayCnt]['value'] = e.value;

                        optnValues[arrayCnt]['text'] = e.text;
                        arrayCnt++;


                    });

                    arrField['options']	= optnValues;
                    arrField['selectedoption']	= frm.dummySBname29890980293.value;

                    // as a comma separated string

                    out.push(newarr);
                    frm.txtSelectBoxDisplayText.value = '';
                    frm.txtSelectBoxName.value = '';
                    frm.chkMultiSelectBox.checked = false;
                    document.getElementById("sbvalues").style.display = "none";
                    var outp = getOutput(out,false);
                    x = document.getElementById("output");
                    x.innerHTML = '';
                    x.innerHTML = outp;
                    sitems.length = 0;



                    addItemtoSession(arrField);


                }else{
                    alert(CUSTOMFRM_SELECTBOX_UNIQUE);
                }

            }
        }
    }

    /*
     *Used to change the display of the temporarily rendered selectbox when the Multiple checkbox is selected or unselected
     */
    function changeSBDisplay(ischecked){
        if(sitems.length >0){
            showSBItems();
        }
    }

    /*
     *gets the chaeckbox text into a variable 'cbox' using the current values of 'txtCheckBoxName', and array 'citems'
     *and adds it to the array 'out'. The div by name 'output' is re-rendered with the value of 'out',
     *displaying all the input elements including the newly addred checkbox
     */
    function addCheckBox(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var cbox = "";

        if(frm.txtCheckBoxDisplayText.value == ""){
            msg += "*" + CUSTOMFRM_CHECKBOX_TEXT + "\n";
        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtCheckBoxDisplayText.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_VALID_CHECKBOX_TEXT + "\n";
            }
        }
        if(frm.txtCheckBoxName.value == ""){
            msg += "*" + CUSTOMFRM_CHECKBOX_NAME + "\n";
        }else{
            if(!validchars(frm.txtCheckBoxName.value,digitsandchars)){
                msg += "*" + CUSTOMFRM_VALIDCHECKBOX_NAME + "\n";
            }
        }

        if(citems.length == 0){
            msg += "*" + CUSTOMFRM_VALIDCHECKBOX_ITEMS + "\n";
        }
        if(msg !=""){
            msg = CUSTOMFRM_CONTINUE + "\n" +msg;
            alert(msg);
            return false;
        }else{
            if (document.getElementById){
                cbox =  getCB(citems,frm.txtCheckBoxName.value,'<br>');
                var newarr = new Array(2);
                newarr[0] = frm.txtCheckBoxDisplayText.value;
                newarr[1] = cbox;
                newarr[2] = frm.txtCheckBoxName.value;
                var present = false;
                for(i=0;i<out.length;i++){
                    if(frm.txtCheckBoxName.value == out[i][2]){
                        present = true;
                        break;
                    }
                }
                if(!present){
                    var arrField = new Array();

                    //arrField[0]	= 'checkbox';
                    //arrField[1]	=  frm.txtCheckBoxDisplayText.value;
                    //arrField[2]	=  frm.txtCheckBoxName.value;
                    
                    arrField['type']	= 'checkbox';
                    arrField['display']	=  frm.txtCheckBoxDisplayText.value;
                    arrField['name']	=  frm.txtCheckBoxName.value;

                    arrField[3] = new Array();
                    var arrayCnt = 0;
                    var optnValues  = new Array();
                    $(frm.dummyCBname23478737737).each(function() {

                        optnValues[arrayCnt]  = new Array();
                        optnValues[arrayCnt][0] = $(this).val();
                        if($(this).attr('checked')) {
                            optnValues[arrayCnt][1] = '1';
                        }
                        else
                            optnValues[arrayCnt][1] = '0';
                        arrayCnt++;
                    });
                    arrField[3] = optnValues;
                    out.push(newarr);
                    frm.txtCheckBoxDisplayText.value = '';
                    frm.txtCheckBoxName.value = '';
                    document.getElementById("cbvalues").style.display = "none";
                    var outp = getOutput(out,false);
                    x = document.getElementById("output");
                    x.innerHTML = '';
                    x.innerHTML = outp;
                    citems.length = 0;
                    addItemtoSession(arrField);

                }else{
                    alert(CUSTOMFRM_VALIDCHECKBOX_UNIQUE);
                }

            }
        }
    }

    /*
     *Adds a check box item to the checkbox temporarily displayed in the preview area
     *The new item is added to the array 'citems' and it is displayed in a div called 'cboutput'
     */
    function addCBItem(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var cboxitem = "";
        var itemdisplayfield = frm.txtCBDisplayField.value;
        var itemactualvalue = frm.txtCBActualValue.value;
        var itemselected;
        if(frm.chkCBChecked.checked == true){
            itemselected = true;
        }else{
            itemselected = false;
        }
        if(frm.txtCBDisplayField.value == ""){
            msg += "*" + CUSTOMFRM_DISPLAYTEXT + "\n";
        }else{
            if(siteLanguageOption =='english'){
                if(!validchars(frm.txtCBDisplayField.value,digitscharsandspace)){
                    msg += "*" + CUSTOMFRM_VALIDCHECKBOX_DESC + "\n";
                }
            }
        }

        if(itemactualvalue == ""){
            itemactualvalue = itemdisplayfield;
        }else if(!validchars(frm.txtCBActualValue.value,digitscharsandspace)){
            msg += "*" + CUSTOMFRM_VALIDCHECKBOX_VALUE + "\n";
        }
        if(cbItemPresent(citems,itemdisplayfield,itemactualvalue)){
            msg += "*" + CUSTOMFRM_CHECKBOX_PRESENT + "\n";
        }
        if(msg !=""){
            msg = CUSTOMFRM_CONTINUE + "\n" +msg;
            alert(msg);
            frm.txtCBDisplayField.focus(); // since we have only Item Display Field as manadatoy field
            return false;
        }else{
            var newarr = new Array(2);
            newarr[0] = itemdisplayfield;
            newarr[1] = itemactualvalue;
            newarr[2] = itemselected;
            citems.push(newarr);
            showComboItems();
            frm.txtCBDisplayField.value= '';
            frm.txtCBActualValue.value= '';
            frm.chkCBChecked.checked = false;
        }
    }

    /*
     *Checks whether the current array of checkbox items contains an item with the name of the given item
     */
    function cbItemPresent(cbarray,itemname,itemval){
        var frm = document.frmexternalcustomformeditor;
        if(cbarray.length == 0){
            return false;
        }else{
            for(i=0;i<cbarray.length;i++){
                if((itemname == cbarray[i][0])){
                    return true;
                    break;
                }
            }
            for(i=0;i<cbarray.length;i++){
                if((itemval == cbarray[i][1]) ){
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    /*
     *Shows the temporarily created check box items in the div called 'cboutput'
     */
    function showComboItems(){
        var frm = document.frmexternalcustomformeditor;
        document.getElementById("cbvalues").style.display = "";
        var cbout = getCB(citems,'','<br>');

        y = document.getElementById("cboutput");
        y.innerHTML = '';
        y.innerHTML = cbout;
    }

    /*
     *Return the input text for a check box using the array of items (citems), combobox name(cbname) and the separator betweeb each items (typically a '<br>')
     */
    function getCB(arritems,cbname,separator){
        var frm = document.frmexternalcustomformeditor;
        var cbout = '';
        if(cbname == ''){
            cbname ='dummyCBname23478737737';
        }

        for(i=0;i<arritems.length;i++){
            cbout += '<input type=checkbox name="'+ cbname +'" value="'+ arritems[i][1] +'"';
            if(arritems[i][2] == true){
                cbout += 'CHECKED ' ;
            }

            cbout += '>'+ arritems[i][0] + separator;
        }
        cbout += separator;
        return cbout;
    }

    /*
     *Adds a radio group item to the radiobutton group temporarily displayed in the preview area
     *The new item is added to the array 'ritems' and it is displayed in a div called 'rboutput'
     */
    function addRBItem(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var rboxitem = "";
        var itemdisplayfield = frm.txtRBDisplayField.value;
        var itemactualvalue = frm.txtRBActualValue.value;
        var itemselected;
        if(frm.chkRBChecked.checked == true){
            itemselected = true;
        }else{
            itemselected = false;
        }
        if(frm.txtRBDisplayField.value == ""){
            msg += "*" + CUSTOMFRM_DISPLAYTEXT + "\n";
        }else{
            if(siteLanguageOption =='english'){
                if(!validchars(frm.txtRBDisplayField.value,digitscharsandspace)){
                    msg += "*" + CUSTOMFRM_CHECKBOX_VALID_DISPLAY + "\n";
                }
            }
        }

        if(itemactualvalue == ""){
            itemactualvalue = itemdisplayfield;
        }else if(!validchars(frm.txtRBActualValue.value,digitscharsandspace)){
            msg += "*" + CUSTOMFRM_CHECKBOX_VALID_VALUE + "\n";
        }
        if(rbItemPresent(ritems,itemdisplayfield,itemactualvalue)){
            msg += "*" + CUSTOMFRM_RADIO + "\n";
        }
        if(itemselected){
            if(rbItemAlreadyChecked(ritems)){
                msg += "*" + CUSTOMFRM_RADIOGROUP + "\n";
            }
        }

        if(msg !=""){
            msg = CUSTOMFRM_CONTINUE + "\n" +msg;
            alert(msg);
            frm.txtRBDisplayField.focus(); // since we have only Item Display Field as manadatoy field
            return false;
        }else{
            var newarr = new Array(2);
            newarr[0] = itemdisplayfield;
            newarr[1] = itemactualvalue;
            newarr[2] = itemselected;
            ritems.push(newarr);
            showRadioItems();
            frm.txtRBDisplayField.value= '';
            frm.txtRBActualValue.value= '';
            frm.chkRBChecked.checked = false;
        }
    }

    /*
     *Checks whether the current array of radiobox items contains an item with the name of the given item
     */
    function rbItemPresent(rbarray,itemname,itemval){
        var frm = document.frmexternalcustomformeditor;
        if(rbarray.length == 0){
            return false;
        }else{
            for(i=0;i<rbarray.length;i++){
                if((itemname == rbarray[i][0])){
                    return true;
                    break;
                }
            }
            for(i=0;i<rbarray.length;i++){
                if((itemval == rbarray[i][1]) ){
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    /*
     *Checks whether the current array of radiobox items contains a checked item already. (A radio group should have only one checked item)
     */
    function rbItemAlreadyChecked(rbarray){
        var frm = document.frmexternalcustomformeditor;
        if(rbarray.length == 0){
            return false;
        }else{
            for(i=0;i<rbarray.length;i++){
                if(rbarray[i][2] == true){
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    /*
     *Shows the temporarily created radio box group items in the div called 'rboutput'
     */
    function showRadioItems(){
        var frm = document.frmexternalcustomformeditor;
        document.getElementById("rbvalues").style.display = "";
        var rbout = getRB(ritems,'','<br>');
        y = document.getElementById("rboutput");
        y.innerHTML = '';
        y.innerHTML = rbout;
    }

    /*
     *Return the input text for a radio box using the array of items (ritems), combobox name(rbname) and the separator betweeb each items (typically a '<br>')
     */
    function getRB(arritems,rbname,separator){
        var frm = document.frmexternalcustomformeditor;
        var rbout = '';
        if(rbname == ''){
            rbname ='dummyRBw34876823746237';
        }
        for(i=0;i<arritems.length;i++){
            rbout += '<input type=radio name="'+ rbname +'" value="'+ arritems[i][1] +'"';
            if(arritems[i][2] == true){
                rbout += 'CHECKED ' ;
            }
            rbout += '>'+ arritems[i][0] + separator;
        }
        rbout += separator;
        return rbout;
    }

    /*
     *gets the radiobutton group text into a variable 'rbox' using the current values of 'txtRadioButtonName', and array 'ritems'
     *and adds it to the array 'out'. The div by name 'output' is re-rendered with the value of 'out',
     *displaying all the input elements including the newly addred radiobutton group
     */
    function addRadioGroup(){
        var frm = document.frmexternalcustomformeditor;
        var msg = "";
        var rbox = "";

        if(frm.txtRadioButtonDisplayText.value == ""){
            msg += "*" + CUSTOMFRM_RADIOTEXT + "\n";
        }else if(siteLanguageOption =='english'){
            if(!validchars(frm.txtRadioButtonDisplayText.value,digitscharsandspace)){
                msg += "*" + CUSTOMFRM_RADIO_VALID + "\n";
            }
        }
        if(frm.txtRadioButtonName.value == ""){
            msg += "*" + CUSTOMFRM_RADIONAME + "\n";
        }else{
            if(!validchars(frm.txtRadioButtonName.value,digitsandchars)){
                msg += "*"+ CUSTOMFRM_RADIO_VALIDNAME + "\n";
            }
        }


        if(ritems.length == 0){
            msg += "*" + CUSTOMFRM_RADIO_ITEMS + "\n";
        }
        if(msg !=""){
            msg = CUSTOMFRM_RADIO_UNIQUE + "\n" +msg;
            alert(msg);
            return false;
        }else{
            if (document.getElementById){
                rbox =  getRB(ritems,frm.txtRadioButtonName.value,'<br>');
                var newarr = new Array(2);
                newarr[0] = frm.txtRadioButtonDisplayText.value;
                newarr[1] = rbox;
                newarr[2] = frm.txtRadioButtonName.value;
                var present = false;
                for(i=0;i<out.length;i++){
                    if(frm.txtRadioButtonName.value == out[i][2]){
                        present = true;
                        break;
                    }
                }
                if(!present){
                    var arrField = new Array();
                    //arrField[0]	= 'radio';
                    //arrField[1]	=  frm.txtRadioButtonDisplayText.value;
                    //arrField[2]	=  frm.txtRadioButtonName.value;

                    arrField['type']	= 'radio';
                    arrField['display']	=  frm.txtRadioButtonDisplayText.value;
                    arrField['name']	=  frm.txtRadioButtonName.value;

                    arrField[3] = new Array();
                    var arrayCnt = 0;
                    var optnValues  = new Array();
                    $(frm.dummyRBw34876823746237).each(function() {

                        optnValues[arrayCnt]  = new Array();
                        optnValues[arrayCnt][0] = $(this).val();
                        if($(this).attr('checked')) {
                            optnValues[arrayCnt][1] = '1';
                        }
                        else
                            optnValues[arrayCnt][1] = '0';
                        arrayCnt++;
                    });
                    arrField[3] = optnValues;
                    out.push(newarr);
                    frm.txtRadioButtonDisplayText.value = '';
                    frm.txtRadioButtonName.value = '';
                    document.getElementById("rbvalues").style.display = "none";
                    var outp = getOutput(out,false);
                    x = document.getElementById("output");
                    x.innerHTML = '';
                    x.innerHTML = outp;
                    ritems.length = 0;


                    addItemtoSession(arrField);
                }else{
                    alert(CUSTOMFRM_RADIO_UNIQUE);
                }

            }
        }
    }
    /*
     *Function to go back
     */
    function clickBack(){
        document.frmexternalcustomformeditor.postback.value="Back";
        document.frmexternalcustomformeditor.submit();
        //window.location.href='selectsite.php?page=feedback';
    }
</script>

<input type="hidden" name="postback" value="">
<input type="hidden" name="sitetype" value="<?php echo htmlentities($sitetype); ?>">
<input type="hidden" name="siteid" value="<?php echo htmlentities($siteid); ?>">
<input type="hidden" name="formelements" value="">
<input type="hidden" name="templatetype" value="<?php echo htmlentities($templatetype); ?>">
<input type="hidden" name="templateid" value="<?php echo htmlentities($templateid); ?>">
<table cellpadding="2" cellspacing="2" width="100%" class="pageadd_tbl">
     <!-- <tr>
        <td align="center"> <img src="images/createcustomform.gif" > </td>
    </tr>
    <tr><td colspan="3" align="left" class="redtext"><?php echo $message; ?></td></tr>

    <tr><td align="left">Page Heading<font color="RED">*</font></td><td>&nbsp;</td><td align="left"><input type="hidden" class="textbox" size="30" maxlength="100" name="txtPageHeading" value="<?php echo htmlentities($pageHeading)?>"></td></tr>
    -->


    <tr><td align="left">
            <input type="hidden" class="textbox" size="30" maxlength="100" name="txtPageHeading" value="<?php echo htmlentities($pageHeading)?>">

            <a title="Valid email address" class="masterTooltip" ><?php echo CUSTOM_EMAIL;?></a><font color="RED">*</font></td><td>&nbsp;</td><td align="left"><input type="text" class="textbox_style1" size="30" maxlength="100" name="txtEmailAddress" value="<?php echo htmlentities($formEmail)?>"></td></tr>
   
                    
                    
    <tr>
   
        <td colspan="3" align="center">
            <fieldset>
                <legend><?php echo CUSTOM_NEW;?></legend>
                <table cellpadding="2" cellspacing="2" border=0 width="100%">
                    <tr>
                        <td width="20%" valign="top" style="padding-left:0; "><?php echo makeDropDownList("ddlFields", $fieldlist, $ddlFields, false, "select_stylenew2" , "", " onChange='displayOptions(this.value);' ")?></td>
                        <td valign="top">
                            <div id="textbox" style="display:;">
                                <fieldset>
                                    <legend><?php echo CUSTOM_TXTBOX;?></legend>
                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                        <tr><td width="50%" align="left"><a title="<?php echo VALID_CFRM_DISPLAY;?>" class="masterTooltip" ><?php echo CUSTOM_TXTBOX_TEXT;?></a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtDisplayText" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTNAME;?>" class="masterTooltip" ><?php echo CUSTOM_TXTNAME;?></a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxName" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTVALUE;?>" class="masterTooltip" ><?php echo CUSTOM_VALUE;?></a></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxValue" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTSIZE;?>" class="masterTooltip" ><?php echo CUSTOM_SIZE;?></a></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxSize" size="15" maxlength="2" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTLEN;?>" class="masterTooltip" ><?php echo CUSTOM_LEN;?></a></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxMaxLength" size="15" maxlength="3" class="textbox_style2"></td></tr>
                                        <tr><td width="100%" align="center" colspan="3" class="border_top1">
                                                <input type="button" name="btnAddTextBox" class="small_btnstyle right" value="Add Textbox" onClick="addTextBox();"></td></tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div id="textarea" style="display:none;">
                                <fieldset>
                                    <legend><?php echo CUSTOM_TXTAREA;?></legend>
                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                        <tr><td width="50%" align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTAREA;?>" class="masterTooltip" ><?php echo CUSTOM_TXTAREA_TEXT;?></a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextAreaDisplayText" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td   align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTAREA_NAME;?>" class="masterTooltip" ><?php echo CUSTOM_TXTAREA_NAME;?></a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaName" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTAREA_VALUE;?>" class="masterTooltip" ><?php echo CUSTOM_VALUE;?></a></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaValue" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTAREA_ROWS;?>" class="masterTooltip" ><?php echo CUSTOM_TXTAREA_ROWS;?></a></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaRows" size="15" maxlength="2" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_TEXTAREA_COLS;?>" class="masterTooltip" ><?php echo CUSTOM_TXTAREA_COLS;?> </a></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaColumns" size="15" maxlength="2" class="textbox_style2"></td></tr>
                                        <tr><td width="100%" align="center" colspan="3" class="border_top1"><input type="button" name="btnAddTextArea" class="small_btnstyle right" value="Add Textarea" onClick="addTextArea();"></td></tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div id="selectbox" style="display:none;">
                                <fieldset>
                                    <legend><?php echo CUSTOM_SELECT;?></legend>
                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                        <tr><td width="50%" align="left"><a title="<?php echo VALID_CUSTOMFRM_SELECT;?>" class="masterTooltip" ><?php echo CUSTOM_SELECT_TXT;?> </a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtSelectBoxDisplayText" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_SELECT_NAME;?>" class="masterTooltip" ><?php echo CUSTOM_SELECT_NAME;?> </a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtSelectBoxName" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_SELECT_MULTIPLE;?>" class="masterTooltip" ><?php echo CUSTOM_SELECT_MULTI;?> </a></td><td  width="2%">&nbsp;</td><td align="left"><input type="checkbox" name="chkMultiSelectBox" class="textbox" ONCLICK="changeSBDisplay(this.checked);"></td></tr>
                                        <div id="createsbvalue" >
                                            <tr><td  align="left" valign="top"><a title="<?php echo VALID_CUSTOMFRM_ADD;?>" class="masterTooltip" ><?php echo CUSTOM_SELECT_ADD;?> </a></td><td  width="2%">&nbsp;</td>
                                                <td align="left">
                                                    <div align="">
                                                        <span class="info_text"><a title="<?php echo VALID_CUSTOMFRM_ITEM;?>" class="masterTooltip" ><?php echo CUSTOM_ITEM_DISPLAY;?>:&nbsp;&nbsp;</a><span style="color: red;">*</span></span><input type="text" name="txtSBDisplayField" size="15" maxlength="50" class="textbox_style2"><br>
                                                        <br><span class="info_text"><a title="<?php echo VALID_CUSTOMFRM_VALUE;?>" class="masterTooltip" ><?php echo CUSTOM_ITEM_ACTUAL;?>:&nbsp;</a></span><input type="text" name="txtSBActualValue" size="10" maxlength="50" class="textbox_style2"><br>
                                                        <br><span style="font-size: 11px; color: #999;"><b><?php echo CUSTOM_ITEM_NOTE;?> </b> :<?php echo CUSTOM_ITEM_NOTE2;?></span>
                                                        <br><br><input type="checkbox" name="chkSBSelected">&nbsp;<?php echo CUSTOM_SELECTED;?><br>
                                                        <div class="">
                                                            <br>
                                                            <input type="button" name="btnAddSBItem" onClick="addSBItem();" value="Add SelectBox Item" class="small_btnstyle right" style="width:166px; ">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                        <tr>
                                            <td width="100%" align="left" colspan="3">
                                                <div id="sbvalues" style="display:none;">
                                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                                        <tr><td  align="left" width="50%" valign='top'><?php echo CUSTOM_EXISTING;?></td><td  width="2%">&nbsp;</td>
                                                            <td align="left" >
                                                                <div id="sboutput" align='left'>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><td width="100%" align="left" colspan="3"></td></tr>
                                        <tr><td width="100%" align="left" colspan="3"></td></tr>
                                        <tr><td width="100%" align="center" colspan="3" class="border_top1"><input type="button" name="btnSelectbox" class="small_btnstyle" value="Add Selectbox to Form" onClick="addSelectBox();"></td></tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div id="checkbox" style="display:none;">
                                <fieldset>
                                    <legend><?php echo CUSTOM_CHECKBOX;?></legend>
                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                        <tr><td width="50%" align="left"><a title="<?php echo VALID_CUSTOMFRM_CHECKBOX;?>" class="masterTooltip" ><?php echo CUSTOM_CHECKBOX_TEXT;?> </a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtCheckBoxDisplayText" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_CHECKBOX_NAME;?>" class="masterTooltip" ><?php echo CUSTOM_CHECKBOX_NAME;?> </a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtCheckBoxName" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <div id="createcbvalue" >
                                            <tr><td  align="left" valign="top"><a title="<?php echo VALID_CUSTOMFRM_CHECKBOX_VALUE;?>" class="masterTooltip" ><?php echo CUSTOM_SELECT_ADD;?></a></td><td  width="2%">&nbsp;</td>
                                                <td align="left">
                                                    <div align="">
                                                        <span class="info_text"><a title="<?php echo VALID_CUSTOMFRM_CHECKBOX_TEXT;?>" class="masterTooltip" ><?php echo CUSTOM_ITEM_DISPLAYTXT;?>:&nbsp;&nbsp;</a></span><span style="color: red;">*</span><input type="text" name="txtCBDisplayField" size="15" maxlength="50" class="textbox_style2">
                                                        <br>
                                                        <br><span class="info_text"><a title="<?php echo VALID_CUSTOMFRM_VALUE;?>" class="masterTooltip" ><?php echo CUSTOM_ITEM_ACTUAL;?>:&nbsp;</a></span><input type="text" name="txtCBActualValue" size="10" maxlength="50" class="textbox_style2">
                                                        <br><br><span style="font-size: 11px; color: #999;"><b><?php echo CUSTOM_ITEM_NOTE;?> </b> :<?php echo CUSTOM_ITEM_NOTE2;?></span>
                                                        <br>
                                                        <br><input type="checkbox" name="chkCBChecked">&nbsp;<?php echo CUSTOM_CHECKED;?>
                                                        <br>
                                                        <br><input type="button" name="btnAddCBItem" onClick="addCBItem();" value="Add CheckBox Item" class="small_btnstyle right" style="width:166px;">
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                        <tr>
                                            <td width="100%" align="left" colspan="3">
                                                <div id="cbvalues" style="display:none;">
                                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                                        <tr><td  align="left" width="50%" valign="top"><?php echo CUSTOM_EXISTING;?></td><td  width="2%">&nbsp;</td>
                                                            <td align="left" >
                                                                <div id="cboutput" align='left'>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><td width="100%" align="left" colspan="3"></td></tr>
                                        <tr><td width="100%" align="left" colspan="3"></td></tr>
                                        <tr><td width="100%" align="center" colspan="3" class="border_top1"><input type="button" name="btnCheckbox" class="small_btnstyle" value="Add Checkbox to Form" onClick="addCheckBox();"></td></tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div id="radiobutton" style="display:none;">
                                <fieldset>
                                    <legend><?php echo CUSTOM_CHECKBOX_GROUP;?></legend>
                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                        <tr><td width="50%" align="left"><a title="<?php echo VALID_CUSTOMFRM_RADIO;?>" class="masterTooltip" ><?php echo CUSTOM_RADIO;?> </a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtRadioButtonDisplayText" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <tr><td  align="left"><a title="<?php echo VALID_CUSTOMFRM_RADIONAME;?>" class="masterTooltip" ><?php echo CUSTOM_RADIO_NAME;?> </a><font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtRadioButtonName" size="15" maxlength="50" class="textbox_style2"></td></tr>
                                        <div id="createrbvalue" >
                                            <tr><td  align="left" valign="top"><a title="<?php echo VALID_CUSTOMFRM_RADIOVALUE;?>" class="masterTooltip" ><?php echo CUSTOM_SELECT_ADD;?></a></td><td  width="2%">&nbsp;</td>
                                                <td align="left">
                                                    <div >
                                                        <span class="info_text"><a title="<?php echo VALID_CUSTOMFRM_CHECKBOX_TEXT;?>" class="masterTooltip" ><?php echo CUSTOM_ITEM_DISPLAYTXT;?>&nbsp;&nbsp;</a></span><span style="color: red;">*</span></div><input type="text" name="txtRBDisplayField" size="15" maxlength="50" class="textbox_style2">
                                                    <br>
                                                    <br><span class="info_text"><a title="<?php echo VALID_CUSTOMFRM_VALUE;?>" class="masterTooltip" ><?php echo CUSTOM_ITEM_ACTUAL;?>&nbsp;</a></span></div><input type="text" name="txtRBActualValue" size="10" maxlength="50" class="textbox_style2">

                                        <br><br><span style="font-size: 11px; color: #999;"><b><?php echo CUSTOM_ITEM_NOTE;?> </b><?php echo CUSTOM_ITEM_NOTE2;?></span>
                                        <br>
                                        <br><input type="checkbox" name="chkRBChecked">&nbsp;<?php echo CUSTOM_CHECKED;?>
                                        <br>
                                        <br><input type="button" name="btnAddRBItem" onClick="addRBItem();" value="Add RadioButton Item" class="small_btnstyle right" style="width:166px;">
                                        </div>
                                        </td>
                                        </tr>
                                        </div>
                                        <tr>
                                            <td width="100%" align="left" colspan="3">
                                                <div id="rbvalues" style="display:none;">
                                                    <table cellpadding="2" cellspacing="2" border=0 width="100%">
                                                        <tr><td  align="left" width="50%" valign="top"><?php echo CUSTOM_EXISTING;?></td><td  width="2%">&nbsp;</td>
                                                            <td align="left" >
                                                                <div id="rboutput" align='left'>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><td width="100%" align="left" colspan="3"></td></tr>
                                        <tr><td width="100%" align="left" colspan="3"></td></tr>
                                        <tr><td width="100%" align="center" colspan="3" class="border_top1"><input type="button" name="btnRadioButton" class="small_btnstyle" value="Add Radiobutton to Form" onClick=" return addRadioGroup();"></td></tr>
                                    </table>
                                </fieldset>
                            </div>

                        </td>
                    </tr>
                </table>
            </fieldset>
        </td>
    </tr>
    
    <tr>
        <td colspan="3" align="center">
            <fieldset>
                <legend><?php echo CUSTOM_OUTPUT;?></legend>
                <div class="editor_form_items">
                    <div id="output" style="display:" >
                        <?php echo $formOutPut;?>
                    </div>
                </div>
            </fieldset>
        </td>
    </tr>


    <!--tr>
        <td colspan="3" align="center">
                <input type="button" name="btnSubmit"  class="button"  value="Create Custom Form" onclick="return validateForm();" >&nbsp;&nbsp;
                <input type="button" name="btnBack"  value="Back" class="button" onClick="javascript:clickBack();" >
                <input type="button" name="btnBack"  value="Back" class="button" onClick="window.location.href='selectsite.php?page=customform';" >
        </td>
    </tr-->
</table>

<div class="popupeditpanel_ftr">
    <input type="button" name="btn_editor_updateCustomForm" id="btn_editor_updateCustomForm" value="Update" class="popup_orngbtn right">
    <div class="clear"></div>
</div>

<?php


?>
<!--
<div class="editor_form_items">
                <div class="editor_form_items_row">
                    <div class="editor_form_items_col1">
                        Textbox Display Text <font color="RED">*</font>
                    </div>
                    <div class="editor_form_items_col2">
                        <input type="text" name="txtDisplayText" size="15" maxlength="50" class="textbox_style3">
                    </div>
                    <div class="editor_form_items_col3">Delete</div>
                </div>
                <div class="editor_form_items_row">
                    <div class="editor_form_items_col1">
                        Textarea Display Text <font color="RED">*</font>
                    </div>
                    <div class="editor_form_items_col2">
                        <input type="text" name="txtTextAreaDisplayText" size="15" maxlength="50" class="textarea_style3">
                    </div>
                    <div class="editor_form_items_col3">Delete</div>
                </div>
                <div class="editor_form_items_row">
                    <div class="editor_form_items_col1">
                        Selectbox Display Text <font color="RED">*</font>
                    </div>
                    <div class="editor_form_items_col2">
                        <select class="select_style3">
                            <option selected> Select Box </option>
                            <option>Short Option</option>
                            <option>This Is A Longer Option</option>
                        </select>
                    </div>
                    <div class="editor_form_items_col3">Delete</div>
                </div>
                <div class="editor_form_items_row">
                    <div class="editor_form_items_col1">
                        Radio button Display Text <font color="RED">*</font>
                    </div>
                    <div class="editor_form_items_col2">
                        <input id="male" type="radio" name="gender" value="male">
                        <label for="male">Male</label>
                        <input id="female" type="radio" name="gender" value="female">
                        <label for="female">Female</label>
                    </div>
                    <div class="editor_form_items_col3">Delete</div>
                </div>
                <div class="editor_form_items_row">
                    <div class="editor_form_items_col1">
                        Checkbox Display Text <font color="RED">*</font>
                    </div>
                    <div class="editor_form_items_col2">
                        <input id="check1" type="checkbox" name="check" value="check1">
                        <label for="check1">Checkbox1</label>
                        <input id="check2" type="checkbox" name="check" value="check2">
                        <label for="check2">Checkbox2</label>
                    </div>
                    <div class="editor_form_items_col3">Delete</div>
                </div>
            </div> -->