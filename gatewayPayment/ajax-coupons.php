<?php
  require_once('config.php');
  require_once('lib/recurly.php'); 
  Recurly_Client::$subdomain = RECURLY_SUBDOMAIN  ;
  Recurly_Client::$apiKey = RECURLY_API_KEY;
  Recurly_js::$privateKey = RECURLY_PRIVATE_KEY;  
  $result = "success";
  $error = "";
  $data = array();
  
  $couponPost = $_POST["couponCode"];
  $accountCode = $_POST["accountCode"];
  $plancode = $_POST["plancode"]; 
  
  $coupons = Recurly_CouponList::get(); 
  if($accountCode == ""){
      $accountCoupon = "";
  }else{ 
     $account = Recurly_Account::get($accountCode); 
    if ($account->redemption) {
      $redemption = $account->redemption->get();
      }
      $redemption = $redemption->coupon._href ;
      $arr = explode( ">", $redemption );  
      $arr_code = explode( "/", $arr[0] ); 
      $accountCoupon = $arr_code[count($arr_code)-1];
      
      $couponList = array();
      foreach ($coupons as $coupon) { 
           if ( !($coupon->state == "inactive" )){
                  if( !($coupon->coupon_code == $accountCoupon)){
                      if( $coupon->applies_to_all_plans=="true"){
                           $couponList[] = $coupon->coupon_code;
                       }else{
                          for( $i=0;$i<count($coupon->plan_codes);$i++){
                            if ( $coupon->plan_codes[$i]==$plancode) {
                                   $couponList[] = $coupon->coupon_code;
                              }
                            }
                         }  
                 }                                
           }
      }
      if( !in_array( $couponPost, $couponList ) ){
           $result = "failed";
      }
      
  }
  if( $result != "failed" ){
   try {
        $coupon = Recurly_Coupon::get($couponPost);
         if($coupon->discount_type == "percent"){
           $coupontAmount = $coupon->discount_percent;
           $couponType = "percent";
         }else{
           $coupontAmount = (array)$coupon->discount_in_cents->EUR->amount_in_cents ;
           $couponType = CURRENCY_TYPE;  
         }   
     } catch (Recurly_NotFoundError $e) {
          $result = "failed";
     }
     $data["couponType"] = $couponType;
     $data["coupontAmount"] = $coupontAmount;
  }
  $data["result"] = $result ;
  
  echo json_encode($data);
?>
