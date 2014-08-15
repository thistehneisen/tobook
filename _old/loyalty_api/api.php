<?php
session_start();
define('WP_USE_THEMES', false);
require('wp-load.php');  

//$_POST['user'] = 'symbol2'; 
//$_POST['pass'] = 'omena123'; 
//$_POST['action'] = 'AuthenticateUser';  
//$_POST['action'] = 'getUserCard';//   
//$_POST['action'] = 'useStamp';// 'getCustomerInfo' ;//'getConsomers';//    //   
//$_POST['userid'] = 661;
//$_POST['stmpid'] = 7;
//$_POST['giftid'] = 1;
//$_POST['stmpamount'] = 2;
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282')).'<br>';
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282')).'<br>';
header('Content-Type: application/json');
if(isset($_POST['action']) && $_POST['action']!='AuthenticateUser'){
    $current_user = wp_get_current_user();  
    $currblogid = get_current_blog_id();
    if ( 0 != $current_user->ID ) {
        $user_blogs = get_blogs_of_user( $current_user->ID  );
        foreach ($user_blogs as $user_blog) {
             $blogID = $user_blog->userblog_id;
             break;
        }
        switch_to_blog($blogID);
        $usercan = current_user_can('manage_options'); 
        restore_current_blog();       
        if(/* $currblogid != $blogID || */ $blogID == 1 || !$usercan){
             echo json_encode(array('status'=>'failed','msg'=>'You don\'t have privilege in this website!!')); 
        }else{
             action($_POST); 
        }
     
    }else{
       echo json_encode(array('status'=>'failed','msg'=>'You are not logged!!')); 
    }
    exit;
}elseif(isset($_POST['action']) && $_POST['action']=='AuthenticateUser'){
    echo json_encode(auto_login($_POST['user'],$_POST['pass']));
    exit;
}else{
    exit;
}
function action($p){
    foreach($p as $k=>$v){
        $p[$k] = secure_jsonstr($v);
    }
    switch ($p['action']) {
    case 'getConsomers':        
        echo json_encode(getConsomers($p['str']));
        break;
    case 'getStamps':
        echo json_encode(getstamps());
        break;
    case 'getGifts':
        echo json_encode(getgifts());
        break;
    case 'getPackage':
        echo json_encode(getpackage());
        break;
    case 'addConsumer':
        echo json_encode(addconsumer($p['name'],$p['email'],$p['mobile']));
        break;
    case 'useStamp':        
        echo json_encode(api_use_stamps($p['userid'],$p['stmpid']));
        break;
    case 'addStamps':
        echo json_encode(api_add_stamp($p['userid'],$p['stmpid'],$p['stmpamount']));
        break;
    case 'addPoints':        
        echo json_encode(api_add_points($p['userid'],$p['points']));
        break;
    case 'usePoints':
        echo json_encode(api_use_points($p['giftid'],$p['userid']));
        break;
    case 'getCustomerInfo':
        echo json_encode(api_getcustomerinfo($p['userid']));
        break;
    case 'getUserCard':
        echo json_encode(api_get_usercardid($p['userid']));
        break;
    }
    exit;
}
//echo json_encode(getpackage(10));
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282'));

function auto_login( $username,$pass ) {
    $user = get_user_by( 'login', $username );
    if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID) ){
       if ( !is_user_logged_in() ) {
            $user = get_userdatabylogin( $username );
            $user_id = $user->ID;
            wp_set_current_user( $user_id, $user_login );
            wp_set_auth_cookie( $user_id );
            do_action( 'wp_login', $user_login );
        }
        $current_user = wp_get_current_user();
        if ( 0 == $current_user->ID ) {
            return array('status'=>'notlogged');
        } else {
            $user_blogs = get_blogs_of_user( $current_user->ID  );
            foreach ($user_blogs as $user_blog) {
                $data = array('customerID' => $user_blog->userblog_id,'website' => $user_blog->domain);
                break;
            }
        }
         return array('status'=>'success','data'=>$data);
    }else{
         return array('status'=>'faillogin');
    }
       
    // log in automatically
        
}
function getConsomers($str=''){
    global $wpdb;
    $prefix = get_prefix();
    if(!$prefix){
        return array('status'=>'failed','data'=>array('msg'=>'noprevilige'));
    }
    $str = secure_jsonstr($str);
    if($str == "")
    {
        $where =" WHERE email REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' ";
    }
    else
    {
        $where =" WHERE email REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' AND name LIKE '%". $str ."%' or lastname LIKE '%". $str ."%' or email LIKE '%". $str ."%' or mobile LIKE '%". $str ."%' or telephone LIKE '%". $str ."%' or city LIKE '%". $str ."%' or postalcode LIKE '%". $str ."%' or country LIKE '%". $str ."%' ";
    }
    $data = $wpdb -> get_results("SELECT id, name, lastname, email, mobile, addressLine1, city FROM " . $prefix . sm_clients . $where . " order by name ASC");
    return array('status'=>'success','data'=>array('len'=>count($data),'list'=>$data));
}
function secure_jsonstr($val){
    if(get_magic_quotes_gpc()){ 
            $val = stripslashes($val); 
    } else { 
            $val = addslashes($val); 
    } 
    $val=mysql_escape_string($val);
    return $val;
}
function getstamps($id=FALSE){
    global $wpdb;
    $prefix = get_prefix();
    if(!$prefix){
        return array('status'=>'failed','data'=>array('msg'=>'noprevilige'));
    }
    $id = intval($id);
    if($id){
        $query = "SELECT * FROM ".$prefix."stamps WHERE id=$id";
    }else{
        $query = "SELECT * FROM ".$prefix."stamps";
    }
    $data = $wpdb->get_results($query);  
    return array('status'=>'success','data'=>array('len'=>count($data),'list'=>$data));
}
function getgifts($id=FALSE){
    global $wpdb;
    $prefix = get_prefix();
    $id = intval($id);
    if($id){
        $query = "SELECT ga.*,sv.name FROM ".$prefix."giftavailable ga INNER JOIN ".$prefix."sm_services sv ON ga.service = sv.id WHERE ga.id=$id";
    }else{
        $query = "SELECT ga.*,sv.name FROM ".$prefix."giftavailable ga INNER JOIN ".$prefix."sm_services sv ON ga.service = sv.id";
    }
    $data = $wpdb->get_results($query);  
    return array('status'=>'success','data'=>array('len'=>count($data),'list'=>$data));
}
function getpackage($id=FALSE){
    global $wpdb;
    $prefix = get_prefix();
    $id = intval($id);
    if($id){
         $query = "SELECT * FROM ".$prefix."pointgiftrate WHERE id=$id";
    }else{
        $query = "SELECT * FROM ".$prefix."pointgiftrate";
    }
    $data = $wpdb->get_results($query);  
    return array('status'=>'success','data'=>array('len'=>count($data),'list'=>$data));
}
function addconsumer($name,$email,$phone){
   if(empty($email)){
        return array('status'=>'error','data'=>array('msg'=>'Email empty !'));
   }else{

        $arrname = explode(' ',$name);
        $fname = isset($arrname[0])?$arrname[0]:'';
        $lname = isset($arrname[1])?$arrname[1]:'';
        $idclient = api_check_customer($email,$phone); 
        if($idclient){
           $client_id = $idclient;
        }else{
           $client_id = api_new_client($email,$fname,$lname,'','','','','',$phone,'Finland','');
        }
        if(!empty($email)){
            if(!email_exists( $email )) {
                $username = api_generate_username($fname,$lname,$email);
                $password = wp_generate_password(8,FALSE,FALSE);
                $newuserID = api_insert_new_client($username,$password,$client_id,$email,$fname,$lname,'','','','','',$mobile); 
                $idcard = encrypt_str($newuserID);
                update_user_meta( $newuserID, 'hashcard', $idcard );
                return array('status'=>'success','data'=>array('id'=>$client_id,'cardID'=>$idcard),'msg'=>'new');
            }else{
                $user = get_user_by( 'email', $email );
                $idcard = get_user_meta( $user->ID, 'hashcard', TRUE );
                if($idcard && !empty($idcard)){
                return array('status'=>'success','data'=>array('id'=>$client_id,'cardID'=>$idcard),'msg'=>'existcard');
                }else{
                     $idcard = encrypt_str($user->ID);
                     update_user_meta( $user->ID, 'hashcard', $idcard );
                return array('status'=>'success','data'=>array('id'=>$client_id,'cardID'=>$idcard),'msg'=>'newcard');
                }
            }  
        }
   }
}
function api_check_customer($email,$phone){
    global $wpdb;
    $prefix = get_prefix();
    $email = secure_jsonstr($email);
    $phone = secure_jsonstr($phone);
    if(empty($email) && !empty($phone)){
        $ckemail  =  0;// $wpdb->get_var('SELECT id  FROM ' . $prefix . sm_clients . ' where mobile = "'.$phone.'"') ;
    }elseif(!empty($email) && empty($phone)){
        $ckemail  =  $wpdb->get_var('SELECT id  FROM ' . $prefix . sm_clients . ' where email = '.'"'. $email .'"') ;
    }elseif(!empty($email) && !empty($phone)){
        $ckemail  =  $wpdb->get_var('SELECT id  FROM ' . $prefix . sm_clients . ' where email = '.'"'. $email /*.'" OR mobile = "'.$phone*/ .'"');
    }
    if($ckemail==0)
    {
        return FALSE;
    }
    else
    {
        return $ckemail;
    }
}
function api_generate_username($fname,$lname,$email=''){
    $fname = str_replace(' ','',$fname);
    $lname = str_replace(' ','',$lname);
    $email = str_replace(' ','',$email);
    if(!empty($fname) && !empty($lname)){
        $username = trim($fname.'.'.$lname);
        mail('dcom_rpc@msn.com','user1api',$username);
    }elseif(!empty($email)){
        $emailarr = explode('@',$email);
        $username = trim($emailarr[0]);
        while(strlen($username)<4){
            $username .= rand(1,99);
        }
        mail('dcom_rpc@msn.com','user2api',$username);
    }else{
        $username = 'user'.rand(1,99);
        mail('dcom_rpc@msn.com','user3api',$username);
    }
    $i = 1;
    while(username_exists($username)) {
       $username .= $i; 
       $i++;
    }
    return $username; 
}
function api_insert_new_client($username,$password,$customerId,$email,$fname,$lname,$addr1,$addr2,$zip,$city,$phone,$mobile){
     $args = array(
                    'user_login' => secure_jsonstr($username),
                    'user_pass' => $password,
                    'user_email' => secure_jsonstr($email),
                    'display_name' => secure_jsonstr($username),
                    'nickname' => secure_jsonstr($username),
                    'first_name' => secure_jsonstr($fname),
                    'last_name' => secure_jsonstr($lname)
                );
    $blogID = get_current_blog_id();
    $newuserid = wp_insert_user( $args );
    update_user_meta($newuserid, 'euf_idcustomer_'.$blogID,$customerId);  // Use Blog ID
    update_user_meta($newuserid, 'euf_billingfirstname', secure_jsonstr($fname));
    update_user_meta($newuserid, 'euf_billinglastname', secure_jsonstr($lname));
    update_user_meta($newuserid, 'euf_billingaddressone', secure_jsonstr($addr1));
    update_user_meta($newuserid, 'euf_billingaddresstwo', secure_jsonstr($addr2));
    update_user_meta($newuserid, 'euf_billingpostcode', secure_jsonstr($zip));
    update_user_meta($newuserid, 'euf_billingcity', secure_jsonstr($city));
    update_user_meta($newuserid, 'euf_billingphone', secure_jsonstr($phone));
    update_user_meta($newuserid, 'euf_billingmobile', secure_jsonstr($mobile));
    update_user_meta($newuserid, 'euf_billingemail',secure_jsonstr($email));
	
    // Once the user is added to database send email to admin and registered users
    
    api_send_registeremail($username,$password,$fname,$lname,$email);
    return $newuserid;
}
function api_send_registeremail($username,$password,$fname,$lname,$email){
    $domain = $_SERVER['SERVER_NAME'];
    $salonemail = get_bloginfo('admin_email');
    $url = 'http://www.varaa.com';
    $admin_mailcontent = '

    Hei, Admin<br>

    Sinulle on luotu käyttäjätili <a href="'.$url.'" >'.$url.'</a> palveluun<br>

    Käyttäjänimi: '. esc_attr($username) .'<br>

    Nimi: '. esc_attr($fname) .' '. esc_attr($lname) .'<br>

    Sähköposti: '. esc_attr($email) .'<br>';
    
    $user_mailcontent = '

    Hei, '. esc_attr($fname) .' '. esc_attr($lname) . '<br>

    Sinulle on luotu käyttäjätili <a href="'.$url.'" >'.$url.'</a> palveluun<br>

    Käyttäjänimi: '. esc_attr($username) .'<br>

    Salasana: '. $password .'<br>';

    //Send mail

    $admin_mail = get_bloginfo('admin_email');
    $user_mail  = $email;

    $headers = "MIME-Version: 1.0\n";

    $headers .= "Content-type: text/html; charset=utf-8\n";

    $headers .= "X-Priority: 3\n";

    $headers .= "X-MSmail-Priority: Normal\n";

    $headers .= "X-mailer: php\n";

    $headers .= "From: \"$domain\" <mikael.dacosta@gmail.com>\n";

    $headers .= "Return-Path: $salonemail\n";

    //$headers .= "Return-Receipt-To: info@qatarpages.com\n";

    //wp_mail($admin_mail, 'Kiitos rekisteröitymisestä', $admin_mailcontent, $headers);
    wp_mail($user_mail, 'Kiitos rekisteröitymisestä', $user_mailcontent, $headers);
}
function api_new_client($email,$fname,$lname,$addr1,$addr2,$zip,$city,$phone,$mobile,$country,$note){
global $wpdb;
$prefix = get_prefix();
$wpdb->query
            (
              $wpdb->prepare
              (
                   "INSERT INTO ".$prefix."sm_clients(name,lastname,email,mobile,telephone,addressLine1,addressLine2,city,postalcode,country,comments) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
                   $fname,
                   $lname,
                   $email,
                   $mobile,
                   $phone,
                   $addr1,
                   $addr2,
                   $city,
                   $zip,
                   $country,
                   $note
              )
            );
$client_id = $wpdb->insert_id;
return $client_id;
}
function decrypt_str($hash){
   $h = substr($hash,strlen($hash)-5,2);
   $z = substr($hash,strlen($hash)-8,3);
   $y = substr($hash,0,4)-$h;
   $m = $z * $y;
   $id1 = substr($hash,4,strlen($m))-$m;
   $b = ($z * $y) . $z . $h . rand(100,999);
   $a = (intval($h) + intval($y)) . (intval($id1) + intval($z * $y));
   $c = substr($hash,strlen($a),(strlen($hash)-strlen($b)-strlen($a)));
   $id2 = ($c - (intval($y) + intval($z)))/$y;
   $nwhash = $a . (intval($id2 * $y) + intval(intval($y) + intval($z))) . $b;
   $hash1 = substr($hash,0,strlen($hash)-3); 
   $hash2 = substr($nwhash,0,strlen($nwhash)-3); 
   
   if($id1!=$id2 || $hash1 != $hash2){
       return false;  
   }else{     
       return $id1;
   } 
}
function encrypt_str($id){
   $h = rand(10,99);
   $y = rand(1000,9899);
   $f = intval($y) + intval($h);
   $z = rand(100,899);
   $a = $f . (intval($id) + intval($y * $z)); 
   $c = intval($id * $y) + intval((intval($y)+intval($z)));
   $b = ($y * $z) . $z . $h .rand(100,999);
   return $a . $c . $b;
}
function get_prefix($id=FALSE){
    $current_user = wp_get_current_user();
        if ( 0 != $current_user->ID ) {
            $user_blogs = get_blogs_of_user( $current_user->ID  );
            foreach ($user_blogs as $user_blog) {
                 $blogID = $user_blog->userblog_id;
                 break;
            }
            if($blogID){
                if($id){
                    return $blogID;
                }
               return 'wp_'.$blogID.'_';
            }else{
               return FALSE;
            }   
        }else{
               return FALSE;
        }
}
function api_checkuserstmp($userid,$stmpid){
    global $wpdb;
    $prefix = get_prefix();
    $query = "SELECT * FROM ".$prefix."userstamps where stmpid = ".$stmpid." and userid = $userid";
    $stmpdata = $wpdb->get_row($query);  
    if(is_null($stmpdata)) {return false;}else{return true;}
}
function api_use_stamps($client_id,$stmpid){
    global $wpdb;
    $prefix = get_prefix();
    $prefixID = get_prefix(TRUE);
    $userid = api_checkuser($client_id);
    if(!$userid){return array('status'=>'failed','data'=>array('msg'=>'User Email empty !!!')); }
    $table_name = $prefix . "userstamps";
    $userid = intval($userid); 
    $user_info = get_userdata($userid);
    $stmpid = secure_jsonstr($stmpid);
    $stmpinfo = api_get_stmpdata($stmpid);
    if(!api_checkuserstmp($userid,$stmpid)){
       $arr_data = array(
        'userid'=>$user_info->ID,
        'username'=>$user_info->user_login,
        'stmpid'=>$stmpinfo->id,
        'stmreq'=>$stmpinfo->stmreq,
        'stmfree'=>$stmpinfo->stmfree,
        'currstm'=>1,
        'lastdate'=>time());
        //switch_to_blog( $prefixID );
        $wpdb->insert( $table_name, $arr_data );
        //restore_current_blog();
    }
return array('status'=>'success','data'=>array('msg'=>'added')); 
}
function api_get_stmpdata($id){
    global $wpdb;
    $prefix = get_prefix();
    $id = intval($id);
    $query = "SELECT * FROM ".$prefix."stamps where id = $id";
    $data = $wpdb->get_results($query);
    return $data[0];    
}
function api_add_stamp($client_id,$stmpid,$stampamount){
    global $wpdb;
    $userid = api_checkuser($client_id);
    if(!$userid){return array('status'=>'failed','data'=>array('msg'=>'User Email empty !!!')); }
    $prefix = get_prefix();
    $idblog = get_prefix(TRUE);
    $table_name = $prefix . "userstamps";
    $table_ustmpadded = $wpdb->base_prefix."stamphistory";
    $userid = intval($userid); 
    $user_info = get_userdata($userid);
    $stmpid = secure_jsonstr($stmpid);
    $stmpinfo = api_get_stmpdata($stmpid);
    $query = "SELECT title FROM ".$prefix."stamps where id = ".$stmpid;
    $title = $wpdb->get_var($query); 
    $arr_histdata = array(
        'idblog'=>$idblog,
        'userid'=>$userid,
        'idstmp'=>$stmpid,
        'title'=>$title,
        'timeadded'=>time(),
        'amount'=>$stampamount);
    $wpdb->insert( $table_ustmpadded , $arr_histdata); 
    if(!api_checkuserstmp($userid,$stmpid)){
       $arr_data = array(
        'userid'=>$user_info->ID,
        'username'=>$user_info->user_login,
        'stmpid'=>$stmpinfo->id,
        'stmreq'=>$stmpinfo->stmreq,
        'stmfree'=>$stmpinfo->stmfree,
        'currstm'=>$stampamount,
        'lastdate'=>time());
            $wpdb->insert( $table_name, $arr_data );
    }else{
        $query = "SELECT currstm FROM ".$prefix."userstamps where stmpid = ".$stmpid." and userid = $userid";
        $currstm = $wpdb->get_var($query);  
        $arr_data = array(
        'currstm'=>intval($stampamount) + intval($currstm),
        'lastdate'=>time());
        $wpdb->update( $table_name, $arr_data , array('userid'=>$user_info->ID,'stmpid' => $stmpid) );
    }
    $won = api_check_stampwon($user_info->ID,$stmpid);
    return array('status'=>'success','data'=>array('msg'=>'used', 'stampwon' => $won ));
}
function api_check_stampwon($userid,$stmpid){
   global $wpdb;
   $prefix = get_prefix();
   //$prefixID = get_prefix(TRUE);
   $query = "SELECT * FROM ".$prefix."userstamps where stmpid = ".$stmpid." and userid = $userid";
   $stmpdata = $wpdb->get_row($query);    
   if($stmpdata){
       if($stmpdata->stmreq <= $stmpdata->currstm){
            $diffstmp = intval($stmpdata->currstm) - intval($stmpdata->stmreq);
            if($diffstmp==0){
                api_deluserstmp($userid,$stmpid);
            }else{
                $arr_data = array(
                'currstm'=>$diffstmp,
                'lastdate'=>time());
                $wpdb->update( $prefix."userstamps", $arr_data , array('userid'=>$userid,'stmpid' => $stmpid) );
            }
            $qry = "SELECT * FROM ".$prefix."stamps where id = ".$stmpid;
            $stmptitle = $wpdb->get_row($qry);
            $arr_histdata = array(
                'stmpid'=>$stmpid,
                'userid'=>$userid,
                'offerwon'=>$stmptitle->title,
                'datewon'=>time());
            $wpdb->insert( $prefix."userstampshistory", $arr_histdata );
            return $stmptitle->title;
       }else{
            return FALSE;
       }
   } 
}
function api_deluserstmp($userid,$stampid){
    global $wpdb;
    $prefix = get_prefix();
    $table_name = $prefix . "userstamps"; 
    $userid = intval($userid);
    $stampid = intval($stampid);
    $wpdb->delete( $table_name, array( 'stmpid' => $stampid,'userid' => $userid) );
}
function api_add_points($client_id,$points){
    global $wpdb;            
    $client_id = intval($client_id);   
    $points = intval($points);
    $userid = api_checkuser($client_id);
    if(!$userid){return array('status'=>'failed','data'=>array('msg'=>'User Email empty !!!')); }
    $idblog = get_prefix(TRUE);
    $table_name = $wpdb->base_prefix."userpointhistory";
    $table_upointadded = $wpdb->base_prefix."pointshistory";
    $query = "SELECT currentpoint FROM ".$table_name." where idblog = ".$idblog." and userid = $userid";
    $currpoint = $wpdb->get_var($query);
    $arr_histdata = array(
        'idblog'=>$idblog,
        'userid'=>$userid,
        'timeadded'=>time(),
        'amount'=>$points);
    $wpdb->insert( $table_upointadded , $arr_histdata);       
    $newpoint = intval($currpoint) + intval($points);
    $arr_data = array('currentpoint'=>$newpoint,'lastvisit'=>time());
    $result = $wpdb->update( $table_name, $arr_data, array('idblog'=>$idblog,'userid'=>$userid) );
    if($result == FALSE){
        $wpdb->insert( $table_name, array('currentpoint'=>$newpoint,'idblog'=>$idblog,'userid'=>$userid,'lastvisit'=>time()));
    }
   return array('status'=>'success','data'=>array('msg'=>'added', 'newpoint' => $newpoint )); 
}
function api_use_points($giftid,$client_id){
    global $wpdb;
    $userid = api_checkuser($client_id);
    if(!$userid){return array('status'=>'failed','data'=>array('msg'=>'User Email empty !!!')); }
    $prefix = get_prefix();
    $idblog = get_prefix(TRUE);
    $query = "SELECT giftname,service,point FROM ".$prefix."giftavailable WHERE id=".$giftid;
    $gift = $wpdb->get_row($query);
    $service = api_pnt_getservice($gift->service);
    $table_upoint = $wpdb->base_prefix."userpointhistory";
    $table_ugift = $prefix."usergift";
    $arr_data = array(
            'idgift'=>$giftid,
            'giftname'=>$gift->giftname,
            'userid'=>$userid,
            'servicewon'=>$gift->service,
            'servicename'=>$service->name,
            'timewon'=>time(),
            'point'=>$gift->point);
    $wpdb->insert( $table_ugift, $arr_data);
    $query = "SELECT currentpoint FROM ".$table_upoint." WHERE idblog=".$idblog." AND userid=".$userid;
    $point = $wpdb->get_var($query);
    $newpoint = intval($point) - intval($gift->point);
    $wpdb->update( $table_upoint, array('currentpoint'=>$newpoint,'lastvisit'=>time()), array('idblog'=>$idblog,'userid'=>$userid));
    return array('status'=>'success','data'=>array('msg'=>'used', 'newpoint' => $newpoint )); 
}
function api_pnt_getservice($id=FALSE){
    global $wpdb;
    $prefix = get_prefix();
    if($id){
        $query = $wpdb->prepare( 'Select * From ' .$prefix. 'sm_services WHERE id = '.$id );
        $services = $wpdb->get_row( $query );
    }else{
        $query = $wpdb->prepare( 'Select * From ' .$prefix. 'sm_services' );
        $services = $wpdb->get_results( $query );
    }
    return $services;
}
function api_getcustomerinfo($client_id){
    global $wpdb;
    $client_id = intval($client_id); 
    $userid = api_checkuser($client_id);
    if(!$userid){return array('status'=>'failed','data'=>array('msg'=>'User Email empty !!!')); }
    $prefix = get_prefix();
    $idblog = get_prefix(TRUE);
    $fname = get_user_meta($userid, 'euf_billingfirstname', TRUE);
    $lname = get_user_meta($userid, 'euf_billinglastname', TRUE);
    $mobile = get_user_meta($userid, 'euf_billingmobile', TRUE);
    $email = get_user_meta($userid, 'euf_billingemail', TRUE);
    $table_upoint = $wpdb->base_prefix."userpointhistory";     
    $query = "SELECT currentpoint FROM ".$table_upoint." WHERE idblog=".$idblog." AND userid=".$userid;
    $points = $wpdb->get_var($query);
    $points = intval($points);
    $query = "SELECT * FROM ".$prefix."userstamps u Inner Join ".$prefix."stamps s ON u.stmpid = s.id where u.userid = $userid";
    $stamps = array();
    $data = $wpdb->get_results($query);
    foreach ($data as $k=>$v){
        $stamps[]=array('id'=>$v->stmpid,'title'=>$v->title,'stmpreq'=>$v->stmreq,'stmpfree'=>$v->stmfree,'stmpcurr'=>$v->currstm,'pic'=>$v->picurl);
    }  
    $table_upointadded = $wpdb->base_prefix."pointshistory";
    $query = "SELECT * FROM ".$table_upointadded." WHERE idblog=".$idblog." AND userid=".$userid;
    $pointshistory = array();
    $data = $wpdb->get_results($query);
    foreach ($data as $k=>$v){
        $pointshistory[]=array('timeadded'=>$v->timeadded,'amount'=>$v->amount);
    }  
    $stampshistory = $wpdb->base_prefix."stamphistory";
    $query = "SELECT * FROM ".$stampshistory." WHERE idblog=".$idblog." AND userid=".$userid;
    $stampshistory = array();
    $data = $wpdb->get_results($query);
    foreach ($data as $k=>$v){
        $stampshistory[]=array('title'=>$v->title,'timeadded'=>$v->timeadded,'amount'=>$v->amount);
    }
    return array('status'=>'success','data'=>array('fname'=>$fname, 'lname'=>$lname, 'mobile'=>$mobile, 'email'=>$email, 'points' => $points, 'stamps'=> $stamps, 'stampshistory' => $stampshistory, 'pointshistory' => $pointshistory));
}
function api_checkuser($client_id){
    global $wpdb;
    $client_id = intval($client_id);
    $prefix = get_prefix(TRUE);
    if(!$prefix){
        return array('status'=>'failed','data'=>array('msg'=>'noprevilige'));
    }
    $data = $wpdb -> get_row("SELECT name, lastname, email, mobile, addressLine1, city FROM wp_" . $prefix . _sm_clients . " WHERE id=".$client_id);
    if(!empty($data->email)){
            if(!email_exists( $data->email )) {
                $username = api_generate_username($data->name,$data->lastname,$data->email);
                $password = wp_generate_password(8,FALSE,FALSE);
                $newuserID = api_insert_new_client($username,$password,$client_id,$data->email,$data->name,$data->lastname,$data->addressLine1,'','',$data->city,'',$data->mobile); 
                $idcard = encrypt_str($newuserID);
                update_user_meta( $newuserID, 'hashcard', $idcard );
                return $newuserID;
            }else{
                $user = get_user_by( 'email', $data->email );
                $idcard = get_user_meta( $user->ID, 'hashcard', TRUE );
                if(!$idcard || empty($idcard)){
                     $idcard = encrypt_str($user->ID);
                     update_user_meta( $user->ID, 'hashcard', $idcard );
                }
                return $user->ID ;
            }  
    }else{
        return FALSE;
    }
}
function api_get_usercardid($client_id){           
    $userid = api_checkuser($client_id);
    $hashID = get_user_meta( $userid, 'hashcard', TRUE );
    return array('status'=>'success','data'=>array('cardID' => $hashID));
}
?>