<?php 
if(file_exists('language/english_lng_admin.php')) {
	include 'language/english_lng_admin.php';
}
else {
	include '../language/english_lng_admin.php';
}
if (basename($_SERVER['PHP_SELF']) <> "index.php") { 
	if(($_SESSION["session_username"] == "" || $_SESSION["session_username"] != "admin") && basename($_SERVER["REQUEST_URI"]) != "index.php" ){
					header("location:index.php");
	}
}
/*
if($_SESSION["session_username"] == "admin" && $_SERVER['HTTP_REFERER'] == "" && basename($_SERVER["REQUEST_URI"]) != "dashboard.php"){
                header("location:dashboard.php");
}
*/

/* LicenceKey Check */
include "license.php";
$objLicense	= new License();

$table="tbl_lookup";
$var_domain	= strtoupper(trim($_SERVER['HTTP_HOST']));
if($var_domain == '192.168.0.11' || $var_domain == 'LOCALHOST' || $var_domain == '127.0.0.1') {
    ;
}else if(!$objLicense->FCE74825B5A01C99B06AF231DE0BD667D($var_domain,$table)) {
    header("Location:invalidlicense.php");
    exit;
}
/* LicenceKey Check */

$currentloc="../";
$_SESSION["session_style"]="site.css";
include "../includes/applicationheader.php"; 
$logo = LookupDisplay('Logourl');
?>
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
    
<!--div class="admin-logo">
	ONLINE SITE BUILDER
</div-->
<div class="top_rightsection">
	<!-- ///start of header links.........................................    -->
	<div class="admin-top-links">
	<p>
            <?php
            if(isset($_SESSION['session_username']) AND $_SESSION['session_username']!=''){?>
                <a href=logout.php ><?php echo LOGOUT;?></a> | <a href="#"  onClick="javascript:openWindow('adminhelp/index.html');" title="Open Admin Help Window"><?php echo HELP;?></a>
        </p>
        <p class="admin_welcome"><?php echo WELCOME;?> <b><?php echo ADMIN;?></b></p>
           <?php
            }?>
	</div>

<div class="clear"></div>
</div>


<div class="clear"></div>
</div>



<div class="clear"></div>
</div>
	<div class="nav">
		<?php
	if ($_SESSION["session_username"] == "admin"){
		include "includes/adminlinks.php";
	}
	?>
	</div>
<!-- top hrd wrap start -->


<!-- Content area home start -->


<div class="cntarea_dvs_admin">

<div class="cnt_innerdvs_admin" align="center">


