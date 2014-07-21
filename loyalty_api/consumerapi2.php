<?php
session_start();
define('WP_USE_THEMES', false);
require('wp-load.php');  
//$_POST['user'] = 'mohamed12'; 
//$_POST['pass'] = '010101'; 
$_POST['action'] = 'getItems';//
//$_POST['token'] = '467312969716952998129548027813728';//
//$_POST['action'] =  'AuthenticateUser';//'useStamp';// 'getCustomerInfo' ;//'getConsomers';//   
//$_POST['userid'] = 1585;
//$_POST['stmpid'] = 7;
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282')).'<br>';
//echo json_encode(addconsumer('Mohamed fatla','mohamedfatla@live.it','068455422282')).'<br>';
header('Content-Type: application/json');
if(isset($_POST['action']) && $_POST['action']!='AuthenticateUser'){
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
    echo json_encode(auto_login($_POST['user'],$_POST['pass']));
    exit;
}elseif(isset($_POST['action']) && $_POST['action']=='getItems'){
    echo json_encode(apic_search_items_ajax());
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
    }
    exit;
}
function auto_login( $username, $pass, $userid = FALSE  ) {  
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
    $s = secure_jsonstr($_POST['s']);
    $c = secure_jsonstr($_POST['c']);
    $l = secure_jsonstr($_POST['l']);
    $d = secure_jsonstr($_POST['d']);
    $lat = secure_jsonstr($_POST['lat']);
    $lng = secure_jsonstr($_POST['lng']);
    $radius = array($d,$lat,$lng);
    apic_search_items($pos,10,$s,$c,$l,$radius);
    exit;
}
function apic_search_items($pos,$cnt,$s,$c,$l,$radius){
  $data = array();
  $allItem = apic_getItems($c,$l,$s,$radius);
  usort($allItem, "cmp");
  $aitems = array_slice($allItem, $pos, $cnt);
  $pag = apic_paginate_items(count($allItem),$cnt,$pos);
  $data['items']=$aitems;
  $data['pag']=$pag;
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
            $radius[1]='60.164785';
            $radius[2]='24.937935';
            $radius[0]=80;
            $o->distance = apic_isPointInRadius(0, $radius[1], $radius[2], $o->optionsDir['gpsLatitude'], $o->optionsDir['gpsLongitude'],TRUE);
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
        //}
        if($o->distance<=$radius[0]){
            $itemsCP[] = $o;
        }
    }
   // mail('dcom_rpc@msn.com',strlen($items).'moz',count($items));
    return $itemsCP;
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
?>