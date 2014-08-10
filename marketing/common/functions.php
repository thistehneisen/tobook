<?php
require_once dirname(__FILE__) . '/locale_fi.php';
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/lib/SendGrid.php';

set_include_path(implode(PATH_SEPARATOR, array(
// realpath('/path/to/zend/library'),
dirname(__FILE__) . '/library',
get_include_path()
)));

require_once dirname(__FILE__) . '/Infobip_sms_api.php';

require_once dirname(__FILE__) . '/class.phpmailer.php';

function logToFile($filename, $msg)
{
    $fd = fopen($filename, "a");
    $str = "[" . date("Y/m/d h:i:s", time()) . "] " . $msg;
    fwrite($fd, $str . "\n");
    fclose($fd);
}

function MT_MkDir($path, $mode = 0777) {
    $dirs = explode(DIRECTORY_SEPARATOR , $path);
    $count = count($dirs);
    $path = '.';
    for ($i = 0; $i < $count; ++$i) {
        $path .= DIRECTORY_SEPARATOR . $dirs[$i];
        if (!is_dir($path) && !mkdir($path, $mode)) {
            return false;
        }
    }
    return true;
}
function MT_generateRandom( $len ){
    $strpattern = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
    $result = "";
    for( $i = 0 ; $i < $len; $i++ ){
        $rand = rand( 0, strlen($strpattern) - 1 );
        $result = $result.$strpattern[$rand];
    }
    return $result;
}

function MT_creditsCalculation( $ownerId, $creditsScore ){
    return true;

    global $db;
    $returnFlag = true;
    $sql = "select * from tbl_owner_credits where owner = $ownerId";
    $dataCredits = $db->queryArray($sql);
    $credits = $dataCredits[0]['credits'];
    if( $credits >= $creditsScore ){
        $credits = $credits - $creditsScore;
    }else{
        $recurlyAmount = ceil( ( $creditsScore - $credits ) / CREDITS_PRICE );
        	
        $sql = "select * from tbl_owner_premium where owner = $ownerId and plan_group_code = 'MT'";
        $dataAccount = $db->queryArray($sql);
        $accountCode = $dataAccount[0]['account_code'];
        $paymentResult = onePay($accountCode, $recurlyAmount);
        $credits = $credits - $creditsScore + $recurlyAmount * CREDITS_PRICE;
    }
    $sql = "update tbl_owner_credits
    set credits = $credits
    where owner = $ownerId";
    $db->query($sql);
    return $returnFlag;
}

function MT_sendSMS( $phoneNo, $title, $content ){
    $phoneNo1 = PHONE_COUNTRY_CODE.substr( $phoneNo, 1);
    $infobip = new Infobip_sms_api();
    $infobip->setUsername(INFOBIP_USERNAME);
    $infobip->setPassword(INFOBIP_PASSWORD);

    // Send long SMS --------------------------------------------------------

    $infobip->setMethod(Infobip_sms_api::OUTPUT_XML); // With xml method
    $infobip->setMethod(Infobip_sms_api::OUTPUT_JSON); // OR With json method
    $infobip->setMethod(Infobip_sms_api::OUTPUT_PLAIN); // OR With plain method

    $message = new Infobip_sms_message();

    $message->setSender($title); // Sender name
    $message->setText($content); // Message
    $message->setType(Infobip_sms_message::LONG_SMS);
    $message->setRecipients($phoneNo1);

    $infobip->addMessages(array(
                    $message
    ));

    $results = $infobip->sendSMS();
    return $results;
    // $results[0]->status , messageid, destination
}

// Send Bulk Email using Send Grid API
function MT_sendEmailBulk( $recipients, $replyEmail, $replyName, $subject, $message, $type, $category ){
    $sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD, array("turn_off_ssl_verification" => true));
    $email = new SendGrid\Email();
    $email->setFrom( $replyEmail )
    ->setFromName( $replyName )
    ->setSubject( $subject )
    ->setTos( $recipients );
    if( $category != "" ){
        $email->addCategory( $category );
    }

    if( $type == 1 ){
        $email->setHtml($message);
    }else{
        $email->setText($message);
    }

    $result = $sendgrid->send($email);
    return $result;
}

// Send Email using Send Grid API
function MT_sendEmail( $email, $subject, $message, $type, $category ){
    // $type : 1 - HTML , 2 : TEXT
    $sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD, array("turn_off_ssl_verification" => true));
    $email    = new SendGrid\Email();
    $email->addTo( $email )
    ->setFrom( EMAIL_FROM )
    ->setSubject( $subject )
    ->addHeader('X-Sent-Using', 'SendGrid-API')
    ->addHeader('X-Transport', 'web');

    if( $category != "" ){
        $email->addCategory( $category );
    }

    if( $type == 1 ){
        $email->setHtml($message);
    }else{
        $email->setText($message);
    }

    $response = $sendgrid->send($email);
    return $response;
}

// 2014-07-08 : Author Jeni
// Description : For campaign create & uploading image, set the image's path

function MT_mailImage( $message ){
    $replaceStr = 'src="http://klikkaaja.com/marketing/img';
    return str_replace( 'src="img', $replaceStr, $message);
}

// 2014-07-08 : Author Jeni
// Description : Get Campaign Info & Send Bulk Email using Send Grid API

function MT_sendBulkEmailByCampaignId( $emailList, $emailCampaign ){
    global $db;
    $sql = "select * from tbl_email_campaign where email_campaign = $emailCampaign";
    $dataCampaign = $db->queryArray($sql);
    $dataCampaign = $dataCampaign[0];

    $replyEmail = $dataCampaign['reply_email'];
    $replyName = $dataCampaign['reply_name'];
    $subject = $dataCampaign['subject'];
    $message = $dataCampaign['content'];
    $type = 1;
    $category = $dataCampaign['category_code'];

    MT_sendEmailBulk( $emailList, $replyEmail, $replyName, $subject, $message, $type, $category );
}

// 2014-07-08 : Author Jeni
// Description : Get Campaign Info & Send Bulk Email using Send Grid API
function MT_getEmailListByCountPreviousBooking( $ownerId, $cntPreviousBooking, $pluginType ){
    if( $pluginType == "RB" ){
        $sql = "
                select c_email as emailAddress
                  from rb_bookings
                 where owner_id = $ownerId
                 group by c_email
                having count(*) = $cntPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    } elseif ($pluginType == "TB") {
        $sql = "
                select customer_email emailAddress
                  from ts_bookings
                 where owner_id = $ownerId
                 group by customer_email
                having count(*) = $cntPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    } elseif ($pluginType == "AS") {
        $sql = "
                select c_email emailAddress
                  from as_bookings
                 where owner_id = $ownerId
                 group by c_email
                having count(*) = $cntPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    }
    $emailList = array( );
    for ($i = 0; $i < count( $dataCustomer ); $i++) {
        $emailList[] = $dataCustomer[$i]['emailAddress'];
    }
    return $emailList;
}

function MT_getPhoneListByCountPreviousBooking( $ownerId, $cntPreviousBooking, $pluginType ){
    if ($pluginType == "RB") {
        $sql = "
                select c_phone as phoneNo
                  from rb_bookings
                 where owner_id = $ownerId
                 group by c_phone
                having count(*) = $cntPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    } elseif ($pluginType == "TB") {
        $sql = "
                select customer_phone as phoneNo
                  from ts_bookings
                 where owner_id = $ownerId
                 group by customer_phone
                having count(*) = $cntPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    }else if( $pluginType == "AS" ){
        $sql = "
                select c_phone as phoneNo
                  from as_bookings
                 where owner_id = $ownerId
                 group by c_phone
                having count(*) = $cntPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    }
    $phoneList = array();
    for ($i = 0; $i < count( $dataCustomer ); $i++) {
        $phoneList[] = $dataCustomer[$i]['phoneNo'];
    }
    return $phoneList;
}

function MT_getEmailListByDaysPreviousBooking( $ownerId, $daysPreviousBooking, $pluginType){
    if($pluginType == "RB"){
        $sql = "
                select t1.emailAddress
                  from (
                        select max(c_email) emailAddress, max(dt) dt
                          from rb_bookings
                         where owner_id = $ownerId
                         group by c_email, dt ) t1
                 where datediff( now(), t1.dt ) = $daysPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    } elseif ($pluginType == "TB") {
        $sql = "
                select t1.emailAddress
                  from (
                      select max(t2.customer_email) as emailAddress, max(t1.booking_date) as dt
                        from ts_bookings_slots t1, ts_bookings t2
                       where t1.booking_id = t2.id
                         and t1.owner_id = $ownerId
                         and t2.owner_id = $ownerId
                       group by t2.customer_email, t1.booking_date
                        ) t1
                 where datediff(now(), t1.dt) = $daysPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    } elseif ($pluginType == "AS") {
        $sql = "
                select t1.emailAddress
                  from
                    (
                    select max(t2.c_email) as emailAddress, max(t1.date) as dt
                      from as_bookings_services t1, as_bookings t2
                     where t1.booking_id = t2.id
                       and t1.owner_id = $ownerId
                       and t2.owner_id = $ownerId
                     group by t2.c_email, t1.date
                    ) t1
                 where datediff(now(), t1.dt) = $daysPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    }
    $emailList = array();
    for ($i = 0; $i < count( $dataCustomer ); $i++) {
        $emailList[] = $dataCustomer[$i]['emailAddress'];
    }
    return $emailList;
}

function MT_getPhoneListByDaysPreviousBooking( $ownerId, $daysPreviousBooking, $pluginType){
    if ($pluginType == "RB") {
        $sql = "
                select t1.phoneNo
                  from (
                        select max(c_phone) phoneNo, max(dt) dt
                          from rb_bookings
                         where owner_id = $ownerId
                         group by c_phone, dt ) t1
                 where datediff( now(), t1.dt ) = $daysPreviousBooking
                   and t1.phoneNo != ''";
        $dataCustomer = $db->queryArray($sql);
    } elseif ($pluginType == "TB") {
        $sql = "
                select t1.phoneNo
                  from
                    (
                    select max(t2.customer_phone) as phoneNo, max(t1.booking_date) as dt
                      from ts_bookings_slots t1, ts_booking_bookings t2
                     where t1.booking_id = t2.id
                       and t1.owner_id = $ownerId
                       and t2.owner_id = $ownerId
                     group by t2.customer_phone, t1.booking_date
                    ) t1
                 where datediff( now(), t1.dt ) = $daysPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    }else if( $pluginType == "AS" ){
        $sql = "
                select t1.phoneNo
                  from
                    (
                    select max(t2.c_phone) as phoneNo, max(t1.date) as dt
                      from as_bookings_services t1, as_bookings t2
                     where t1.booking_id = t2.id
                       and t1.owner_id = $ownerId
                       and t2.owner_id = $ownerId
                     group by t2.c_phone, t1.date
                    ) t1
                 where datediff( now(), t1.dt ) = $daysPreviousBooking";
        $dataCustomer = $db->queryArray($sql);
    }
    $phoneList = array( );
    for ($i = 0; $i < count($dataCustomer); $i++) {
        $phoneList[] = $dataCustomer[$i]['phoneNo'];
    }
    return $phoneList;
}

function MT_getUsernameById( $ownerId ){
    global $db;
    $sql = "select vuser_login from tbl_user_mast where nuser_id = $ownerId";
    $dataUser = $db->queryArray($sql);
    $dataUser = $dataUser[0];
    return $dataUser['vuser_login'];
}

// 2014-07-08 : Author Jeni
// Description : Send Bulk Email & Log the Email Sending history
function MT_sendEmailAutomationBulk( $marketingAuto, $ownerId, $emailCampaign, $emailList, $planGroupCode ){
    // Credits Manage
    global $db;
    MT_creditsCalculation( $ownerId, count($emailList) * CREDITS_EMAIL );

    // Send Bulk Email
    MT_sendBulkEmailByCampaignId( $emailList, $emailCampaign );

    // Track Log
    $sql = "";
    for ($i = 0 ; $i < count( $emailList ); $i++) {
        $sql .= "( $marketingAuto, '".$emailList[$i]."', now(), now() )";
        if ($i == count( $emailList ) - 1) {
            $sql .=";";
        } else {
            $sql .=",";
        }
    }
    $sql = "insert into tbl_marketing_auto_history(marketing_auto, destination, created_time, updated_time)
            values $sql";
    $db->queryInsert($sql);
}

// 2014-07-08 : Author Jeni
// Description : Send Bulk SMS & Log the SMS Sending history
function MT_sendSMSAutomationBulk( $marketingAuto, $ownerId, $smsContent, $phoneList, $planGroupCode ){
    // Credits Manage
    global $db;
    MT_creditsCalculation( $ownerId, count($phoneList) * CREDITS_SMS);

    $sql = "select *
              from tbl_marketing_auto
             where marketing_auto = $marketingAuto";
    $dataMarketingAuto = $db->queryArray($sql);
    $dataMarketingAuto = $dataMarketingAuto[0];
    $smsTitle = $dataMarketingAuto['title'];

    // Send Bulk SMS
    MT_sendBulkSMS( $phoneList, $smsTitle, $smsContent );

    // Track Log
    $sql = "";
    for ($i = 0 ; $i < count( $phoneList ); $i++) {
        $sql .= "( $marketingAuto, '".$phoneList[$i]."', now(), now() )";
        if ($i == count( $phoneList ) - 1)
            $sql .=";";
        else
            $sql .=",";
    }
    $sql = "insert into tbl_marketing_auto_history( marketing_auto, destination, created_time, updated_time )
            values $sql";
    $db->queryInsert($sql);
}

function MT_sendBulkSMS( $phoneList, $smsTitle, $smsContent ){
    for ($i = 0 ; $i < count( $phoneList ); $i++) {
        MT_sendSMS($phoneList[$i], $smsTitle, $smsContent);
    }
}

// 2014-07-08 : Author Jeni
// Description : extract Email List for Automation & call function for Bulk Email Sending
function MT_sendEmailAutomation( $marketingAuto, $ownerId, $emailCampaign, $pluginType, $cntPreviousBooking, $daysPreviousBooking ){
    $emailList = array( );
    if ($cntPreviousBooking != "") {
        $emailList = MT_getEmailListByCountPreviousBooking( $ownerId, $cntPreviousBooking, $pluginType );
    } else {
        $emailList = MT_getEmailListByDaysPreviousBooking( $ownerId, $daysPreviousBooking, $pluginType );
    }
    MT_sendEmailAutomationBulk( $marketingAuto, $ownerId, $emailCampaign, $emailList, $pluginType);
}

// 2014-07-08 : Author Jeni
// Description : extract SMS List for Automation & call function for Bulk SMS Sending
function MT_sendSMSAutomation( $marketingAuto, $ownerId, $smsContent, $pluginType, $cntPreviousBooking, $daysPreviousBooking ){
    $phoneList = array( );
    if ($cntPreviousBooking != "") {
        $phoneList = MT_getPhoneListByCountPreviousBooking( $ownerId, $cntPreviousBooking, $pluginType );
    } else {
        $phoneList = MT_getPhoneListByDaysPreviousBooking( $ownerId, $daysPreviousBooking, $pluginType );
    }
    MT_sendSMSAutomationBulk( $marketingAuto, $ownerId, $smsContent, $phoneList, $pluginType);
}
?>
