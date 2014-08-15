<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>






<body>
<?php

$filename = 'gb.txt';
// make sure the file exists and is writable first.
if (is_writable($filename)) {

   
    if (!$handle = fopen($filename, 'a+')) {
         $message.= "Cannot open file ($filename)";
         exit;
    }

    // Write $somecontent to our opened file.
	If($_GET["act"]=="post"){
		
		$message= "";
		$content = addslashes($_POST["name"])."`|^".$_POST["email"]."`|^".$_POST["matter"]."`|^".date("Y-m-d")."~`|\n";
	
		if (fwrite($handle, $content) === FALSE) {
			$message.= "Cannot write to file ($filename)";
			exit;
		}
		
		$message.= "Thank you. Your Guest book entry added"; 
		fseek($handle, 0);

	}
    //read file content to make display 
	$displaycontents.="<table align=center width=70%><tr><td align=center><font face=verdana size=2><b>Current GuestBook Entries<br>&nbsp;</b></font></td></tr>";

	if(filesize($filename)>0){

			$readcontents = @fread($handle, filesize($filename));
			$entryarray=explode("~`|\n",$readcontents);
		
		
			for($i=0;$i<count($entryarray)-1;$i++){
				
				$valuearray=explode("`|^",$entryarray[$i]);
				$displaycontents.="<tr><td align=left bgcolor=#dddddd><font face=verdana size=2>Posted &nbsp;by &nbsp;".stripslashes($valuearray[0])."( ".$valuearray[1]." ) &nbsp;on&nbsp; ".$valuearray[3]."</font></td></tr>";
				$displaycontents.="<tr><td align=left valign=top><font face=verdana size=2><br>".$valuearray[2]."</font></td></tr>";
				$displaycontents.="<tr><td align=left valign=top>&nbsp;</td></tr>";
				
			
			}
			
                    
	}else{
	
			$displaycontents.="<tr><td align=center valign=top><font face=verdana size=2>Sorry! Guest book is empty.</font></td></tr>";
	
	}
	$displaycontents.="</table>";			
	fclose($handle);


} else {

    $message.= "The file $filename is not writable.Please provide write permission to it";

}




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

		if(document.gbForm.name.value=="" ){
		
			alert("Please enter your name");
			document.gbForm.name.focus();
			
		}else if (document.gbForm.email.value==""){
		
			alert("Please enter your email");
			document.gbForm.email.focus();
			
		}else if(checkMail(document.gbForm.email.value)==false){

     		alert('Invalid mail format');
     		document.gbForm.email.focus();
     		return false;

    	}else if (document.gbForm.matter.value==""){
		
			alert("Please enter your matter");
			document.gbForm.matter.focus();
			
		}else{
		
			document.gbForm.submit();
		}
		
}
</script>
<?php
echo $displaycontents;
?>
<table width="100%"  border="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form name="gbForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?act=post">
      <fieldset style="width:400px;">
	  <table width="100%"  border="0">
        <tr align="center">
          <td colspan="3"><br>
            <strong><font face=verdana size=2>Add your guestbook entry</font> </strong><br>&nbsp;</td>
          </tr>
		          <tr>
          <td width="100%" align="center" colspan=3><font face=verdana size=1 color=red><?php echo $message; ?></font></td>
        </tr>
        <tr>
          <td width="45%" align="right"><font face=verdana size=2>Your Name</font></td>
          <td width="3%">&nbsp;</td>
          <td width="52%" align="left" valign="top"><input name="name" type="text" id="name"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><font face=verdana size=2>Your Email Address</font></td>
          <td>&nbsp;</td>
          <td align="left" valign="top"><input name="email" type="text" id="email"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><font face=verdana size=2>Guest Book Matter</font></td>
          <td>&nbsp;</td>
          <td align="left" valign="top">            <textarea name="matter" id="matter"></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr align="center">
          <td colspan="3"><input type="button" value="Sign Guest Book" onclick=validate();></td>
          </tr>
      </table>
	  </fieldset>
    </form>
	<a href="gbadmin.php">Admin</a>
	</td>
  </tr>
</table>
</body>






</html>
