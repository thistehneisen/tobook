<?php
include "../includes/session.php";
include "../includes/config.php";
include "includes/license.php";

$objLicense	= new License($objDB);
$message	= "";

$table= "tbl_lookup";
$var_domain = strtoupper(trim($_SERVER['HTTP_HOST']));
$error = '';


function isNotNull($value) {
    if (is_array($value)) {
        if (sizeof($value) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
            return true;
        } else {
            return false;
        }
    }
}

if($objLicense->FCE74825B5A01C99B06AF231DE0BD667D($var_domain,$table)) {
    header("Location:index.php");
    exit;
}

if($_REQUEST['submit']) {
    $license    =   trim($_REQUEST['inputlicense']);
    $password   =   md5(trim($_REQUEST['password']));

    if(!isNotNull($password)) {
        $message .= "  Enter Admin Password!" . "<br>";
        $error = true;
    }else {
        $adminValue = $objLicense->FB65FDD43B9A0C83B8499D74B1A31890A($table,$password);
        if(empty($adminValue)) {
            $message .= "  Enter a valid Admin Password!" . "<br>";
            $error = true;
        }
    }
    if (!isNotNull($license)) {
        $message .= "  Enter LicenseKey!" . "<br>";
        $error = true;
    }
    if(count($adminValue)>0) {
        $objLicense->F03FD063C610FFF78F127C6DCC52A6524($table,$license);
    }

    if(!$error) {
        if($objLicense->FCE74825B5A01C99B06AF231DE0BD667D($var_domain,$table)) {
            header("Location:index.php");
            exit;
        }else {
            $message = "Invalid License Key!<br/> Please Enter A Valid License Key.";
            $error = true;
        }
    }
}

$logo = getSettingsValue('Logourl');
$theme = getSettingsValue('theme');
$_SESSION["session_style"] = $theme;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo getSettingsValue('site_name') ?> - The do it yourself online website builder</title>
        <META name="description" content="<?php echo getSettingsValue('site_name') ?> will let you build your own websites online using our large collection of graphically intensive templates and template editors.Build a web site in six easy steps.">
        <META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
        <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
        <link href="../favicon.ico" type="image/x-icon" rel="icon">
        <link href="../favicon.ico" type="image/x-icon" rel="shortcut icon">
        <link href="<?php echo BASE_URL?>themes/<?php echo $theme;?>/style.css" type="text/css" rel="stylesheet">
    </head>


    <body  topmargin="0">
        <div class="wrps_main">
            <!-- top hrd wrap start -->
            <div class="inner_top_wrps">

                <div class="tpwrp_inner">
                    <div class="logo_tpsections">
                        <a href="index.php">
                            <a href="index.php"><img src ="<?php echo BASE_URL.$logo; ?>" border=0></a>
                        </a>
                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>

            <!-- top hrd wrap start -->


            <!-- Content area home start -->


            <div class="cntarea_dvs_admin">

                <div class="cnt_innerdvs_admin" align="center">


                    <table width="100%">
                        <tr>
                            <td>
                                <div class="admin-pnl">
                                    <table width="100%" class="admin-login">
                                        <tr><td><h3>Invalid License key</h3></td></tr>
                                        <tr>
                                            <td>

                                                <form name="frmLicenseKeyCheck" method="POST" action="">
                                                    <table border="0" cellspacing="0" cellpadding="0" class="login-table">
                                                        <?php
                                                        if($error) {
                                                            ?>
                                                        <tr>
                                                            <td colspan="2" class="maintext warning"><b><font color="#FF0000">Please correct the following errors to continue:</font></b></td>
                                                        </tr>
                                                        <tr><td> &nbsp;</td></tr>
                                                        <tr>
                                                            <td colspan="2" class=maintext><font color=red><?php echo $message;?></font></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class="maintext warning">&nbsp;</td>
                                                        </tr>
                                                            <?php }//end if?>

                                                        <tr>
                                                            <td class=maintext><label>Admin Password<font color=red><sup>*</sup></font></label></td>
                                                            <td align=left><input type=password  class=textbox  name="password" id="inputPassword" maxlength="100"  placeholder="Admin Password"></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="maintext" width="30%"><label>License Key<font color=red><sup>*</sup></font></label></td>
                                                            <td align=left><input type="text" class=textbox name="inputlicense" id="inputlicense" placeholder="License Key" maxlength="100" value="<?php echo htmlentities($_REQUEST["inputlicense"]); ?>"></td>
                                                        </tr>



                                                        <tr align="left">
                                                            <td>&nbsp;</td>
                                                            <td><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                        <td align="right"><span class="maintext3">
                                                                                <input type="submit" name="submit" class="btn01" value="Update"  />
                                                                            </span></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                    </table>
                                                </form>


                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>


                    <?php include "includes/adminfooter.php";?>