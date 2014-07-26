<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+

$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "searchengine/_config.php";

$temp=array();

//sent back if back button is clicked
if ($_GET["goback"] == "true") {
    header("Location:promotionmanager.php");
    exit;
}

include "includes/userheader.php";

#Submit to Google
if(is_readable($engine_file)) {
    $engines_temp = @file($engine_file);
    $temp_engine = array();
    $engines     = array();


    $index=0; //index of filtered array
    while(list($key,$val)=each($engines_temp)) {
        $temp_engine = explode('#',$val);
        $temp_engine[0] = trim($temp_engine[0]);
        if(!empty($temp_engine[0])) $engines[$index++]=$temp_engine[0];
    }

}





function google($domain) {
    $host = "www.google.com";
    $builturl = "http://www.google.com/addurl?";
    //$builturl="https://www.google.com/webmasters/tools/submit-url?";
    $builturl .= "q=$domain&dq=&submit=Add%20URL";
    $builtpath .= "/addurl?q=$domain&dq=&submit=Add%20URL";
    //$builtpath .= "/webmasters/tools/submit-url?q=$domain&dq=&submit=Add%20URL";
    if (!$socket = fsockopen($host, 80, $err_num, $err_msg, 10))
        echo "<tr><td class=maintext align=right>Google : </td><td class=redtext  align=left>Connection to Google timed out submitting $builturl<br></td></tr>";
    else {
        fputs($socket, "GET $builtpath HTTP/1.0\r\n\r\n");
        while (!feof($socket))
            $reply .= fgets($socket, 1024);
        echopre($reply);
        $pos = strpos($reply, VAL_DOC_REMOVED);
        if ($pos == false)
            echo "<tr><td class=maintext align=right>Google : </td><td class=redtext  align=left>Google didn't accept $domain<br></td></tr>";
        else {
            echo "<tr><td class=maintext align=right>Google : </td><td class=greentext  align=left>$domain successfully submitted to Google.<br></td></tr>";
        }
        flush();
        fclose($socket);
    }
}

#Submit 'em to ActiveSearchResult

// function activesearchresult($domain) {
//
//    $host = "www.activesearchresults.com";
//    $builturl = " http://www.activesearchresults.com/addwebsite.php?";
//    $builturl .= "url=$domain&email=example@example.com";
//    $builtpath .= "/addwebsite.php?url=$domain&email=example@example.com";
//    if (!$socket = fsockopen($host, 80, $err_num, $err_msg, 10))
//        echo "<tr><td class=maintext align=right>Google : </td><td class=redtext  align=left>Connection to activesearchresults timed out submitting $builturl<br></td></tr>";
//    else {
//        fputs($socket, "GET $builtpath HTTP/1.0\r\n\r\n");
//        while (!feof($socket))
//            $reply .= fgets($socket, 1024);
//        echopre($reply);
//        //exit;
//        $pos = strpos($reply, "The document has moved");
//        if ($pos == false)
//            echo "<tr><td class=maintext align=right>Google : </td><td class=redtext  align=left>Google didn't accept $domain<br></td></tr>";
//        else {
//            echo "<tr><td class=maintext align=right>Google : </td><td class=greentext  align=left>$domain successfully submitted to Google.<br></td></tr>";
//        }
//        flush();
//        fclose($socket);
//    }
//}



#Submit 'em to SearchIt
function searchit($domain) {
    global $urls, $results;
    $host = "index.searchit.com";

    $builturl = "http://index.searchit.com/add-site.php?";
    $builturl .= "url=$domain&email=example@example.com&submit=Send";
    $builtpath .= "/add-site.php?url=$domain&email=info@easycreate.com";
    $builtpath .="&submit=Send";

    if (!$socket = fsockopen($host, 80, $err_num, $err_msg, 10))
        echo "<tr><td><td class=maintext align=right>SearchIt : </td><td class=redtext  align=left>Connection to SearchIt timed out submitting $builturl<br></td></tr>";
    else {
        fputs($socket, "GET $builtpath HTTP/1.0\r\n\r\n");
        while (!feof($socket))
            $reply .= fgets($socket, 1024);
        echopre($reply);

        if (!(preg_match(VAL_DOC_REMOVED, $reply)))
            echo "<tr><td><td class=maintext align=right>SearchIt : </td><td class=redtext  align=left>SearchIt didn't accept $domain<br></td></tr>";
        else
            echo "<tr><td><td class=maintext align=right>SearchIt : </td><td class=greentext  align=left>$domain successfully submitted to SearchIt.<br></td></tr>";

        flush();
        fclose($socket);
    }
}

#Submit 'em to SplatSearch

function splat($domain) {
    global $urls, $results;
    $host = "www.splatsearch.com";
    $builturl = "http://www.splatsearch.com/cgi-bin/spider.cgi?";
    $builturl .= "url=$domain&instant=1&submit=Submit";
    $builtpath .= "/cgi-bin/spider.cgi?url=$domain&instant=1";
    $builtpath .="&submit=Submit";

    if (!$socket = fsockopen($host, 80, $err_num, $err_msg, 10))
        echo "<tr><td><td class=maintext align=right>SplatSearch : </td><td class=redtext  align=left>Connection to SplatSearch timed out submitting $builturl<br></td></tr>";
    else {
        $header = "Host: www.splatsearch.com\r\nConnection: close\r\n";
        fputs($socket, "GET $builtpath HTTP/1.1\r\n$header\r\n");
        while (!feof($socket))
            $reply .= fgets($socket, 1024);
        echopre($reply);
        if (!(preg_match("thankyou.html", $reply)))
            echo "<tr><td><td class=maintext align=right>SplatSearch : </td><td class=redtext  align=left>SplatSearch didn't accept $domain<br></td></tr>";
        else
            echo "<tr><td><td class=maintext align=right>SplatSearch : </td><td class=greentext  align=left>$domain successfully submitted to SplatSearch.<br></td></tr>";
        flush();
        fclose($socket);
    }
}

#Entry point
//==============================================================================
//get postback values

$act = $_GET["act"];
$domain = $_POST["domainname"];

/*if ($act == "post") {

    echo "<br><br><table width=50% border=1>";
    if ($_POST["google"]) {
        google($domain);
    }

//    if ($_POST["searchit"]) {
//        activesearchresult($domain);
//    }
//    if ($_POST["splat"]) {
//        splat($domain);
//    }

    if ($_POST["activesearchresult"]) {
        activesearchresult($domain);
    }

    echo "</table>";
}*/
//==============================================================================
?>

<script>
    function goback(){

        document.submitForm.action="sitesubmission.php?goback=true";
        document.submitForm.submit();

    }
    function validate(){
        if(document.submitForm.domainname.value==""){

            alert("Please enter a valid domain name");
            document.submitForm.domainname.focus;
            return false;

            //}else if(document.submitForm.searchit.checked==false && document.submitForm.splat.checked==false && document.submitForm.google.checked==false){
        }else if(document.submitForm.google.checked==false){

            alert("Select atleast one search engine");

        }else{

            document.submitForm.submit();

        }

    }

</script>
<?php
$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
        FOOTER_PROMOTION_MANAGER =>'promotionmanager.php',
        PROMOTION_MANAGER_SEARCH_ENGINE_TITLE =>'sitesubmission.php');
echo getBreadCrumb($linkArray);
?>
<h2><?php echo PROMOTION_MANAGER_SEARCH_ENGINE_TITLE; ?></h2>

<p class="txt09"><?php echo SEARCH_ENGINE_SUBMISSION_DESC;?>



</p>

<!--start HELPPANEL CODE -->

<script language="javascript">
    function genHex(){
        colors = new Array(14);
        colors[0]="0";
        colors[1]="1";
        colors[2]="2";
        colors[3]="3";
        colors[4]="4";
        colors[5]="5";
        colors[5]="6";
        colors[6]="7";
        colors[7]="8";
        colors[8]="9";
        colors[9]="a";
        colors[10]="b";
        colors[11]="c";
        colors[12]="d";
        colors[13]="e";
        colors[14]="f";
        digit = new Array(5);
        color="";
        for (i=0;i<6;i++){
            digit[i]=colors[Math.round(Math.random()*14)];
            color = color+digit[i];
        }
        document.getElementById('lady').style.backgroundColor=color;
    }


    function showLoader(){
        document.getElementById('sesubmissionloader').style.display = 'block';
    }

</script>


<!--end  HELPPANEL CODE -->
<form action="" name=submitForm method="post">
    <div class="form-pnl submit-site">

        <?php if($_POST['Submit']) {
            $url=$_POST['url'];
            if($url != '')
                echo '<div style="text-align:center;"><img src="images/img_loading_bar.gif"></div>';

            $selected_engines=$_POST['selected_engines'];

            $email=$_POST['email'];
            include "searchengine/SubmitForce.class.php";

            $addit = new SubmitForce;							// create object
            $addit->set_mode(1);							// operating mode ONLINE or DEBUG

            $addit->queue_engines($engine_file);				// Load the engine data file
            $addit->init($url,$email,$user,$selected_engines);	// initialize variables

            $submissionRes = $addit->submit_page();

            if($submissionRes) {

                echo "<script language=\"JavaScript\">\n";
                echo "window.location = \"sitesubmissionresult.php\";\n";
                echo "</script>\n";

                //header('location:sitesubmissionresult.php');
            }
        }?>
        <ul>
            <li>
                <label style="width:350px; vertical-align:middle"><?php echo SEARCH_ENGINE_SITENAME;?>&nbsp;&nbsp;</label>
                <input class=textbox type="text" name="url" id="domainname" style="width:500px; " value="<?php echo htmlentities($url); ?>"> <br>
                <p style="float:right; padding:3px 5px 3px 0;"> <?php echo SEARCH_ENGINE_EG;?></p>
            </li>    <input type="hidden" name="email" value="<?php echo $_SESSION['session_email'];?>">
            <li>
                <label style="width:350px; display:block; float:left; vertical-align:middle"><?php echo SEARCH_ENGINE_TO_SUBMIT;?></label>
                <!--<input type="checkbox" name="google" value="checkbox" checked>Google -->

                <div class="searchengine_options">
                    <?php
                    for($i=0,$j=1;$i<sizeof($engines);$i++,$j++) {
                        if(isset($selected_engines) && in_array(trim($temp[0]),$selected_engines )) {

                            $checked = 'checked';
                        }else {
                            $checked = '';
                        }
                        if(!isset($_POST['Submit']))
                            $checked = 'checked';
                        $temp=explode(',',$engines[$i]);
                        echo "<p class=\"txt_10\"><input type=\"checkbox\" name=\"selected_engines[$i]\" value=\"".trim($temp[0])."\" class=check ".$checked." >".ucwords(trim($temp[0]))."</p>";
                        if($j==$num_of_cols) {
                            $j=0;
                            echo "\n";
                        }
                    }

                    ?>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </li>
            <li class="bordertop">
                <label style="width:300px; vertical-align:middle">&nbsp;</label>
                <span class="btn-container" style="text-align:left !important;width:275px !important" >
                    <input type="button" name="btnBack" value="Back" class="btn02"  onClick="goback();" >
                    <input type="submit" name="Submit" value="Submit" onClick='showLoader()' class="btn01">

                    <div style="float:right;padding:12px 0px 0px 20px; display:none;" class="sesubmissionloader" id="sesubmissionloader"><img src="images/img_loading_bar.gif"></div>


                </span>
            </li>
        </ul>
        <div class="clear"></div>
    </div>

    <!--fieldset style="width:300px; margin:0 auto;"><legend class="maintext">Search engine you wish to submit your site</legend>
        <div class="form-pnl">
            <ul>
               <li>
                   <input type="checkbox" name="google" value="checkbox" checked>
                   Google
               </li>
               <li>
                   <label>&nbsp;</label>
                   <span class="btn-container">
                       <input type="button" name="btnBack" value="Back" class="btn02"  onClick="goback();" >
                       <input type="Button" name="Submit" value="Submit" onClick=validate(); class="btn01">

                   </span>
               </li>

           </ul>
       </div>
        <!--<table>
            <tr><td width=100% align=center colspan=2 class=maintext></td></tr>
            <tr>
                <td align=right><br><input type="checkbox" name="google" value="checkbox" checked></td>
                <td  align=left class=maintext><br>Google</td>
            </tr>

            <!--<tr>
            <td align=right><br><input type="checkbox" name="searchit" value="checkbox" checked></td>
            <td  align=left class=maintext><br>SearchIt</td>
            </tr>
            <tr>
            <td align=right><br><input type="checkbox" name="splat" value="checkbox" checked></td>
            <td  align=left class=maintext><br>SplatSearch</td>
            </tr>


            </td>
            </tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td colspan="2">
                    <input type="Button"  name="Submit" value="Submit" onClick=validate(); class="button">&nbsp;&nbsp;
                    <input type="button" class="button" value="Back"   onClick="goback();"></td></tr></table>-->
<!--/fieldset-->
<br><br>&nbsp;
</form>
<?php
include "includes/userfooter.php";
?>