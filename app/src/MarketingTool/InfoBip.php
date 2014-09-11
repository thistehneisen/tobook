<?php
namespace App\MarketingTool;

class InfoBip {
    // InfoBip account credentials
    private $infobip_username = '';
    private $infobip_password = '';

    public function InfoBip($infobip_username, $infobip_password)
    {
        $this->infobip_username = $infobip_username;
        $this->infobip_password = $infobip_password;
    }

    public function send_sms_infobip($sender_name, $recipient_no_with_plus, $sms_body, $msg_id="")
    {
        if (substr($recipient_no_with_plus,0,1)!="+") {
            $recipient_no_with_plus = "+".$recipient_no_with_plus;
        }
        $msg_id = ($msg_id=="") ? time() : $msg_id;
        $postUrl = "http://api.infobip.com/api/v3/sendsms/xml";

        //  XML-formatted data
        $infobip_username=$this->infobip_username;
        $infobip_password=$this->infobip_password;
        $no_of_sms_will_send=ceil(strlen($sms_body)/160);
        for ($i = 0; $i < $no_of_sms_will_send; $i++) {
            $sms_part = substr($sms_body,0,160);
            if (strlen($sms_body) > 160) {
                $sms_body = substr($sms_body,160);
            }
            $xmlString = "
            <SMS>
                <authentification>
                    <username>$infobip_username</username>
                    <password>$infobip_password</password>
                </authentification>
                <message>
                    <sender>$sender_name</sender>
                    <text>$sms_part</text>
                    <flash></flash>
                    <type></type>
                    <wapurl></wapurl>
                    <binary></binary>
                    <datacoding></datacoding>
                    <esmclass></esmclass>
                    <srcton></srcton>
                    <srcnpi></srcnpi>
                    <destton></destton>
                    <destnpi></destnpi>
                    <ValidityPeriod>23:59</ValidityPeriod>
                </message>
                <recipients>
                    <gsm messageId=\"$msg_id\">$recipient_no_with_plus</gsm>
                </recipients>
            </SMS>";
            //  previously formatted XML data becomes value of "XML" POST variable
            $fields  = "XML=" . urlencode($xmlString);

            //  in this example, POST request was made using PHP's CURL
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $postUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $fields);

            //  response of the POST request
            $response = curl_exec($ch);
            curl_close($ch);
        }
        return $response;
    }
}
