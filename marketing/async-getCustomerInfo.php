<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$planGroupCode = mysql_escape_string($_POST['planGroupCode']);
$cId = mysql_escape_string($_POST['cId']);
$ownerId = mysql_escape_string($_POST['ownerId']);
$bookingList = array( );

$sql = "select vuser_login
          from tbl_user_mast
         where nuser_id = $ownerId
           and vdel_status = 0";
$dataUser = $db->queryArray($sql);
$dataUser = $dataUser[0];
$ownerName = $dataUser['vuser_login'];

if ($planGroupCode == "rb") {
    $sql = "select *
              from rb_bookings
             where id = $cId
               and owner_id = $ownerId";
    $dataConsumer = $db->queryArray($sql);

    $name = $dataConsumer[0]['c_fname']." ".$dataConsumer[0]['c_lname'];
    $phoneNo = $dataConsumer[0]['c_phone'];
    $email = $dataConsumer[0]['c_email'];
    $note = $dataConsumer[0]['c_notes'];

    $sql = "select *
              from rb_bookings
             where c_email = '$email' 
               and c_phone = '$phoneNo'
               and owner_id = $ownerId";
    $dataBooking = $db->queryArray($sql);
     
    for ($i = 0; $i < count($dataBooking ); $i++){
        $bookingList[] = $dataBooking[$i]['created'];
    }
    $data['cnt'] = count($dataBooking);
} elseif ($planGroupCode == "tb") {
    $sql = "select *
              from ts_bookings
             where id = $cId
               and owner_id = $ownerId";
    $dataConsumer = $db->queryArray($sql);
     
    $name = $dataConsumer[0]['customer_name'];
    $phoneNo = $dataConsumer[0]['customer_phone'];
    $email = $dataConsumer[0]['customer_email'];
    $note = $dataConsumer[0]['customer_notes'];
    	
    $sql = "select *
              from ts_bookings
             where customer_email = '$email' 
               and customer_phone = '$phoneNo'
               and owner_id = '$ownerId'";
    $dataBooking = $db->queryArray($sql);

    for ( $i = 0; $i < count($dataBooking); $i++) {
        $bookingList[] = $dataBooking[$i]['created'];
    }
    $data['cnt'] = count($dataBooking);
} else if ($planGroupCode == "as") {
    $sql = "select *
              from as_bookings
             where id = $cId
               and owner_id = $ownerId";
    $dataConsumer = $db->queryArray($sql);

    $name = $dataConsumer[0]['c_name'];
    $phoneNo = $dataConsumer[0]['c_phone'];
    $email = $dataConsumer[0]['c_email'];
    $note = $dataConsumer[0]['c_notes'];

    $sql = "select *
              from as_bookings
             where c_email = '$email'
               and c_phone = '$phoneNo'";
    $dataBooking = $db->queryArray($sql);
     
    for($i = 0; $i < count($dataBooking); $i ++){
        $bookingList[] = $dataBooking[$i]['created'];
    }
    $data['cnt'] = count($dataBooking);
}

$data['bookingList'] = $bookingList;
$data['name'] = $name;
$data['phoneNo'] = $phoneNo;
$data['email'] = $email;
$data['note'] = $note;

$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>