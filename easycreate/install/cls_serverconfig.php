<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+
// | File name : Utils.php                                         		  |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              		  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class ServerConfig {

    public static function is_valid_email($address) {
        $rx = "^[a-z0-9\\_\\.\\-]+\\@[a-z0-9\\-]+\\.[a-z0-9\\_\\.\\-]+\\.?[a-z]{1,4}$";
        return (preg_match("~" . $rx . "~i", $address));
    }

    // For Stripslashes
    public static function stripslashes($string) {

        return stripslashes($string);
    }

    /* -----------------------------INSTALLER SPECIFIC FUNCTIONS------------------------ */

    // import data from sql files - installer
    public static function splitSqlFile($sql, $delimiter) {
        // Split up our string into "possible" SQL statements.
        $tokens = explode($delimiter, $sql);
        // try to save mem.
        $sql = "";
        $output = array();
        // we don't actually care about the matches preg gives us.
        $matches = array();
        // this is faster than calling count($oktens) every time thru the loop.
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++) {
            // Don't wanna add an empty string as the last thing in the array.
            if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
                // This is the total number of single quotes in the token.
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                // Counts single quotes that are preceded by an odd number of backslashes,
                // which means they're escaped quotes.
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

                $unescaped_quotes = $total_quotes - $escaped_quotes;
                // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
                if (($unescaped_quotes % 2) == 0) {
                    // It's a complete sql statement.
                    $output[] = $tokens[$i];
                    // save memory.
                    $tokens[$i] = "";
                } else {
                    // incomplete sql statement. keep adding tokens until we have a complete one.
                    // $temp will hold what we have so far.
                    $temp = $tokens[$i] . $delimiter;
                    // save memory..
                    $tokens[$i] = "";
                    // Do we have a complete statement yet?
                    $complete_stmt = false;

                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                        // This is the total number of single quotes in the token.
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        // Counts single quotes that are preceded by an odd number of backslashes,
                        // which means they're escaped quotes.
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                        $unescaped_quotes = $total_quotes - $escaped_quotes;

                        if (($unescaped_quotes % 2) == 1) {
                            // odd number of unescaped quotes. In combination with the previous incomplete
                            // statement(s), we now have a complete statement. (2 odds always make an even)
                            $output[] = $temp . $tokens[$j];
                            // save memory.
                            $tokens[$j] = "";
                            $temp = "";
                            // exit the loop.
                            $complete_stmt = true;
                            // make sure the outer loop continues at the right point.
                            $i = $j;
                        } else {
                            // even number of unescaped quotes. We still don't have a complete statement.
                            // (1 odd and 1 even always make an odd)
                            $temp .= $tokens[$j] . $delimiter;
                            // save memory.
                            $tokens[$j] = "";
                        }
                    } // for..
                } // else
            }
        }
        return $output;
    }

    //check folder permissions
    public static function fileWritable($file, $userDisplaypath = 'N.A') 
		{
			if(is_writable('../' .$file))
				{
				$file_status['status'] = true;
				$file_status['message'] = " * '" . $userDisplaypath . "' is writable";
				}
			else
			{
			$file_status['status'] = false;
            $file_status['message'] = " * Change the permission of '" . $userDisplaypath . "' to 777 <br/>";
        	}
			
		return $file_status;
		
   /*     $permission = substr(sprintf('%o', fileperms( '../' . $file )), -4);
        if ($permission == '0777' || $permission == '0666') {
            $file_status['status'] = true;
            $file_status['message'] = " * '" . $userDisplaypath . "' is writable";
        } else {
            $file_status['status'] = false;
            $file_status['message'] = " * Change the permission of '" . $userDisplaypath . "' to 777 <br/>";
        }

        return $file_status;
	*/
    }

    //check server requirements
    public static function checkServerConfiguration() {
        $server_flag = true;

        $val1 = ini_get("safe_mode");
        $val3 = ini_get("file_uploads");


        $gd = function_exists('gd_info');
        $curl = function_exists('curl_init');
        $mysql = function_exists('mysql_connect');
        $val_mb  = function_exists('mb_strlen');

        if (!empty($val1) || $val1 == 1) {
            $server_flag = false;
        } elseif (empty($val3) || $val3 != 1) {
            $server_flag = false;
        } elseif (!$gd) {
            $server_flag = false;
        } elseif (!$curl) {
            $server_flag = false;
        } elseif (!$mysql) {
            $server_flag = false;
        } elseif (!$val_mb) {
            $server_flag = false;
        }

        $mysqlsupport = true;
        if (!function_exists('mysql_connect')) {
            $mysqlsupport = false;
        }

        if (!$server_flag) {
            $serverconfiguration = "FAILURE";
        } else {
            $serverconfiguration = "OK";
        }

        return $serverconfiguration;
    }

    //check the server OS
    public static function getServerOS() {
        $sapi_type = php_sapi_name();
        $server['chmodstatus'] = '000';
        if (substr($sapi_type, 0, 3) == 'cgi') {
            $server['chmodstatus'] = '755';
            $server['write'] = 'WRITABLE';
        } else {
            if (substr(@php_uname(s), 0, 7) == "Windows") {
                $server['chmodstatus'] = '000';
            } else {
                $server['chmodstatus'] = '777';
            }
            $server['write'] = 'UNWRITABLE';
        }

        return $server;
    }

    //current configuration of the server
    public static function getServerSettings() {
        $requirements[0]['feature'] = "Checking PHP Version...";
        if (version_compare(PHP_VERSION, "4.2.0") >= 0) {
            $requirements[0]['setting'] = "OK";
            $requirements[0]['flag'] = true;
        } 
        else{
            $requirements[0]['setting'] = "4.2.0 or higher required";
            $requirements[0]['flag'] = false;
        }
        
        $requirements[1]['feature'] = "Checking System Information...";
        $requirements[1]['setting'] = PHP_OS;
        $requirements[1]['flag'] = true;
        
        $requirements[2]['feature'] = "Checking PHP Server API...";
        $requirements[2]['setting'] = php_sapi_name();
        $requirements[2]['flag'] = true;
        
        $requirements[3]['feature'] = "Checking Path to 'php.ini'...";
        $requirements[3]['setting'] = PHP_CONFIG_FILE_PATH;
        $requirements[3]['flag'] = true;
        
        $requirements[4]['feature'] = "Checking Mysql support...";
        if (function_exists('mysql_connect') >= 0) {
            $requirements[4]['setting'] = "OK";
            $requirements[4]['flag'] = true;
        } 
        else{
            $requirements[4]['setting'] = "This program requires MYSQL support. Please recompile your PHP with MYSQL Support";
            $requirements[4]['flag'] = false;
        }
        
        $requirements[5]['feature'] = "Checking safe_mode...";
        $val1 = ini_get("safe_mode");
        if ((!empty($val1) || $val1 == 1)) {
            $requirements[5]['setting'] = "ON-Please turn off safe_mode in the php.ini";
            $requirements[5]['flag'] = false;
        } 
        else{
            $requirements[5]['setting'] = "OFF";
            $requirements[5]['flag'] = true;
        }
        
        $requirements[6]['feature'] = "Checking file_uploads...";
        $val2 = ini_get("file_uploads");
        if ((!empty($val2) || $val2 == 1)) {
            $requirements[6]['setting'] = "OK";
            $requirements[6]['flag'] = true;
        } 
        else{
            $requirements[6]['setting'] = "OFF - Please turn on file_uplaods in the php.ini file";
            $requirements[6]['flag'] = false;
        }
        
        $requirements[7]['feature'] = "Checking CURL support...";
        $val3 = function_exists('curl_init');
        if ($val3) {
            $requirements[7]['setting'] = "OK";
            $requirements[7]['flag'] = true;
        } 
        else{
            $requirements[7]['setting'] = "OFF - Please re-compile php with CURL support";
            $requirements[7]['flag'] = false;
        }
        
        $requirements[8]['feature'] = "Checking GD support...";
        $val4 = function_exists('gd_info');
        if ($val4) {
            $requirements[8]['setting'] = "OK";
            $requirements[8]['flag'] = true;
        } 
        else{
            $requirements[8]['setting'] = "OFF - Please re-compile php with GD support";
            $requirements[8]['flag'] = false;
        }

        $requirements[9]['feature'] = "Checking  mbstring support...";
        $val5 = function_exists('mb_strlen');
        if ($val5) {
            $requirements[9]['setting'] = "OK";
            $requirements[9]['flag'] = true;
        }
        else{
            $requirements[9]['setting'] = "OFF - Please re-compile php with mbstring support";
            $requirements[9]['flag'] = false;
        }
        
        return $requirements;
    }

}

// End Class
?>