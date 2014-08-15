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
?>
<?php

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
if(is_readable($engine_file))
{ 
	$engines_temp = @file($engine_file);
	$temp_engine = array();
	$engines     = array();


$index=0; //index of filtered array
while(list($key,$val)=each($engines_temp))
	{
	 $temp_engine = explode('#',$val);
	 $temp_engine[0] = trim($temp_engine[0]);
	 if(!empty($temp_engine[0])) $engines[$index++]=$temp_engine[0];	 
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
    

</script>
<?php
$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_PROMOTION_MANAGER =>'promotionmanager.php',
                    PROMOTION_MANAGER_SEARCH_ENGINE_TITLE =>'sitesubmission.php');
echo getBreadCrumb($linkArray);
?>
<h2><?php echo PROMOTION_MANAGER_SEARCH_ENGINE_TITLE; ?></h2>
    
    <p class="txt09">Search engine submission is an attempt to make a search engine aware of a site or page. It ensures that the web page gets spidered and indexed. You can submit your site using the form given below.</p>

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
</script>
        
     
        <!--end  HELPPANEL CODE -->
        <form action="" name=submitForm method="post">
            <div class="form-pnl submit-site">
                <?php echo "<span class=\"successmessage\"><b>Your Site is Submitted Successfully to the Search Engines</b></span><br>";?>
                 <ul>
                 
				<div class="clear"></div>
            </div>
    
            
            <br><br>&nbsp;
        </form>
<?php
include "includes/userfooter.php";
?>