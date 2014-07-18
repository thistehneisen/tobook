<?php
if(($_SESSION["session_loginname"] == "") && basename($_SERVER["REQUEST_URI"]) != "login.php") {
    header("location:login.php");
    exit;
}
include "applicationheader.php";
?>
<link href="style/common.css" type="text/css" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<!-- script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script -->
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>
<!-- script src="/asset/js/postMessage.js"></script -->

<script>
function openWindow(url){
    window.open(url,'Help','left=20,top=20,width=700,height=500,toolbar=0,resizable=1' );
}
</script>
</head>
<body>
	<div id="mainHeader">
		<div style="font-family: 'Comfortaa';font-weight:bold;font-size:56px;padding-top:20px;padding-left:100px;">
			<span style="color:#FFF;">varaa</span><sapn style="color:#000;">.com</sapn>
		</div>
		<?php if(isset($_SESSION["session_loginname"]) || $_SESSION["session_loginname"]!="") { ?>
		<div id="mainHeaderWelcome">
			<?php echo DASHBOARD_WELCOME; ?> <span ><?php echo ucwords($_SESSION['session_loginname']);?></span>
		</div>
		<?php } ?>
		<div id="mainHeaderNav" style="">
		    <ul>
		        <li><a href="index.php" class="<?php echo ($curTab=='home')?'active':''?>"><?php echo TOP_LINKS_HOME; ?></a></li>
		        <?php
		        if(!isset($_SESSION["session_loginname"]) || $_SESSION["session_loginname"]=="") {
		        ?>
		            <li><a href="signup.php" class="<?php echo ($curTab=='signup')?'active':''?>"><?php echo TOP_LINKS_SIGNUP; ?></a></li>
		            <li><a href="login.php" class="<?php echo ($curTab=='login')?'active':''?>"><?php echo LOGIN_TITLE; ?></a></li>
		        <?php
		        }else {
		        ?>
		            <li><a href="<?php echo BASE_URL?>usermain.php" class="<?php echo ($curTab=='dashboard')?'active':''?>" ><?php echo TOP_LINKS_DASHBOARD; ?></a></li>
		            <li><a href="<?php echo BASE_URL?>profilemanager.php" class="<?php echo ($curTab=='profile')?'active':''?>"><?php echo TOP_LINKS_MY_ACCOUNT; ?></a></li>
		            <li><a href="#" onClick="javascript:openWindow('<?php echo BASE_URL ?>userhelp/index.html');" title="<?php echo HEADER_HELP_TITLE; ?>"><?php echo HEADER_HELP; ?></a></li>
		            <li><a href="<?php echo BASE_URL?>logout.php" class="<?php echo ($curTab=='logout')?'active':''?>"><?php echo TOP_LINKS_LOGOUT; ?></a></li>
		        <?php
		        }
		        ?>
		    </ul>
		    <div class="clear"></div>
		</div>
	</div>
	
	<div style="width:100%;height:4px;background:#3C3C3C;"></div>