<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>                                  |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
} 
if (isset($_GET["siteid"]) and $_GET["siteid"] != "") {
    $siteid = $_GET["siteid"];
} else if (isset($_POST["siteid"]) and $_POST["siteid"] != "") {
    $siteid = $_POST["siteid"];
} 
if (isset($_GET["sitetype"]) and $_GET["sitetype"] != "") {
    $sitetype = $_GET["sitetype"];
} else if (isset($_POST["sitetype"]) and $_POST["sitetype"] != "") {
    $sitetype = $_POST["sitetype"];
} 
if (isset($_GET["templatetype"]) and $_GET["templatetype"] != "") {
    $templatetype = $_GET["templatetype"];
} else if (isset($_POST["templatetype"]) and $_POST["templatetype"] != "") {
    $templatetype = $_POST["templatetype"];
} 

if (isset($_GET["templateid"]) and $_GET["templateid"] != "") {
    $templateid = $_GET["templateid"];
} else if (isset($_POST["templateid"]) and $_POST["templateid"] != "") {
    $templateid = $_POST["templateid"];
} 
if ($sitetype == "completed") {//populate the variables for usage depending on whether the site is completed or not
    $sitemaster = "tbl_site_mast";
    $sitepagetable = "tbl_site_pages";
    $siteidfield = "nsite_id";
    $sitefoldername = "./sites/" . $siteid;
    $sitepagesfile = "";
    $ptype = "edit";
} else {
    $sitemaster = "tbl_tempsite_mast";
    $sitepagetable = "tbl_tempsite_pages";
    $siteidfield = "ntempsite_id";
    $sitefoldername = "./workarea/tempsites/" . $siteid;
    $sitepagesfoldername = "./sitepages/tempsites/" . $siteid;
    $ptype = "new";
} 

$fieldlist = array();//for storing the types of inputs that can be created by the user
$fieldlist["1"] = "Text Box";
$fieldlist["2"] = "Text Area";
$fieldlist["3"] = "Select Box";
$fieldlist["4"] = "Checkbox";
$fieldlist["5"] = "Radiobutton";

$pages = array();
$sql = "SELECT vpage_name FROM " . $sitepagetable . "  WHERE " . $siteidfield . "='" . addslashes($siteid) . "' ";
$res = mysql_query($sql);
if (mysql_num_rows($res) != 0) {
    while ($row = mysql_fetch_array($res)) {
        array_push($pages, strtolower($row["vpage_name"])) ;
    } 
} 
$pages = array_unique(array_values($pages));

if($_POST["postback"] == "Back"){
	header("Location:".$_SESSION["gbackurl"]);
	exit;
}

if ($_POST["btnSubmit"] == "Create Custom Form") {
    $message = "";
    $txtEmailAddress = $_POST["txtEmailAddress"];//the form will be mailed to this email address on submission
    $txtPageName = $_POST["txtPageName"];//the actual page name
    $txtPageDisplayName = $_POST["txtPageDisplayName"];//for display in links
    $txtPageHeading = $_POST["txtPageHeading"];//the heading of the page
    $formelements = $_POST["formelements"];//holds the form elements to be embedded in the page

    $formstart = "<table cellspacing='2' cellpadding='2' width='100%' border='0'><tr><td align='center'>" . $txtPageHeading . "</td></tr><tr><td>";

    $formend = "</td></tr></table>";

    $formtext = $formstart . $formelements . $formend;

    if ($message != "") {
        $message = "<br>Please correct the following errors to continue!" . $message;
    } 
    if ($message == "") {//if no error, proceed with creating page, inserting to database etc
        $sql = "SELECT sm.vlinks, sm.vsub_sitelinks, tm.vlink_separator,tm.vsublink_separator,tm.vlink_type,tm.vsublink_type FROM " . $sitemaster . " sm INNER JOIN tbl_template_mast tm ON sm.ntemplate_id = tm.ntemplate_mast ";
        $sql .= " WHERE sm." . $siteidfield . "  = '" . addslashes($siteid) . "'"; 
        // echo $sql;
        $res = mysql_query($sql);
        if (mysql_num_rows($res) != 0) {
            $row = mysql_fetch_array($res);
            $links = $row["vlinks"];
            $sublinks = $row["vsub_sitelinks"];
            $linkseparator = $row["vlink_separator"];
            $sublinkseparator = $row["vsublink_separator"];
            $linktype = $row["vlink_type"];
            $sublinktype = $row["vsublink_type"];
            $pagelink = "<a class=anchor1 href='./" . $txtPageName . "'>" . $txtPageDisplayName . "</a>";
            if ($linktype == "horizontal") {
                $newlink = $links . $linkseparator . $pagelink;
            } else if ($linktype == "vertical") {
                $newlink = $links . $linkseparator . $pagelink . "<br>";
            } 

            if ($sublinktype == "horizontal") {
                $newsublink = $sublinks . $sublinkseparator . $pagelink;
            } else if ($sublinktype == "vertical") {
                $newsublink = $sublinks . $sublinkseparator . $pagelink . "<br>";
            } 

            if ($links != "") {
                $sql = "UPDATE " . $sitemaster . " SET vlinks = '" . addslashes($newlink) . "', vsub_sitelinks='" . addslashes($newsublink) . "' WHERE " . $siteidfield . " = '" . addslashes($siteid) . "' ";
                mysql_query($sql);//adding links to database
                $pagename = $txtPageDisplayName;
                $filename = $txtPageName;
                $pagetitle = $txtPageDisplayName;
                $pagetype = "custom";
                $type = "simple";

                $sql2 = "INSERT INTO " . $sitepagetable . "(" . $siteidfield . ", vpage_name,vpage_title,vpage_type,vtype) VALUES ('" . addslashes($siteid) . "','" . addslashes($pagename) . "','" . addslashes($pagetitle) . "','" . addslashes($pagetype) . "','" . addslashes($type) . "') ";
                mysql_query($sql2); //adding the new page to database

                $newfilename = $sitefoldername . "/" . $filename;
                if ($sitepagesfoldername != "") {//if it is a temp site(not completed), then create a file in the sitepages folder also
                    $newsitepagefilename = $sitepagesfoldername . "/" . $filename;
                    $fp1 = fopen($newsitepagefilename, "w+");
                    fwrite($fp1, $formtext);
                    fclose($fp1);
                } 

				//create file in the sites folder or workarea/tempsites folder depending on whether the site is completed or not
                $fp = fopen($newfilename, "w+");
                fwrite($fp, $formtext);
                fclose($fp);
				$filenamewithoutextension = substr($filename,0,-4);
                $link = "editsitepageoption.php?tempsiteid=" . addslashes($siteid) . "&type=" . $ptype . "&go=true&page=".$filenamewithoutextension."&msg=creatednew";
                header("Location:$link");
                exit;
            } else {//the site is created, but no files in it. Prompting to create the homepage.
                $message .= "<br>Home Page not present! Please create home page!";
            } 
        } 
    } 
    if ($message != "") {
        $message = "<br>Please correct the following errors to continue!" . $message;
    } 
} 

$pages = implode(",", $pages);
include "includes/userheader.php";

?>
<script>
	var digitsandchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	var digitscharsandspace = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789";
	var digits = "0123456789";
	var output = "";
	var firsttdwidth = 20;
	var secondtdwidth = 2;
	var thirdtdwidth = 78;
	var out = new Array();
	var citems = new Array();
	var sitems = new Array();
	var ritems = new Array();
	var pages = '<?php echo $pages; ?>';	// the names of pages for cheking against duplicate file names
	
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
	function setPageName(){
		var frm = document.frmCreateCustomForm;
		if(frm.txtPageDisplayName.value.length == 0){
			alert("Page Display Name is required!");
		}else if(!validchars(frm.txtPageDisplayName.value,digitscharsandspace)){
			alert("* Please enter a valid page display name (No special characters)!");
		}else{
			var lowerpagename = frm.txtPageDisplayName.value.toLowerCase();
			lowerpagename = trimSpaces(lowerpagename);
			if(lowerpagename!=""){
				frm.txtPageName.value = lowerpagename + ".htm";
			}
		}
		return;
	}
	
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
	function validateForm(){
		var frm = document.frmCreateCustomForm;
		var msg = "";
		var pages = '<?php echo $pages;?>';
		pages = pages.split(",");
		if(frm.txtPageDisplayName.value.length == 0){
			msg += "* Page Display Name is required!"+ "\n";
		}else if(!validchars(frm.txtPageDisplayName.value,digitscharsandspace)){
			msg += "* Please enter a valid page display name (No special characters)!"+ "\n";
		}
		if(frm.txtPageName.value.length == 0){
			msg += "* Page Name is required!"+ "\n";
		}else{
			var lowerpagename = frm.txtPageName.value.toLowerCase();
			var pagepresent = false;
			var currentpage;
			for(i=0;i<pages.length;i++){
				currentpage = pages[i] + ".htm";
				if(currentpage == lowerpagename ){
					pagepresent = true;
					break;
				}
			}
			if(pagepresent){
				msg += "* Page with the same name exists! Please enter a unique page name!"+ "\n";
			}else if((lowerpagename == "feedback.htm") || (lowerpagename == "guestbook.htm") ){
				msg += "* Feedback and Guestbook pages require to be created by Integration Manager. Please rename the page!"+ "\n";
			}
		}
		
		if(frm.txtPageHeading.value.length == 0){
			msg += "* Page Heading is required!"+ "\n";
		}
		if(frm.txtEmailAddress.value.length == 0){
			msg += "* Email Address is required!"+ "\n";
		}else if(!checkMail(frm.txtEmailAddress.value)){
			msg += "* Invalid Email! Please enter a valid email address!"+ "\n";
		}
		if(out.length == 0){
			msg += "* Please add elements to your form!"+ "\n";
		}
		if(msg != ""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
			alert(msg);
			return false;
		}else{
			frm.formelements.value = getOutput(out,true);
			return true;
		}
	}
	
	/*
	*Toggles the display of entry fields for creating different input elements like textbox, 
	*textarea, selectbox, checkbox and radiobutton
	*It is called on the change of dropdown list 'ddlFields'
	*/
	function displayOptions(val){
		var frm = document.frmCreateCustomForm;
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
		var textboxtext = '<input type = text name='+ tbname +' value = "' + tbval + '" ';
		if(!isNaN(tbsize)){
			textboxtext += ' size='+tbsize;
		}
		if(!isNaN(tbmaxlen)){
			textboxtext += ' maxlength='+tbmaxlen;
		}
		textboxtext += ' >';
		return textboxtext;
	}
	
	/*
	*returns input text for a textarea with given name, value, rows and cols
	*will be something like '<textarea name="address" rows="5" cols= "40">Some address</textarea>'
	*/
	function getTextArea(taname,tavalue,tarows,tacols){
		var textareatext = '<textarea name='+ taname ;
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
		var frm = document.frmCreateCustomForm;
		var msg = "";
		var tbsize = parseInt(frm.txtTextboxSize.value);
		var tbmaxlen = parseInt(frm.txtTextboxMaxLength.value);
		var tbox = "";
		if(frm.txtDisplayText.value == ""){
			msg += "* Textbox Display Text is required!"+ "\n";
		}else if(!validchars(frm.txtDisplayText.value,digitscharsandspace)){
			msg += "* Please enter a valid Display Text for the Textbox!"+ "\n";
		}
		if(frm.txtTextboxName.value == ""){
			msg += "* Textbox Name is required!"+ "\n";
		}else{
			if(!validchars(frm.txtTextboxName.value,digitsandchars)){
				msg += "* Please enter a valid field name for the Textbox!"+ "\n";
			}
		}
		
		if(frm.txtTextboxValue.value != ""){
			if(!validchars(frm.txtTextboxValue.value,digitscharsandspace)){
				msg += "* Please enter a valid value for the Textbox (Only digits, characters and space allowed)!"+ "\n";
			}
		}
		
		if(frm.txtTextboxSize.value.length >0){
			if(isNaN(tbsize) || tbsize<=0 || !validchars(tbsize,digits)){//if(isNaN(tbsize) || tbsize<=0 || !validchars(tbsize,"0123456789") ){
				msg += "* Please enter a positive numeric value for Textbox Size!"+ "\n";
				frm.txtTextboxSize.value = "";
			}else{
				frm.txtTextboxSize.value = tbsize;
			}
		}
		
		if(frm.txtTextboxMaxLength.value.length >0){
			if(isNaN(tbmaxlen) || tbmaxlen<=0 || !validchars(tbmaxlen,digits)){
				msg += "* Please enter a positive numeric value for Textbox Max Length!"+ "\n";
				frm.txtTextboxMaxLength.value = "";
			}else{
				frm.txtTextboxMaxLength.value = tbmaxlen;
			}
		}
		if(msg != ""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
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
				}else{
					alert("An element with the same name already exists! Please enter a unique Field Name for the Textbox!");
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
		var frm = document.frmCreateCustomForm;
		var msg = "";
		var tarows = parseInt(frm.txtTextareaRows.value);
		var tacols = parseInt(frm.txtTextareaColumns.value);
		var tbox = "";
		if(frm.txtTextAreaDisplayText.value == ""){
			msg += "* Textarea Display Text is required!"+ "\n";
		}else if(!validchars(frm.txtTextAreaDisplayText.value,digitscharsandspace)){
			msg += "* Please enter a valid Display Text for the Textarea!"+ "\n";
		}
		if(frm.txtTextareaName.value == ""){
			msg += "* Textarea Name is required!"+ "\n";
		}else{
			if(!validchars(frm.txtTextareaName.value,digitsandchars)){
				msg += "* Please enter a valid field name for the Textarea!"+ "\n";
			}
		}
		
		if(frm.txtTextareaRows.value.length >0){
			if(isNaN(tarows) || tarows<=0 || !validchars(tarows,digits)){
				msg += "* Please enter a positive numeric value for Textarea Rows!"+ "\n";
				frm.txtTextareaRows.value = "";
			}else{
				frm.txtTextareaRows.value = tarows;
			}
		}
		
		if(frm.txtTextareaColumns.value.length >0){
			if(isNaN(tacols) || tacols<=0 || !validchars(tacols,digits)){
				msg += "* Please enter a positive numeric value for Textarea Columns!"+ "\n";
				frm.txtTextareaColumns.value = "";
			}else{
				frm.txtTextareaColumns.value = tacols;
			}
		}
		if(msg != ""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
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
				}else{
					alert("An element with the same name already exists! Please enter a unique Field Name for the Textarea!");
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
		outtr += '<tr><td width=' + firsttdwidth + '% align=left valign=top>' + inputnametext + '</td>';
		outtr += '<td width=' + secondtdwidth + '% align=left valign=top>&nbsp;</td>';
		outtr += '<td width=' + thirdtdwidth + '% align=left valign=top><div align=left>' + inputfield;
		if(!finalout){
			outtr += getManipulationLinks(arraycount,currentelement);
			
		}
		
		outtr += '</div></td></tr>';
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
		var outtext = '';
		if(finalout){
			outtext += '<form name=frmCustom method="POST" action="mailto:'+ frm.txtEmailAddress.value +'" >';
			//outtext += '<input type="hidden" name="mailfrom" value="test@test.com">';
		}else{
			outtext += '<fieldset><legend>Output</legend>';	
			if(frm.txtPageHeading.value !=""){
				outtext += '<br><center>'+frm.txtPageHeading.value+'</center><br>';
			}
				
		}
		outtext += '<table width=100% cellpadding=2 cellspacing=2 border=0>';	
		for(i=0;i<inputarray.length;i++){
			outtext += getOutTR(inputarray[i][0],inputarray[i][1],finalout,inputarray.length,i);
		}
		outtext += '</table>';	
		
		if(finalout){
			outtext += '<center><input type="submit" value="Submit Form"></center>';
			outtext += '</form>';
		}else{
			outtext += '</fieldset>';	
		}
		
		return outtext;
	}
	
	/*
	*Adds a select box item to the selectbox temporarily displayed in the preview area
	*The new item is added to the array 'sitems' and it is displayed in a div called 'sboutput'
	*/
	function addSBItem(){
		var frm = document.frmCreateCustomForm;
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
			msg += "* Item Display Text is required!"+ "\n";
		}else{
			if(!validchars(frm.txtSBDisplayField.value,digitscharsandspace)){
				msg += "* Please enter a valid display text for the Selectbox Item (Only digits, characters and space allowed)!"+ "\n";
			}
		}
		
		
		if(itemactualvalue == ""){
			itemactualvalue = itemdisplayfield;
		}else if(!validchars(frm.txtSBActualValue.value,digitscharsandspace)){
			msg += "* Please enter a valid value for the Selectbox Item (Only digits, characters and space allowed)!"+ "\n";
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
			msg += "* Item already present!"+ "\n";
		}
		if(msg !=""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
			alert(msg);
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
		var frm = document.frmCreateCustomForm;
		var sbout = '';
		if(sbname == ''){
			sbname ='dummySBname29890980293';
		}
		sbout += '<select name="'+ sbname +'" ';
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
		var msg = "";
		var sbox = "";
		if(frm.txtSelectBoxDisplayText.value == ""){
			msg += "* Selectbox Display Text is required!"+ "\n";
		}else if(!validchars(frm.txtSelectBoxDisplayText.value,digitscharsandspace)){
			msg += "* Please enter a valid Display Text for the Selectbox!"+ "\n";
		}
		if(frm.txtSelectBoxName.value == ""){
			msg += "* Selectbox Name is required!"+ "\n";
		}else{
			if(!validchars(frm.txtSelectBoxName.value,digitsandchars)){
				msg += "* Please enter a valid field name for the Selectbox!"+ "\n";
			}
		}
		
		if(sitems.length == 0){
			msg += "* Selectbox Items required! Please add the selectbox items!"+ "\n";
		}
		if(msg != ""){
			msg = "Please correct the following error to continue!"+ "\n" +msg;
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
				}else{
					alert("An element with the same name already exists! Please enter a unique Field Name for the Selectbox!");
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
		var frm = document.frmCreateCustomForm;
		var msg = "";
		var cbox = "";
				
		if(frm.txtCheckBoxDisplayText.value == ""){
			msg += "* Checkbox Display Text is required!"+ "\n";
		}else if(!validchars(frm.txtCheckBoxDisplayText.value,digitscharsandspace)){
			msg += "* Please enter a valid Display Text for the Checkbox!"+ "\n";
		}
		if(frm.txtCheckBoxName.value == ""){
			msg += "* Checkbox Name is required!"+ "\n";
		}else{
			if(!validchars(frm.txtCheckBoxName.value,digitsandchars)){
				msg += "* Please enter a valid field name for the Checkbox!"+ "\n";
			}
		}
		
		if(citems.length == 0){
			msg += "* Checkbox Items required! Please add the checkbox items!"+ "\n";
		}
		if(msg !=""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
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
					out.push(newarr);
					frm.txtCheckBoxDisplayText.value = '';
					frm.txtCheckBoxName.value = '';
					document.getElementById("cbvalues").style.display = "none";
					var outp = getOutput(out,false);
					x = document.getElementById("output");
					x.innerHTML = '';
					x.innerHTML = outp;
					citems.length = 0;
				}else{
					alert("An element with the same name already exists! Please enter a unique Field Name for the Checkbox!");
				}
				
			} 
		}
	}
	
	/*
	*Adds a check box item to the checkbox temporarily displayed in the preview area
	*The new item is added to the array 'citems' and it is displayed in a div called 'cboutput'
	*/
	function addCBItem(){
		var frm = document.frmCreateCustomForm;
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
			msg += "* Item Display Text is required!"+ "\n";
		}else{
			if(!validchars(frm.txtCBDisplayField.value,digitscharsandspace)){
				msg += "* Please enter a valid display text for the Checktbox Item (Only digits, characters and space allowed)!"+ "\n";
			}
		}
		
		if(itemactualvalue == ""){
			itemactualvalue = itemdisplayfield;
		}else if(!validchars(frm.txtCBActualValue.value,digitscharsandspace)){
			msg += "* Please enter a valid value for the Checkbox Item (Only digits, characters and space allowed)!"+ "\n";
		}
		if(cbItemPresent(citems,itemdisplayfield,itemactualvalue)){
			msg += "* Checkbox Item already present/Duplicate value!"+ "\n";
		}
		if(msg !=""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
			alert(msg);
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
			msg += "* Item Display Text is required!"+ "\n";
		}else{
			if(!validchars(frm.txtRBDisplayField.value,digitscharsandspace)){
				msg += "* Please enter a valid display text for the Radiobutton Item (Only digits, characters and space allowed)!"+ "\n";
			}
		}
		
		if(itemactualvalue == ""){
			itemactualvalue = itemdisplayfield;
		}else if(!validchars(frm.txtRBActualValue.value,digitscharsandspace)){
			msg += "* Please enter a valid value for the Radiobutton Item (Only digits, characters and space allowed)!"+ "\n";
		}
		if(rbItemPresent(ritems,itemdisplayfield,itemactualvalue)){
			msg += "* Radiobutton Item already present/Duplicate value!"+ "\n";
		}
		if(itemselected){
			if(rbItemAlreadyChecked(ritems)){
				msg += "* The radio group contains a checked item! A radio group cannot have more than one checked item!"+ "\n";
			}
		}
		
		if(msg !=""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
			alert(msg);
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
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
		var frm = document.frmCreateCustomForm;
		var msg = "";
		var rbox = "";
		
		if(frm.txtRadioButtonDisplayText.value == ""){
			msg += "* Radiobutton Display Text is required!"+ "\n";
		}else if(!validchars(frm.txtRadioButtonDisplayText.value,digitscharsandspace)){
			msg += "* Please enter a valid Display Text for the Radiobutton!"+ "\n";
		}
		if(frm.txtRadioButtonName.value == ""){
			msg += "* Radiobutton Name is required!"+ "\n";
		}else{
			if(!validchars(frm.txtRadioButtonName.value,digitsandchars)){
				msg += "* Please enter a valid field name for the Radiobutton!"+ "\n";
			}
		}
		
		
		if(ritems.length == 0){
			msg += "* Radiobutton Items required! Please add the radio button items!"+ "\n";
		}
		if(msg !=""){
			msg = "Please correct the following errors to continue!"+ "\n" +msg;
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
					out.push(newarr);
					frm.txtRadioButtonDisplayText.value = '';
					frm.txtRadioButtonName.value = '';
					document.getElementById("rbvalues").style.display = "none";
					var outp = getOutput(out,false);
					x = document.getElementById("output");
					x.innerHTML = '';
					x.innerHTML = outp;
					ritems.length = 0;
				}else{
					alert("An element with the same name already exists! Please enter a unique Field Name for the Radiobutton!");
				}
				
			} 
		}
	}
	/*
	*Function to go back
	*/
	function clickBack(){
        document.frmCreateCustomForm.postback.value="Back";
        document.frmCreateCustomForm.submit();
		//window.location.href='selectsite.php?page=feedback';
	}
</script>

<table width="94%"  border="0" cellspacing="0" cellpadding="0" class="text">
	<tr>
		<td  valign="top" align=center>
   			<table  width="100%"  border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td align="center"><img src="images/createcustomform.gif" ></td>
            	</tr>
				<tr><td align="left">&nbsp;</td></tr>
				<form name="frmCreateCustomForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return validateForm();">
				<input type="hidden" name="postback" value="">
				<input type="hidden" name="sitetype" value="<?php echo htmlentities($sitetype); ?>">
				<input type="hidden" name="siteid" value="<?php echo htmlentities($siteid); ?>">
				<input type="hidden" name="formelements" value="">
				<input type="hidden" name="templatetype" value="<?php echo htmlentities($templatetype); ?>">
				<input type="hidden" name="templateid" value="<?php echo htmlentities($templateid); ?>">
				<?php
				if ($templatetype == "advanced") {
    				$url = "code/editor.php?type=" . $ptype . "&actiontype=editsite&templateid=" . $templateid . "&tempsiteid=" . $siteid;    ?>
				<tr><td align="left">Please add your custom page by going to the <a href="<?php echo $url;    ?>">advanced editor</a>!</td></tr>
				<tr><td align="left">&nbsp;</td></tr>
				<tr><td align="left"><input type="button" name="btnBack"  value="Back" class="button" onClick="window.location.href='selectsite.php?page=customform';" ></td></tr>
				<?php } else if ($templatetype == "simple") { 
					if(!validateSizePerUser($_SESSION["session_userid"],$size_per_user,$allowed_space)) {
           								$errorinlink = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete 
		   									unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>";
										echo "<tr><td align='left' class='redtext'>".$errorinlink."</td></tr>";
										echo "<tr><td align='center' ><a href='integrationmanager.php'>Back to Integration Manager</a></td></tr>";
					}else{
					?>
				<tr>
					<td>
                                            
                                            <div class="form-pnl">
                                               
                                                <?php if($message){ ?>
                                                <div class="<?php echo $messageClass;?>"><?php  echo $message;?></div>
                                                <?php } ?>
                                                <ul>
                                                    <li>
                                                        <label>Page Display Name <sup>*</sup></label>
                                                        <input type="text" class="textbox" size="30" maxlength="100" name="txtPageDisplayName" value="<?php echo htmlentities($txtPageDisplayName)?>" onBlur="setPageName();" >&nbsp;For display in menu and links
                                                    </li>
                                                    <li>
                                                        <label>Page Name (Actual)<sup>*</sup></label>
                                                        <input type="text" class="textbox" size="30" maxlength="100" name="txtPageName" value="<?php echo htmlentities($txtPageName)?>" readonly>
                                                    </li>
                                                    <li>
                                                        <label>Page Name (Actual)<sup>*</sup></label>
                                                        <input type="text" class="textbox" size="30" maxlength="100" name="txtPageName" value="<?php echo htmlentities($txtPageName)?>" readonly>
                                                    </li>
                                                    <?php if($thumpnaail !=""){ ?>
                                                    <li>
                                                        <label>&nbsp;</label>
                                                        <img border=1 src="<?php echo $thumpnaail?>">
                                                    </li>
                                                    <?php } ?>
                                                    <li>
                                                        <label>&nbsp;</label>
                                                        <span class="btn-container">
                                                            <input type="button" name="btnBack" value="Back" class="btn02"  onClick="clickback();" >
                                                            <?php
                                                            if(isset($catid) AND $catid != "" ){?>
                                                                <input type="submit" name="btnSaveChanges" value="Update" class="btn01">
                                                            <?php }else{?>
                                                                <input type="submit" name="btnAddNewCategory" value="Add" class="btn01">
                                                            <?php } ?>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            
						
							<!--<table cellpadding="2" cellspacing="2" width="100%">
								<tr><td colspan="3" align="left" class="redtext"><?php echo $message; ?></td></tr>
								<tr><td colspan="3" align="center">&nbsp;</td></tr>
								<tr><td width="23%" align="left">Page Display Name<font color="RED">*</font></td><td width="2%">&nbsp;</td><td width="75%" align="left"><input type="text" class="textbox" size="30" maxlength="100" name="txtPageDisplayName" value="<?php echo htmlentities($txtPageDisplayName)?>" onBlur="setPageName();" >&nbsp;For display in menu and links</td></tr>
								<tr><td width="23%" align="left">Page Name (Actual)<font color="RED">*</font></td><td width="2%">&nbsp;</td><td width="75%" align="left"><input type="text" class="textbox" size="30" maxlength="100" name="txtPageName" value="<?php echo htmlentities($txtPageName)?>" readonly></td></tr>
								
								<tr><td align="left">Page Heading<font color="RED">*</font></td><td>&nbsp;</td><td align="left"><input type="text" class="textbox" size="30" maxlength="100" name="txtPageHeading" value="<?php echo htmlentities($txtPageHeading)?>"></td></tr>
								<tr><td align="left">Your EMail Address<font color="RED">*</font></td><td>&nbsp;</td><td align="left"><input type="text" class="textbox" size="30" maxlength="100" name="txtEmailAddress" value="<?php echo htmlentities($txtEmailAddress)?>"></td></tr>
								<tr>
									<td colspan="3" align="center">
										<fieldset>
											<legend>Add New Field</legend>
											<table cellpadding="2" cellspacing="2" border=0 width="100%">
												<tr>
													<td width="20%" valign="top"><?php echo makeDropDownList("ddlFields", $fieldlist, $ddlFields, false, "textbox" , "", " onChange='displayOptions(this.value);' ")?></td>
													<td  width="2%">&nbsp;</td>
													<td valign="top">
														<div id="textbox" style="display:;">
															<fieldset>
																<legend>Create Textbox</legend>
																<table cellpadding="2" cellspacing="2" border=0 width="100%">
																<tr><td width="25%" align="left">Textbox Display Text <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtDisplayText" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Textbox Name <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxName" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Value</td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxValue" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td align="left">Size</td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxSize" size="20" maxlength="2" class="textbox"></td></tr>
																	<tr><td  align="left">Max Length</td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextboxMaxLength" size="20" maxlength="3" class="textbox"></td></tr>
																	<tr><td width="100%" align="center" colspan="3"><input type="button" name="btnAddTextBox" class="button" value="Add Textbox" onClick="addTextBox();"></td></tr>
																</table>
															</fieldset>
														</div>
														<div id="textarea" style="display:none;">
															<fieldset>
																<legend>Create Textarea</legend>
																<table cellpadding="2" cellspacing="2" border=0 width="100%">
																<tr><td width="25%" align="left">Textarea Display Text <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextAreaDisplayText" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td   align="left">Textarea Name <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaName" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Value</td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaValue" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Rows</td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaRows" size="20" maxlength="2" class="textbox"></td></tr>
																	<tr><td  align="left">Columns</td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtTextareaColumns" size="20" maxlength="2" class="textbox"></td></tr>
																	<tr><td width="100%" align="center" colspan="3"><input type="button" name="btnAddTextArea" class="button" value="Add Textarea" onClick="addTextArea();"></td></tr>
																</table>
															</fieldset>
														</div>
														<div id="selectbox" style="display:none;">
															<fieldset>
																<legend>Create SelectBox</legend>
																<table cellpadding="2" cellspacing="2" border=0 width="100%">
																	<tr><td width="25%" align="left">Selectbox Display Text <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtSelectBoxDisplayText" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Selectbox Name <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtSelectBoxName" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Multiselect?</td><td  width="2%">&nbsp;</td><td align="left"><input type="checkbox" name="chkMultiSelectBox" class="textbox" ONCLICK="changeSBDisplay(this.checked);"></td></tr>
																		<div id="createsbvalue" >
																			<tr><td  align="left" valign="top">Add New Value</td><td  width="2%">&nbsp;</td>
																				<td align="left">
																					<div align="">
																					Item Display Field:&nbsp;&nbsp;<input type="text" name="txtSBDisplayField" size="20" maxlength="50" class="textbox">
																					<br>Item Actual Value:&nbsp;<input type="text" name="txtSBActualValue" size="10" maxlength="50" class="textbox">
																					<br><input type="checkbox" name="chkSBSelected">&nbsp;Selected
																					<br><input type="button" name="btnAddSBItem" onClick="addSBItem();" value="Add SelectBox Item" class="button">
																					</div>
																				</td>
																			</tr>
																		</div>
																	<tr>
																		<td width="100%" align="left" colspan="3">
																			<div id="sbvalues" style="display:none;">
																				<table cellpadding="2" cellspacing="2" border=0 width="100%">
																					<tr><td  align="left" width="18%" valign='top'>Existing items</td><td  width="2%">&nbsp;</td>
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
																	<tr><td width="100%" align="center" colspan="3"><input type="button" name="btnSelectbox" class="button" value="Add Selectbox to Form" onClick="addSelectBox();"></td></tr>
																</table>
															</fieldset>
														</div>
														<div id="checkbox" style="display:none;">
															<fieldset>
																<legend>Create CheckBox</legend>
																<table cellpadding="2" cellspacing="2" border=0 width="100%">
																	<tr><td width="25%" align="left">Checkbox Display Text <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtCheckBoxDisplayText" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">Checkbox Name <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtCheckBoxName" size="20" maxlength="50" class="textbox"></td></tr>
																		<div id="createcbvalue" >
																			<tr><td  align="left" valign="top">Add New Value</td><td  width="2%">&nbsp;</td>
																				<td align="left">
																					<div align="">
																					Item Display Text:&nbsp;&nbsp;<input type="text" name="txtCBDisplayField" size="20" maxlength="50" class="textbox">
																					<br>Item Actual Value:&nbsp;<input type="text" name="txtCBActualValue" size="10" maxlength="50" class="textbox">
																					<br><input type="checkbox" name="chkCBChecked">&nbsp;Checked
																					<br><input type="button" name="btnAddCBItem" onClick="addCBItem();" value="Add CheckBox Item" class="button">
																					</div>
																				</td>
																			</tr>
																		</div>
																	<tr>
																		<td width="100%" align="left" colspan="3">
																			<div id="cbvalues" style="display:none;">
																				<table cellpadding="2" cellspacing="2" border=0 width="100%">
																					<tr><td  align="left" width="18%" valign="top">Existing items</td><td  width="2%">&nbsp;</td>
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
																	<tr><td width="100%" align="center" colspan="3"><input type="button" name="btnCheckbox" class="button" value="Add Checkbox to Form" onClick="addCheckBox();"></td></tr>
																</table>
															</fieldset>
														</div>
														<div id="radiobutton" style="display:none;">
															<fieldset>
																<legend>Create RadioButton Group</legend>
																<table cellpadding="2" cellspacing="2" border=0 width="100%">
																	<tr><td width="25%" align="left">RadioButton Display Text <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtRadioButtonDisplayText" size="20" maxlength="50" class="textbox"></td></tr>
																	<tr><td  align="left">RadioButton Name <font color="RED">*</font></td><td  width="2%">&nbsp;</td><td align="left"><input type="text" name="txtRadioButtonName" size="20" maxlength="50" class="textbox"></td></tr>
																		<div id="createrbvalue" >
																			<tr><td  align="left" valign="top">Add New Value</td><td  width="2%">&nbsp;</td>
																				<td align="left">
																					<div align="">
																					Item Display Text:&nbsp;&nbsp;<input type="text" name="txtRBDisplayField" size="20" maxlength="50" class="textbox">
																					<br>Item Actual Value:&nbsp;<input type="text" name="txtRBActualValue" size="10" maxlength="50" class="textbox">
																					<br><input type="checkbox" name="chkRBChecked">&nbsp;Checked
																					<br><input type="button" name="btnAddRBItem" onClick="addRBItem();" value="Add RadioButton Item" class="button">
																					</div>
																				</td>
																			</tr>
																		</div>
																	<tr>
																		<td width="100%" align="left" colspan="3">
																			<div id="rbvalues" style="display:none;">
																				<table cellpadding="2" cellspacing="2" border=0 width="100%">
																					<tr><td  align="left" width="18%" valign="top">Existing items</td><td  width="2%">&nbsp;</td>
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
																	<tr><td width="100%" align="center" colspan="3"><input type="button" name="btnRadioButton" class="button" value="Add Radiobutton to Form" onClick=" return addRadioGroup();"></td></tr>
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
											<legend>Your Output Form</legend>
											<table cellpadding="2" cellspacing="2" border=0 width="100%">
												<tr>
													<td valign="top" colspan="3">
														<div id="output"  style="display:;" >
															
														</div>
													</td>
												</tr>
											</table>
										</fieldset>
									</td>
								</tr>
								<tr><td colspan="3" align="center"><input type="submit" name="btnSubmit"  class="button"  value="Create Custom Form" >&nbsp;&nbsp;<input type="button" name="btnBack"  value="Back" class="button" onClick="javascript:clickBack();" > <!-- <input type="button" name="btnBack"  value="Back" class="button" onClick="window.location.href='selectsite.php?page=customform';" > --></td></tr>	
							</table>-->
						
					</td>
				</tr>
				<?php }
				
				}?>
				<tr><td>&nbsp;</td></tr>
				</form>
             	<tr><td>&nbsp;</td></tr>
      		</table>
		</td>
	</tr>
</table>

<?php

include "includes/userfooter.php";

?>




