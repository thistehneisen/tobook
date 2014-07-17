<?php

     include 'config.php';
     include 'function.php';
     include 'xmlapi.php';
     include 'dnsimple.php';
     $domain=$_GET["domain"];
     $domainObject= new DNSimple;
     $domainObject->url=SITE_URL;
     $domainObject->username=USER_EMAIL; 
     $domainObject->password=USER_PASSWORD;
     $ip=HOST_IP;
     
     $check=isValidDomain($domain);
     
     if($check=="success"){
         echo "The domain is not available now";
     }else
     {
        
       $result=createFTPAccount($domain);
        if ($result->result=="success"){
            if($result->error==null){
                echo "Your account was successfully registered";
                print_r($result);
                
            } else{
                echo $result->error; 
                }
            } else{
                echo "Please make sure you typed correctly.";
            }
               
         }
         
?>