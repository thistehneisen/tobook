<?php
/******************************************************************
*	
*	SubmitForce.class.php								
*	--------------------------------------------------------------								
*	Author	:	Arun Vijayan. C	<ifetchmaster@hotmail.com>						
*	Date	:	Wed Aug 20 2003							
*																		
*	Desc : SubmitForce v1.0 Search Engine submission class file			
*																			
*******************************************************************/
		
/******************************************************************
*                                         				                               
*   This program is free software; You can redistribute it and/or
*	modify it under the terms of the GNU General Public License as
*	published by the Free Software Foundation; provided, you keep
*	this text intact.  
*
*******************************************************************/


class SubmitForce{

//  Public vars
	
	var $name			=	"SubmitForce ";		
	var $version		=	"1.0";				
	var $host			=	"www.google.com";	// engine hostname
	var $port			=	80;					// default port
	var $user_agent		=	"";
	var $referer		=	"";	
	var $user			=	"";					// name of the person submitting the page
	var $email			=	"nospam@somewhere.com";
														// email of the user
	var $url			=	"";						// url of the page to be submitted
	
//  Private vars

	var $_error			=	"";					// Last error message
	var $_fatal			=	false;				// True if the error is fatal
	var $_response		=	"";					// response returned from server
	var $_status		=	"";					// status of submission
	var $_result		=	0;					// 0 = submission fails, 1 = success
	var $_headers		=	"";					// headers returned from server 
	var $_block_size	=	128;				// socket read block size
												
	var $_connect_timeout = 10;					// max time for connection establishment
	var $_read_timeout  =   20;					// allowed max read time [>=PHP 4 Beta 4]
	var $_engine_timeout=   40;					// allowed for each submission	
	
	var $_SF_mode;								// ONLINE 1 | DEBUG 0
												
	var $_headers_sent	=	"";					// custom formatted header to be sent
	var $_submit_method	=	"GET";				// default form sending method
	var $_http_version	=	"HTTP/1.0";			// alternate version HTTP/1.1
	var $_success_str	=	"/thank you/i";		// returned str if engine accpted the URL.
	var $_fail_str		=	"could not";		// URL not accepted by engine
	var $_e_form_vars	=	"";					// submitted form query string
	var $_e_file_path	=	"";					// filepath to engine server page
	var $_engine_name	=	"Google";			// Engine name
	var $_selected_engines	= array();			// engines selected for submission

	var $_timer_init;							// init timer
	var $_timer_start;							// stopwatch start time for each event
	var $_validate_user_data = TRUE;			// if url and email has to be 
												// validated(recommended)
	


// function description
function SubmitForce()
{  
	define("DEBUG", 0);
	define("ONLINE", 1);
	$this->_SF_mode = ONLINE;
	return true;
} // end function



// ONLINE or DEBUG modes
function set_mode($mode=ONLINE)
{
	switch($mode)
	{
		case DEBUG  :	$this->_SF_mode = DEBUG;
						$this->_status($this->name." is in debugging mode.<br>Change the mode to ONLINE <br>for turning off status and error messages.<BR>");
						break; //debug
		case ONLINE :	$this->_SF_mode = ONLINE; break;	// online
		default     :  echo "You are using an invalid status display mode<br>";
	}
}	

// set the sataus message
function _status($msg)
{
	$this->_status	=  $msg;
	switch($this->_SF_mode)
	{
		case DEBUG  :	echo "<BR>".$this->_status ;
						Break;					
		case ONLINE  :   Break;
		default :	echo "<br>You are using an invalid status display mode<br>";
	}
}


// return recent elapsed time
function _reset_stopwatch()
{
	$this->_timer_start = microtime();
}

// calculate difference b/w two microtimes
// cut-n-paste code from www.php.net/microtime
function _micro_difference($a,$b)
{    list($a_micro, $a_int)=explode(' ',$a);
     list($b_micro, $b_int)=explode(' ',$b);
     if ($a_int>$b_int) {
        return ($a_int-$b_int)+($a_micro-$b_micro);
     } elseif ($a_int==$b_int) {
        if ($a_micro>$b_micro) {
          return ($a_int-$b_int)+($a_micro-$b_micro);
        } elseif ($a_micro<$b_micro) {
           return ($b_int-$a_int)+($b_micro-$a_micro);
        } else {
          return 0;
        }
     } else { // $a_int<$b_int
        return ($b_int-$a_int)+($b_micro-$a_micro);
     }
} // end function

// return recent elapsed time
function _display_stopwatch()
{   
	if(!$this->_SF_mode)//print if in debug mode
	{
		echo "&nbsp<span class=\"time\">[";
		echo sprintf("%.6f",$this->_micro_difference($this->_timer_start,microtime()));
		echo "]</span>";
    }
	$this->_reset_stopwatch();//printed time, now reset timer
} // end function


// initialize class variables
function init($url,$email,$user,$selected_engines)
{
	$this->_timer_init = microtime();
	$this->_timer_start = 	$this->_timer_init;

	$this->_status("Loading user data...");
	$url = trim($url);

	if($this->_validate_user_data) 
	{
		//js version
		//$pat = "/^(http:\/\/)([a-z0-9]+([-|.]{1}[a-z0-9]+)*\.(com|edu|biz|org|gov|int|info|mil|net|arpa|name|museum|coop|aero|[a-z][a-z])|(\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]))\b";
		
		// real pattern
		//$pat='/^(?:(?:ftp|https?):\/\/)?(?:[a-z0-9](?:[-a-z0-9]*[a-z0-9])?\.)+(?:com|edu|biz|org|gov|int|info|mil|net|name|museum|coop|aero|[a-z][a-z])\b(?:\d+)?(?:\/[^;"\'<>()\[\]{}\s\x7f-\xff]*(?:[.,?]+[^;"\'<>()\[\]{}\s\x7f-\xff]+)*)?/';
		
		// ftp and https are disabled in this version since some engines do not work with them
		$pat_url='/^(?:http:\/\/)?(?:[a-z0-9](?:[-a-z0-9]*[a-z0-9])?\.)+(?:com|edu|biz|org|gov|int|info|mil|net|name|museum|coop|aero|[a-z][a-z])\b(?:\d+)?(?:\/[^;"\'<>()\[\]{}\s\x7f-\xff]*(?:[.,?]+[^;"\'<>()\[\]{}\s\x7f-\xff]+)*)?/i';
		if(preg_match($pat_url,$url))
		{
			$this->url=$url;
		}
		else
		{
			$this->_result = 0;
			$this->_fatal = true;
			$this->_error = "<span class=\"errormessage\"><b>Please submit a valid URL, starting with http://<br></b></span>";
		}

		//$pat="^(.+)((@){1})(.+)[a-zA-Z]{2,3}$"; //mild Js version
		$pat_email='/^[a-z0-9]+([-|_|.][a-z0-9]+)*\@([a-z0-9]+([-|.]{1}[a-z0-9]+)*\.(com|edu|biz|org|gov|int|info|mil|net|arpa|name|museum|coop|aero|[a-z][a-z])|(\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]))$/';
		if(preg_match($pat_email,$email))
		{
				$this->email=trim($email);
		}
		else
		{
			$this->_result = 0;
			$this->_fatal = true;
			$this->_error = "<span class=\"errormessage\"><b>Please submit a valid e-mail<br></b></span>";
		}

	}

	$this->user=trim($user);

	$this->_selected_engines=$selected_engines;
	if(!$selected_engines)
	{
		echo "<span class=\"errormessage\"><b><br>You did not select any engines.</b></span>";
		//echo " Please <a href=\"javascript:history.go(-1);\">Go back</a> and select engines.";
		//exit();
	}
	$this->_display_stopwatch();// = Loading User data 
}

// Re assign varibles after eche engine submission
function _restart()
{
	
	$this->_e_form_vars	=	'';
	$this->_e_file_path	=	'';
	$this->_engine_name	=	" Search Engine ";
	$this->_success_str	=	"thank you";
	$this->user_agent	=	"$this->name$this->version";
	$this->referer		=	'';
	$this->_headers_sent	=	'';
	$this->_result		=	0;
	$this->_response	=	'';
	$this->_error		=	'';
	return true;
}


// Format custom HTTP headers that has to be submitted
function _format_header()
{	
	$this->_reset_stopwatch();
	$this->_status("Formatting HTTP headers...");
	$myget_vars="";
	$newline="\x0D\x0A";		//   \r\n
	$doublenewline="\x0A\x0A";	//   \n\n

	if($this->_submit_method=="GET") 
	{
		$myget_vars="?".$this->_e_form_vars;
	}

	$headers = $this->_submit_method." ".$this->_e_file_path.$myget_vars." ".$this->_http_version.$newline;
	
	if(!empty($this->user_agent))
	{
		$headers .= "User-Agent: ".$this->user_agent.$newline;
	}

	if(!empty($this->host))
	{
		$headers .= "Host: ".$this->host.$newline;
	}

	$headers .= "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*".$newline;
	$headers .= "Accept-Language: en-us".$newline;
	$headers .= "Content-Type: application/x-www-form-urlencoded".$newline;
	
	if(!empty($this->referer))
	{
		$headers .= "Referer: ".$this->referer.$newline;
	}

	if($this->_submit_method == "POST")
	{
		$headers .= "Content-Length: ".strlen($this->_e_form_vars).$doublenewline;
		//throw error if changed to $newline...well, u find out

		$headers .= $this->_e_form_vars.$doublenewline;
	}
	
	$this->_headers_sent	=	$headers."\x0D\x0A";
	$this->_display_stopwatch();
}


// load the object with engine data
function load_engine($engine_name,$engine_url,$submit_method = "GET",$success_str="Thank you",$user_agent,$referer)
{	
	$this->_reset_stopwatch();
	
	$this->_status("<hr size=1 width=40% align=left noshade>Loading engine variables... ");
	$temp_url='';
	$this->_restart();	// Reinit variables.

	if(!empty($engine_name)) $this->_engine_name=ucwords($engine_name);

	$E_URI_PARTS	=	parse_url($engine_url);
	$this->host		=	$E_URI_PARTS["host"];
	$temp_url		=   str_replace("[URL]",$this->url,$E_URI_PARTS["query"]);	// replace [URL] with real url
	$temp_url		=	str_replace("[EMAIL]",$this->email,$temp_url);			// replace [EMAIL] with real email
	
	//$temp_url	 =	str_replace("[IP]",$REMOTE_ADDR,$temp_url);					// replace [IP] with real IP {intented for HOTBOT, but not anymore.}
	
	$this->_e_form_vars = $temp_url;
	$this->_e_file_path = $E_URI_PARTS["path"];
	
	

	if(!empty($user_agent)) 
	{
		$this->user_agent=$user_agent;
	}
	if(!empty($success_str))
	{
		$this->_success_str=$success_str;
	}
	if(!empty($referer))
	{
		$this->referer	=	$referer;
	}
	if(!empty($E_URI_PARTS["port"]))
	{
		$this->port = $E_URI_PARTS["port"];
	}
											
	switch(strtolower($submit_method))
	{
		case "post" :	$this->_submit_method = "POST";	Break;
		Default		:	$this->_submit_method = "GET";
	}
	
	$this->_display_stopwatch();
	$this->_format_header();
}




// Open socket connection
function _socket_connect(&$fp)
{
	$this->_reset_stopwatch();
	$this->_status("Connecting : ".$this->host." ");			
	$fp	= @fsockopen($this->host,$this->port,$errno,$errstr,$this->_connect_timeout);
	
	if($fp)
	{
		$this->_display_stopwatch();
		return true;
	}
	else
	{
		// socket connection failed		
		switch($errno)
		{
			case 110:
				$this->_error="Socket init timeout (110)";
			case -3:
				$this->_error="Socket creation failed (-3)";
			case -4:
				$this->_error="DNS lookup failure (-4)";
			case -5:
				$this->_error="Connection refused or timed out (-5)";
			default:
				$this->_error="Socket connection failed (".$errno.")";
		}
	$this->_result = 0;
	$this->_display_stopwatch();
	return false;
	}

}

// Disconnect socket
function _socket_disconnect($fp)
{	
	$this->_status("Disconnecting...");
	return(fclose($fp));
}


function queue_engines($fname)
{
	$engines_temp = Array();
	$temp_engine  = Array();
	$this->_reset_stopwatch();
	$this->_status("Loading \"$fname\" file...");	
	if(is_readable($fname) && ($engines_temp = @file($fname)))
	{
		// filter out junk
		$f_index = 0; //index of filtered array
		while(list($key,$val)=each($engines_temp))
		{
			$temp_engine = explode('#',$val);
			$temp_engine[0] = trim($temp_engine[0]);
			if(!empty($temp_engine[0])) $this->engines_array[$f_index++] = $temp_engine[0]; 
		}		
		// filtered

		$this->_display_stopwatch();
		return true;
	}
	else
	{
		$this->_error  = "Could not read engine data file : $fname";
		$this->_result = 0;
		$this->_fatal  = true ; // this error is fatal
		$this->_display_stopwatch();
	}
	return false;
}



// Submit the page to all selected search engines
function submit_page()
{

	 if($this->_fatal)
	{
			echo("<br><span class=\"error\"><i>$this->_error</i></span><br>");
			return false;
	}
	// display fatal error





	$engines= Array();
	$temp	= Array();
	for($i=0;$i<sizeof($this->engines_array);$i++) // upto the number of engines
	{
			
		$temp=explode(',',$this->engines_array[$i]); //extract comma[,] seperated vars		
		if(empty($this->_selected_engines[$i])) continue; //var[0]=alltheweb, var[1]=google etc..		
		
		$this->load_engine(	trim($temp[0]),		// engine name
							trim($temp[1]),		// engine url
							trim($temp[2]),		// submit method
							trim($temp[3]),		// success string
							trim($temp[4]),		// user agent
							trim($temp[5]));	// referer

		set_time_limit($this->_engine_timeout);// allow more time for each submission

		$this->_submit();

		//echo "<br>\n<span class=result>".$this->_engine_name." : ";echo "</span>";
		if($this->_result)
		{
			//echo "<span class=\"result1\"><b>Submitted</b></span><br>";
		}
		else
		{
			//echo "<span class=\"result0\">Submission Failed</span><br>";
			//$this->_status("<i><span class=\"error\">$this->_error</span></i><br>");
		}
			
	}
  //echo "<span class=\"successmessage\"><b>Your Site is Submitted Successfully to the Search engines</b></span><br>";
	return 'success';

 $this->_timer_start=$this->_timer_init; // calculate overall submission time
// echo "<br><br>SubmitForce took "; 
 //echo sprintf("%.6f",$this->_micro_difference($this->_timer_init,microtime()));
 //echo " seconds";
}

// now, real submit
function _submit()
{
		
   if($this->_socket_connect($fp))
	{
		$this->_status("Connected to ".$this->host);
	}
	else
	{
		return false;
	}
	
	
	if ($this->_read_timeout > 0)
	{
		@socket_set_timeout($fp, $this->_read_timeout);// PHP4 BETA
	}
	
	$this->_status("<form>HTTP headers : <br><textarea class=response rows=5 cols=50>".trim($this->_headers_sent)."</textarea></form>Sending headers......");
	
	$this->_reset_stopwatch();
	fputs($fp, $this->_headers_sent);
	$this->_display_stopwatch();

	
	$this->_status("Receiving response...");
	$temp_str_1="";
	$temp_str_2="";
	
	

	
	while($this->_socket_live($fp) && !$this->_result && !feof($fp) )	//read if socket is connected, not reached EOF, or the _success_str is absent so far. 
	{	//eregi
		$temp_str_2 = fread($fp,$this->_block_size);
             //   echo $temp_str_1 . $temp_str_2; 
		//if(preg_match($this->_success_str,$temp_str_1 . $temp_str_2)) // success_str is present
		//{	
			$this->_result = 1; // done
		//}
		
		$temp_str_1 = substr($temp_str_2,$this->_block_size - strlen($this->_success_str));
		//comparing string is the slice of privious block tailed with current block
		
		$this->_response .= $temp_str_2;		
	} 

	
	if(!$this->_result){$this->_error  = $this->_engine_name." rejected your submission.";}

	flush();
	$this->_display_stopwatch();
	$this->_socket_disconnect($fp);	

	$this->_status("<form>Received response : <br><textarea class=response rows=5 cols=50>".trim($this->_response)."</textarea></form>");
	
	return $this->_result;
	
}

// check if socket is ready communicate
function _socket_live($fp)
{
	$fp_status = socket_get_status($fp);
	if ($fp_status["timed_out"])
	{
		return false; //socket timed out
	}

	return true;
}// end function




//end class
}

?>