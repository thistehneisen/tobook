<?php
@session_start();
require_once realpath(__DIR__.'/../../Bridge.php');

if (!Bridge::hasOwnerId()) {
    @session_destroy();
    echo <<< JS
<script>
window.parent.location = '/business/auth/login';
</script>
JS;
    exit;
}

$varaaDb = Bridge::dbConfig();

$owner_id = intval($_SESSION['owner_id']);
$dns = sprintf("mysql:dbname=%s;host=%s", $varaaDb['database'], $varaaDb['host']);
$dbh = new PDO($dns, $varaaDb['username'], $varaaDb['password']);

try {
    // Check user existence first
    $sql = 'SELECT * FROM `rb_users` WHERE `owner_id` = ?';
    $stm = $dbh->prepare($sql);
    $stm->execute([$owner_id]);
    $user = $stm->fetch();
    if ($user) {
        $stm->closeCursor();
        // Cancel installation
        header("location: session.php");
        return;
    }
    $stm->closeCursor();

    $sql_user = <<<SQL
SELECT vuser_email, vuser_login, vuser_password INTO @user_email, @username, @password FROM tbl_user_mast WHERE nuser_id = %1\$d;

INSERT INTO `rb_users` (`owner_id`,`role_id`, `email`, `password`, `name`, `created`, `last_login`, `status`) VALUES
(%1\$d, 1, @user_email, @password, @username, NOW(), NOW(), 'T');
SQL;

    $sth = $dbh->prepare(sprintf($sql_user, $owner_id));
    $sth->execute();
    $sth->closeCursor();

    $sql_opt = <<<SQL
INSERT INTO `rb_options` (`owner_id`,`key`,`tab_id`, `group`, `value`, `description`, `label`, `type`, `order`, `style`, `is_visible`) VALUES
(%1\$d,'cm_include_address', 7, NULL, '1|2::1', 'Address', 'No|Yes', 'enum', 7, NULL, 1),
(%1\$d,'cm_include_city', 7, NULL, '1|2::1', 'City', 'No|Yes', 'enum', 12, NULL, 1),
(%1\$d,'cm_include_company', 7, NULL, '1|2::1', 'Company', 'No|Yes', 'enum', 6, NULL, 1),
(%1\$d,'cm_include_country', 7, NULL, '1|2::1', 'Country', 'No|Yes', 'enum', 15, NULL, 1),
(%1\$d,'cm_include_email', 7, NULL, '1|2::2', 'E-Mail address', 'No|Yes', 'enum', 5, NULL, 1),
(%1\$d,'cm_include_fname', 7, NULL, '1|2::2', 'First Name', 'No|Yes', 'enum', 2, NULL, 1),
(%1\$d,'cm_include_lname', 7, NULL, '1|2::2', 'Last Name', 'No|Yes', 'enum', 3, NULL, 1),
(%1\$d,'cm_include_phone', 7, NULL, '1|2::2', 'Phone', 'No|Yes', 'enum', 4, NULL, 1),
(%1\$d,'cm_include_state', 7, NULL, '1|2::1', 'State', 'No|Yes', 'enum', 13, NULL, 1),
(%1\$d,'cm_include_title', 7, NULL, '1|2::1', 'Title', 'No|Yes', 'enum', 1, NULL, 1),
(%1\$d,'cm_include_zip', 7, NULL, '1|2::1', 'Zip', 'No|Yes', 'enum', 14, NULL, 1),
(%1\$d,'cm_include_count', 7, NULL, '1|2::2', 'Count', 'No|Yes', 'enum', 17, NULL, 1),
(%1\$d,'bf_include_address', 4, NULL, '1|2|3::1', 'Address&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 7, NULL, 1),
(%1\$d,'bf_include_captcha', 4, NULL, '1|3::3', 'Captcha&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes (Required)', 'enum', 16, NULL, 1),
(%1\$d,'bf_include_city', 4, NULL, '1|2|3::1', 'City&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 12, NULL, 1),
(%1\$d,'bf_include_company', 4, NULL, '1|2|3::1', 'Company&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 6, NULL, 1),
(%1\$d,'bf_include_country', 4, NULL, '1|2|3::1', 'Country&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 15, NULL, 1),
(%1\$d,'bf_include_email', 4, NULL, '1|2|3::3', 'E-Mail address&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 5, NULL, 1),
(%1\$d,'bf_include_fname', 4, NULL, '1|2|3::3', 'First Name&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 2, NULL, 1),
(%1\$d,'bf_include_lname', 4, NULL, '1|2|3::3', 'Last Name&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 3, NULL, 1),
(%1\$d,'bf_include_notes', 4, NULL, '1|2|3::1', 'Notes&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 8, NULL, 1),
(%1\$d,'bf_include_phone', 4, NULL, '1|2|3::3', 'Phone&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 4, NULL, 1),
(%1\$d,'bf_include_promo', 4, NULL, '1|2|3::2', 'Voucher&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 11, NULL, 1),
(%1\$d,'bf_include_state', 4, NULL, '1|2|3::1', 'State&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 13, NULL, 1),
(%1\$d,'bf_include_title', 4, NULL, '1|2|3::3', 'Title&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 1, NULL, 1),
(%1\$d,'bf_include_zip', 4, NULL, '1|2|3::1', 'Zip&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 14, NULL, 1),
(%1\$d,'booking_earlier', 2, NULL, '2', 'Book X hours earlier&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;how many hours earlier, you can book a table&lt;/span&gt;', NULL, 'int', 1, NULL, 1),
(%1\$d,'booking_length', 2, NULL, '180', 'Booking length&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set the default booking length, values in minutes&lt;/span&gt;', NULL, 'int', 1, NULL, 1),
(%1\$d,'booking_price', 2, NULL, '50', 'Booking price&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set default booking price&lt;/span&gt;', NULL, 'float', 1, NULL, 1),
(%1\$d,'booking_group_booking', 2, NULL, '10', 'Group booking', NULL, 'int', 1, NULL, 1),
(%1\$d,'booking_front_end', 2, NULL, '1|2::1', 'Booking front end', 'Time|Category', 'enum', 1, NULL, 1),
(%1\$d,'booking_status', 2, NULL, 'confirmed|pending|cancelled::pending', 'Default booking status&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set the default status for each booking after it is made&lt;/span&gt;', NULL, 'enum', 5, NULL, 1),
(%1\$d,'currency', 1, NULL, 'USD|GPB|EUR::USD', 'Currency', NULL, 'enum', 1, NULL, 1),
(%1\$d,'datetime_format', 1, NULL, 'd.m.Y, H:i|d.m.Y, H:i:s|m.d.Y, H:i|m.d.Y, H:i:s|Y.m.d, H:i|Y.m.d, H:i:s|j.n.Y, H:i|j.n.Y, H:i:s|n.j.Y, H:i|n.j.Y, H:i:s|Y.n.j, H:i|Y.n.j, H:i:s|d/m/Y, H:i|d/m/Y, H:i:s|m/d/Y, H:i|m/d/Y, H:i:s|Y/m/d, H:i|Y/m/d, H:i:s|j/n/Y, H:i|j/n/Y, H:i:s|n/j/Y, H:i|n/j/Y, H:i:s|Y/n/j, H:i|Y/n/j, H:i:s|d-m-Y, H:i|d-m-Y, H:i:s|m-d-Y, H:i|m-d-Y, H:i:s|Y-m-d, H:i|Y-m-d, H:i:s|j-n-Y, H:i|j-n-Y, H:i:s|n-j-Y, H:i|n-j-Y, H:i:s|Y-n-j, H:i|Y-n-j, H:i:s::j/n/Y, H:i', 'Date/Time format', 'd.m.Y, H:i (25.09.2010, 09:51)|d.m.Y, H:i:s (25.09.2010, 09:51:47)|m.d.Y, H:i (09.25.2010, 09:51)|m.d.Y, H:i:s (09.25.2010, 09:51:47)|Y.m.d, H:i (2010.09.25, 09:51)|Y.m.d, H:i:s (2010.09.25, 09:51:47)|j.n.Y, H:i (25.9.2010, 09:51)|j.n.Y, H:i:s (25.9.2010, 09:51:47)|n.j.Y, H:i (9.25.2010, 09:51)|n.j.Y, H:i:s (9.25.2010, 09:51:47)|Y.n.j, H:i (2010.9.25, 09:51)|Y.n.j, H:i:s (2010.9.25, 09:51:47)|d/m/Y, H:i (25/09/2010, 09:51)|d/m/Y, H:i:s (25/09/2010, 09:51:47)|m/d/Y, H:i (09/25/2010, 09:51)|m/d/Y, H:i:s (09/25/2010, 09:51:47)|Y/m/d, H:i (2010/09/25, 09:51)|Y/m/d, H:i:s (2010/09/25, 09:51:47)|j/n/Y, H:i (25/9/2010, 09:51)|j/n/Y, H:i:s (25/9/2010, 09:51:47)|n/j/Y, H:i (9/25/2010, 09:51)|n/j/Y, H:i:s (9/25/2010, 09:51:47)|Y/n/j, H:i (2010/9/25, 09:51)|Y/n/j, H:i:s (2010/9/25, 09:51:47)|d-m-Y, H:i (25-09-2010, 09:51)|d-m-Y, H:i:s (25-09-2010, 09:51:47)|m-d-Y, H:i (09-25-2010, 09:51)|m-d-Y, H:i:s (09-25-2010, 09:51:47)|Y-m-d, H:i (2010-09-25, 09:51)|Y-m-d, H:i:s (2010-09-25, 09:51:47)|j-n-Y, H:i (25-9-2010, 09:51)|j-n-Y, H:i:s (25-9-2010, 09:51:47)|n-j-Y, H:i (9-25-2010, 09:51)|n-j-Y, H:i:s (9-25-2010, 09:51:47)|Y-n-j, H:i (2010-9-25, 09:51)|Y-n-j, H:i:s (2010-9-25, 09:51:47)', 'enum', 3, NULL, 1),
(%1\$d,'date_format', 1, NULL, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::j/n/Y', 'Date format', 'd.m.Y (25.09.2010)|m.d.Y (09.25.2010)|Y.m.d (2010.09.25)|j.n.Y (25.9.2010)|n.j.Y (9.25.2010)|Y.n.j (2010.9.25)|d/m/Y (25/09/2010)|m/d/Y (09/25/2010)|Y/m/d (2010/09/25)|j/n/Y (25/9/2010)|n/j/Y (9/25/2010)|Y/n/j (2010/9/25)|d-m-Y (25-09-2010)|m-d-Y (09-25-2010)|Y-m-d (2010-09-25)|j-n-Y (25-9-2010)|n-j-Y (9-25-2010)|Y-n-j (2010-9-25)', 'enum', 2, NULL, 1),
(%1\$d,'db_version', 99, NULL, '1.0.0', 'Database version', NULL, 'string', NULL, NULL, 0),
(%1\$d,'email_address', 3, NULL, 'info@domain.com', 'Notification email address', NULL, 'string', 1, NULL, 1),
(%1\$d,'email_confirmation', 3, NULL, '1|2|3::2', 'Send confirmation email&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select if and when confirmation email should be sent to clients after they make a booking&lt;/span&gt;', 'None|After booking form|After payment', 'enum', 2, NULL, 1),
(%1\$d,'email_confirmation_message', 3, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nBooking details:\r\nDate/Time From: {DtFrom}\r\nTable: {Table}\r\nPeople: {People}\r\nBooking ID: {BookingID}\r\nUnique ID: {UniqueID}\r\nTotal: {Total}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Confirmation email&lt;br /&gt;\n&lt;u&gt;Available Tokens:&lt;/u&gt;&lt;br /&gt;\n{Title}&lt;br /&gt;\n{FirstName}&lt;br /&gt;\n{LastName}&lt;br /&gt;\n{Email}&lt;br /&gt;\n{Phone}&lt;br /&gt;\n{Notes}&lt;br /&gt;\n{Country}&lt;br /&gt;\n{City}&lt;br /&gt;\n{State}&lt;br /&gt;\n{Zip}&lt;br /&gt;\n{Address}&lt;br /&gt;\n{Company}&lt;br /&gt;\n{DtFrom}&lt;br /&gt;\n{Table}&lt;br /&gt;\n{People}&lt;br /&gt;\n{BookingID}&lt;br /&gt;\n{UniqueID}&lt;br /&gt;\n{Total}&lt;br /&gt;\n{PaymentMethod}&lt;br /&gt;\n{CCType}&lt;br /&gt;\n{CCNum}&lt;br /&gt;\n{CCExp}&lt;br /&gt;\n{CCSec}&lt;br /&gt;\n{CancelURL}&lt;br /&gt;', NULL, 'text', 4, 'height: 465px', 1),
(%1\$d,'email_confirmation_subject', 3, NULL, 'Confirmation message', 'Confirmation email subject', NULL, 'string', 3, NULL, 1),
(%1\$d,'email_enquiry', 3, NULL, '1|4::4', 'Send enquiry email&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select if and when confirmation email should be sent to clients after they make a enquiry for  booking&lt;/span&gt;', 'None|After booking form', 'enum', 8, NULL, 1),
(%1\$d,'email_enquiry_message', 3, NULL, 'You''ve just made a enquiry.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nEnquiry details:\r\nDate/Time From: {DtFrom}\r\nPeople: {People}\r\nUnique ID: {UniqueID}\r\n\r\nIf you want to cancel your enquiry follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Enquiry email&lt;br /&gt;\r\n&lt;u&gt;Available Tokens:&lt;/u&gt;&lt;br /&gt;\r\n{Title}&lt;br /&gt;\r\n{FirstName}&lt;br /&gt;\r\n{LastName}&lt;br /&gt;\r\n{Email}&lt;br /&gt;\r\n{Phone}&lt;br /&gt;\r\n{Notes}&lt;br /&gt;\r\n{Country}&lt;br /&gt;\r\n{City}&lt;br /&gt;\r\n{State}&lt;br /&gt;\r\n{Zip}&lt;br /&gt;\r\n{Address}&lt;br /&gt;\r\n{Company}&lt;br /&gt;\r\n{DtFrom}&lt;br /&gt;\r\n{People}&lt;br /&gt;\r\n{UniqueID}&lt;br /&gt;\r\n{CancelURL}&lt;br /&gt;\r\n', NULL, 'text', 10, 'height: 420px', 1),
(%1\$d,'email_enquiry_subject', 3, NULL, 'Enquiry message', 'Enquiry email subject', NULL, 'string', 9, NULL, 1),
(%1\$d,'email_payment', 3, NULL, '1|3::1', 'Send payment confirmation email&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select if and when confirmation email should be sent to clients after they make a payment for their booking&lt;/span&gt;', 'None|After payment', 'enum', 5, NULL, 1),
(%1\$d,'email_payment_message', 3, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nBooking details:\r\nDate/Time From: {DtFrom}\r\nTable: {Table}\r\nPeople: {People}\r\nBooking ID: {BookingID}\r\nUnique ID: {UniqueID}\r\nTotal: {Total}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Payment confirmation email&lt;br /&gt;\n&lt;u&gt;Available Tokens:&lt;/u&gt;&lt;br /&gt;\n{Title}&lt;br /&gt;\n{FirstName}&lt;br /&gt;\n{LastName}&lt;br /&gt;\n{Email}&lt;br /&gt;\n{Phone}&lt;br /&gt;\n{Notes}&lt;br /&gt;\n{Country}&lt;br /&gt;\n{City}&lt;br /&gt;\n{State}&lt;br /&gt;\n{Zip}&lt;br /&gt;\n{Address}&lt;br /&gt;\n{Company}&lt;br /&gt;\n{DtFrom}&lt;br /&gt;\n{Table}&lt;br /&gt;\n{People}&lt;br /&gt;\n{BookingID}&lt;br /&gt;\n{UniqueID}&lt;br /&gt;\n{Total}&lt;br /&gt;\n{PaymentMethod}&lt;br /&gt;\n{CCType}&lt;br /&gt;\n{CCNum}&lt;br /&gt;\n{CCExp}&lt;br /&gt;\n{CCSec}&lt;br /&gt;\n{CancelURL}&lt;br /&gt;', NULL, 'text', 7, 'height: 465px', 1),
(%1\$d,'email_payment_subject', 3, NULL, 'Payment message', 'Payment email subject', NULL, 'string', 6, NULL, 1),
(%1\$d,'enquiry', 5, NULL , 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eu ipsum consectetur arcu commodo egestas nec eu ante. Aenean nec enim lorem. Proin accumsan luctus luctus. Vivamus pulvinar mollis orci, id convallis eros ultricies vel. Nullam adipiscing, risus non pellentesque aliquam, nibh ligula dictum justo, quis commodo nisi dolor ut nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ante leo, ultricies quis gravida id, vestibulum nec risus. Mauris adipiscing vestibulum nibh non ullamcorper. Suspendisse justo turpis, mattis a cursus ac, vulputate quis metus. Fusce vestibulum faucibus dignissim. Aliquam fermentum mauris felis, a ultrices sem.', 'Enquiry', NULL , 'text', 2, 'height: 300px', 1),
(%1\$d,'payment_authorize_key', 2, NULL, '', 'Authorize.net transaction key', NULL, 'string', 34, NULL, 1),
(%1\$d,'payment_authorize_mid', 2, NULL, '', 'Authorize.net merchant ID', NULL, 'string', 35, NULL, 1),
(%1\$d,'payment_disable', 2, NULL, 'No|Yes::No', 'Disable payments&lt;br /&gt;&lt;span style=&quot;font-size: 0.9em&quot;&gt;to disable payments and only accept bookings, set this to &quot;Yes&quot;&lt;/span&gt;', NULL, 'enum', 30, NULL, 1),
(%1\$d,'payment_enable_authorize', 2, NULL, 'Yes|No::No', 'Allow Authorize.net payments', NULL, 'enum', 33, NULL, 1),
(%1\$d,'payment_enable_cash', 2, NULL, 'Yes|No::Yes', 'Allow Cash payments', NULL, 'enum', 37, NULL, 1),
(%1\$d,'payment_enable_creditcard', 2, NULL, 'Yes|No::Yes', 'Allow payments with Credit cards', NULL, 'enum', 38, NULL, 1),
(%1\$d,'payment_enable_paypal', 2, NULL, 'Yes|No::Yes', 'Allow PayPal payments', NULL, 'enum', 31, NULL, 1),
(%1\$d,'payment_status', 2, NULL, 'confirmed|pending|cancelled::confirmed', 'Default booking status after payment&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set the default status for each booking after payment is made for it&lt;/span&gt;', NULL, 'enum', 6, NULL, 1),
(%1\$d,'paypal_address', 2, NULL, 'paypal@domain.com', 'PayPal business email address', NULL, 'string', 32, NULL, 1),
(%1\$d,'reminder_body', 6, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nBooking details:\r\nDate/Time From: {DtFrom}\r\nTable: {Table}\r\nPeople: {People}\r\nBooking ID: {BookingID}\r\nUnique ID: {UniqueID}\r\nTotal: {Total}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Email Reminder body<br />\n<u>Available Tokens:</u><br />\n{Title}<br />\n{FirstName}<br />\n{LastName}<br />\n{Email}<br />\n{Phone}<br />\n{Notes}<br />\n{Country}<br />\n{City}<br />\n{State}<br />\n{Zip}<br />\n{Address}<br />\n{Company}<br />\n{DtFrom}<br />\n{Table}<br />\n{People}<br />\n{BookingID}<br />\n{UniqueID}<br />\n{Total}<br />\n{PaymentMethod}<br />\n{CCType}<br />\n{CCNum}<br />\n{CCExp}<br />\n{CCSec}<br />\n{CancelURL}<br />', NULL, 'text', 4, 'height:405px;', 1),
(%1\$d,'reminder_email_before', 6, NULL, '2', 'Send email reminder', NULL, 'int', 2, NULL, 1),
(%1\$d,'reminder_enable', 6, NULL, 'Yes|No::Yes', 'Enable notifications', NULL, 'enum', 1, NULL, 1),
(%1\$d,'reminder_sms_country_code', 6, NULL, '358', 'SMS country code', NULL, 'int', 6, NULL, 1),
(%1\$d,'reminder_sms_hours', 6, NULL, '1', 'Send SMS reminder', NULL, 'int', 5, NULL, 1),
(%1\$d,'reminder_sms_message', 6, NULL, '{FirstName}, booking reminder\r\n\r\n{Table}', 'SMS message<br />\n<u>Available Tokens:</u><br />\n{Title}<br />\n{FirstName}<br />\n{LastName}<br />\n{Email}<br />\n{Phone}<br />\n{Notes}<br />\n{Country}<br />\n{City}<br />\n{State}<br />\n{Zip}<br />\n{Address}<br />\n{Company}<br />\n{DtFrom}<br />\n{Table}<br />\n{People}<br />\n{BookingID}<br />\n{UniqueID}<br />\n{Total}<br />\n{PaymentMethod}<br />\n{CCType}<br />\n{CCNum}<br />\n{CCExp}<br />\n{CCSec}<br />\n{CancelURL}<br />', NULL, 'text', 7, 'height:380px;', 1),
(%1\$d,'reminder_subject', 6, NULL, 'Booking Reminder', 'Email Reminder subject', NULL, 'string', 3, NULL, 1),
(%1\$d,'terms', 5, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eu ipsum consectetur arcu commodo egestas nec eu ante. Aenean nec enim lorem. Proin accumsan luctus luctus. Vivamus pulvinar mollis orci, id convallis eros ultricies vel. Nullam adipiscing, risus non pellentesque aliquam, nibh ligula dictum justo, quis commodo nisi dolor ut nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ante leo, ultricies quis gravida id, vestibulum nec risus. Mauris adipiscing vestibulum nibh non ullamcorper. Suspendisse justo turpis, mattis a cursus ac, vulputate quis metus. Fusce vestibulum faucibus dignissim. Aliquam fermentum mauris felis, a ultrices sem.\r\n\r\nSuspendisse porttitor, odio eget eleifend aliquet, nibh urna placerat lacus, a rhoncus metus metus et lectus. Fusce convallis nunc dignissim magna condimentum sed lobortis nibh faucibus. Vivamus gravida libero et elit sagittis vel dignissim erat euismod. Nullam quam mi, mollis non feugiat et, facilisis eget sapien. Pellentesque sapien enim, dictum sit amet tincidunt eget, mollis et velit. Aenean scelerisque sem quis eros imperdiet et interdum nunc pellentesque. Morbi consectetur mauris sed sapien tristique eget malesuada tellus suscipit. Praesent aliquam hendrerit purus et vestibulum. Pellentesque dictum lorem velit, id semper tortor. ', 'Terms &amp;amp; Conditions', NULL, 'text', 1, 'height: 400px', 1),
(%1\$d,'thank_you_page', 2, NULL, 'http://varaa.com/', '&quot;Thank you&quot; page location&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;this is the page where people will be redirected after paying&lt;/span&gt;', NULL, 'string', 7, NULL, 1),
(%1\$d,'timezone', 1, NULL, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'Timezone&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select your time zone so booking interval can be limited based on your time zone&lt;/span&gt;', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 5, NULL, 1),
(%1\$d,'time_format', 1, NULL, 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'Time format', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', 4, NULL, 1),
(%1\$d,'use_map', 99, NULL, '1|0::1', 'Use seat map', 'Yes|No', 'bool', NULL, NULL, 0);
SQL;
    $sth = $dbh->prepare(sprintf($sql_opt, $owner_id));
    $sth->execute();
} catch(PDOException $e) {
    echo $e->getMessage();
}

header("location: session.php");
