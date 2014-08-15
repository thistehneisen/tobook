<?php
 function CheckServerConfig(&$safemode,&$openbasedir, &$gdfreesupport){
    if( ini_get("safe_mode") =="1" ){
	   $safemode="Safe mode restriction in effect";
	}else{
	   $safemode="0";
	}
	if( ini_get("open_basedir") !="" ){
	   $openbasedir="Openbase directory enabled.".ini_get("open_basedir");
	    //this was the original code here
            //$oprarray=explode(":",)
            $oprarray=explode(":", $openbasedir);
	}else{
	   $openbasedir="0";
	}
	$gdinfo=gd_info();
	if($gdinfo["FreeType Support"]){
	  	$gdfreesupport="1";
	}else{
	  	$gdfreesupport="GD free type support not available";
	}

}
  CheckServerConfig($safemode,$openbasedir, $gdfreesupport);
  echo "tmpdir".ini_get("user_dir");
  echo "<br>safemode==".$safemode;
  echo "<br>openbasedir=".$openbasedir;
  echo "<br>gdfreesupport=".$gdfreesupport;
  
?>