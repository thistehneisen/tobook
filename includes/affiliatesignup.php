<?php
if($_POST["btnSubmit"] == "Sign up"){//submitted form

        $txtName =  addslashes($_POST["txtName"]);
        $txtLogin =  addslashes($_POST["txtLogin"]);
        $txtPassword =  addslashes($_POST["txtPassword"]);
        $txtEmail =  addslashes($_POST["txtEmail"]);
        $txtAddress =  addslashes($_POST["txtAddress"]);
        $txtCity =  addslashes($_POST["txtCity"]);
        $txtState =  addslashes($_POST["txtState"]);
        $txtZIP =  addslashes($_POST["txtZIP"]);
        $txtPhone =  addslashes($_POST["txtPhone"]);
        $ddlCountry = addslashes($_POST["ddlCountry"]);


        $sqluserexists = "SELECT vaff_login FROM tbl_affiliates WHERE vaff_login = '$txtLogin'";
        $resultuserexists = mysql_query($sqluserexists) or die(mysql_error());
        if(mysql_num_rows($resultuserexists)!=0){
                $message = "This Login id '$txtLogin' is in use!. Please select a different one!";
        }else{
                $encpass = md5($txtPassword);
                $sql = "INSERT INTO tbl_affiliates(naff_id,vaff_name,vaff_login,vaff_pass,vaff_mail,vaff_address ,vaff_city ,vaff_state ,vaff_country ,vaff_zip, vaff_phone,vdelstatus) ";
                $sql .="VALUES ('','$txtName','$txtLogin','$encpass','$txtEmail','$txtAddress','$txtCity','$txtState','$ddlCountry','$txtZIP','$txtPhone','0')";

                mysql_query($sql) or die(mysql_error());



                //sending mail to the armia site
                $message="<html><head></head><body><table>
                        <tr><td width=100% align=left colspan=2>Hi,<br>A new affiliate has been registered with " . $_SESSION["session_lookupsitename"] . "<br>&nbsp; </td></tr>
                        <tr><td width=30% align=right>Name : </td><td width=70% align=left>$txtName</td></tr>
                        <tr><td width=30% align=right>EMail : </td><td width=70% align=left>$txtEmail</td></tr>
                        <tr><td width=30% align=right>Country : </td><td width=70% align=left>$ddlcountry</td></tr>
                        <tr><td width=30% align=right>Phone : </td><td width=70% align=left>$txtPhone</td></tr>
                        </table></body></html>";

                @mail($_SESSION["session_lookupadminemail"], "New Affiliate Registered at " . $_SESSION["session_lookupsitename"] , "$message","MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom:$txtEmail");
                //sending mail to the user with attached agreement doc
                $mailcontent1 = "Dear ".$txtName.", <br>";
                $mailcontent1 .= "Your affiliate account with " . $_SESSION["session_lookupsitename"] . " has been successfully created.";
                $mailcontent1 .= " Please place the banners displayed at the affiliate panel of " . $_SESSION["session_lookupsitename"] . " , on your site to maximize your profit. ";
                $mailcontent1 .= " Meanwhile you can contact us in case of any difficulties at : <br><br>";
                $mailcontent1 .= " E-mail: " . $_SESSION["session_lookupadminemail"] . "<br><br>";
                $mailcontent1 .= " <br><br><br>";
                $mailcontent1 .= " Thanks & Regards,<br>";
                $mailcontent1 .= " Sales Team<br>";
                $mailcontent1 .= $_SESSION["session_lookupsitename"];
                $subject = "Your affiliate account information with " . $_SESSION["session_lookupsitename"];
                $EMail = $txtEmail;
                $Headers="From: " . $_SESSION["session_lookupadminemail"] . "\n";
                $Headers.="Reply-To: " . $_SESSION["session_lookupadminemail"] . "\n";
                $Headers.="MIME-Version: 1.0\n";
                $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                @mail($EMail,$subject,$mailcontent1,$Headers);


                $_SESSION["session_affiliate"] = mysql_insert_id();
                $_SESSION["session_affiliatename"] = $txtName;
                echo"<script>location.href='affiliates/main.php'</script>";
                exit();




        }
}


?>

<script>
function loadFields(){
        var frm = document.frmAffiliates;
        var country ="<?php echo $ddlCountry?>";
        if(country != ""){
                for(i=0;i<frm.ddlCountry.options.length;i++){
            if(frm.ddlCountry.options[i].text == country){
                        frm.ddlCountry.options[i].selected=true;
                        break;
            }
            }
        }else{
                return true;
        }

}


function validateForm(){
        var frm = document.frmAffiliates;
        if(frm.txtName.value == ""){
                alert("Name cannot be empty.");
                frm.txtName.focus();
                return false;
        }else if(frm.txtLogin.value == ""){
                alert("login cannot be empty.");
                frm.txtLogin.focus();
                return false;
        }else if(frm.txtPassword.value == ""){
                alert("Password cannot be empty.");
                frm.txtPassword.focus();
                return false;
        }else if(frm.txtEmail.value == ""){
                alert("Email cannot be empty.");
                frm.txtEmail.focus();
                return false;
        }else if(frm.txtAddress.value == ""){
                alert("Address cannot be empty.");
                frm.txtAddress.focus();
                return false;
        }else if(frm.txtCity.value == ""){
                alert("City cannot be empty.");
                frm.txtCity.focus();
                return false;
        }else if(frm.txtState.value == ""){
                alert("State cannot be empty.");
                frm.txtState.focus();
                return false;
        }else if(frm.txtZIP.value == ""){
                alert("ZIP cannot be empty.");
                frm.txtZIP.focus();
                return false;
        }else if(frm.txtPhone.value == ""){
                alert("Phone Number cannot be empty.");
                frm.txtPhone.focus();
                return false;
        }else if(frm.txtEmail.value != ""){
                if(!checkMail(frm.txtEmail.value) ){
                        alert('Please enter a valid email address.');
                        frm.txtEmail.focus();
                        return false;
                }
        }

        return true;
}

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
</script>

<table  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
                <td width="100%">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                        <td valign="top" bgcolor="FFFFFF" width="100%">
                                            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td align="center" width="100%">
                                                                                                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" background="images/horline.jpg">
                                                                                                        <tr>
                                                                                                      <td><img src="images/spacer.gif" width="1" height="1"></td>
                                                                                                    </tr>
                                                                                                          </table>
                                                        <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="maintext">
                                                        <tr>
                                                                  <td width="100%">
                                                                                                <P align=left>
<!-- Main Signup Part Starts -->
<form action="affiliatesignup.php" method="post" name="frmAffiliates" onSubmit="return validateForm();">
                                                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                                                                                        <?php
                                                                                                         if($message!=""){
                                                                                                         ?>
                                                                                                        <tr>
                                                                                                                  <td align="center" class="message">
                                                                                                            <?php echo $message;?></td>
                                                                                                        </tr>
                                                                                                        <?php }
                                                                                                                                        ?>
                                                                                                        <tr>
                                                                                                                  <td width="100%" height="20" align="left" valign="bottom" class="txt1">&nbsp;</td>
                                                                                                        </tr>
                                                                                                </table>
                                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                  <tr>
                                                                                                    <td width="31%" height="25" class="maintext">Name<font color="#ff0000">*</font></td>
                                                                                                    <td width="69%" height="25"><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                                                                                      <input name="txtName" type="text" id="txtName" size="35" maxlength="100" value="<?php echo $txtName?>" class="textbox">
                                                                                                    </font></td>
                                                                                                  </tr>
   <tr>
                                                                                                                   <td width="31%" height="25" class="maintext">Login Name<font color="#ff0000">*</font></td>
                                                                                                    <td width="69%" height="25">
                                                                                                      <input name="txtLogin" type="text" id="txtLogin" size="35" maxlength="100" class="textbox" >
                                                                                                                                                </font></td>
                                                                                                           </tr>                                                                                                                                                                                                     <tr>
                                                                                                                   <td width="31%" height="25" class="maintext">Password<font color="#ff0000">*</font></td>
                                                                                                    <td width="69%" height="25">
                                                                                                      <input name="txtPassword" type="password" id="txtPassword" size="35" maxlength="100" class="textbox" >
                                                                                                                                                </font></td>
                                                                                                           </tr>

                                                                                                  <tr>
                                                                                                    <td height="25" class="maintext">Email <font color="#ff0000">*</font></td>
                                                                                                    <td height="25">
                                                                                                      <input name="txtEmail" type="text" id="txtEmail" size="35" maxlength="100"  value="<?php echo $txtEmail?>" class="textbox">
                                                                                                    </td>
                                                                                                  </tr>
                                                                                                                                                                                                                                        <tr>
                                                                                                    <td height="25" class="maintext" valign="top">Address<font color="#ff0000">*</font></td>
                                                                                                    <td height="25">
                                                                                                                                                        <textarea name="txtAddress" type="text" id="txtAddress" cols="34" rows="4" class="textbox"><?php echo $txtAddress;?></textarea>
                                                                                                    </td>
                                                                                                  </tr>
                                                                                                                                        <tr>
                                                                                                    <td height="25" class="maintext">City<font color="#ff0000">*</font></td>
                                                                                                    <td height="25">
                                                                                                      <input name="txtCity" type="text" id="txtCity" size="35" maxlength="100"  value="<?php echo $txtCity?>" class="textbox">
                                                                                                    </td>
                                                                                                          </tr>
                                                                                                                                          <tr>
                                                                                                    <td height="25" class="maintext">State<font color="#ff0000">*</font></td>
                                                                                                    <td height="25">
                                                                                                      <input name="txtState" type="text" id="txtState" size="35" maxlength="100"  value="<?php echo $txtState?>" class="textbox">
                                                                                                    </td>
                                                                                                  </tr>
                                                                                                  <tr>
                                                                                                    <td height="25" class="maintext">Country</td>
                                                                                                    <td height="25">
                                                                                                              <select name="ddlCountry" style="width=200px; " class="selectbox">
                                                                                                                <option>Afghanistan
                                                                                                                    <option>Albania
                                                                                                                    <option>Algeria
                                                                                                                    <option>Andorra
                                                                                                                    <option>Angola
                                                                                                                    <option>Antigua&nbsp; and&nbsp; Barbuda
                                                                                                                    <option>Argentina
                                                                                                                    <option>Armenia
                                                                                                                    <option>Australia
                                                                                                                    <option>Austria
                                                                                                                    <option>Azerbaijan
                                                                                                                    <option>Bahamas
                                                                                                                    <option>Bahrain
                                                                                                                    <option>Bangladesh
                                                                                                                    <option>Barbados
                                                                                                                    <option>Belarus
                                                                                                                    <option>Belgium
                                                                                                                    <option>Belize
                                                                                                                    <option>Benin
                                                                                                                    <option>Bhutan
                                                                                                                    <option>Bolivia
                                                                                                                    <option>Bosnia &amp; Herzegovina
                                                                                                                    <option>Botswana
                                                                                                                    <option>Brazil
                                                                                                                    <option>Brunei
                                                                                                                    <option>Bulgaria
                                                                                                                    <option>Burkina Faso
                                                                                                                    <option>Burundi
                                                                                                                    <option>Cambodia
                                                                                                                    <option>Cameroon
                                                                                                                    <option>Canada
                                                                                                                    <option>Cape Verde
                                                                                                                    <option>Cent African Rep
                                                                                                                    <option>Chad
                                                                                                                    <option>Chile
                                                                                                                    <option>China
                                                                                                                    <option>Colombia
                                                                                                                    <option>Comoros
                                                                                                                    <option>Congo
                                                                                                                    <option>Costa Rica
                                                                                                                    <option>Croatia
                                                                                                                    <option>Cuba
                                                                                                                    <option>Cyprus
                                                                                                                    <option>Czech Republic
                                                                                                                    <option>C&ocirc;te d'Ivoire
                                                                                                                    <option>Denmark
                                                                                                                    <option>Djibouti
                                                                                                                    <option>Dominica
                                                                                                                    <option>Dominican Republic
                                                                                                                    <option>East Timor
                                                                                                                    <option>Ecuador
                                                                                                                    <option>Egypt
                                                                                                                    <option>El Salvador
                                                                                                                    <option>Equatorial Guinea
                                                                                                                    <option>Eritrea
                                                                                                                    <option>Estonia
                                                                                                                    <option>Ethiopia
                                                                                                                    <option>Fiji
                                                                                                                    <option>Finland
                                                                                                                    <option>France
                                                                                                                    <option>Gabon
                                                                                                                    <option>Gambia
                                                                                                                    <option>Georgia
                                                                                                                    <option>Germany
                                                                                                                    <option>Ghana
                                                                                                                    <option>Greece
                                                                                                                    <option>Grenada
                                                                                                                    <option>Guatemala
                                                                                                                    <option>Guinea
                                                                                                                    <option>Guinea-Bissau
                                                                                                                    <option>Guyana
                                                                                                                    <option>Haiti
                                                                                                                    <option>Honduras
                                                                                                                    <option>Hungary
                                                                                                                    <option>Iceland
                                                                                                                    <option>India
                                                                                                                    <option>Indonesia
                                                                                                                    <option>Iran
                                                                                                                    <option>Iraq
                                                                                                                    <option>Ireland
                                                                                                                    <option>Israel
                                                                                                                    <option>Italy
                                                                                                                    <option>Jamaica
                                                                                                                    <option>Japan
                                                                                                                    <option>Jordan
                                                                                                                    <option>Kazakhstan
                                                                                                                    <option>Kenya
                                                                                                                    <option>Kiribati
                                                                                                                    <option>Korea, North
                                                                                                                    <option>Korea, South
                                                                                                                    <option>Kuwait
                                                                                                                    <option>Kyrgyzstan
                                                                                                                    <option>Laos
                                                                                                                    <option>Latvia
                                                                                                                    <option>Lebanon
                                                                                                                    <option>Lesotho
                                                                                                                    <option>Liberia
                                                                                                                    <option>Libya
                                                                                                                    <option>Liechtenstein
                                                                                                                    <option>Lithuania
                                                                                                                    <option>Luxembourg
                                                                                                                    <option>Macedonia
                                                                                                                    <option>Madagascar
                                                                                                                    <option>Malawi
                                                                                                                    <option>Malaysia
                                                                                                                    <option>Maldives
                                                                                                                    <option>Mali
                                                                                                                    <option>Malta
                                                                                                                    <option>Marshall Islands
                                                                                                                    <option>Mauritania
                                                                                                                    <option>Mauritius
                                                                                                                    <option>Mexico
                                                                                                                    <option>Micronesia
                                                                                                                    <option>Moldova
                                                                                                                    <option>Monaco
                                                                                                                    <option>Mongolia
                                                                                                                    <option>Morocco
                                                                                                                    <option>Mozambique
                                                                                                                    <option>Myanmar
                                                                                                                    <option>Namibia
                                                                                                                    <option>Nauru
                                                                                                                    <option>Nepal
                                                                                                                    <option>Netherlands
                                                                                                                    <option>New Zealand
                                                                                                                    <option>Nicaragua
                                                                                                                    <option>Niger
                                                                                                                    <option>Nigeria
                                                                                                                    <option>Norway
                                                                                                                    <option>Oman
                                                                                                                    <option>Pakistan
                                                                                                                    <option>Palau
                                                                                                                    <option>Panama
                                                                                                                    <option>Papua New Guinea
                                                                                                                    <option>Paraguay
                                                                                                                    <option>Peru
                                                                                                                    <option>Philippines
                                                                                                                    <option>Poland
                                                                                                                    <option>Portugal
                                                                                                                    <option>Qatar
                                                                                                                    <option>Romania
                                                                                                                    <option>Russia
                                                                                                                    <option>Rwanda
                                                                                                                    <option>Saint Kitts
                                                                                                                    <option>Saint Lucia
                                                                                                                    <option>Saint Vincent
                                                                                                                    <option>Samoa
                                                                                                                    <option>San Marino
                                                                                                                    <option>Sao Tome
                                                                                                                    <option>Saudi Arabia
                                                                                                                    <option>Senegal
                                                                                                                    <option>Seychelles
                                                                                                                    <option>Sierra Leone
                                                                                                                    <option>Singapore
                                                                                                                    <option>Slovakia
                                                                                                                    <option>Slovenia
                                                                                                                    <option>Solomon Islands
                                                                                                                    <option>Somalia
                                                                                                                    <option>South Africa
                                                                                                                    <option>Spain
                                                                                                                    <option>Sri Lanka
                                                                                                                    <option>Sudan
                                                                                                                    <option>Suriname
                                                                                                                    <option>Swaziland
                                                                                                                    <option>Sweden
                                                                                                                    <option>Switzerland
                                                                                                                    <option>Syria
                                                                                                                    <option>Taiwan
                                                                                                                    <option>Tajikistan
                                                                                                                    <option>Tanzania
                                                                                                                    <option>Thailand
                                                                                                                    <option>Togo
                                                                                                                    <option>Tonga
                                                                                                                    <option>Trinidad and Tobago
                                                                                                                    <option>Tunisia
                                                                                                                    <option>Turkey
                                                                                                                    <option>Turkmenistan
                                                                                                                    <option>Tuvalu
                                                                                                                    <option>Uganda
                                                                                                                    <option>Ukraine
                                                                                                                    <option>United Arab Emirates
                                                                                                                    <option>United Kingdom
                                                                                                                    <option selected>UnitedStates
                                                                                                                    <option>Uruguay
                                                                                                                    <option>Uzbekistan
                                                                                                                    <option>Vanuatu
                                                                                                                    <option>Vatican City
                                                                                                                    <option>Venezuela
                                                                                                                    <option>Vietnam
                                                                                                                    <option>Western Sahara
                                                                                                                    <option>Yemen
                                                                                                                    <option>Yugoslavia
                                                                                                                    <option>Zambia
                                                                                                                    <option>Zimbabwe</option>
                                                                                                              </select>
                                                                                                    </td>
                                                                                                  </tr>
                                                                                                                                          <tr>
                                                                                                    <td height="25" class="maintext">ZIP Code<font color="#ff0000">*</font></td>
                                                                                                    <td height="25">
                                                                                                      <input name="txtZIP" type="text" id="txtZIP" size="6" maxlength="20"  value="<?php echo $txtZIP?>" class="textbox">
                                                                                                    </td>
                                                                                                  </tr>
                                                                                                                                                                                                <tr>
                                                                                                    <td height="25" class="maintext">Phone<font color="#ff0000">*</font></td>
                                                                                                    <td height="25">
                                                                                                      <input name="txtPhone" type="text" id="txtPhone"  size="35" maxlength="20" value="<?php echo $txtPhone?>" class="textbox">
                                                                                                    </td>
                                                                                                  </tr>

                                                                                                  <tr>
                                                                                                    <td height="15" colspan="2" class="maintext"><font color="#666666">&nbsp;</font><font color="#666666">&nbsp;</font></td>

                                                                                                    </tr>
                                                                                                  <tr>
                                                                                                    <td height="25" class="maintext"><div align="right"><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif"> </font><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;
                                                                                                            </font></div></td>
                                                                                                    <td height="25" class="maintext"><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                                                                                      <input name="btnSubmit" type="submit"  value="Sign up" class="editorbutton">
                                                                                                      <input name="reset" type="reset" id="reset3" value="Clear"  class="editorbutton">
                                                                                                                                                </font></td>
                                                                                                  </tr>
                                                                                                                                        <tr><td colspan="2"><font face="Verdana" color="#666666" size="1"><b><font color="#ff0000">*</font>&nbsp;</b></font><font class="maintext"><i> Indicates required field</i></font></td></tr>
                                                                                        </table>

                                                                                                        </form>
                                                                                                  <!-- Main Signup Part Ends -->


                                                                                                </p>
                                                                                                 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" class="dotedline"><img src="images/spacer.gif" width="1" height="1"></td>
                                </tr>
                              </table>

<br><br>&nbsp;
                                                                                        </td>
                                                        </tr>
                                                    </table>
                                                                </td>
                                          </tr>
                                                </table>
                                        </td>
                        <td width="1"  background=images/vline.gif > </td>
                        </tr>
                        </table>
                </td>
        </tr>
</table>