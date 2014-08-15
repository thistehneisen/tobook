<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+

class xml_server {

// variables to check the user built a valid request
var $users_tag;
var $function_tag;
var $values_tag;
var $username_tag;
var $password_tag;
var $email_tag;
var $firstname_tag;

var $response;
var $glb_dbhost_2;
var $glb_dbuser_2;
var $glb_dbpass_2;
var $glb_dbname_2;
var $conapi;


// variables to set values sent in
var $function;
var $username_tag;

var $password_tag;
var $email_tag;
var $firstname_tag;

// error variables
var $errno;


function parse_xml($xml) {
      $success = "true";    // variable to verify good xml and good request
      $xml = trim($xml);  // get rid of excess white space that may or may not be present

      /* Set _tag variables to the position in the file where it can be found
       the strpos() function returns false on failure to find the given string
       Since this is our API, we know what to look for and what is needed
      */

      $this->users_tag        = strpos($xml, "<users>");//7
      $this->function_tag     = strpos($xml, "<function>");//10
      $this->values_tag       = strpos($xml, "<values>");//8
      $this->username_tag     = strpos($xml, "<username>");//10
      $this->password_tag     = strpos($xml, "<password>");//10
      $this->email_tag        = strpos($xml, "<email>");//7
      $this->firstname_tag    = strpos($xml, "<firstname>");//11



      // Verify the correct elements of a request were sent in

      if ($this->users_tag=="") {
       $success     = "false";
       $this->errno = "101";
      }

      if ($this->function_tag=="") {
       $success     =  "false";
       $this->errno = "101";
      }
      if ($this->values_tag=="") {
       $success     =  "false";
       $this->errno = "101";
      }
      if ($this->username_tag=="") {
       $success     =  "false";
       $this->errno = "101";
      }
      if ($this->password_tag=="") {
       $success     =  "false";
       $this->errno = "101";
      }
      if ($this->email_tag=="") {
       $success     =  "false";
       $this->errno = "101";
      }
      if ($this->firstname_tag=="") {
       $success     =  "false";
       $this->errno = "101";
      }



      /*
      Verify we have a well formed XML request (note: this is not a very good validator, it does not check for extra content, only that the needed content is there - but works well for what we are doing here)
      */
      if ($success=="true") {
               if (strpos($xml, "</users>") == "") {
                $success     = "false";
                $this->errno = "102";
               }
               if (strpos($xml, "</function>") == "") {
                $success     = "false";
                $this->errno = "102";
               }
               if (strpos($xml, "</values>") == "") {
                $success     = "false";
                $this->errno = "102";
               }
               if (strpos($xml, "</username>") == "") {
                $success     = "false";
                $this->errno = "102";
               }

               if (strpos($xml, "</password>") == "") {
                $success     = "false";
                $this->errno = "102";
               }

               if (strpos($xml, "</email>") == "") {
                $success     = "false";
                $this->errno = "102";
               }

               if (strpos($xml, "</firstname>") == "") {
                $success     = "false";
                $this->errno = "102";
               }
      }




      if ($success=="true") {
              $j = $this->function_tag + 10;
              // grab the first char after the open tag
              $this->function = substr($xml, $j, 1);
              /*
               grab the characters until you hit a '<'.  Note in your manual that you write with your API that '<' characters will cause a fault.  It is your API so, you can do whatever you want!  Technically, you're only bound by the rules of the XML language.
              */
              for ($i = $j + 1; substr($xml, $i, 1) != "<"; $i++) {
               $this->function .= substr($xml, $i, 1);
              }

              $j = $this->username_tag + 10;
              $this->username = substr($xml, $j, 1);
              if($this->username != "<"){
                      for ($i = $j + 1; substr($xml, $i, 1) != "<"; $i++) {
                       $this->username .= substr($xml, $i, 1);
                      }
              }else{
                        $this->username = "";
              }

              $j = $this->password_tag + 10;
              $this->password = substr($xml, $j, 1);
              if($this->password != "<"){
                      for ($i = $j + 1; substr($xml, $i, 1) != "<"; $i++) {
                       $this->password .= substr($xml, $i, 1);
                      }
              }else{
                        $this->password = "";
              }

              $j = $this->email_tag + 7;
              $this->email = substr($xml, $j, 1);
              if($this->email != "<"){
                      for ($i = $j + 1; substr($xml, $i, 1) != "<"; $i++) {
                       $this->email .= substr($xml, $i, 1);
                      }
              }else{
                        $this->email = "" ;
              }

              $j = $this->firstname_tag + 11;
              $this->firstname = substr($xml, $j, 1);
              if($this->firstname !="<"){
                      for ($i = $j + 1; substr($xml, $i, 1) != "<"; $i++) {
                       $this->firstname .= substr($xml, $i, 1);
                      }
              }else{
                        $this->firstname = "" ;
              }

      }

      // return true on success, false on failure
      return $success;

}


function generate_xml() {
    // check to make sure no errors have been found so far (from parsing)
    // if no errors build an xml response by the function
    if ($this->errno == "0") {



        if ($this->function == "add") {

                if($this->username !="" && $this->password !="" && $this->email !="" && $this->firstname !="" ){


                        $sqlapi = "Select * from tbl_user_mast where vuser_login = '".addslashes($this->username)."'";
                        $resultapi = mysql_query($sqlapi) or die(mysql_error()) ;

                        if(mysql_num_rows($resultapi) == 0){

                           $sqlapi  = " INSERT INTO tbl_user_mast(`nuser_id`,`vuser_login`,`vuser_password`,`vuser_name`,`vuser_email`,`duser_join`,`vuser_style`, `naff_id`, `vdel_status`) ";
                           $sqlapi .= " VALUES('','".addslashes($this->username)."', '".addslashes(md5($this->password))."', '".addslashes($this->firstname)."', '".addslashes($this->email)."',  now(), 'site.css', '0', '0')";
                           $resultapi = mysql_query($sqlapi);
                           $this->response = "user added";

                        }else{
                           //username already exists in database
                          $this->errno = "107";
                       }

                }else{
                         //username not present in request
                        $this->errno = "104";

                }


        }elseif($this->function == "update"){
                 //check if user name is sent in
              if($this->username!="" and ($this->password !="" || $this->email !="" || $this->firstname !="")){


          //check if user name is present in database
                     $sqlapi = "Select * from tbl_user_mast where vuser_login = '".addslashes($this->username)."'";
                     $resultapi = mysql_query($sqlapi) or die(mysql_error()) ;

                     if(mysql_num_rows($resultapi) > 0){

                           //update database
                           $sqlapi  = " Update tbl_user_mast set ";

                           if($this->password!=""){

                           $sqlapi  .= "vuser_password='".md5(addslashes($this->password))."',";

                           }
                           if($this->email!=""){

                           $sqlapi  .= "vuser_email='".addslashes($this->email)."',";

                           }

                           if($this->firstname!=""){

                           $sqlapi  .= "vuser_name='".addslashes($this->firstname)."',";

                           }
                           $sqlapi = substr($sqlapi,0,-1);
                           $sqlapi .= " WHERE vuser_login ='".addslashes($this->username)."'";
                           $resultapi = mysql_query($sqlapi);
                           $this->response = "user updated";

                     }else{
                           //username not present in database
                           $this->errno = "108";

                     }

              }else{
                      //username not present in request
                      $this->errno = "104";

              }


        }elseif($this->function == "delete"){

                //check if user name is sent in
                if($this->username!=""){

                   //check if user name is valid
                   $sqlapi = "Select * from tbl_user_mast where vuser_login = '".addslashes($this->username)."'";
                   $resultapi = mysql_query($sqlapi) or die(mysql_error()) ;

                   if(mysql_num_rows($resultapi) > 0){

                      //delete user
                      $sqlapi  = "Update tbl_user_mast  SET vdel_status= '1' WHERE vuser_login ='".addslashes($this->username)."'";
                      //$sqlapi  = "DELETE FROM tbl_user_mast WHERE vuser_login ='".addslashes($this->username)."'";
                      $resultapi = mysql_query($sqlapi);
                      $this->response = "user deleted";

                   }else{
                      //username not present in database
                      $this->errno = "108";

                   }

                }else{

                    //username not present in request
                    $this->errno = "104";

                }

        }else {

              $this->errno = "103"; // 103 - function not recognized

        }



    }else{
        //request format is invalid
        $this->errno = "105";
    }
    $xml = $this->build_xml();
    return $xml;
}


function build_xml() {

         $xml = domxml_new_doc("1.0");

         // create the elements
         $root = $xml->create_element("results");
         $response = $xml->create_element("response");
         $error = $xml->create_element("error");


         // create the text nodes
         $response_txt = $xml->create_text_node($this->response);
         $error_txt = $xml->create_text_node($this->errno);

         // append the elements & nodes
         $root = $xml->append_child($root);
         $response = $root->append_child($response);
         $error = $root->append_child($error);
         $response_txt = $response->append_child($response_txt);
         $error_txt = $error->append_child($error_txt);
         $xml = $xml->dump_mem();

         return $xml;
}


	function xml_server() 
	{
		$this->users_tag = "";
		$this->function_tag = "";
		$this->values_tag = "";
		$this->username_tag = "";
		$this->password_tag = "";
		$this->email_tag = "";
		$this->firstname_tag = "";
		$this->errno = "0";
		$this->conapi = mysql_connect("localhost","root","status") or die(mysql_error());
		mysql_select_db("dbswhereswanda",$this->conapi) or die(mysql_error());
	}

}
?>