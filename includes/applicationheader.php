<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of easycreate sitebuilder                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php 
    if((strpos($_SERVER['REQUEST_URI'], "admin") != '') ){
        include_once "../includes/config.php"; 
    }else{ 
        include_once "includes/config.php";
    }
    include_once "function.php";
    
    $theme = getSettingsValue('theme');
  // 	$theme = 'Coastal-Green';
    $_SESSION["session_style"] = $theme;

    $logo = getSettingsValue('Logourl');
    
    //if(!isset($_SESSION["session_lookupsitename"]) || $_SESSION["session_lookupsitename"] == "") {   $R130D64A4AD653C91E0FD80DE8FEADC3A = "Select vname,vvalue from tbl_lookup where vname IN('site_name','admin_mail','Logourl','rootserver','secureserver','template_dir') Order by vname ASC";   $R679E9B9234E2062F809DBD3325D37FB6=mysql_query($R130D64A4AD653C91E0FD80DE8FEADC3A) or die(mysql_error());   if(mysql_num_rows($R679E9B9234E2062F809DBD3325D37FB6) >0) {    while($R4EEB713E57BBAAF1217CF39632604473 = mysql_fetch_array($R679E9B9234E2062F809DBD3325D37FB6)) {     switch($R4EEB713E57BBAAF1217CF39632604473["vname"]) {      case "site_name":       $_SESSION["session_lookupsitename"] = $R4EEB713E57BBAAF1217CF39632604473["vvalue"];       break;      case "Logourl":       $_SESSION["session_logourl"] = $R4EEB713E57BBAAF1217CF39632604473["vvalue"];       break;      case "admin_mail":       $_SESSION["session_lookupadminemail"] = $R4EEB713E57BBAAF1217CF39632604473["vvalue"];       break;      case "rootserver":       $_SESSION["session_rootserver"] = $R4EEB713E57BBAAF1217CF39632604473["vvalue"];       break;      case "secureserver":       $_SESSION["session_secureserver"] = $R4EEB713E57BBAAF1217CF39632604473["vvalue"];       break;      case "template_dir":       $_SESSION["session_template_dir"] = $R4EEB713E57BBAAF1217CF39632604473["vvalue"];       break;     }    }   }  }  function FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613){   preg_match("/^(http:\/\/)?([^\/]+)/i",$RD7A9632D7A0B3B4AC99AAFB2107A2613, $R2BC3A0F3554F7C295CD3CC4A57492121);   $RADA370F97D905F76B3C9D4E1FFBB7FFF = $R2BC3A0F3554F7C295CD3CC4A57492121[2];   $R74A7D124AAF5D989D8BDF81867C832AC = 0;   $RA7B9A383688A89B5498FC84118153069 = strlen($RADA370F97D905F76B3C9D4E1FFBB7FFF);   for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {    $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);    if($RF5687F6BBE9EC10202A32FA6C037D42B == ".")     $R74A7D124AAF5D989D8BDF81867C832AC = $R74A7D124AAF5D989D8BDF81867C832AC + 1;   }   $R14AFFF8F3EA02262F39E2785944AAF6F = explode('.',$RADA370F97D905F76B3C9D4E1FFBB7FFF);   $R7CC58E1ED1F92A448A027FD22153E078 = substr($RADA370F97D905F76B3C9D4E1FFBB7FFF, -7);     $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = "";   $R368D5A631F1B03C79555B616DDAC1F43 = array('.com.uk','kids.us','kids.uk','.com.au','.com.br','.com.pl','.com.ng','.com.ar','.com.ve',           '.com.ng','.com.mx','.com.cn');   $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = in_array($R7CC58E1ED1F92A448A027FD22153E078, $R368D5A631F1B03C79555B616DDAC1F43);     if(!$RF413F06AEBBCEF5E1C8B1019DEE6FE6B) {    if(count($R14AFFF8F3EA02262F39E2785944AAF6F) == 1){     $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $RADA370F97D905F76B3C9D4E1FFBB7FFF;    }else if((count($R14AFFF8F3EA02262F39E2785944AAF6F) > 1) && (strlen(substr($R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-2],0,38)) > 2)){     preg_match("/[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);     $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];    }else{     preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $RADA370F97D905F76B3C9D4E1FFBB7FFF, $R2BC3A0F3554F7C295CD3CC4A57492121);     $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R2BC3A0F3554F7C295CD3CC4A57492121[0];    }   }else    $RF877B1AAD1B2CBCDEC872ADF18E765B7 = $R14AFFF8F3EA02262F39E2785944AAF6F[count($R14AFFF8F3EA02262F39E2785944AAF6F)-3];     $R10870E60972CEA72E14A11D115E17EA5 = explode('.',$RF877B1AAD1B2CBCDEC872ADF18E765B7);   $RD48CAD37DBDD2B2F8253B59555EFBE03   = strtoupper(trim($R10870E60972CEA72E14A11D115E17EA5[0]));      return $RD48CAD37DBDD2B2F8253B59555EFBE03;  }  function FCE74825B5A01C99B06AF231DE0BD667D($RD7A9632D7A0B3B4AC99AAFB2107A2613){   if(F12DE84D0D1210BE74C53778CF385AA4D($RD7A9632D7A0B3B4AC99AAFB2107A2613))    return true;   $RD7A9632D7A0B3B4AC99AAFB2107A2613  = FC718EAC1D5F164063CBA5FB022329FC7($RD7A9632D7A0B3B4AC99AAFB2107A2613);   $RB5719367F67DC84F064575F4E19A2606 =  getLicense();     $RFDFD105B00999E2642068D5711B49D5D  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, 0, 3);   $RA6CC906CDD1BAB99B7EB044E98D68FAE  =  substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, -3,3);     $R8439A88C56A38281A17AE2CE034DB5B7  =  substr($RB5719367F67DC84F064575F4E19A2606, 0, 3);   $R254A597F43FF6E1BE7E3C0395E9409D4 =  substr($RB5719367F67DC84F064575F4E19A2606, 3, 3);   $RDE2A352768EABA0E164B92F7ACA37DEE  =  substr($RB5719367F67DC84F064575F4E19A2606, -3,3);      $R254A597F43FF6E1BE7E3C0395E9409D4 = FCE67EB692054EBB3F415F8AF07562D82($R254A597F43FF6E1BE7E3C0395E9409D4, 3);   $RDE2A352768EABA0E164B92F7ACA37DEE = FCE67EB692054EBB3F415F8AF07562D82($RDE2A352768EABA0E164B92F7ACA37DEE, 3);     $R705EE0B4D45EEB1BC55516EB53DF7BCE  = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6,          'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10,'K' => 11,'L' => 12,          'M' => 13,'N' => 14,'O' => 15,'P' => 16,'Q' => 17,'R' => 18,          'S' => 19,'T' => 20,'U' => 21,'V' => 22,'W' => 23,'X' => 24,          'Y' => 25,'Z' => 26,'1' => 1, '2' => 2, '3' => 3, '4' => 4,          '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9);   $RA7B9A383688A89B5498FC84118153069 = strlen($RD7A9632D7A0B3B4AC99AAFB2107A2613);   $RA5694D3559F011A29A639C0B10305B51 = 0;   for ($RA09FE38AF36F6839F4A75051DC7CEA25 = 0; $RA09FE38AF36F6839F4A75051DC7CEA25 < $RA7B9A383688A89B5498FC84118153069; $RA09FE38AF36F6839F4A75051DC7CEA25++) {    $RF5687F6BBE9EC10202A32FA6C037D42B = substr($RD7A9632D7A0B3B4AC99AAFB2107A2613, $RA09FE38AF36F6839F4A75051DC7CEA25, 1);    $RA5694D3559F011A29A639C0B10305B51 = $RA5694D3559F011A29A639C0B10305B51 + $R705EE0B4D45EEB1BC55516EB53DF7BCE[$RF5687F6BBE9EC10202A32FA6C037D42B];   }   if($RA5694D3559F011A29A639C0B10305B51 != ($R8439A88C56A38281A17AE2CE034DB5B7 - 9))    return false;   else if(strcmp($RFDFD105B00999E2642068D5711B49D5D,$R254A597F43FF6E1BE7E3C0395E9409D4) != 0)    return false;   else if(strcmp($RA6CC906CDD1BAB99B7EB044E98D68FAE,$RDE2A352768EABA0E164B92F7ACA37DEE) != 0)    return false;   else    return true;  }  function FCE67EB692054EBB3F415F8AF07562D82($R8409EAA6EC0CE2EA307354B2E150F8C2, $R68EAF33C4E51B47C7219F805B449C109) {   $RF413F06AEBBCEF5E1C8B1019DEE6FE6B = strrev($R8409EAA6EC0CE2EA307354B2E150F8C2);   return $RF413F06AEBBCEF5E1C8B1019DEE6FE6B;  }  function F12DE84D0D1210BE74C53778CF385AA4D($R5E4A58653A4742A450A6F573BD6C4F18){   if (preg_match("/^[0-9].+$/", $R5E4A58653A4742A450A6F573BD6C4F18)){       return true;      }else    return false;  }  $R8FF184E9A1491F3EC1F61AEB9A33C033 = "invalidlicenseadmin.php";  $RD7A9632D7A0B3B4AC99AAFB2107A2613 = strtoupper(trim($_SERVER['HTTP_HOST']));  if($RD7A9632D7A0B3B4AC99AAFB2107A2613 == '192.168.0.11' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == 'LOCALHOST' || $RD7A9632D7A0B3B4AC99AAFB2107A2613 == '127.0.0.1'){ ;   }else if(!FCE74825B5A01C99B06AF231DE0BD667D($RD7A9632D7A0B3B4AC99AAFB2107A2613)) {   header("Location:$R8FF184E9A1491F3EC1F61AEB9A33C033");   exit;  }
?>
<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<TITLE><?php echo getSettingsValue('site_name'); ?> - <?php echo HEADER_SITE_TITLE; ?></TITLE>
<META name="description" content="<?php echo getSettingsValue('site_name'); ?> will let you build your own websites online using our large collection of graphically intensive templates and template editors.Build a web site in six easy steps.">
<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

<link href="<?php echo BASE_URL?>themes/<?php echo $theme;?>/style.css" type="text/css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Dosis:300' rel='stylesheet' type='text/css'>

<script language="javascript1.1" type="text/javascript">
history.forward();
</script>
<link href="favicon.ico" type="image/x-icon" rel="icon"> 
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">


<link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>