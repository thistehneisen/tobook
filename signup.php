<?php
$curTab = 'signup';

// include common files
include "includes/session.php";
include "includes/config.php";

// Setup Swiftmailer
require 'vendor/autoload.php';
$transport = Swift_SmtpTransport::newInstance(SMTP_HOST, SMTP_PORT)
    ->setUsername(SMTP_USERNAME)
    ->setPassword(SMTP_PASSWORD);
$mailer = Swift_Mailer::newInstance($transport);


if (isset($_GET['type']) && $_GET['type'] === 'startnow') {
    $_SESSION['start_type'] = "sitemanager";
} else {
    unset($_SESSION['start_type']);
}

redirectLoginUser();

function isValidUsername($str) {
    if (trim($str) != "") {
        if (preg_match("[^0-9a-zA-Z+_]", $str)) {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return false;
    }
}

// handle post back of signup
$message = "";
$act = "";

if (isset($_GET["act"])) {
    $act = $_GET["act"];
}

$vuser_login     = isset($_POST['vuser_login']) ? $_POST['vuser_login'] : '';
$vuser_password  = isset($_POST['vuser_password']) ? $_POST['vuser_password'] : '';
$vuser_password1 = isset($_POST['vuser_password1']) ? $_POST['vuser_password1'] : '';
$txtFirstName    = isset($_POST['txtFirstName']) ? $_POST['txtFirstName'] : '';
$txtLastName     = isset($_POST['txtLastName']) ? $_POST['txtLastName'] : '';
$txtCity         = isset($_POST['txtCity']) ? $_POST['txtCity'] : '';
$txtState        = isset($_POST['txtState']) ? $_POST['txtState'] : '';
$txtZip          = isset($_POST['txtZip']) ? $_POST['txtZip'] : '';
$vuser_email     = isset($_POST['vuser_email']) ? $_POST['vuser_email'] : '';
$vuser_phone     = isset($_POST['vuser_phone']) ? $_POST['vuser_phone'] : '';

if ($act == "post") { //if postback
    /* For setting editor path */
    $rootPath = getSettingsValue('rootpath');
    $_SESSION['ROOT_PATH'] = $rootPath;
    $serverPermission = getSettingsValue('serverPermission');
    if ($serverPermission == "0755") {
        $_SESSION['SERVER_PERMISSION'] = 0755;
    }
    else {
        $_SESSION['SERVER_PERMISSION'] = 0777;
    }

    // permission setting ends
    // check for duplicate user name
    $sql = "SELECT * from tbl_user_mast WHERE vuser_login='" . addslashes($_POST["vuser_login"]) . "' AND vdel_status != '1'";
    $result = mysql_query($sql, $con) or die(mysql_error());

    // check for duplicate user name
    $sqlEmailCheck = "SELECT * from tbl_user_mast WHERE vuser_email='" . addslashes($_POST["vuser_email"]) . "' AND vdel_status != '1'";
    $resultEmailCheck = mysql_query($sqlEmailCheck, $con) or die(mysql_error());
    if ($_POST['vuser_password'] == '' || $_POST['vuser_password1'] == '') {
        $message = SIGNUP_PWDS;
    } else if ($_POST['vuser_password'] != $_POST['vuser_password1']) {
        $message = SIGNUP_SIMILAR_PWDS;
    } else if (!isValidUsername($_POST["vuser_login"])) {
        $message = SIGNUP_LOG_NAME;
    } else if (mysql_num_rows($result) > 0) {
        $message = SIGNUP_LOGINEXISTS;
    } else if (mysql_num_rows($resultEmailCheck) > 0) {
        $message = SIGNUP_EMAILEXISTS;
    } else {
        $var_naffid = 0;
        if (isset($_SESSION["session_naffid"]) && $_SESSION["session_naffid"] != "") {
            $var_naffid = $_SESSION["session_naffid"];
        }
        
        // create new account
        $sql = "insert into `tbl_user_mast` (nuser_id,vuser_login,vuser_password,
                vuser_name,vuser_lastname,vuser_address1,vuser_address2,vcity,vstate,vzip,vcountry,
                        vuser_email,vuser_phone,vuser_fax,
                        duser_join,vuser_style,naff_id,vdel_status) values
                (  NULL, '".addslashes($_POST["vuser_login"])."','".md5(addslashes($_POST["vuser_password"]))."','".
                addslashes($_POST["txtFirstName"])."','".
                addslashes($_POST["txtLastName"])."','".
                addslashes($_POST["vuser_address1"])."','".
                addslashes($_POST["vuser_address2"])."','".
                addslashes($_POST["txtCity"])."','".
                addslashes($_POST["txtState"])."','".
                addslashes($_POST["txtZip"])."','".
                addslashes($_POST["cmbCountry"])."','".
                addslashes($_POST["vuser_email"])."','".
                addslashes($_POST["vuser_phone"])."','".
                addslashes($_POST["vuser_fax"])."',now(),'site.css','" . $var_naffid . "','0')";

        mysql_query($sql, $con);
        $_SESSION["owner_id"] = mysql_insert_id();
        $_SESSION["session_naffid"] = "";

        // check if session alredy populated
        if ($_SESSION["session_lookupsitename"] != "") {
            $lookupsitename = getSettingsValue('site_name');
            $admin_email = getSettingsValue('admin_mail');
        } //if session not populated
        else {

            // get values from lookup table
            $sql = "Select vname,vvalue from tbl_lookup where vname IN('site_name','admin_mail','Logourl','rootserver','secureserver','template_dir','signupfield_disp') Order by vname ASC";
            $result = mysql_query($sql) or die(mysql_error());
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    switch ($row["vname"]) {
                    case "site_name":
                        $_SESSION["session_lookupsitename"] = $row["vvalue"];
                        break;

                    case "Logourl":
                        $_SESSION["session_logourl"] = $row["vvalue"];
                        break;

                    case "admin_mail":
                        $_SESSION["session_lookupadminemail"] = $row["vvalue"];
                        break;

                    case "rootserver":
                        $_SESSION["session_rootserver"] = $row["vvalue"];
                        break;

                    case "secureserver":
                        $_SESSION["session_secureserver"] = $row["vvalue"];
                        break;

                    case "template_dir":
                        $_SESSION["session_template_dir"] = $row["vvalue"];
                        break;
                    } //end swith
                } //end while
            } //end if
            $lookupsitename = $_SESSION["session_lookupsitename"];
            $admin_email = $_SESSION["session_lookupadminemail"];
        }

        // mail the user welcome mail
        $sitelogo = BASE_URL . getSettingsValue('Logourl');
        $body = "<table>
                <tr>
                    <td>
                        <img src=" . $sitelogo . "><br /><br />
                    </td>
                  </tr>
                  <tr>
                       <td> 
                        Dear " . htmlentities($_POST["txtFirstName"] . "  " . $_POST["txtLastName"]) . ",<br /><br />
                       </td>
                  </tr>
                  <tr>
                    <td>
                        " . SIGNUP_WEL_MSG . " " . $lookupsitename . "<br /><br />

                        " . LOGIN_INFO . "<br />

                        " . SIGNUP_USERNAME . $_POST["vuser_login"] . "   <br />
                         " . SIGNUP_PWD . $_POST["vuser_password"] . "  <br /><br />
                         
                        " . LOGIN_CLICK . " <a href='" . BASE_URL . "/login.php'>here</a> " . TO_LOGIN . THANKU . " <br /><br />


                       " . REGARDS . "<br />
                        $lookupsitename" . TEAM . "<br />
                    </td>
                 </tr>";
        $admin_email = 'hieu@iki.fi';
        $msg1 = Swift_Message::newInstance("Welcome to $lookupsitename")
            ->setFrom(array($admin_email))
            ->setTo(array($_POST["vuser_email"]))
            ->setBody($body, 'text/html');
        $mailer->send($msg1);

        $body = "<table>
                <tr>
                    <td>
                        <img src=" . $sitelogo . "><br /><br />
                    </td>
                  </tr>
                  <tr>
                       <td> Hello Admin,<br /><br />There is a new signup at $lookupsitename<br />
          The following are the details: <br />
          First Name :" . htmlentities($_POST["txtFirstName"]) . " <br />
          Last Name :" . htmlentities($_POST["txtLastName"]) . " <br />
          Username :" . $_POST["vuser_login"] . "   <br />
          Email :" . $_POST["vuser_email"] . "   <br />
          <br />Regards<br />
      $lookupsitename<br /></td></tr></table>";

        $msg2 = Swift_Message::newInstance("A new signup at $lookupsitename")
            ->setFrom(array($admin_email))
            ->setTo(array($admin_email))
            ->setBody($body, 'text/html');
        $mailer->send($msg2);

        // redirect to logged in area
        $_SESSION["session_userid"] = mysql_insert_id();
        $_SESSION["session_loginname"] = $_POST["vuser_login"];
        $_SESSION["session_email"] = $_POST["vuser_email"];
        if ($_SESSION['session_templateselectedfromindex'] == "YES") {
            Header("location:usermain.php");
        } else {
            Header("location:usermain.php?action=newuser");
        }

        exit;
    }
}

$sql = "Select vname,vvalue from tbl_lookup where vname='signupfield_disp'";
$result = mysql_query($sql) or die(mysql_error());
$dispRow = mysql_fetch_row($result);
$formfiledarray = unserialize($dispRow[1]);
include "includes/applicationheader.php";

?>
</head>

<body>
    <div class="topNavigationMenu">
        <a href="websiteList.php">ETUSIVU</a>&nbsp;|&nbsp;
        <a href="signup.php" class="selected">REKISTERÖIDY</a>&nbsp;|&nbsp;
        <a href="login.php">KIRJAUDU</a>
    </div>

    <div class="guideTopArea">
        <div class="guideTopVaraa">
            <span>varaa</span>
            <span class="fontBlack">.com</span>
        </div>
        <div class="guideTopTitle">
            <span>Rekisteröidy</span>
        </div>
    </div>
    <div class="greyDivider"></div>
    <div class="regTopBody">
        <div style="padding-top:80px;color:#ec7923;font-size:40px;font-weight:500;font-family:'Comfortaa';padding-left:200px;">Luo Tilin</div>
        <div style="padding-top:5px;color:#000;font-size:18px;font-weight:500;font-family:'Comfortaa';padding-left:200px;">Täytä seuraavat tiedot:</div>

        <div class="common_box" align="center" style="margin-top:30px;">

            <form name="regForm" method=post action=signup.php?act=post style="margin-bottom: 0px;">
                <table cellpadding="0" cellspacing="0" border="0" class="commntbl_style1" align="left" style="float: none;color:#000;">
                    <tr>
                        <td align=center colspan=3>
                            <font color=red>
                                <?php echo $message; ?>
                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td width="25%" align=left>
                            <?php echo LOGIN_NAME; ?>
                            <font color=red>*</font>
                        </td>
                        <td width="4%"></td>
                        <td width="71%" align=left>
                            <input type=text name="vuser_login" id="vuser_login" maxlength="15" value="<?php echo htmlentities($vuser_login) ?>" size="30">
                        </td>
                    </tr>

                    <tr>
                        <td align=left>
                            <?php echo LOGIN_PASSWORD; ?>
                            <font color=red>*</font>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=password name="vuser_password" id="vuser_password" maxlength="50" value="<?php echo $vuser_password ?>" size="30">
                        </td>
                    </tr>


                    <tr>
                        <td align=left>
                            <?php echo LOGIN_PASSWORD_CONFIRM; ?>
                            <font color=red>*</font>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=password name="vuser_password1" id="vuser_password1" maxlength="50" value="<?php echo $vuser_password1 ?>" size="30">
                        </td>
                    </tr>


                    <tr>
                        <td align=left>
                            <?php if (getFormFieldstatus($formfiledarray, 'lastName', 0)) echo SIGNUP_FIRST_NAME; else echo SIGNUP_NAME; ?>
                            <font color=red>*</font>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text name="txtFirstName" id="txtFirstName" maxlength="100" value="<?php echo htmlentities($txtFirstName) ?>" size="30">
                        </td>
                    </tr>

                    <!--commented for new design-->
                    <?php if (getFormFieldstatus($formfiledarray, 'lastName', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_LAST_NAME;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'lastName', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text name="txtLastName" id="txtLastName" maxlength="100" value="<?php echo htmlentities($txtLastName) ?>" size="30">
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_EMAIL;?>
                            <font color=red>*</font>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text value="<?php echo htmlentities($vuser_email) ?>" name="vuser_email" id="vuser_email" maxlength="100" size="30">
                        </td>
                    </tr>
                    <?php if (getFormFieldstatus($formfiledarray, 'address1', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_ADDRESS1;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'address1', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text name="vuser_address1" value="<?php echo htmlentities($address1) ?>" id="vuser_address1" maxlength="200" size="30">
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (getFormFieldstatus($formfiledarray, 'city', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_CITY;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'city', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text value="<?php echo htmlentities($txtCity) ?>" name="txtCity" id="txtCity" maxlength="100" size="30">
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (getFormFieldstatus($formfiledarray, 'state', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_STATE;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'state', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text value="<?php echo htmlentities($txtState) ?>" name="txtState" id="txtState" maxlength="100" size="30">
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if (getFormFieldstatus($formfiledarray, 'country', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_COUNTRY;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'country', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                        </td>
                        <td></td>
                        <td align=left>
                            <select name="cmbCountry" class="selectbox" style="width:368px;height: 34px;padding-top: 6px; ">
                                <?php include "includes/countries.php"; ?>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (getFormFieldstatus($formfiledarray, 'zip', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_ZIP;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'zip', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text value="<?php echo htmlentities($txtZip) ?>" name="txtZip" id="txtZip" maxlength="20" size="30">
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (getFormFieldstatus($formfiledarray, 'phone', 0)) { ?>
                    <tr>
                        <td align=left>
                            <?php echo SIGNUP_PHONE;?>
                            <?php if (getFormFieldstatus($formfiledarray, 'phone', 1)) { ?>
                            <font color=red>*</font>
                            <?php } ?>
                            <font color=red></font>
                        </td>
                        <td></td>
                        <td align=left>
                            <input type=text name="vuser_phone" id="vuser_phone" maxlength="30" value="<?php echo htmlentities($vuser_phone) ?>" size="30">
                        </td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td valign="top" align="left" colspan="2" class="extrapadding"></td>
                        <td style="text-align:left;">
                            <p class="notetxt" style="font-size:11px; ">
                                <?php echo SIGNUP_CONDITION; ?>
                            <?php if (isset($_SESSION[ "session_lookupsitename"])) :
                                echo(htmlentities($_SESSION[ "session_lookupsitename"]));
                            endif; ?>
                                <a href="terms.php" target=tos>
                                    <?php echo SIGNUP_TERMS; ?>
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td align="left">
                            <p class="login_extralinks" style="float:left; padding:7px 85px 7px 0; ">
                                <?php echo SIGNUP_EXISTING_USER; ?>
                                <a href="login.php">
                                    <?php echo SIGNUP_LOGIN_HERE;?>
                                </a>
                            </p>
                            <!-- commented for new design   <input  class=button type=reset value="Reset">-->
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td align="left">
                            <input class="btnSignUp" type="button" onClick="validate();" value="<?php echo strtoupper(TOP_LINKS_SIGNUP); ?>">
                        </td>
                    </tr>
                </table>
                <div class="clearboth"></div>
            </form>

        </div>
    </div>
    <div class="greyDivider"></div>
    <?php include "includes/footArea.php"; ?>

    <script>
    if (document.regForm.cmbCountry) {
        for (i = 0; i < regForm.cmbCountry.options.length; i++) {
            if (regForm.cmbCountry.options[i].text == "UnitedStates") {
                regForm.cmbCountry.options[i].selected = true;
                break;
            }
        }
    }

    function checkMail(email) {
        var str1 = email;
        var arr = str1.split('@');
        var eFlag = true;
        if (arr.length != 2) {
            eFlag = false;
        } else if (arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1) {
            eFlag = false;
        } else {
            var dot = arr[1].split('.');
            if (dot.length < 2) {
                eFlag = false;
            } else {
                if (dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1) {
                    eFlag = false;
                }

                for (i = 1; i < dot.length; i++) {
                    if (dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1 || dot[i].length > 4) {
                        eFlag = false;
                    }
                }
            }
        }
        return eFlag;
    }

    function validate() {

        var frm = document.regForm;
        if (frm.vuser_login.value == "") {

            alert("<?php echo VAL_LOGINNAME;?>");
            frm.vuser_login.focus();
            return false;

        } else if (frm.vuser_login.value.length > 15) {

            alert("<?php echo VAL_NAMELENGHTH;?>");
            frm.vuser_login.focus();
            return false;
        } else if (frm.vuser_password.value == "") {

            alert("<?php echo VAL_SIGNUP_PWD;?>");
            frm.vuser_password.focus();
            return false;

        } else if (frm.vuser_password1.value == "") {

            alert("<?php echo VAL_SIGNUP_CFRMPWD;?>");
            frm.vuser_password1.focus();
            return false;

        } else if (frm.txtFirstName.value == "") {

            alert("<?php echo VAL_SIGNUP_FNAME_EMPTY;?>");
            frm.txtFirstName.focus();
            return false;

        } else {
            if (checkOptionalFields()) {
                frm.submit();
            } else {
                return false;
            }
        }

    }

    function checkOptionalFields() {
        var frm = document.regForm;

        if (frm.vuser_email) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'email', 1)) echo '1'; else echo '0'; ?>;
            if (frm.vuser_email.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_EMAIL_EMPTY;?>");
                frm.vuser_email.focus();
                return false;
            } else if (checkMail(frm.vuser_email.value) == false) {

                alert('<?php echo VAL_SIGNUP_EMAIL_FORMAT;?>');
                frm.vuser_email.focus();
                return false;

            } else if (checkMail(frm.vuser_email.value) == false) {

                alert('<?php echo VAL_SIGNUP_EMAIL_FORMAT;?>');
                frm.vuser_email.focus();
                return false;

            }
        }

        if (frm.txtLastName) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'lastName', 1)) echo '1'; else echo '0'; ?>;
            if (frm.txtLastName.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_LNAME_EMPTY;?>");
                frm.txtLastName.focus();
                return false;
            }
        }

        if (frm.vuser_address1) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'address1', 1)) echo '1'; else echo '0'; ?>;
            if (frm.vuser_address1.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_ADDRESS;?>");
                frm.vuser_address1.focus();
                return false;
            }
        }


        if (frm.txtCity) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'city', 1)) echo '1'; else echo '0'; ?>;
            if (frm.txtCity.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_CITY;?>");
                frm.txtCity.focus();
                return false;
            }
        }

        if (frm.txtState) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'state', 1)) echo '1'; else echo '0'; ?> ;
            if (frm.txtState.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_STATE;?>");
                frm.txtState.focus();
                return false;
            }
        }

        if (frm.txtZip) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'zip', 1)) echo '1'; else echo '0'; ?> ;
            if (frm.txtZip.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_ZIP;?>");
                frm.txtZip.focus();
                return false;
            }
        }

        if (frm.vuser_phone) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'phone', 1)) echo '1'; else echo '0'; ?> ;
            if (frm.vuser_phone.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_PHONE;?>");
                frm.vuser_phone.focus();
                return false;
            }
        }

        if (frm.vuser_fax) {
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'fax', 1)) echo '1'; else echo '0'; ?> ;
            if (frm.vuser_fax.value == "" && proceedFlag == 1) {
                alert("<?php echo VAL_SIGNUP_FAX;?>");
                frm.vuser_fax.focus();
                return false;
            }
        }

        return true;
    }
    </script>

</body>
</html>
