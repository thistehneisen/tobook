<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
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

if($sitetype == "completed"){//setting folder names and selecting the database tables depending on whether site is completed or not
        $sitemaster = "tbl_site_mast";
        $sitepagetable = "tbl_site_pages";
        $siteidfield = "nsite_id";
        $sitefoldername  = "./sites/".$siteid;
        $sitepagesfile = "";
        $ptype = "edit";
}else{
        $sitemaster = "tbl_tempsite_mast";
        $sitepagetable = "tbl_tempsite_pages";
        $siteidfield = "ntempsite_id";
        $sitefoldername  = "./workarea/tempsites/".$siteid;
        $sitepagesfoldername =  "./sitepages/tempsites/".$siteid;
        $ptype = "new";
}

$paramlist = array();//list of fields that can be selected
$paramlist["1"] =  "First Name";
$paramlist["2"] =  "Last Name";
$paramlist["3"] =  "Address Line 1";
$paramlist["4"] =  "Address Line 2";
$paramlist["5"] =  "City";
$paramlist["6"] =  "State";
$paramlist["7"] =  "Country";
$paramlist["8"] =  "ZIP";
$paramlist["9"] =  "Phone";
$paramlist["10"] =  "FAX";
$paramlist["11"] =  "Email";


if($_POST["btnSubmit"] == "Create Feedback Page"){
        $message = "";
        $ddlParameters = $_POST["ddlParameters"];
        $txtEmailAddress = $_POST["txtEmailAddress"];
        if(!isNotNull($txtEmailAddress)){
                $message .= "<br>* Email address is required!";
        }else{
                if(!isValidEmail($txtEmailAddress)){
                        $message .= "<br>* Invalid email address!";
                }
        }
        if(!isNotNull($ddlParameters)){
                $message .= "<br>* No fields were selected!";
        }

        if($message == ""){
                $formstart  = "<table cellspacing='2' cellpadding='2' width='100%' border='0'><tr><td>";
                $formstart .= "<form name=\"frmFeedback\" method=\"POST\" action=\"mailto:".$txtEmailAddress."\">";
                //$formstart .= "<input type=\"hidden\" name=\"mailfrom\" value=\"test@test.com\" >";
                $formend = "</form></td></tr></table>";
                $fields = "<table cellspacing='2' cellpadding='2' width='100%' border='0'>";
                $fields .= "<tr><td colspan=\"3\" align=\"center\">Feedback Form</td></tr>";
                $fields .= "<tr><td width=\"20\" align=\"left\">&nbsp;</td><td width=\"1%\">&nbsp;</td><td width=\"79%\" align=\"left\" >&nbsp;</td></tr>";
                if(in_array(1,$ddlParameters)){//First Name
                        $fields .= "<tr><td align=\"left\">First Name</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"30\"  maxlength=\"100\"  name=\"txtFirstName\" ></td></tr>";
                }
                if(in_array(2,$ddlParameters)){//Last Name
                        $fields .= "<tr><td align=\"left\">Last Name</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"30\"  maxlength=\"100\"  name=\"txtLastName\" ></td></tr>";
                }
                if(in_array(3,$ddlParameters)){//AddressLine 1
                        $fields .= "<tr><td align=\"left\">Address Line 1</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"40\"  maxlength=\"100\"  name=\"txtAddress1\" ></td></tr>";
                }
                if(in_array(4,$ddlParameters)){//AddressLine 2
                        $fields .= "<tr><td align=\"left\">Address Line 2</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"40\"  maxlength=\"100\"  name=\"txtAddress2\" ></td></tr>";
                }
                if(in_array(5,$ddlParameters)){//City
                        $fields .= "<tr><td align=\"left\">City</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"30\"  maxlength=\"100\"  name=\"txtCity\" ></td></tr>";
                }
                if(in_array(6,$ddlParameters)){//State
                        $fields .= "<tr><td align=\"left\">State</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"30\"  maxlength=\"100\"  name=\"txtState\" ></td></tr>";
                }
                if(in_array(7,$ddlParameters)){//Country
                        $fields .= "<tr><td align=\"left\">Country</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"30\"  maxlength=\"100\"  name=\"txtCountry\" ></td></tr>";
                }
                if(in_array(8,$ddlParameters)){//ZIP
                        $fields .= "<tr><td align=\"left\">ZIP</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"20\"  maxlength=\"20\"  name=\"txtZIP\" ></td></tr>";
                }
                if(in_array(9,$ddlParameters)){//Phone
                        $fields .= "<tr><td align=\"left\">Phone</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"20\"  maxlength=\"50\"  name=\"txtPhone\" ></td></tr>";
                }
                if(in_array(10,$ddlParameters)){//Fax
                        $fields .= "<tr><td align=\"left\">FAX</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"20\"  maxlength=\"50\"  name=\"txtFAX\" ></td></tr>";
                }
                if(in_array(11,$ddlParameters)){//Email
                        $fields .= "<tr><td align=\"left\">Email</td><td>&nbsp;</td><td align=\"left\" ><input type=\"textbox\"  size=\"30\"  maxlength=\"100\"  name=\"txtEmail\" ></td></tr>";
                }
                $fields .= "<tr><td colspan=\"3\" align=\"center\">&nbsp;</td></tr>";
                $fields .= "<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" name=\"btnSubmit\" value=\"Post Feedback\"></td></tr>";
                $fields .="</table>";
                $formtext = $formstart.$fields.$formend;//formtext contains the content to be inserted into the file


                $sql = "SELECT sm.vlinks, sm.vsub_sitelinks, tm.vlink_separator,tm.vsublink_separator,tm.vlink_type,tm.vsublink_type FROM ".$sitemaster." sm INNER JOIN tbl_template_mast tm ON sm.ntemplate_id = tm.ntemplate_mast ";
                $sql .= " WHERE sm.".$siteidfield."  = '".addslashes($siteid)."'";
                //echo $sql;
                $res = mysql_query($sql);//getting the link details
                if(mysql_num_rows($res)!= 0){
                        $row = mysql_fetch_array($res);
                        $links = $row["vlinks"];
                        $sublinks = $row["vsub_sitelinks"];
                        $linkseparator = $row["vlink_separator"];
                        $sublinkseparator = $row["vsublink_separator"];
                        $linktype = $row["vlink_type"];
                        $sublinktype = $row["vsublink_type"];
                        $feedbacklink = "<a class=anchor1 href='./feedback.htm'>Feedback</a>";
                        if($linktype == "horizontal"){
                                $newlink = $links .$linkseparator.$feedbacklink;
                        }else if($linktype == "vertical"){
                                $newlink = $links .$linkseparator.$feedbacklink."<br>";
                        }

                        if($sublinktype == "horizontal"){
                                $newsublink = $sublinks .$sublinkseparator.$feedbacklink;
                        }else if($sublinktype == "vertical"){
                                $newsublink = $sublinks .$sublinkseparator.$feedbacklink."<br>";
                        }

                        if($links != ""){
                                $sql = "UPDATE ".$sitemaster." SET vlinks = '".addslashes($newlink)."', vsub_sitelinks='".addslashes($newsublink)."' WHERE ". $siteidfield." = '".addslashes($siteid)."' ";
                                mysql_query($sql);//updating the links
                                $pagename = "Feedback";
                                $filename = "feedback.htm";
                                $pagetitle = "Feedback";
                                $pagetype = "feedback";
                                $type = "simple";


                                $sql2 = "INSERT INTO ".$sitepagetable."(".$siteidfield.", vpage_name,vpage_title,vpage_type,vtype) VALUES ('".addslashes($siteid)."','".addslashes($pagename)."','".addslashes($pagetitle)."','".addslashes($pagetype)."','".addslashes($type)."') ";
                                mysql_query($sql2);//Inserting the page details
                                //echo "<br>".$sql2."<br>";
                                $newfilename = $sitefoldername."/".$filename;
                                if($sitepagesfoldername!=""){//if tempsite, then add a copy of this page in sitepages
                                        $newsitepagefilename = $sitepagesfoldername."/".$filename;
                                        $fp1 = fopen($newsitepagefilename,"w+");
                                        fwrite($fp1,$formtext);
                                        fclose($fp1);
                                }
                                //echo "<br>".$sql2;
                                $fp = fopen($newfilename,"w+");
                                fwrite($fp,$formtext);
                                fclose($fp);
                                $link = "editsitepageoption.php?tempsiteid=".addslashes($siteid)."&type=".$ptype."&go=true&page=feedback&msg=creatednew";
                                header("Location:$link");
                                exit;
                        }else{
                                $message .= "<br>Home Page not present! Please create home page!";
                        }

                }

        }
        if($message!=""){
                $message = "<br>Please correct the following errors to continue!".$message;
        }
}

if($_POST["postback"] == "Back"){
	header("Location:".$_SESSION["gbackurl"]);
	exit;
}
include "includes/userheader.php";

?>
<script>
        function validateForm(){
                var frm = document.frmCreateFeedbackForm;
                if(frm.txtEmailAddress.value.length == 0){
                        alert("Your email address is required!");
                        return false;
                }
                return true;
        }
function clickBack()
{
        document.frmCreateFeedbackForm.postback.value="Back";
        document.frmCreateFeedbackForm.submit();
		//window.location.href='selectsite.php?page=feedback';
}

</script>

<table width="82%"  border="0" cellspacing="0" cellpadding="0" class="text">
    <tr>
        <td  valign="top" align=center>
            <table  width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center"><img src="images/createfeedback.gif" ></td>
                </tr>
                <tr><td align="left">&nbsp;</td></tr>
                <tr><td align="left">&nbsp;</td></tr>
                <?php
                if ($templatetype == "advanced") {
                    $url = "code/editor.php?type=" . $ptype . "&actiontype=editsite&templateid=" . $templateid . "&tempsiteid=" . $siteid;
                    ?>
                    <tr><td align="left">Please add your custom page by going to the <a href="<?php echo $url; ?>">advanced editor</a>!</td></tr>
                    <tr><td align="left">&nbsp;</td></tr>
                    <tr><td align="left"><input type="button" name="btnBack"  value="Back" class="button" onClick="window.location.href='selectsite.php?page=feedback';" ></td></tr>
                <?php } else { ?>
                    <tr><td align="left">&nbsp;</td></tr>
                    <?php
                    $feedbackpresent = false;
                    $arr = array();
                    $sql = "SELECT vpage_type FROM " . $sitepagetable . " WHERE  " . $siteidfield . " = '" . addslashes($siteid) . "' ";
                    //echo "<br>".$sql;
                    $res = mysql_query($sql);
                    if (mysql_num_rows($res) != 0) {
                        while ($row = mysql_fetch_array($res)) {
                            array_push($arr, $row["vpage_type"]);
                        }
                    }

                    $arr = array_unique(array_values($arr));
                    if (in_array("feedback", $arr)) {
                        $feedbackpresent = true;
                    }

                    if ($feedbackpresent) {
                        if ($sitetype == "completed") {
                            $type = "edit";
                        } else {
                            $type = "new";
                        }
                        $link = "editsitepageoption.php?tempsiteid=" . addslashes($siteid) . "&type=" . $type . "&go=true&page=feedback&";
                        ?>
                        <tr><td align="left">Feedback Page already present. Please <a href="<?php echo $link; ?>">edit it</a>!</td></tr>
                        <tr><td align="left">&nbsp;</td></tr>
                        <tr><td align="left"><input type="button" class="button" value="Back" name="btnBack" onclick="window.location.href='selectsite.php?page=feedback'; "></td></tr>
                        <?php
                    } else {
                        if (!validateSizePerUser($_SESSION["session_userid"], $size_per_user, $allowed_space)) {
                            $errorinlink = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete 
		   									unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>";
                            echo "<tr><td align='left' class='redtext'>" . $errorinlink . "</td></tr>";
                            echo "<tr><td align='center' ><a href='integrationmanager.php'>Back to Integration Manager</a></td></tr>";
                        } else {
                            ?>
                            <tr>
                                <td>
                                    <form name="frmCreateFeedbackForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm();">
                                        <input type="hidden" name="postback" value="">
                                        <input type="hidden" name="sitetype" value="<?php echo htmlentities($sitetype); ?>">
                                        <input type="hidden" name="siteid" value="<?php echo htmlentities($siteid); ?>">
                                        <div class="form-pnl">
                                        
                                        <?php if($message){ ?>
                                        <div class="<?php echo $messageClass;?>"><?php  echo $message;?></div>
                                        <?php } ?>
                                        <ul>
                                            <li>
                                                <label>Your Email Address </label>
                                                <input type="text" class="textbox" size="30" maxlength="100" name="txtEmailAddress" value="<?php echo htmlentities($txtEmailAddress) ?>">
                                            </li>
                                            <li>
                                                <label>Required Parameters</label>
                                                <?php echo makeDropDownList("ddlParameters[]", $paramlist, $ddlParameters, false, "textbox", " multiple size=15 ", $behaviors) ?>
                                            </li>
                                            <li>
                                                &nbsp;* Use 'Ctrl' or 'Shift' to select multiple fields.
                                            </li>
                                            <li>
                                                &nbsp;* The selected fields will appear in your feedback form.
                                            </li>
                                            <li>
                                                &nbsp;* When the user submits the feedback form, the data will be sent to the email address specified in 'Your Email Address'.
                                            </li>
                                            
                                            <li>
                                                <label>&nbsp;</label>
                                                <span class="btn-container">
                                                    <input type="button" name="btnBack" value="Back" class="btn02"  onClick="javascript:clickBack();" >
                                                    <input type="submit" name="btnAddNewCategory" value="Add" class="btn01">
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                       
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                                        
                    <tr><td>&nbsp;</td></tr>
                <?php }
                ?>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<?php

include "includes/userfooter.php";

?>



