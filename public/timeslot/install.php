<?php
    require_once realpath(__DIR__.'/../../Bridge.php');
    $varaaDb = Bridge::dbConfig();

    @session_start();
    error_reporting(E_ALL);
    $dns = sprintf("mysql:dbname=%s;host=%s", $varaaDb['database'], $varaaDb['host']);
    $pdo = new PDO($dns, $varaaDb['username'], $varaaDb['password']);
    $owner_id = intval($_SESSION['owner_id']);

    $sql = <<< SQL
        INSERT INTO ts_users(role_id, owner_id, session_id, username, password, created) value(1,:owner_id, :session_id,'admin', 'admin123', now());
        SELECT LAST_INSERT_ID() INTO @user_id;
        INSERT INTO `ts_calendars` (`id`, `user_id`,`calendar_title`,`owner_id`) VALUES (NULL,@user_id, 'Calendar 1', :owner_id);
        SELECT LAST_INSERT_ID() INTO @calendar_id;
        INSERT INTO `ts_options` (`calendar_id`, `owner_id`,`key`, `tab_id`, `group`, `value`, `description`, `label`, `type`, `order`) VALUES
(@calendar_id, :owner_id , 'bf_include_address', 5, NULL, '1|2|3::2', 'Address<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 7),
(@calendar_id, :owner_id , 'bf_include_captcha', 5, NULL, '1|2|3::3', 'Captcha<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 9),
(@calendar_id, :owner_id , 'bf_include_city', 5, NULL, '1|2|3::2', 'City<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 6),
(@calendar_id, :owner_id , 'bf_include_country', 5, NULL, '1|2|3::3', 'Country<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 5),
(@calendar_id, :owner_id , 'bf_include_email', 5, NULL, '1|2|3::3', 'E-Mail address<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 2),
(@calendar_id, :owner_id , 'bf_include_name', 5, NULL, '1|2|3::3', 'Name<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 1),
(@calendar_id, :owner_id , 'bf_include_notes', 5, NULL, '1|2|3::2', 'Notes<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 4),
(@calendar_id, :owner_id , 'bf_include_phone', 5, NULL, '1|2|3::2', 'Phone<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 3),
(@calendar_id, :owner_id , 'bf_include_zip', 5, NULL, '1|2|3::3', 'Zip<br />\r\n<span style="font-size: 0.9em">Select "Yes" if you want to include the field in the booking form, otherwise select "No"</span>', 'No|Yes|Yes (Required)', 'enum', 8),
(@calendar_id, :owner_id , 'booking_status', 3, NULL, 'confirmed|pending|cancelled::pending', 'Default booking status<br />\r\n<span style="font-size: 0.9em">set the default status for each booking after it is made</span>', NULL, 'enum', 5),
(@calendar_id, :owner_id , 'calendar_height', 2, NULL, '400', 'Calendar height (px)', NULL, 'int', 2),
(@calendar_id, :owner_id , 'calendar_width', 2, NULL, '500', 'Calendar width (px)', NULL, 'int', 1),
(@calendar_id, :owner_id , 'color_bg_dayoff', 2, 'colors', 'a37ca3', 'Day off background', NULL, 'color', 211),
(@calendar_id, :owner_id , 'color_bg_empty_cells', 2, 'colors', 'ededed', 'Empty slots', NULL, 'color', 205),
(@calendar_id, :owner_id , 'color_bg_form', 2, 'colors', 'ffffff', 'Booking form background', NULL, 'color', 206),
(@calendar_id, :owner_id , 'color_bg_full', 2, 'colors', 'd60d3f', 'Fully booked days', NULL, 'color', 207),
(@calendar_id, :owner_id , 'color_bg_legend', 2, 'colors', 'ffffff', 'Legend background', NULL, 'color', 208),
(@calendar_id, :owner_id , 'color_bg_month', 2, 'colors', '000000', 'Month background<br />\r\n<span style="font-size: 0.9em">month name printed on top of the calendar</span>', NULL, 'color', 201),
(@calendar_id, :owner_id , 'color_bg_slot', 2, 'colors', '58a222', 'Days with slots background', NULL, 'color', 203),
(@calendar_id, :owner_id , 'color_bg_today', 2, 'colors', 'cc6600', 'Current date', NULL, 'color', 209),
(@calendar_id, :owner_id , 'color_bg_tooltip', 2, 'colors', '000000', 'Tooltip background color', NULL, 'color', 210),
(@calendar_id, :owner_id , 'color_bg_weekday', 2, 'colors', '3d3c3d', 'Week days background', NULL, 'color', 202),
(@calendar_id, :owner_id , 'color_bg_partly', 2, 'colors', 'FFCC00', 'Partly booked days background', NULL, 'color', 204),
(@calendar_id, :owner_id , 'color_bg_past', 2, 'colors', 'cfcfcf', 'Past days background', NULL, 'color', 212),
(@calendar_id, :owner_id , 'color_border_form', 2, 'colors', 'ffffff', 'Form border color', NULL, 'color', 270),
(@calendar_id, :owner_id , 'color_border_inner', 2, 'colors', 'ffffff', 'Inner border color', NULL, 'color', 271),
(@calendar_id, :owner_id , 'color_border_legend', 2, 'colors', 'ffffff', 'Legend border color', NULL, 'color', 272),
(@calendar_id, :owner_id , 'color_border_outer', 2, 'colors', 'ffffff', 'Outer border color', NULL, 'color', 273),
(@calendar_id, :owner_id , 'color_font_day', 2, 'colors', 'ffffff', 'Days  font color', NULL, 'color', 240),
(@calendar_id, :owner_id , 'color_font_dayoff', 2, 'colors', 'ffffff', 'Day off font color', NULL, 'color', 247),
(@calendar_id, :owner_id , 'color_font_event', 2, 'colors', 'ffffff', 'Events font color', NULL, 'color', 241),
(@calendar_id, :owner_id , 'color_font_form', 2, 'colors', '000000', 'Form font color', NULL, 'color', 242),
(@calendar_id, :owner_id , 'color_font_legend', 2, 'colors', '000000', 'Legend font color', NULL, 'color', 243),
(@calendar_id, :owner_id , 'color_font_month', 2, 'colors', 'ffffff', 'Month font color', NULL, 'color', 244),
(@calendar_id, :owner_id , 'color_font_tooltip', 2, 'colors', 'ffffff', 'Tooltip font color', NULL, 'color', 245),
(@calendar_id, :owner_id , 'color_font_weekday', 2, 'colors', 'ffffff', 'Week days font color', NULL, 'color', 246),
(@calendar_id, :owner_id , 'color_font_partly', 2, 'colors', '000000', 'Partly booked days font color', NULL, 'color', 248),
(@calendar_id, :owner_id , 'color_font_past', 2, 'colors', 'ffffff', 'Past days font color', NULL, 'color', 249),
(@calendar_id, :owner_id , 'color_legend', 1, NULL, 'Yes|No::Yes', 'Show color legend below calendar', NULL, 'enum', 7),
(@calendar_id, :owner_id , 'currency', 3, NULL, 'AUD|BRL|CAD|CZK|DKK|EUR|HKD|HUF|ILS|JPY|MYR|MXN|NOK|NZD|PHP|PLN|GBP|SGD|SEK|CHF|TWD|THB|USD::USD', 'Currency', NULL, 'enum', 1),
(@calendar_id, :owner_id , 'date_format', 1, NULL, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d.m.Y', 'Date format', 'd.m.Y (25.09.2010)|m.d.Y (09.25.2010)|Y.m.d (2010.09.25)|j.n.Y (25.9.2010)|n.j.Y (9.25.2010)|Y.n.j (2010.9.25)|d/m/Y (25/09/2010)|m/d/Y (09/25/2010)|Y/m/d (2010/09/25)|j/n/Y (25/9/2010)|n/j/Y (9/25/2010)|Y/n/j (2010/9/25)|d-m-Y (25-09-2010)|m-d-Y (09-25-2010)|Y-m-d (2010-09-25)|j-n-Y (25-9-2010)|n-j-Y (9-25-2010)|Y-n-j (2010-9-25)', 'enum', 5),
(@calendar_id, :owner_id , 'deposit_percent', 3, NULL, '10', 'Deposit payment %<br />\r\n<span style="font-size: 0.9em">set % of the booking amount to be paid as a deposit. For full payment enter 100</span>', NULL, 'int', 2),
(@calendar_id, :owner_id , 'email_address', 4, NULL, 'info@domain.com', 'Notification email address', NULL, 'string', 1),
(@calendar_id, :owner_id , 'email_confirmation', 4, NULL, '1|2|3::2', 'Send confirmation email<br />\r\n<span style="font-size: 0.9em">select if and when confirmation email should be sent to clients after they make a booking</span>', 'None|After booking form|After payment', 'enum', 2),
(@calendar_id, :owner_id , 'email_confirmation_message', 4, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nName: {Name}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nCountry: {Country}\r\nCity: {City}\r\nAddress: {Address}\r\nZip: {Zip}\r\nNotes: {Notes}\r\n\r\nBooking details:\r\nBooking ID: {BookingID}\r\nBooking Slots: {BookingSlots}\r\nDeposit: {Deposit}\r\nTotal: {Total}\r\nTax: {Tax}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Confirmation email<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{Address}<br />\r\n{Zip}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 4),
(@calendar_id, :owner_id , 'email_confirmation_subject', 4, NULL, 'Confirmation message', 'Confirmation email subject', NULL, 'string', 3),
(@calendar_id, :owner_id , 'email_payment', 4, NULL, '1|3::1', 'Send payment confirmation email<br />\r\n<span style="font-size: 0.9em">select if and when confirmation email should be sent to clients after they make a payment for their booking</span>', 'None|After payment', 'enum', 5),
(@calendar_id, :owner_id , 'email_payment_message', 4, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nName: {Name}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nCountry: {Country}\r\nCity: {City}\r\nAddress: {Address}\r\nZip: {Zip}\r\nNotes: {Notes}\r\n\r\nBooking details:\r\nBooking ID: {BookingID}\r\nBooking Slots: {BookingSlots}\r\nDeposit: {Deposit}\r\nTotal: {Total}\r\nTax: {Tax}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Payment email<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{Address}<br />\r\n{Zip}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 7),
(@calendar_id, :owner_id , 'email_payment_subject', 4, NULL, 'Payment message', 'Payment email subject', NULL, 'string', 6),
(@calendar_id, :owner_id , 'month_year_format', 1, NULL, 'Month Year|Month, Year|Year Month|Year, Month::Month Year', 'Month / Year format', NULL, 'enum', 4),
(@calendar_id, :owner_id , 'payment_authorize_key', 3, NULL, '', 'Authorize.net transaction key', NULL, 'string', 34),
(@calendar_id, :owner_id , 'payment_authorize_mid', 3, NULL, '', 'Authorize.net merchant ID', NULL, 'string', 35),
(@calendar_id, :owner_id , 'payment_disable', 3, NULL, 'No|Yes::No', 'Disable payments<br /><span style="font-size: 0.9em">to disable payments and only accept bookings, set this to "Yes"</span>', NULL, 'enum', 30),
(@calendar_id, :owner_id , 'payment_enable_authorize', 3, NULL, 'Yes|No::No', 'Allow Authorize.net payments', NULL, 'enum', 33),
(@calendar_id, :owner_id , 'payment_enable_creditcard', 3, NULL, 'Yes|No::No', 'Allow payments with Credit cards', NULL, 'enum', 38),
(@calendar_id, :owner_id , 'payment_enable_paypal', 3, NULL, 'Yes|No::Yes', 'Allow PayPal payments', NULL, 'enum', 31),
(@calendar_id, :owner_id , 'payment_status', 3, NULL, 'confirmed|pending|cancelled::confirmed', 'Default booking status after payment<br />\r\n<span style="font-size: 0.9em">set the default status for each booking after payment is made for it</span>', NULL, 'enum', 6),
(@calendar_id, :owner_id , 'paypal_address', 3, NULL, 'paypal@domain.com', 'PayPal business email address', NULL, 'string', 32),
(@calendar_id, :owner_id , 'show_tooltip', 1, NULL, 'Yes|No::Yes', 'Show tooltip on date hover', NULL, 'enum', 2),
(@calendar_id, :owner_id , 'size_border_form', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Booking form border size', NULL, 'enum', 401),
(@calendar_id, :owner_id , 'size_border_inner', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Inner border size', NULL, 'enum', 402),
(@calendar_id, :owner_id , 'size_border_legend', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Legend border size', NULL, 'enum', 403),
(@calendar_id, :owner_id , 'size_border_outer', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Outer border size', NULL, 'enum', 404),
(@calendar_id, :owner_id , 'size_font_day', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::12', 'Days font size', NULL, 'enum', 405),
(@calendar_id, :owner_id , 'size_font_event', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::12', 'Events font size', NULL, 'enum', 406),
(@calendar_id, :owner_id , 'size_font_month', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::20', 'Month font size', NULL, 'enum', 407),
(@calendar_id, :owner_id , 'size_font_tooltip', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::10', 'Tooltip font size', NULL, 'enum', 408),
(@calendar_id, :owner_id , 'size_font_weekday', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::12', 'Week days font size', NULL, 'enum', 409),
(@calendar_id, :owner_id , 'style_font_day', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-style: italic', 'Dates font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 301),
(@calendar_id, :owner_id , 'style_font_event', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::text-decoration: underline', 'Events font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 302),
(@calendar_id, :owner_id , 'style_font_family', 2, 'fonts', 'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana::Arial', 'Font family<br />\r\n<span style="font-size: 0.9em">default font family for all the text on your calendar</span>', 'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana', 'enum', 300),
(@calendar_id, :owner_id , 'style_font_month', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: bold', 'Month font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 304),
(@calendar_id, :owner_id , 'style_font_weekday', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: normal', 'Week days font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 305),
(@calendar_id, :owner_id , 'tax', 3, NULL, '0', 'Tax payment %<br />\r\n<span style="font-size: 0.9em">set % for tax that clients pay</span>', NULL, 'float', 3),
(@calendar_id, :owner_id , 'thank_you_page', 3, NULL, 'http://www.phpjabbers.com', '"Thank you" page location<br />\r\n<span style="font-size: 0.9em">this is the page where people will be redirected after paying</span>', NULL, 'string', 7),
(@calendar_id, :owner_id , 'timezone', 1, NULL, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'Timezone<br />\r\n<span style="font-size: 0.9em">select your time zone so booking interval can be limited based on your time zone</span>', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 9),
(@calendar_id, :owner_id , 'time_format', 1, NULL, 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'Time format', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', 6),
(@calendar_id, :owner_id , 'ts_booking_form_position', 1, NULL, '1|2|3::1', 'Timeslot details and booking form position', 'Below calendar|Replace calendar|Right of calendar', 'enum', 1),
(@calendar_id, :owner_id , 'week_start', 1, NULL, '0|1|2|3|4|5|6::1', 'Week start day', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', 3),
(@calendar_id, :owner_id , 'hide_prices', 3, NULL, 'Yes|No::No', 'Hide prices', NULL, 'enum', 21),
(@calendar_id, :owner_id , 'reminder_body', 6, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nName: {Name}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nNotes: {Notes}\r\n\r\nBooking details:\r\nBooking ID: {BookingID}\r\nBooking Slots: {BookingSlots}\r\nDeposit: {Deposit}\r\nTotal: {Total}\r\nTax: {Tax}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Email Reminder body<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{State}<br />\r\n{Zip}<br />\r\n{Address}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 4),
(@calendar_id, :owner_id , 'reminder_email_before', 6, NULL, '2', 'Send email reminder', NULL, 'int', 2),
(@calendar_id, :owner_id , 'reminder_enable', 6, NULL, 'Yes|No::Yes', 'Enable notifications', NULL, 'enum', 1),
(@calendar_id, :owner_id , 'reminder_sms_api', 6, NULL, '', 'SMS api key', NULL, 'string', 6),
(@calendar_id, :owner_id , 'reminder_sms_hours', 6, NULL, '1', 'Send SMS reminder', NULL, 'int', 5),
(@calendar_id, :owner_id , 'reminder_sms_message', 6, NULL, '{Name}, booking reminder\r\n\r\n{BookingSlots}', 'SMS message<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{State}<br />\r\n{Zip}<br />\r\n{Address}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 7),
(@calendar_id, :owner_id , 'reminder_subject', 6, NULL, 'Booking Reminder', 'Email Reminder subject', NULL, 'string', 3);
        INSERT INTO `ts_working_times` (`calendar_id`, `owner_id`,`monday_from`, `monday_to`, `monday_price`, `monday_limit`, `monday_dayoff`, `tuesday_from`, `tuesday_to`, `tuesday_price`, `tuesday_limit`, `tuesday_dayoff`, `wednesday_from`, `wednesday_to`, `wednesday_price`, `wednesday_limit`, `wednesday_dayoff`, `thursday_from`, `thursday_to`, `thursday_price`, `thursday_limit`, `thursday_dayoff`, `friday_from`, `friday_to`, `friday_price`, `friday_limit`, `friday_dayoff`, `saturday_from`, `saturday_to`, `saturday_price`, `saturday_limit`, `saturday_dayoff`, `sunday_from`, `sunday_to`, `sunday_price`, `sunday_limit`, `sunday_dayoff`) VALUES
(@calendar_id, , :owner_id, '09:00:00', '18:00:00', 0.00, 1, 'F', '09:00:00', '18:00:00', 0.00, 1, 'F', '09:00:00', '18:00:00', 0.00, 1, 'F', '09:00:00', '18:00:00', 0.00, 1, 'F', '09:00:00', '18:00:00', 0.00, 1, 'F', NULL, NULL, NULL, 1, 'T', NULL, NULL, NULL, 1, 'T');
SQL;
   
    $newSession = md5(uniqid(rand(), true));
    $query  = $pdo->prepare($sql);
    $query->bindParam(':owner_id', $owner_id);
    $query->bindParam(':session_id', $newSession);
    $query->execute();
    
    header("location: session.php?username=".$_GET['username'] );
