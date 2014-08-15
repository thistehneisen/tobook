<?php
require_once('config.php');
require_once('lib/recurly.php');
Recurly_Client::$subdomain = RECURLY_SUBDOMAIN  ;
Recurly_Client::$apiKey = RECURLY_API_KEY;
Recurly_js::$privateKey = RECURLY_PRIVATE_KEY;
$result = "success";
$error = "";
$data = array();
$coupons = Recurly_CouponList::get();
$plancode = $_POST["plancode"];
$accountCode = $_POST["accountCode"] ;
if ($accountCode == "") {
    $accountCoupon="";
} else {
    $account = Recurly_Account::get($accountCode);
    if ($account->redemption) {
        $redemption = $account->redemption->get();
    }
    $redemption = $redemption->coupon._href ;
    $arr = explode( ">", $redemption );
    $arr_code = explode( "/", $arr[0] );
    $accountCoupon = $arr_code[count($arr_code)-1];
}
$update = '<option value="no" selected>----------</option>';
foreach ($coupons as $coupon) {
    if (!($coupon->state == "inactive")) {
        if (!($coupon->coupon_code == $accountCoupon)) {
            if ($coupon->applies_to_all_plans == "true") {
                $update = $update.'<option value="'.$coupon->coupon_code.'">'.$coupon->name.'</option>';
            } else {
                for ($i = 0;$i < count($coupon->plan_codes); $i++) {
                    if ($coupon->plan_codes[$i] == $plancode) {
                        $update = $update.'<option value="'.$coupon->coupon_code.'">'. $coupon->name.'</option>';
                    }
                }
            }
        }
    }
}
$data["update"]=$update;
header('Content-Type: application/json');
echo json_encode($data);
?>