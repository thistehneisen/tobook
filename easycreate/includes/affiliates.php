<table  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" bgcolor="FFFFFF">
                   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center"><table width="70%"  border="0" cellpadding="5" cellspacing="0" class="maintext">
                        <tr>
                          <td>
<?php
	$sql = "Select vname,vvalue from tbl_lookup where vname='naff_amnt'";
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$aff_percent = $row["vvalue"];
	}
?>						  
						  <P align=left><b>Affiliate Program of <?php echo($_SESSION["session_lookupsitename"]); ?></b>
<br>&nbsp;<br>
<?php echo("Sign up with " . $_SESSION["session_lookupsitename"] . " affiliate program today. Refer visitors to our site. We will pay you <b>$" . $aff_percent . "</b> of the plan rate purchased by the referred user at " . $_SESSION["session_lookupsitename"] . ". Just simple as that.<br>" . 
"<br>If you have any questions about the program please <a href=\"" . $_SESSION["session_rootserver"] . "/contactus.php\">contact us </a><br>or refer to the <a href=\"affiliatefaq.php\">Affiliate FAQ</a>" .
"<br><br><a href=affiliatesignup.php>Sign up as an Affiliate</a><br>"); ?>
</P>
                              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" class="dotedline"><img src="images/spacer.gif" width="1" height="1"></td>
                                </tr>
                              </table>









<!-- ============================================================================= -->

<script>
function validateAffiliateForm(){
        var frm = document.frmAffiliate;

        if(frm.txtAffLogin.value == ""){
                alert("Please enter your login id.");
                frm.txtAffLogin.focus();
                return false;
        }else if(frm.txtAffPassword.value == ""){
                alert("Please enter your password.");
                frm.txtAffPassword.focus();
                return false;
        }

        return true;
}
</script>


 <form name=frmAffiliate id=frmAffiliate method=post action="<?php echo $_SERVER["PHP_SELF"]?>" onSubmit="return validateAffiliateForm();">
            <fieldset>
				<legend>Affiliate Login</legend> 
			<table width="60%"  border="0" cellpadding="0" cellspacing="0" class="maintext">
              <tr>
                <td width="100%" colspan=2 align=center><font color=red>
                  <?php echo $affloginmessage?>
                </font></td>
              </tr>
              <tr>
                <td width="32%" height="23"> &nbsp;&nbsp;Login ID</td>
                <td width="68%" height="23"><input  type="text" class="textbox" maxlength=100 size="15" id=txtAffLogin name=txtAffLogin value="<?php echo($_POST["txtAffLogin"]);?>"></td>
              </tr>
              <tr>
                <td height="23">&nbsp;&nbsp;Password</td>
                <td height="23"><input name="txtAffPassword" id="txtAffPassword" type="password" class="textbox"  maxlength=100  size="15"></td>
              </tr>

              <tr>
                <td colspan=2 align=center><br>
                <input type=submit value="Login" class="editorbutton"></td>
              </tr>
              <tr><td align=center colspan=2 width=100%>
              </td></tr>
            </table>
			</fieldset>

            </form>





<!-- ============================================================================= -->








<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" class="dotedline"><img src="images/spacer.gif" width="1" height="1"></td>
                                </tr>
                            </table>
                               <br><br>&nbsp;
</td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
                <td width="1" background=images/vline.gif ></td>
              </tr>
          </table></td>
        </tr>
      </table>