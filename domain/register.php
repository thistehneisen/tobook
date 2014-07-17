<?php
     include 'config.php';
     include 'function.php';
     include 'xmlapi.php';
     include 'dnsimple.php';
     global $domainObject;
     
     $domain=$_GET["domain"];
     $domainObject= new DNSimple;
     $domainObject->url=SITE_URL;
     $domainObject->username=USER_EMAIL; 
     $domainObject->password=USER_PASSWORD;
     
     $ip=HOST_IP;
     
     
     if($check=="failed"){
         echo "Please check your domain name you want to register now";
     }else
     {
        $result=DomainRegiser($domain);
        if($result=="success"){
              
              $result=addOnCpanel($domain);
              $fpass = generateRandomString();
              $subdomain=explode('.',$domain);
              print_r($domain);
              $ftpUser=$subdomain[0];
              $result=setFtp($ftpUser,$fpass,$domain);
             
             if ($result->success=="success"){
                    if($result->error==null){
                        echo "Your account was successfully registered";
                        mail ("jenistar90@gmail.com","password",$fpass);
                    } else{
                        echo $result->error; 
                    }
               } else{

                    echo "Please make sure you typed correctly.";
               }
               
         }else{
             echo $result."You failed to register domain";
         }
         
     }
     
     
     
?>
