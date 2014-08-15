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
include "includes/function.php";
include "includes/session.php";
include "includes/config.php";
//sent back if back button is clicked
if ($_GET["goback"] == "true") {
    header("Location:promotionmanager.php");
    exit;
}
//handle post back of signup
$act = $_GET["act"];
$message = "";
if ($act == "post") {
    //get the postback data from form	
    $yourname = $_POST["yourname"];
    $yourmail = $_POST["yourmail"];
    $friendname = $_POST["friendname"];
    $friendmail = $_POST["friendmail"];
    $subject = $_POST["subject"];
    $matter = "Hi $friendname,\n" . $_POST["matter"] . "\nThanks\n$yourname";

  $sitelogo=BASE_URL.getSettingsValue('Logourl');
            $matter="<html><table>
                <tr>
                    <td>
                        <img src=".$sitelogo."><br><br>
                    </td>
                  </tr>
                  <tr>
                       <td> 
                        Dear ". htmlentities($friendname) . " ,<br><br>
                       </td>
                  </tr>
                  <tr>
                    <td>
                        ".$_POST["matter"]." <br><br>


                       Regards<br>$yourname 
                    </td>
                 </tr></table></html>";
               



    $message = "<font color='green'><b>".VAL_MSG_SENT."</b></font>";
    $mailParams = "-f$yourmail";
    //mail the friend about the site
//echo "<pre>";
//print_r($matter);
//exit();

$Headers="From: $yourmail\r\n";
$Headers.="Reply-To: $yourmail\r\n";
$Headers.="MIME-Version: 1.0\n";
$Headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
$Headers.="Content-type: text/html; charset=iso-8859-1\r\n";

$mailsent = @mail($friendmail, $subject, $matter,$Headers,$mailParams);
    
/*
    @mail("$friendmail", "$subject", $matter, "From: $yourmail\r\n" .
                    "Reply-To: $yourmail\r\n" .
                    "X-Mailer: PHP/" . phpversion(), $mailParams); */
}
include "includes/userheader.php";
?>
<script>
    function checkMail(email)
    {
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
    
    function validate(){
        
        if(document.tellForm.yourname.value==""){
            
            alert("<?php echo VAL_NAME;?>");
            document.tellForm.yourname.focus();
            return false;
            
        }else if(document.tellForm.yourmail.value==""){
            
            alert("<?php echo VAL_EMAIL;?>");
            document.tellForm.yourmail.focus();
            return false;
            
        }else if(checkMail(document.tellForm.yourmail.value)==false){
            
            alert('<?php echo VAL_INVALIDMAIL;?>');
            document.tellForm.yourmail.focus();
            return false;
            
        }else if(document.tellForm.friendname.value==""){
            
            alert("<?php echo VAL_FRNDNAME;?>");
            document.tellForm.friendname.focus();
            return false;
            
        }else if(document.tellForm.friendmail.value==""){
            
            alert("<?php echo VAL_FRNDMAIL;?>");
            document.tellForm.friendmail.focus();
            return false;
            
        }else if(checkMail(document.tellForm.friendmail.value)==false){
            
            alert('<?php echo VAL_INVALIDMAIL;?>');
            document.tellForm.vuser_email.focus();
            return false;
            
        }else if(document.tellForm.subject.value==""){
            
            alert("<?php echo VAL_FRIEND_EMAIL;?>");
            document.tellForm.subject.focus();
            return false;
            
        }else if(document.tellForm.matter.value==""){
            
            alert("<?php echo VAL_FRIEND_MAILCONTENT;?>");
            document.tellForm.matter.focus();
            return false;
            
        }else{
            
            document.tellForm.submit();
            
        }
        
    }
    function goback(){
        
        document.tellForm.action="tellfriend.php?goback=true";
        document.tellForm.submit();
        
    }
    
</script>
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

<?php
$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_PROMOTION_MANAGER =>'promotionmanager.php',
                    PROMOTION_MANAGER_TELL_FRIEND_TITLE =>'tellfriend.php');
echo getBreadCrumb($linkArray);
?>
<h2><?php echo PROMOTION_MANAGER_TELL_FRIEND_TITLE; ?></h2>

<div class="comm_div">
    <div class="comm_text">
		<p class="txt09">
        <?php echo TELL_FRIEND_DESCRIPTION; ?>
		</p>
    </div>
</div>
<form name="tellForm" method=post action=tellfriend.php?act=post>


    <div class="form-pnl">
    <p><br> <?php echo MANDATORY_PART1; ?> <font color=red>*</font>  <?php echo MANDATORY_PART2; ?><br></p>
    <?php if($message){ ?>
    <div class="<?php echo $messageClass;?>"><br><?php  echo $message;?></div>
    <?php } ?>
	

    <ul>
        <h4 class="hding_10"><?php echo YOUR_INFORMATION; ?></h4>
            <li>
                <label><?php echo YOUR_NAME; ?> <sup>*</sup></label>
                <input class=textbox type=text name="yourname" id="yourname" maxlength="50">
            </li>
            <li>
                <label><?php echo YOUR_EMAIL; ?> <sup>*</sup></label>
                <input  class=textbox type=text name="yourmail" id="yourmail" maxlength="50" >
            </li>
     
		 <h4 class="hding_10"><?php echo FRIEND_INFO; ?></h4>	 
       
            <li>
                <label><?php echo FRIEND_NAME; ?> <sup>*</sup></label>
                <input  class=textbox type=text name="friendname" id="friendname" >
            </li>
            <li>
                <label><?php echo FRIEND_EMAIL; ?><sup>*</sup></label>
                <input class=textbox  type=text name="friendmail"  id="friendmail" >
            </li>
     
         <h4 class="hding_10"><?php echo FRIEND_MAILCONTENT; ?></h4>	 
            <li>
                <label><?php echo FRIEND_SUBJECT; ?> <sup>*</sup></label>
                <input type=text  class=textbox  name="subject" id="subject" value="Please visit this site">
            </li>
            <li>
                <label><?php echo FRIEND_CONTENT; ?><sup>*</sup></label>
                <span  class="txt09"><?php echo FRIEND_REPLACEINFO; ?></span>
            </li>
			<li>
				 <label>&nbsp;</label>
				<textarea rows=5 name=matter style="padding:10px; width:365px;"><?php echo SITE_LINK; ?> </textarea>
                
			</li>
       
        <li>
            <label>&nbsp;</label>
            <span class="btn-container">
                <input type=Button  class=btn02  value="Back"   onClick="goback();">
                <input type="button" onClick="validate();" value="Tell Friend" class=btn01 >
             </span>
        </li>
    </ul>
</div>
</form>


<!--<table border=0 width="100%"><tr>
        <td align=center valign=top>
            <form name="tellForm" method=post action=tellfriend.php?act=post>
                <table>
                    <tr>
                        <td align=center colspan=3 class=redtext valign=top><?php echo $message; ?>
                        </td>
                    </tr>
                    
            </table>
    
                <fieldset width=100%><legend class=subtext><b>Your Information</b></legend>
                    <br>
                    <table width=100% border=0 >
                        <tr>
                            <td align=right class=maintext valign=top width=40%>Your Name<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left valign=top ><input class=textbox type=text name="yourname" id="yourname" maxlength="50"></td>
                        </tr>
    
                        <tr>
                            <td align=right class=maintext valign=top >Your Email<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left valign=top ><input  class=textbox type=text name="yourmail" id="yourmail" maxlength="50" ><br>&nbsp;</td>
                        </tr>
                    </table>
                </fieldset>
    
    
    
                <fieldset><legend class=subtext><b>Friend's Information</b></legend>
                    <br>
                    <table width=100%  border=0 >
                        <tr>
                            <td align=right valign=top class=maintext width=40%>Friend's Name<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left valign=top ><input  class=textbox type=text name="friendname" id="friendname" ></td>
                        </tr>
    
                        <tr>
                            <td align=right class=maintext valign=top >Friend's Email<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left valign=top ><input class=textbox  type=text name="friendmail"  id="friendmail" ><br>&nbsp;</td>
                        </tr>
    
                    </table>
                </fieldset>
                <fieldset>
                    <legend class=subtext><b>Mail Matter</b></legend>
                    <br>
                    <table border=0 width=100%>
    
                        <tr>
                            <td align=right valign=top  class=maintext valign=top  width=40%>Subject<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left valign=top ><input type=text  class=textbox  name="subject" id="subject" value="Please visit this site"></td>
                        </tr>
    
                        <tr>
                            <td align=right valign=top  class=maintext>Matter<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left valign=top class=maintext>Please replace 'yoursite.com' with your site address<br><textarea  class=textbox style="width:200px;" rows=10 name=matter>Please have a look at the following site.
    
                            Here is the Link:
                            http://www.yoursite.com </textarea>
                                <br>&nbsp;</td>
                        </tr>
                    </table>
                </fieldset>
    
    
    
                <table>
                    <tr>
                        <td align=right colspan=3 align=center><input type="button" onClick="validate();" value="Tell Friend" class=button >&nbsp;&nbsp;<input type=Button  class=button  value="Back"   onClick="goback();"></td>
                    </tr>
                </table>
    
    
    
            </form>
        </td>
    </tr>
</table>-->

<?php
include "includes/userfooter.php";
?>