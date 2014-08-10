<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");
require_once("common/config.php");

$result = "success";
$error = "";
$data = array();

$subject = mysql_escape_string($_POST['name']);
$content = mysql_escape_string($_POST['content']);
$thumb = mysql_escape_string($_POST['thumb']);
$templateId = mysql_escape_string($_POST['templateId']);

if ($thumb == "")
    $thumb = DEFAULT_TEMPLATE_EMAIL;
if ($templateId == "") {
    $sql = "insert into tbl_email_template(thumbnail, subject, content, created_time, updated_time)
             value ('$thumb', '$subject', '$content', now(), now())";
    $db->queryInsert( $sql );
} else {
    $sql = "update tbl_email_template
               set thumbnail = '$thumb'
                 , subject = '$subject'
                 , content = '$content'
                 , updated_time = now()
             where email_template = $templateId";
    $db->query($sql);
}

$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>