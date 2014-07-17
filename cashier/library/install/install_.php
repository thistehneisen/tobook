<?php

/************************************
* 	@author			Mian Saleem		*
* 	@package 		SMA2			*
* 	@subpackage 	install			*
************************************/

$indexFile = "../index.php";
$configFolder = "../sma/config";
$configFile = "../sma/config/config.php";
$dbFile = "../sma/config/database.php";

require_once("../../../includes/configsettings.php");

global $prefix; 
	
$db_url = isset($_COOKIE['cashier_manager']) ? $_COOKIE['cashier_manager'] : null;
$db = array();

if (isset($db_url)) {
	$db_url = urldecode($db_url);

	foreach ( explode('&', $db_url) as $value ) {
		$_db = explode('=', $value);
		$db[$_db[0]] = $_db[1];
	}
}

$dbpw = isset($_COOKIE['cashier_manager_p']) ? $_COOKIE['cashier_manager_p'] : '';


$localpath = str_replace("\\", "/", dirname(getenv("SCRIPT_NAME")));

$localpath = str_replace("\\", "/", $localpath);
$localpath = preg_replace('/^\//', '', $localpath, 1) . '/';
$localpath = !in_array($localpath, array('/', '\\')) ? $localpath : NULL;

$install_url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $localpath;
	
	
switch($_GET['step']){
	default: ?>
		<ul class="steps">
		<li class="active pk">Checklist</li>
		<!--<li>Register</li>-->
		<li>Database</li>
		<li>Site Config</li>
		<li class="last">Done!</li>
		</ul>
		<h3>Pre-Install Checklist</h3>
		<?php 
			$error = FALSE;
			if(!is_writeable($indexFile)){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Index Filer (index.php) is not writeable!</div>"; }
			if(!is_writeable($configFolder)){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Config Folder (sma/config/) is not writeable!</div>"; }
			if(!is_writeable($configFile)){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Config File (sma/config/config.php) is not writeable!</div>"; }
			if(!is_writeable($dbFile)){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Database File (sma/config/database.php) is not writeable!</div>"; }
			if(phpversion() < "5.3"){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Your PHP version is ".phpversion()."! PHP 5.3 or higher required!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> You are running PHP ".phpversion()."</div>";} 
			if(!extension_loaded('mcrypt')){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Mcrypt PHP exention missing!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> Mcrypt PHP exention loaded!</div>";}
			if(!extension_loaded('mysql')){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Mysql PHP exention missing!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> Mysql PHP exention loaded!</div>";}
			if(!extension_loaded('mysqli')){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Mysqli PHP exention missing!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> Mysqli PHP exention loaded!</div>";}
			if(!extension_loaded('mbstring')){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> MBString PHP exention missing!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> MBString PHP exention loaded!</div>";}
			if(!extension_loaded('gd')){echo "<div class='alert alert-error'><i class='icon-remove'></i> GD PHP exention missing!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> GD PHP exention loaded!</div>";}
			if(!extension_loaded('curl')){$error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> CURL PHP exention missing!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> CURL PHP exention loaded!</div>";}
?>      
		<div class="bottom">
			<?php if($error){ ?>
				<a href="#" class="btn btn-primary disabled">Next Step</a>
			<?php }else{
			
					$code = 'e499efe2-7f15-4be8-9f9a-6694026b5c39'; //$_POST["code"];
					$username = 'varaacom1'; //$_POST["username"];
					$mail = 'varaacom1@gmail.com';
		    		?>
		    		
		            <form action="?step=1&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
				
						<!-- <div class="alert alert-success"><i class='icon-ok'></i> <strong><?php echo ucfirst($object->status); ?></strong>:<br /><?php echo $object->message; ?></div>-->   
						<input id="code" type="hidden" name="code" value="<?php echo $code; ?>" />
				        <input id="username" type="hidden" name="username" value="<?php echo $username; ?>" />
						<div class="bottom">
							<input type="submit" class="btn btn-primary" value="Next Step"/>
						</div>
					</form> 
			<?php } ?>
		</div>

<?php
	break;
	case "0": ?>
	<ul class="steps">
		<li class="ok"><i class="icon icon-ok"></i>Checklist</li>
		<li class="active">Register SMA</li>
		<li>Database</li>
		<li>Site Config</li>
		<li class="last">Done!</li>
		</ul>
	<h3>Varify your purchase</h3>
	<?php
		if($_POST){
			$code = $_POST["code"];
			$username = $_POST["username"];
			$curl_handle = curl_init();  
			curl_setopt($curl_handle, CURLOPT_URL, 'http://tecdiary.com/support/api/register/');  
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);  
			curl_setopt($curl_handle, CURLOPT_POST, 1);  
			$referer = "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -15);
			$path = substr(realpath(dirname(__FILE__)), 0, -8);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(  
				'username' => $_POST["username"], 
				'email' => $_POST["email"], 
				'code' => $_POST["code"] ,
				'id' => '5403161',
				'ip' => $_SERVER['REMOTE_ADDR'],
				'referer' => $referer,
				'path' => $path
			));  
			  
			$buffer = curl_exec($curl_handle);  
			curl_close($curl_handle);  
			  
			$object = json_decode($buffer);  

			if ($object->status == 'success') { 
		    ?>
            <form action="?step=1&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		
				<div class="alert alert-success"><i class='icon-ok'></i> <strong><?php echo ucfirst($object->status); ?></strong>:<br /><?php echo $object->message; ?></div>   
				<input id="code" type="hidden" name="code" value="<?php echo $code; ?>" />
		        <input id="username" type="hidden" name="username" value="<?php echo $username; ?>" />
				<div class="bottom">
					<input type="submit" class="btn btn-primary" value="Next Step"/>
				</div>
			</form>
				    <?php
			}else{ ?>
				<div class="alert alert-error"><i class='icon-remove'></i> <strong><?php echo ucfirst($object->status); ?></strong>:<br /> <?php echo $object->message; ?></div>
				<form action="?step=0&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
					<div class="control-group">
			          <label class="control-label" for="username">Envato Username</label>
			          <div class="controls">
			          <input id="username" type="text" name="username" class="input-large" required data-error="Username is required" placeholder="Envato Username" />
			          </div>
			        </div>
			        <div class="control-group">
			          <label class="control-label" for="email">Support Email</label>
			          <div class="controls">
			          <input id="email" type="text" name="email" class="input-large" required data-error="Email is required" placeholder="Support Email" />
			          </div>
			        </div>
			        <div class="control-group">
			          <label class="control-label" for="code">Purchase Code <a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i></a></label>
			          <div class="controls">
			          <input id="code" type="text" name="code" class="input-large" required data-error="Purchase Code is required" placeholder="Purchase Code" />
			          </div>
			        </div>
					<div class="bottom">
						<input type="submit" class="btn btn-primary" value="Check"/>
					</div>
				</form>
			<?php
			}
		}else{	?>
	<p>Please enter the required informarmation to register free support account and varify your purchase. </p><br>
		<form action="?step=0&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="username">Envato Username</label>
          <div class="controls">
          <input id="username" type="text" name="username" class="input-large" required data-error="Username is required" placeholder="Envato Username" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="email">Support Email</label>
          <div class="controls">
          <input id="email" type="text" name="email" class="input-large" required data-error="Email is required" placeholder="Support Email" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="code">Purchase Code <a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i></a></label>
          <div class="controls">
          <input id="code" type="text" name="code" class="input-large" required data-error="Purchase Code is required" placeholder="Purchase Code" />
          </div>
        </div>
		
		<div class="bottom">
			<input type="submit" class="btn btn-primary" value="Validate"/>
		</div>
		</form>
	<?php }
	break;
	case "1": ?>
	<ul class="steps">
		<li class="ok"><i class="icon icon-ok"></i>Checklist</li>
		<!-- <li class="ok"><i class="icon icon-ok"></i>Register</li>-->
		<li class="active">Database</li>
		<li>Site Config</li>
		<li class="last">Done!</li>
		</ul>
	<?php if($_POST){ ?>
	<h3>Database Config</h3>
	<p>If the database does not exist the system will try to create it.</p>
		<form action="?step=2&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		<div style="display:none;">
		<div class="control-group" >
          <label class="control-label" for="dbhost">Database Host</label>
          <div class="controls">
          <input id="dbhost" readonly type="text" name="dbhost" class="input-large" required data-error="DB Host is required" placeholder="DB Host" value="<?php echo MYSQL_HOST ; ?>" />
          </div>
        </div>
        <div class="control-group" >
          <label class="control-label" for="dbusername">Database Username</label>
          <div class="controls">
          <input id="dbusername" readonly type="text" name="dbusername" class="input-large" required data-error="DB Username is required" placeholder="DB Username" value="<?php echo MYSQL_USERNAME; ?>" />
          </div>
        </div>
        <div class="control-group" >
          <label class="control-label" for="dbpassword">Database Password</a></label>
          <div class="controls">
          <input id="dbpassword" readonly type="password" name="dbpassword" class="input-large" data-error="DB Password is required" placeholder="DB Password" value="<?php echo MYSQL_PASSWORD; ?>" />
          </div>
        </div>
        <div class="control-group" >
          <label class="control-label" for="dbname">Database Name</label>
          <div class="controls">
          <input id="dbname" readonly type="text" name="dbname" class="input-large" required data-error="DB Name is required" placeholder="DB Name" value="<?php echo MYSQL_DB; ?>"/>
          </div>
        </div>
        <div class="control-group" >
          <label class="control-label" for="dbprefix">Table prefix</label>
          <div class="controls">
          <input id="dbprefix" readonly type="text" name="dbprefix" class="input-large" placeholder="DB Name" value="<?php echo $prefix; ?>"/>
          </div>
        </div>
        </div>
		<input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
        <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
		<div class="bottom">
			<input type="submit" class="btn btn-primary" value="Next Step"/>
		</div>
		</form>
	<?php }
	break;
	case "2":
	?>
	<ul class="steps">
		<li class="ok"><i class="icon icon-ok"></i>Checklist</li>
		<!-- <li class="ok"><i class="icon icon-ok"></i>Register</li>-->
		<li class="active">Database</li>
		<li>Site Config</li>
		<li class="last">Done!</li>
		</ul>
	<h3>Saving database config</h3>
	<?php
		if($_POST){
			$dbhost = $_POST["dbhost"];
			$dbusername = $_POST["dbusername"];
			$dbpassword = $_POST["dbpassword"];
			$dbname = $_POST["dbname"];
			$dbprefix = isset($_POST["dbprefix"]) ? $_POST["dbprefix"] . 'sma_' : 'sma_';
			$code = $_POST["code"];
			$username = $_POST["username"];
			$link = @mysql_connect($dbhost, $dbusername, $dbpassword);

		if (!$link) {
		    echo "<div class='alert alert-error'><i class='icon-remove'></i> Could not connect to MYSQL!</div>";
		}else{
			echo '<div class="alert alert-success"><i class="icon-ok"></i> Connection to MYSQL successful!</div>';
			
			$db_selected = @mysql_select_db($dbname, $link);
			if (!$db_selected) {
				if(!mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */")){
					echo "<div class='alert alert-error'><i class='icon-remove'></i> Database ".$dbname." does not exist and could not be created. Please create the Database manually and retry this step.</div>";
					
					return FALSE;
				}else{ echo "<div class='alert alert-success'><i class='icon-ok'></i> Database ".$dbname." created</div>";}
			}
				mysql_select_db($dbname);
				
				require_once('includes/core_class.php');
				$core = new Core();
				$dbdata = array(
						'hostname' => $dbhost,
						'username' => $dbusername,
						'password' => $dbpassword,
						'database' => $dbname,
						'dbprefix' => $dbprefix
						);
				
				if ($core->write_database($dbdata) == false) {
					echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write database details to ".$dbFile."</div>";
				} else { 
					echo "<div class='alert alert-success'><i class='icon-ok'></i> Database config written to the database file.</div>"; 
				}
		
		}
		} else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
		?>
		<div class="bottom">
			<form action="?step=1&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		    <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
            <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
			<input type="submit" class="btn pull-left" value="Previous Step"/>
			</form>
			<form action="?step=3&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		    <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
            <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
			<input type="submit" class="btn btn-primary pull-right" value="Next Step">
			</form>
			<br clear="all">
		</div>
		<?php
	break;
	case "3":
	?>
		<ul class="steps">
		<li class="ok"><i class="icon icon-ok"></i>Checklist</li>
		<!-- <li class="ok"><i class="icon icon-ok"></i>Register</li>-->
		<li class="ok"><i class="icon icon-ok"></i>Database</li>
		<li class="active">Site Config</li>
		<li class="last">Done!</li>
		</ul>
        <h3>Site Config</h3>
		<?php if($_POST){ ?>
		<form action="?step=4&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		<div class="control-group">
          <label class="control-label" for="domain">SMA URL</a></label>
          <div class="controls">
          
          <input type="text" id="domain" name="domain" class="xlarge" required data-error="SMA URL is required" value="<?php echo substr($install_url, 0, -8); ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="domain">Your Timezone</a></label>
          <div class="controls">
            <?php 
			require_once('includes/timezones_class.php');
			$tz = new Timezones();
			$timezones = $tz->get_timezones();
			echo '<select name="timezone" required="required" data-error="TimeZone is required">';
            foreach ($timezones as $key => $zone){
            echo '<option value="'.$key.'">'.$zone.'</option>';
            }
            echo '</select>'; ?>
          </div>
        </div>    
		<input type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
        <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
		<div class="bottom">
			<a href="?step=2&amp;prefix=<?php echo $prefix; ?>" class="btn pull-left">Previous Step</a>
			<input type="submit" class="btn btn-primary" value="Next Step"/>
		</div>
		</form>
		
	<?php }
	break;
	case "4":
	?>
	<ul class="steps">
		<li class="ok"><i class="icon icon-ok"></i>Checklist</li>
		<!-- <li class="ok"><i class="icon icon-ok"></i>Register</li>-->
		<li class="ok">Database</li>
		<li class="active">Site Config</li>
		<li class="last">Done!</li>
		</ul>
	<h3>Saving site config</h3>
	<?php
		if($_POST){
			$domain = $_POST['domain'];
			$timezone = $_POST['timezone'];
			$code = $_POST["code"];
			$username = $_POST["username"];

			require_once('includes/core_class.php');
			$core = new Core();
						
			if ($core->write_config($domain) == false) {
				echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write config details to ".$configFile."</div>";
			} elseif ($core->write_index($timezone) == false) {
				echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write timezone details to ".$indexFile."</div>";
			} else { 
				echo "<div class='alert alert-success'><i class='icon-ok'></i> Config details written to the config file.</div>"; 
			}
		
		
		} else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
		?>
		<div class="bottom">
			<form action="?step=2&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		    <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
            <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
			<input type="submit" class="btn pull-left" value="Previous Step"/>
			</form>
			<form action="?step=5&amp;prefix=<?php echo $prefix; ?>" method="POST" class="form-horizontal">
		    <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
            <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
			<input type="submit" class="btn btn-primary pull-right" value="Next Step">
			</form>
			<br clear="all">
		</div>

	<?php
	break;
	case "5": ?>
		<ul class="steps">
		<li class="ok"><i class="icon icon-ok"></i>Checklist</li>
		<!-- <li class="ok"><i class="icon icon-ok"></i>Register</li>-->
		<li class="ok"><i class="icon icon-ok"></i>Database</li>
		<li class="ok"><i class="icon icon-ok"></i>Site Config</li>
		<li  class="active">Done!</li>
	</ul>

	<?php if($_POST){
			$code = $_POST['code'];
			$username = $_POST['username'];
			define("BASEPATH", "install/");
			include("../sma/config/database.php");
			$file_sql = 'config/database.sql';
			$database_str = '';
			if ( is_file($file_sql)) {
				ob_start();
				readfile($file_sql);
				$database_str = ob_get_contents();
				ob_end_clean();
				if ($database_str !== false)
				{
					$database_str = preg_replace(
							array(
									'/INSERT\s+INTO\s+`/',
									'/DROP\s+TABLE\s+`/',
									'/DROP\s+TABLE\s+IF\s+EXISTS\s+`/',
									'/CREATE\s+TABLE\s+`/',
									'/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/'
							),
							array(
									'INSERT INTO `'.$db['default']['dbprefix'],
									'DROP TABLE `'.$db['default']['dbprefix'],
									'DROP TABLE IF EXISTS `'.$db['default']['dbprefix'],
									'CREATE TABLE `'.$db['default']['dbprefix'],
									'CREATE TABLE IF NOT EXISTS `'.$db['default']['dbprefix']
							),
							$database_str);
				}
			}
			$dbdata = array(
						'hostname' => $db['default']['hostname'],
						'username' => $db['default']['username'],
						'password' => $db['default']['password'],
						'database' => $db['default']['database'],
						'dbtables' => $database_str
						);
			require_once('includes/database_class.php');
			$database = new Database();
			if ($database->create_tables($dbdata) == false) {
				$finished = FALSE;
				echo "<div class='alert alert-warning'><i class='icon-warning'></i> The database tables could not be created, please try again.</div>";
			} else {
				$finished = TRUE;
					if(!@unlink('../SMA2')){
					echo "<div class='alert alert-warning'><i class='icon-warning'></i> Please remove the SMA2 file from the main folder in order to lock the ipdate tool.</div>";
					}
			}
		} 
		if($finished) {?>
			<h3><i class='icon-ok'></i> Installation completed!</h3>
			<div class="alert alert-info">
				<i class='icon-info-sign'></i> You can create your account on the login page:<br /><br />
			</div>
			
			<a href="/cashier/library/index.php?module=auth&view=create_user&prefix=<?php echo $prefix; ?>" class="btn btn-success pull-right" style="padding: 6px 15px;">Create User</a>
			<div style="clear:both;"></div>

			<!-- div class="alert alert-info"><i class='icon-info-sign'></i> You can login now using the following credential:<br /><br />
	            Username: <span style="font-weight:bold; letter-spacing:1px;">owner@tecdiary.com</span><br />
	            Password: <span style="font-weight:bold; letter-spacing:1px;">12345678</span><br /><br />
            </div>
            <div class="alert alert-warning"><i class='icon-warning-sign'></i> Please don't forget to change username and password.</div -->
            
			<!-- <div class="bottom">
				<a href="<?php echo substr($install_url, 0, -8); ?>index?prefix=<?php echo $prefix; ?>&amp;module=home" class="btn btn-primary">Go to Login</a>
			</div>-->
			
	<?php	
		}
	}
?>

 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">How to find your purchase code</h3>
  </div>
  <div class="modal-body">
    <img src="img/purchaseCode.png">
  </div>
</div>