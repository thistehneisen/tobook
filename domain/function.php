<?php   
error_reporting(E_ERROR);

$domainObject = new DNSimple;
$domainObject->url = SITE_URL;
$domainObject->username = USER_EMAIL;
$domainObject->password = USER_PASSWORD;
$ip = HOST_IP;

function createFTPAccount($domain) {
    $ftpPassword = generateRandomString();
    $ftpUser = $domain;
    $ftpUser = preg_replace("/[^A-Za-z0-9 ]/", "", $ftpUser);
    addOnCpanel($domain);
    $cpaneluser = HOST_USER;
    $cpanelpass = HOST_USER_PASSWORD;
    $fhomedir = "public_html/".$domain;
    
    $url = "http://$cpaneluser:$cpanelpass@$domain:2082/json-api/cpanel?";
    $url .= "cpanel_jsonapi_version=2&cpanel_jsonapi_module=Ftp&cpanel_jsonapi_func=addftp&";
    $url .= "user=$ftpUser&pass=$ftpPassword&homedir=$fhomedir&quota=0";
     
    $result = @file_get_contents($url);
    if ($result === FALSE) {
        $success="failed";
    }else{
        $success="success";
    }
    $data->result = $success;
    $resultArray = json_decode($result);
    $data->error = $resultArray->cpanelresult->error;
    $data->ftp_server = FTP_SERVER;
    $data->ftp_pass = $ftpPassword;
    $data->ftp_user = $ftpUser."@".MAIN_DOMAIN;
    return $data;
}

function registerDomain($domain) {
    global $domainObject;
    try {
        $result = $domainObject->domains_register($domain,CONTACT_ID);

    } catch (Exception $e) {
        $result="failed" ;
    }
    return $result;
}

function setDomainIp($domain,$ip) {
    global $domainObject;
    $newRecord = array(
                    'name'=> '' ,
                    'record_type'=> 'A' ,
                    'content'=> HOST_IP ,
                    'ttl'=> '3600' ,
                    'prio'=> '10'
                     
    );
    try {
        $result = $domainObject->dns_create($domain,$newRecord);
        $result = "success";
    }
    catch (Exception $e) {
        $result="failed" ;
    }
    return $result;
}
 
function chnageNameServer($domain) {
    global $domainObject;
    $base = DNSIMPLE_URL."domains/".$domain."/name_servers";
    $header1 = 'X-DNSimple-Token:'.USER_EMAIL.':'.USER_API_KEY;

    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$base);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        $header1,
        'Accept: application/json' ,
        'Content-Type: application/json',
        )
    );

    $postData = array(
                    name_servers => array(
                                    'ns1'=>'ns1.varaaravintola.com',
                                    'ns2'=>'ns2.varaaravintola.com'
                    ));
    $postData1 = json_encode($postData);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData1);
    curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
    $result = curl_exec($curl);
    $result = "success";
    return result;
}
function createDomain($domain) {
    $result = registerDomain($domain);

    if ($result == null) {
        $result = setDomainIp($domain,$ip);
        if ($result == "success") {
            $result = chnageNameServer($domain);
            $result = "success";
        } else {
            $result="failed";
        }
    } else {
        $result = $result["message"];
    }
    return $result;
}

function addOnCpanel($domain) {
    $ip = HOST_IP;
    $root_pass = HOST_USER_PASSWORD;
    $port = "2082";
    $account = HOST_USER;
    $xmlapi = new xmlapi($ip);
    $xmlapi->password_auth($account,$root_pass);
    $xmlapi->set_port($port);
    $xmlapi->set_output('xml');
    $xmlapi->set_debug(1);
    $subdomain = explode('.',$domain);
    $subdomain = $subdomain[0];
    $result = ($xmlapi->api2_query($account, "AddonDomain", "addaddondomain",
                    array('newdomain'=>$domain,
                          'dir'=>'public_html/'.$domain,
                          'subdomain'=>$subdomain,
                          'pass'=>'%CVUSMt[JyuQ') )
              );
    return $result="success";
}

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function isValidDomain($domain) {
    $URL = "http://".$domain."/";
    $PARSED_URL = parse_url($URL);
    $DOMAIN = $PARSED_URL['host'];
    $ipCheck = gethostbyname($DOMAIN);
    if ($ipCheck === $DOMAIN) {
        return "success";
    } else {
        return "failed";
    }
}
?>