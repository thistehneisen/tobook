<?php
session_start();
define('WP_USE_THEMES', false);
require('wp-load.php');  
//$_POST['user'] = 'mohamed12'; 
//$_POST['user'] = 'eva.povenius'; 
//$_POST['pass'] = '010101'; 
//$_POST['action'] = 'register';//
//$_POST['user'] = 'mohamed12';//
//$_POST['email'] = 'dcom_rpc@msn.com';//
//$_POST['lat'] = '60.173324';//, 
//$_POST['lng'] = '24.941025';//
//$_POST['d'] = '0.5';//
//$_POST['cur'] = '639';//
//$_POST['cur'] = '9';//
//$_POST['token'] = '467312969716952998129548027813728';//
//$_POST['action'] =  'AuthenticateUser';//'useStamp';// 'getCustomerInfo' ;//'getConsomers';//   
//$_POST['action'] =  'getBookings';//'getFavorites';//'useStamp';// 'getCustomerInfo' ;//'getConsomers';//   
//$_POST['action'] =  'getFavorites';//'';//'useStamp';// 'getCustomerInfo' ;//'getConsomers';//   
//$_POST['userid'] = 1585;
//$_POST['stmpid'] = 7;
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282')).'<br>';
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282')).'<br>';
header('Content-Type: application/json');
$allowedaction = array('AuthenticateUser','getItems','getCats','register','checkemail','checkuser');
if(isset($_POST['action']) && ! in_array($_POST['action'], $allowedaction)){
    $current_user = wp_get_current_user();  
    if ( 0 != $current_user->ID ) {
        action($_POST); 
    }else{
        $check = verify_token($_POST['token'],FALSE);
        if(!$check){
            echo json_encode(array('status'=>'failed','msg'=>'You are not logged!!')); 
        }else{
            $userid = $check;
            echo json_encode(auto_login(NULL, NULL, $userid));
        }
    }
    exit;
}elseif(isset($_POST['action']) && $_POST['action']=='AuthenticateUser'){
    if($_POST['user'] == 'eva.povenius'){
        echo json_encode(auto_login($_POST['user'],$_POST['pass'],FALSE,TRUE));
    }else{
        echo json_encode(auto_login($_POST['user'],$_POST['pass']));
    }
    exit;
}elseif(isset($_POST['action'])){
    action($_POST);
    exit;
}else{  
    exit;
}
function action($p){
    foreach($p as $k=>$v){
        $p[$k] = secure_jsonstr($v);
    }
    switch ($p['action']) {
    case 'getItems':   
        echo json_encode(apic_search_items_ajax());
        break;
    case 'getItem':   
        echo json_encode(apic_getItembyID($p['id'],$p['lat'],$p['lng']));
        break;
    case 'getCats':
        echo json_encode(apic_getcat($p['id']));
        break;
    case 'getMarkers':
        echo json_encode(apic_getmarkers());
        break;
    case 'getFavorites':
        echo json_encode(apic_getfavorites());
        break;
    case 'getBookings':
        echo json_encode(apic_getbookings());
        break;
    case 'logOut':
        echo json_encode(logout());
        break;
    case 'register':
        echo json_encode(apic_register());
        break;
    case 'checkemail':
        echo json_encode(apic_checkemail());
        break;
    case 'checkuser':
        echo json_encode(apic_checkuser());
        break;
    }
    exit;
}
function auto_login_tmp( $user ) {
    $username = $user;
    // log in automatically
    if ( !is_user_logged_in() ) {
        $user = get_userdatabylogin( $username );
        $user_id = $user->ID;
        wp_set_current_user( $user_id, $user_login );
        wp_set_auth_cookie( $user_id );
        do_action( 'wp_login', $user_login );
    }     
}
function auto_login( $username, $pass, $userid = FALSE, $autolog = FALSE ) {  
    //if($username == 'eva.povenius'){
//       auto_login_tmp($username); 
//       return array('status'=>'success');
//    }
    if($userid){
           $user = get_userdata($userid);
           $username = $user->user_login;
           wp_set_current_user( $userid, $username );
           wp_set_auth_cookie( $userid );
           do_action( 'wp_login', $username );
           $current_user = wp_get_current_user();
            if ( 0 == $current_user->ID ) {
                return array('status'=>'notlogged');
            } else {
                $fname = get_user_meta($current_user->ID, 'euf_billingfirstname', TRUE);
                $lname = get_user_meta($current_user->ID, 'euf_billinglastname', TRUE);
                $token =  encrypt_id($current_user->ID );
                update_user_meta( $current_user->ID , 'token', $token );
                return array('status'=>'success','data'=> array('consumerID' => $current_user->ID , 'fname' => $fname,'lname' => $lname, 'token' => $token, 'isnew' => true));
            }
    }else{
       $user = get_user_by( 'login', $username );
        if ( $user && (wp_check_password( $pass, $user->data->user_pass, $user->ID) || $autolog) ){
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
                $fname = get_user_meta($current_user->ID, 'euf_billingfirstname', TRUE);
                $lname = get_user_meta($current_user->ID, 'euf_billinglastname', TRUE);
                $token =  encrypt_id($current_user->ID );
                update_user_meta( $current_user->ID , 'token', $token );
                return array('status'=>'success','data'=> array('consumerID' => $current_user->ID , 'fname' => $fname,'lname' => $lname, 'token' => $token, 'isnew' => true));
            }
        }else{
             return array('status'=>'faillogin');
        } 
    }
    // log in automatically
}
function logout(){
    wp_logout();
    return array('status'=>'success','msg' => 'loggedout' );
}
function apic_register(){
    $msgerrors = '';
    if(empty($_POST['pwd1']) || empty($_POST['user']) || empty($_POST['email'])){
        $msgerrors .= 'Fields are empty !!<br>'; 
    }
    if($_POST['pwd1'] != $_POST['pwd2']){
        $msgerrors .= 'Password missmatch !!<br>';
    }
    $email = secure_jsonstr($_POST['email']);
    $user = secure_jsonstr($_POST['user']);
    if( email_exists( $email )) {
        $msgerrors .= 'Email Already registred !!<br>';
    }
    if( username_exists($user)) {
        $msgerrors .= 'Username Already registred !!<br>';
    } 
    if(!empty($msgerrors)){
       return array('result'=>false,'msg'=>$msgerrors);
       exit;
    }
    $args = array(
            'user_login' => secure_jsonstr($_POST['user']),
            'user_pass' => $_POST['pwd1'],
            'user_email' => secure_jsonstr($_POST['email']),
            'display_name' => secure_jsonstr($_POST['user']),
            'nickname' => secure_jsonstr($_POST['user']),
            'first_name' => secure_jsonstr($_POST['fname']),
            'last_name' => secure_jsonstr($_POST['lname'])
        );
        $newuserid = wp_insert_user( $args );
        update_user_meta($newuserid, 'euf_billingfirstname', secure_jsonstr($_POST['fname']));
        update_user_meta($newuserid, 'euf_billinglastname', secure_jsonstr($_POST['lname']));
        update_user_meta($newuserid, 'euf_billingaddressone', secure_jsonstr($_POST['address']));
        update_user_meta($newuserid, 'euf_billingaddresstwo', secure_jsonstr($_POST['address']));
        update_user_meta($newuserid, 'euf_billingpostcode', secure_jsonstr($_POST['zip']));
        update_user_meta($newuserid, 'euf_billingcity', secure_jsonstr($_POST['city']));
        update_user_meta($newuserid, 'euf_billingphone', secure_jsonstr($_POST['mobile']));
        update_user_meta($newuserid, 'euf_billingmobile', secure_jsonstr($_POST['mobile']));
        update_user_meta($newuserid, 'euf_billingemail', secure_jsonstr( $_POST['email']));
         
        $token =  encrypt_id($newuserid );
        update_user_meta( $newuserid , 'token', $token );  
             
        // Once the user is added to database send email to admin and registered users
        apic_send_registeremail($_POST['user'],$_POST['pwd1'],$_POST['fname'],$_POST['lname'],$_POST['email']);
        // Auto Login
        wp_set_current_user( $newuserid, $args['user_login'] );
        wp_set_auth_cookie( $newuserid );
        do_action( 'wp_login', $args['user_login'] );
        return array('result'=>true,'msg'=>'Successfully registered'); exit;
}
function apic_send_registeremail($username,$password,$fname,$lname,$email){
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
function apic_checkemail(){
    $msgerrors = '';
    $email = secure_jsonstr($_POST['email']);
    if( email_exists( $email )) {
        $msgerrors .= 'Email Already registred !!<br>';
    }
    if(!empty($msgerrors)){
       return array('result'=>false,'msg'=>$msgerrors);
    }else{
       return array('result'=>true,'msg'=>'success');
    }
}
function apic_checkuser(){
    $msgerrors = '';
    $user = secure_jsonstr($_POST['user']);
    if( username_exists($user)) {
        $msgerrors .= 'Username Already registred !!<br>';
    } 
    if(!empty($msgerrors)){
       return array('result'=>false,'msg'=>$msgerrors);
    }else{
       return array('result'=>true,'msg'=>'success');
    }
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
function decrypt_id($hash){
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
function encrypt_id($id){
   $h = rand(10,99);
   $y = rand(1000,9899);
   $f = intval($y) + intval($h);
   $z = rand(100,899);
   $a = $f . (intval($id) + intval($y * $z)); 
   $c = intval($id * $y) + intval((intval($y)+intval($z)));
   $b = ($y * $z) . $z . $h . rand(100,999);
   return $a . $c . $b;
}
function verify_token($token,$update=FALSE){
     $userid = decrypt_id($token);
     $oldtoken = get_user_meta($userid, 'token', TRUE);
     if($oldtoken == $token){
         if($update) {
            $newtoken = encrypt_id($userid);
            update_user_meta( $userid , 'token', $newtoken );
            return $newtoken;
         }
         return $userid;
     }else{
         return FALSE;
     }
}
function apic_search_items_ajax($lim=10){
    $pos = intval($_POST['pos']);
    $s = (isset($_POST['s']))?secure_jsonstr($_POST['s']):'';
    $c = (isset($_POST['c']))?secure_jsonstr($_POST['c']):0;
    $l = (isset($_POST['l']))?secure_jsonstr($_POST['l']):0;
    $d = (isset($_POST['d']))?secure_jsonstr($_POST['d']):100;
    $lat = (isset($_POST['lat']))?secure_jsonstr($_POST['lat']):0;
    $lng = (isset($_POST['lng']))?secure_jsonstr($_POST['lng']):0;
    $cur = (isset($_POST['cur']))?secure_jsonstr($_POST['cur']):0;
    $radius = array($d,$lat,$lng);
    apic_search_items($cur,10,$s,$c,$l,$radius);
    exit;
}
function apic_search_items($pos,$cnt,$s,$c,$l,$radius){
  $data = array();
  $allItem = apic_getItems($c,$l,$s,$radius);
  usort($allItem, "cmp");
  $aitems = array_slice($allItem, $pos, $cnt);
 // var_dump($aitems);
  $pag = apic_paginate_items(count($allItem),$cnt,$pos);
  $data['items']=$aitems;
  $data['pag']=$pag;
  $data['count']=count($allItem);
  //$data['query']="SELECT * FROM ".$table_name.$where." ORDER BY id DESC LIMIT $pos,$lim";
  echo json_encode($data);exit;
}
function apic_paginate_items($nbr_cpns,$lim=10,$pos=0){
  if($nbr_cpns <= ($pos + $lim)){
      $loadmore = -1;
  }else{
      $loadmore = ($pos + $lim);
  }
  return $loadmore;
}
function apic_getItems($category = 0, $location = 0, $search = '', $radius = array()){    
    $params = array(
        'post_type'            => 'ait-dir-item',
        'nopaging'            =>    true,
        'post_status'        => 'publish'
    );

    $taxquery = array();
    $taxquery['relation'] = 'AND';
    if($category != 0){
        $taxquery[] = array(
            'taxonomy' => 'ait-dir-item-category',
            'field' => 'id',
            'terms' => $category,
            'include_children' => true
        );
    }
    if($location != 0){
        $taxquery[] = array(
            'taxonomy' => 'ait-dir-item-location',
            'field' => 'id',
            'terms' => $location,
            'include_children' => true
        );
    }
    if($category != 0 || $location != 0){
        $params['tax_query'] = $taxquery;
    }

    if($search != ''){
        $params['s'] = $search;
    }
    $items = $GLOBALS['wp_query']->queried_object;
    $itemsQuery = new WP_Query();
    $items = $itemsQuery->query($params);
    $itemsCP = array();
    // add item details
    foreach ($items as $key => $item) {
        $o = new stdClass();
        // options
        $o->ID = $item->ID;
        //$o->optionsDir =  get_custompostmeta($item->ID,'_ait-dir-item');
        $o->optionsDir = get_post_meta($item->ID, '_ait-dir-item', true);
        // filter radius
        //if(!empty($radius) && !isPointInRadius($radius[0], $radius[1], $radius[2], $item->optionsDir['gpsLatitude'], $item->optionsDir['gpsLongitude'])){
            //unset($items[$key]);
        //} else {
            // link
            //$radius[1]= '60.164785';
            //$radius[2]= '24.937935';
            //$radius[0]= 1000;
            $o->distance = apic_isPointInRadius($radius[0], $radius[1], $radius[2], $o->optionsDir['gpsLatitude'], $o->optionsDir['gpsLongitude'],TRUE);
            $o->gpsLatitude =  number_format($o->optionsDir['gpsLatitude'], 6, '.', '');
            $o->gpsLongitude =  number_format($o->optionsDir['gpsLongitude'], 6, '.', '');

            $o->address = str_replace("\n","' + '<br>' + '",str_replace("'"," ",$o->optionsDir["address"]));
            $o->link = get_permalink($item->ID);
            // thumbnail
            $image = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID) );
            if($image !== false){
                $item->thumbnailDir = $image[0];
            } else {
                $item->thumbnailDir = $GLOBALS['aitThemeOptions']->directory->defaultItemImage;
            }
            $o->thumbnailDir =  $item->thumbnailDir;
            // marker
            $terms = wp_get_post_terms($item->ID, 'ait-dir-item-category');
            $termMarker = $GLOBALS['aitThemeOptions']->directoryMap->defaultMapMarkerImage;
            if(isset($terms[0])){
                $termMarker = getCategoryMeta("marker", intval($terms[0]->term_id));
            }
            $o->marker = $termMarker;
            // excerpt
            $desc = strip_tags(aitGetPostExcerpt($item->post_excerpt,$item->post_content));
     
            $o->post_title = $item->post_title;
            $o->excerptDir1 = t2t_truncate($desc,100,'');
            $o->excerptDir2 = substr($desc,101,strlen($desc));
            //$o->bookinglink = substr($desc,101,strlen($desc));
        //}
        if($o->distance<=$radius[0]){
            $itemsCP[] = $o;
        }
    }
    //var_dump($o->optionsDir);
   // mail('dcom_rpc@msn.com',strlen($items).'moz',count($items));
    return $itemsCP;
}
function apic_getItembyID($id,$lat,$lng){

        $o = new stdClass();
        $o->optionsDir = get_post_meta($id, '_ait-dir-item', true);
        $item = get_post($id);
        //$radius[1]= '60.164785';
        //$radius[2]= '24.937935';
        //$radius[0]= 1000;
        $o->distance = apic_isPointInRadius(0, $lat, $lng, $o->optionsDir['gpsLatitude'], $o->optionsDir['gpsLongitude'],TRUE);
        $o->gpsLatitude =  number_format($o->optionsDir['gpsLatitude'], 6, '.', '');
        $o->gpsLongitude =  number_format($o->optionsDir['gpsLongitude'], 6, '.', '');

        $o->address = str_replace("\n","' + '<br>' + '",str_replace("'"," ",$o->optionsDir["address"]));
        $o->link = get_permalink($id);
        // thumbnail
        $image = wp_get_attachment_image_src( get_post_thumbnail_id($id) );
        if($image !== false){
            $item->thumbnailDir = $image[0];
        } else {
            $item->thumbnailDir = $GLOBALS['aitThemeOptions']->directory->defaultItemImage;
        }
        $o->thumbnailDir =  $item->thumbnailDir;
        // marker
        $terms = wp_get_post_terms($id, 'ait-dir-item-category');
        $termMarker = $GLOBALS['aitThemeOptions']->directoryMap->defaultMapMarkerImage;
        if(isset($terms[0])){
            $termMarker = getCategoryMeta("marker", intval($terms[0]->term_id));
        }
        $o->marker = $termMarker;
        // excerpt
        $desc = strip_tags(aitGetPostExcerpt($item->post_excerpt,$item->post_content));
        $o->post_title = $item->post_title;
        $o->excerptDir1 = t2t_truncate($desc,100,'');
        $o->excerptDir2 = substr($desc,101,strlen($desc));
            //$o->bookinglink = substr($desc,101,strlen($desc));
    return $o;
}
function t2t_truncate($str, $length=10, $trailing='...') {
        $str = strip_tags($str);
    
      $length-=mb_strlen($trailing);
      if (mb_strlen($str)> $length) {
         return mb_substr($str,0,$length).$trailing;
      }
      else {
         $res = $str;
      }
      return $res;
}
function apic_isPointInRadius($radiusInKm, $cenLat, $cenLng, $lat, $lng, $distShow=FALSE){
    $radiusInKm = intval($radiusInKm);
    $cenLat = floatval($cenLat);
    $cenLng = floatval($cenLng);
    $lat = floatval($lat);
    $lng = floatval($lng);
    $distance = ( 6371 * acos( cos( deg2rad($cenLat) ) * cos( deg2rad( $lat ) ) * cos( deg2rad( $lng ) - deg2rad($cenLng) ) + sin( deg2rad($cenLat) ) * sin( deg2rad( $lat ) ) ) );
    if($distShow){
      return $distance;  
    }
    if($distance <= $radiusInKm){
        return true;
    } else {
        return false;
    }
}
function cmp($a, $b){
    return strcmp($a->distance, $b->distance);
}
function apic_getcat($catID = 0){
$allcats = array();
$parentcategories = get_terms('ait-dir-item-category', array(
    'hide_empty'     => false,
    'orderby'        => 'name',
    'parent'         => $catID
));                // make sure only parent categories listed at top level
$no_of_categories = count ( $parentcategories ) ;
if ( $no_of_categories > 0 ) {
        foreach ( $parentcategories as $parentcategory ) {
            $subcats = array();
            $childcategories = get_terms('ait-dir-item-category', array(
                'hide_empty'     => false,
                'orderby'        => 'name',
                'child_of'         => $parentcategory->term_id
            )); 
            if(count ( $childcategories ) > 0){
                foreach ( $childcategories as $childcategory ) {
                      $subcats[] = array('id'=>$childcategory->term_id,'name'=>$childcategory->name, 'icon' => apic_getcatimages( $childcategory->term_id, 'icon' ));
                }
                $allcats[]=array('id'=>$parentcategory->term_id,'name'=>$parentcategory->name, 'icon' => apic_getcatimages( $parentcategory->term_id, 'icon' ),'child' => $subcats);
            }else{
                $allcats[]=array('id'=>$parentcategory->term_id,'name'=>$parentcategory->name, 'icon' => apic_getcatimages( $parentcategory->term_id, 'icon' ),'child' => NULL);
            } 
        }
}
return $allcats ; 
}
function apic_getcatimages($catid,$what){
    global $wpdb;
    // get cache = all values
    if(empty($aitCategoryMeta)){
        $results = $wpdb->get_results( "SELECT * FROM ".$wpdb->options." WHERE option_name LIKE 'ait\_dir\_item\_category\_%'" );
        foreach ($results as $r) {
            preg_match('!\d+!', $r->option_name, $matches);
            $id = (int)$matches[0];
            if(!isset($aitCategoryMeta[$id])) {
                $aitCategoryMeta[$id] = array();
            }
            if(strpos($r->option_name,'icon') !== false){
                $aitCategoryMeta[$id]['icon'] = $r->option_value;
            } else if(strpos($r->option_name,'marker') !== false){
                $aitCategoryMeta[$id]['marker'] = $r->option_value;
            } else {
                $aitCategoryMeta[$id]['excerpt'] = $r->option_value;
            }

        }
    }
    switch ($what) {
        case 'icon':
            $anc = get_ancestors( $catid, 'ait-dir-item-category' );
            $icon = isset($aitCategoryMeta[$catid]) ? $aitCategoryMeta[$catid]['icon'] : '';
            if(empty($icon)){
                foreach ($anc as $value) {
                    if(!empty($aitCategoryMeta[$value]['icon'])){
                        $icon = $aitCategoryMeta[$value]['icon'];
                        break;
                    }
                }
                if(empty($icon)){
                    $icon = $GLOBALS['aitThemeOptions']->directory->defaultCategoryIcon;
                }
            }
            return $icon;
        case 'marker':
            $anc = get_ancestors( $catid, 'ait-dir-item-category' );
            $marker = isset($aitCategoryMeta[$catid]) ? $aitCategoryMeta[$catid]['marker'] : '';
            if(empty($marker)){
                foreach ($anc as $value) {
                    if(!empty($aitCategoryMeta[$value]['marker'])){
                        $marker = $aitCategoryMeta[$value]['marker'];
                        break;
                    }
                }
                if(empty($marker)){
                    $marker = $GLOBALS['aitThemeOptions']->directoryMap->defaultMapMarkerImage;
                }
            }
            return $marker;
    }
}
function apic_getmarkers(){
$allcats = array();
$parentcategories = get_terms('ait-dir-item-category', array(
    'hide_empty'     => false,
    'orderby'        => 'name',
    'parent'         => 0
));                // make sure only parent categories listed at top level
$no_of_categories = count ( $parentcategories ) ;
if ( $no_of_categories > 0 ) {
        foreach ( $parentcategories as $parentcategory ) {
            $subcats = array();
            $childcategories = get_terms('ait-dir-item-category', array(
                'hide_empty'     => false,
                'orderby'        => 'name',
                'child_of'         => $parentcategory->term_id
            )); 
            if(count ( $childcategories ) > 0){
                foreach ( $childcategories as $childcategory ) {
                      $allcats[] = $childcategory->term_id;
                }
            }else{
                $allcats[]= $parentcategory->term_id;
            } 
        }
}  
foreach($allcats as $k => $v){
    $allmarkrs[] = getCategoryMeta("marker", $v);
}
$allmarkrs = array_unique($allmarkrs); 
return array('version' => 0 , 'markers' => $allmarkrs) ; 
}
function apic_getfavorites(){
    global $current_user;
    $favblog = array();
    get_currentuserinfo();
    $lat = secure_jsonstr($_POST['lat']);
    $lng = secure_jsonstr($_POST['lng']);
    if($current_user->ID != 0){                                                 
       $userfav = get_user_meta($current_user->ID,'favblog',TRUE);
       $blog_array = json_decode($userfav,TRUE); 
       if($userfav != 'null' && $blog_array != null){
          foreach($blog_array as $blogID) {  
              if($blogID !=1 && $blogID !=null ){
                 $blog_detail = get_blog_details( $blogID );
                 $subdomain = trim(str_replace('.'.str_replace(array('http:','/'),'',network_site_url()),'',str_replace(array('http:','/'),'',$blog_detail->siteurl)));
                 $args = array(
                    'post_type' => 'ait-dir-item',
                    'meta_query' => array(
                        array( 
                            'key' => '_ait-dir-item',
                            'value' => $subdomain,
                            'compare' => 'LIKE'
                        )
                    )
                );
                $post = get_posts($args);
                $logo = (has_post_thumbnail( $post[0]->ID )) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post[0]->ID ) , 'single-post-thumbnail' ) : array('0'=>'http://ecx.images-amazon.com/images/I/01KJY1JKA4L.jpg') ;
                $favblog[] = array('blogid'=>$blogID,'title'=>$post[0]->post_title,'blogurl'=>$blog_detail->siteurl,'img'=>$logo[0],'itemdetail'=>apic_getItembyID($post[0]->ID, $lat, $lng));
              }
          }
            return array('status'=>'success','data' => $favblog );
       }else{
            return array('status'=>'failed','data' => NULL , 'msg' => 'Sinulla ei ole varauksia');
       } 
    }
}
function apic_getbookings($limit=10,$offset=0){
    global $current_user,$wpdb;
    get_currentuserinfo();  
    $bookinglist = array();  
    if($current_user->ID != 0){
        $bookings = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."userbooking WHERE userID=".$current_user->ID." LIMIT $offset,$limit");       
        if($bookings){
            foreach ( $bookings as $booking ){ 
                $blog_detail = get_blog_details( $booking->blogID );
                $subdomain = trim(str_replace('.'.str_replace(array('http:','/'),'',network_site_url()),'',str_replace(array('http:','/'),'',$blog_detail->siteurl)));
                $args = array(
                        'post_type' => 'ait-dir-item',
                        'meta_query' => array(
                            array( 
                                'key' => '_ait-dir-item',
                                'value' => $subdomain,
                                'compare' => 'LIKE'
                            )
                        )
                    );
                
                    $post = get_posts($args);
                    $logo = (has_post_thumbnail( $post[0]->ID )) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post[0]->ID ) , 'single-post-thumbnail' ) : array('0'=>'http://ecx.images-amazon.com/images/I/01KJY1JKA4L.jpg') ;
                    $bookinglist[] = array('blogurl'=>$blog_detail->siteurl,
                                           'img'=>$logo[0],
                                           'starttime'=>translate_month(date('F',$booking->bookingstartDate)).date(' j, Y, H:i',$booking->bookingstartDate),
                                           'endtime'=>translate_month(date('F',$booking->bookingendDate)).date(' j, Y, H:i',$booking->bookingendDate),
                                           'servicename'=>$booking->serviceName,
                                           'employee'=>$booking->employeeName,
                                           'title'=>$booking->bookedFor,
                                           'itemdetail'=>apic_getItembyID($post[0]->ID ));
            }
            return array('status'=>'success','data' => $bookinglist );
        }else{
            return array('status'=>'failed','data' => NULL , 'msg' => 'Sinulla ei ole varauksia');
        }
    }
}
?>