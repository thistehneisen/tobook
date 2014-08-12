<?php 

$sql = <<<SQL
SELECT vuser_email INTO @user_email FROM tbl_user_mast WHERE nuser_id = %1\$d;

INSERT INTO `as_users` (`id`, `owner_id`, `role_id`, `email`, `password`, `name`, `phone`,`created`) VALUES
(NULL,  %1\$d, 1, @user_email, NULL, 'admin', '123456789', NOW());

SELECT LAST_INSERT_ID() INTO @user_id;

INSERT INTO `as_plugin_locale` ( `id`,`language_iso`,`sort`,`is_default`,`owner_id`) VALUES (NULL, 'gb', 1, 1, %1\$d);

INSERT INTO `as_calendars` (`id`, `user_id`, `owner_id`) VALUES (NULL,  @user_id, %1\$d);
SELECT LAST_INSERT_ID() INTO @calendar_id;

INSERT INTO `as_formstyle` (`id`, `logo`, `banner`, `color`, `background`, `font`, `message`, `owner_id`) VALUES
(NULL, '', '', '#444', '#444', NULL, NULL, %1\$d);

INSERT INTO `as_plugin_invoice_config` (`id`,`y_logo`, `y_company`, `y_name`, `y_street_address`, `y_city`, `y_state`, `y_zip`, `y_phone`, `y_fax`, `y_email`, `y_url`, `y_template`, `p_accept_payments`, `p_accept_paypal`, `p_accept_authorize`, `p_accept_creditcard`, `p_accept_bank`, `p_paypal_address`, `p_authorize_tz`, `p_authorize_key`, `p_authorize_mid`, `p_authorize_hash`, `p_bank_account`, `si_include`, `si_shipping_address`, `si_company`, `si_name`, `si_address`, `si_street_address`, `si_city`, `si_state`, `si_zip`, `si_phone`, `si_fax`, `si_email`, `si_url`, `si_date`, `si_terms`, `si_is_shipped`, `si_shipping`, `o_booking_url`, `o_qty_is_int`, `owner_id`) VALUES
(NULL, NULL, 'New York Knicks', 'John Smith', 'Madison Square', 'New York City', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '<table style="width: 100%%;" border="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 50%%;"><strong>{y_company}</strong></td>\r\n<td><strong>Invoice no.</strong> {uuid}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td><strong>Date</strong> {issue_date}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="width: 85%%;" border="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;"><strong>Bill To:</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>{b_name}</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>{b_company}</strong></td>\r\n</tr>\r\n<tr>\r\n<td>{b_billing_address}</td>\r\n</tr>\r\n<tr>\r\n<td>{b_city}</td>\r\n</tr>\r\n<tr>\r\n<td>{b_state} {b_zip}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>Phone: {b_phone}, Fax: {b_fax}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p style="text-align: center;"><span style="font-size: large;"><strong>Invoice</strong></span></p>\r\n<p>{items}</p>\r\n<p>&nbsp;</p>\r\n<table style="width: 100%%;" border="0">\r\n<tbody>\r\n<tr>\r\n<td>Note:</td>\r\n<td style="text-align: right;">SubTotal:</td>\r\n<td style="text-align: right;">{subtotal}</td>\r\n</tr>\r\n<tr>\r\n<td>Thanks for your business!</td>\r\n<td style="text-align: right;">Discount:</td>\r\n<td style="text-align: right;">{discount}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;"><strong>Total:</strong></td>\r\n<td style="text-align: right;"><strong>{total}</strong></td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;">Deposit:</td>\r\n<td style="text-align: right;">{paid_deposit}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;"><strong>Amount Due:</strong></td>\r\n<td style="text-align: right;"><strong>{amount_due}</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="width: 100%; border-collapse: collapse;" border="0">\r\n<tbody>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;"><strong>{y_company}</strong></td>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>{y_street_address}</td>\r\n<td>Phone: {y_phone}</td>\r\n</tr>\r\n<tr>\r\n<td>{y_city}</td>\r\n<td>Email: {y_email}</td>\r\n</tr>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">{y_state} {y_zip}</td>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">Website: {y_url}</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, 1, 0, 1, 1, 'info@domain.com', '0', NULL, NULL, NULL, 'bank account information goes here', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'index.php?controller=pjAdminBookings&action=pjActionUpdate&uuid={ORDER_ID}', 0, %1\$d);

INSERT INTO `as_options` (`owner_id`,`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(%1\$d, @calendar_id, 'o_accept_bookings', 3, '1|0::1', NULL, 'bool', 1, 1, NULL),
(%1\$d, @calendar_id, 'o_allow_authorize', 7, '1|0::1', NULL, 'bool', 18, 1, NULL),
(%1\$d, @calendar_id, 'o_allow_bank', 7, '1|0::1', NULL, 'bool', 24, 1, NULL),
(%1\$d, @calendar_id, 'o_allow_creditcard', 7, '1|0::1', NULL, 'bool', 23, 1, NULL),
(%1\$d, @calendar_id, 'o_allow_paypal', 7, '1|0::1', NULL, 'bool', 16, 1, NULL),
(%1\$d, @calendar_id, 'o_authorize_hash', 7, 'abcd', NULL, 'string', 21, 1, NULL),
(%1\$d, @calendar_id, 'o_authorize_key', 7, '53N2U726wZwksK4a', NULL, 'string', 20, 1, NULL),
(%1\$d, @calendar_id, 'o_authorize_mid', 7, '745ean5JCt2Y', NULL, 'string', 19, 1, NULL),
(%1\$d, @calendar_id, 'o_authorize_tz', 7, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 22, 1, NULL),
(%1\$d, @calendar_id, 'o_bank_account', 7, 'Bank of America', NULL, 'text', 25, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_address_1', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 6, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_address_2', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 7, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_captcha', 4, '1|3::1', 'No|Yes (Required)', 'enum', 16, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_city', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 12, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_country', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 15, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_email', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 4, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_name', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 3, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_notes', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 8, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_phone', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 5, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_state', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 13, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_terms', 4, '1|3::1', 'No|Yes (Required)', 'enum', 17, 1, NULL),
(%1\$d, @calendar_id, 'o_bf_zip', 4, '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', 14, 1, NULL),
(%1\$d, @calendar_id, 'o_currency', 1, 'AED|AFN|ALL|AMD|ANG|AOA|ARS|AUD|AWG|AZN|BAM|BBD|BDT|BGN|BHD|BIF|BMD|BND|BOB|BOV|BRL|BSD|BTN|BWP|BYR|BZD|CAD|CDF|CHE|CHF|CHW|CLF|CLP|CNY|COP|COU|CRC|CUC|CUP|CVE|CZK|DJF|DKK|DOP|DZD|EEK|EGP|ERN|ETB|EUR|FJD|FKP|GBP|GEL|GHS|GIP|GMD|GNF|GTQ|GYD|HKD|HNL|HRK|HTG|HUF|IDR|ILS|INR|IQD|IRR|ISK|JMD|JOD|JPY|KES|KGS|KHR|KMF|KPW|KRW|KWD|KYD|KZT|LAK|LBP|LKR|LRD|LSL|LTL|LVL|LYD|MAD|MDL|MGA|MKD|MMK|MNT|MOP|MRO|MUR|MVR|MWK|MXN|MXV|MYR|MZN|NAD|NGN|NIO|NOK|NPR|NZD|OMR|PAB|PEN|PGK|PHP|PKR|PLN|PYG|QAR|RON|RSD|RUB|RWF|SAR|SBD|SCR|SDG|SEK|SGD|SHP|SLL|SOS|SRD|STD|SYP|SZL|THB|TJS|TMT|TND|TOP|TRY|TTD|TWD|TZS|UAH|UGX|USD|USN|USS|UYU|UZS|VEF|VND|VUV|WST|XAF|XAG|XAU|XBA|XBB|XBC|XBD|XCD|XDR|XFU|XOF|XPD|XPF|XPT|XTS|XXX|YER|ZAR|ZMK|ZWL::EUR', NULL, 'enum', 3, 1, NULL),
(%1\$d, @calendar_id, 'o_custom_status', 1, '1|0::1', NULL, 'bool', 2, 1, NULL),
(%1\$d, @calendar_id, 'o_datetime_format', 1, 'd.m.Y, H:i|d.m.Y, H:i:s|m.d.Y, H:i|m.d.Y, H:i:s|Y.m.d, H:i|Y.m.d, H:i:s|j.n.Y, H:i|j.n.Y, H:i:s|n.j.Y, H:i|n.j.Y, H:i:s|Y.n.j, H:i|Y.n.j, H:i:s|d/m/Y, H:i|d/m/Y, H:i:s|m/d/Y, H:i|m/d/Y, H:i:s|Y/m/d, H:i|Y/m/d, H:i:s|j/n/Y, H:i|j/n/Y, H:i:s|n/j/Y, H:i|n/j/Y, H:i:s|Y/n/j, H:i|Y/n/j, H:i:s|d-m-Y, H:i|d-m-Y, H:i:s|m-d-Y, H:i|m-d-Y, H:i:s|Y-m-d, H:i|Y-m-d, H:i:s|j-n-Y, H:i|j-n-Y, H:i:s|n-j-Y, H:i|n-j-Y, H:i:s|Y-n-j, H:i|Y-n-j, H:i:s::j/n/Y, H:i', 'd.m.Y, H:i (25.09.2010, 09:51)|d.m.Y, H:i:s (25.09.2010, 09:51:47)|m.d.Y, H:i (09.25.2010, 09:51)|m.d.Y, H:i:s (09.25.2010, 09:51:47)|Y.m.d, H:i (2010.09.25, 09:51)|Y.m.d, H:i:s (2010.09.25, 09:51:47)|j.n.Y, H:i (25.9.2010, 09:51)|j.n.Y, H:i:s (25.9.2010, 09:51:47)|n.j.Y, H:i (9.25.2010, 09:51)|n.j.Y, H:i:s (9.25.2010, 09:51:47)|Y.n.j, H:i (2010.9.25, 09:51)|Y.n.j, H:i:s (2010.9.25, 09:51:47)|d/m/Y, H:i (25/09/2010, 09:51)|d/m/Y, H:i:s (25/09/2010, 09:51:47)|m/d/Y, H:i (09/25/2010, 09:51)|m/d/Y, H:i:s (09/25/2010, 09:51:47)|Y/m/d, H:i (2010/09/25, 09:51)|Y/m/d, H:i:s (2010/09/25, 09:51:47)|j/n/Y, H:i (25/9/2010, 09:51)|j/n/Y, H:i:s (25/9/2010, 09:51:47)|n/j/Y, H:i (9/25/2010, 09:51)|n/j/Y, H:i:s (9/25/2010, 09:51:47)|Y/n/j, H:i (2010/9/25, 09:51)|Y/n/j, H:i:s (2010/9/25, 09:51:47)|d-m-Y, H:i (25-09-2010, 09:51)|d-m-Y, H:i:s (25-09-2010, 09:51:47)|m-d-Y, H:i (09-25-2010, 09:51)|m-d-Y, H:i:s (09-25-2010, 09:51:47)|Y-m-d, H:i (2010-09-25, 09:51)|Y-m-d, H:i:s (2010-09-25, 09:51:47)|j-n-Y, H:i (25-9-2010, 09:51)|j-n-Y, H:i:s (25-9-2010, 09:51:47)|n-j-Y, H:i (9-25-2010, 09:51)|n-j-Y, H:i:s (9-25-2010, 09:51:47)|Y-n-j, H:i (2010-9-25, 09:51)|Y-n-j, H:i:s (2010-9-25, 09:51:47)', 'enum', 8, 1, NULL),
(%1\$d, @calendar_id, 'o_date_format', 1, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d-m-Y', 'd.m.Y (25.09.2012)|m.d.Y (09.25.2012)|Y.m.d (2012.09.25)|j.n.Y (25.9.2012)|n.j.Y (9.25.2012)|Y.n.j (2012.9.25)|d/m/Y (25/09/2012)|m/d/Y (09/25/2012)|Y/m/d (2012/09/25)|j/n/Y (25/9/2012)|n/j/Y (9/25/2012)|Y/n/j (2012/9/25)|d-m-Y (25-09-2012)|m-d-Y (09-25-2012)|Y-m-d (2012-09-25)|j-n-Y (25-9-2012)|n-j-Y (9-25-2012)|Y-n-j (2012-9-25)', 'enum', 6, 1, NULL),
(%1\$d, @calendar_id, 'o_deposit', 7, '20', NULL, 'float', 12, 1, NULL),
(%1\$d, @calendar_id, 'o_deposit_type', 7, 'amount|percent::percent', 'Amount|Percent', 'enum', NULL, 0, NULL),
(%1\$d, @calendar_id, 'o_disable_payments', 7, '1|0::1', NULL, 'bool', 4, 1, NULL),
(%1\$d, @calendar_id, 'o_hide_prices', 3, '1|0::0', NULL, 'bool', 2, 1, NULL),
(%1\$d, @calendar_id, 'o_layout', 1, '1|2|3::1', 'Layout 1|Layout 2|Layout 3', 'enum', 1, 1, NULL),
(%1\$d, @calendar_id, 'o_layout_backend', 1, '1|2::2', 'Layout 1|Layout 2', 'enum', 1, 1, NULL),
(%1\$d, @calendar_id, 'o_multi_lang', 99, '1|0::0', NULL, 'enum', NULL, 0, NULL),
(%1\$d, @calendar_id, 'o_paypal_address', 7, 'paypal_seller@example.com', NULL, 'string', 17, 1, NULL),
(%1\$d, @calendar_id, 'o_reminder_body', 8, 'Hei {Name},\\r\\n\\r\\nTama on muistutus viesti varauksestasi!\\r\\n\\r\\nVaraus id: {BookingID}\\r\\n\\r\\nPalvelut\\r\\n{Services}\\r\\n\\r\\nTerveisin,', NULL, 'text', 4, 1, 'height:350px'),
(%1\$d, @calendar_id, 'o_reminder_email_before', 8, '10', NULL, 'int', 2, 1, NULL),
(%1\$d, @calendar_id, 'o_reminder_enable', 8, '1|0::1', NULL, 'bool', 1, 1, NULL),
(%1\$d, @calendar_id, 'o_reminder_sms_country_code', 8, '358', 'SMS country code', 'string', 5, 1, NULL),
(%1\$d, @calendar_id, 'o_reminder_sms_send_address', 8, 'varaa.com', 'SMS send address', 'string', 6, 1, NULL),
(%1\$d, @calendar_id, 'o_reminder_sms_hours', 8, '2', NULL, 'int', 5, 1, NULL),
(%1\$d, @calendar_id, 'o_reminder_sms_message', 8, 'Hei {Name}, tama on muistutusviesti varauksestasi.\\r\\n\\r\\nTerveisin,.', NULL, 'text', 7, 1, 'height:200px'),
(%1\$d, @calendar_id, 'o_reminder_subject', 8, 'Booking Reminder', NULL, 'string', 3, 1, NULL),
(%1\$d, @calendar_id, 'o_send_email', 1, 'mail|smtp::mail', 'PHP mail()|SMTP', 'enum', 11, 1, NULL),
(%1\$d, @calendar_id, 'o_seo_url', 1, '1|0::0', NULL, 'bool', 20, 1, NULL),
(%1\$d, @calendar_id, 'o_smtp_host', 1, NULL, NULL, 'string', 12, 1, NULL),
(%1\$d, @calendar_id, 'o_smtp_pass', 1, NULL, NULL, 'string', 15, 1, NULL),
(%1\$d, @calendar_id, 'o_smtp_port', 1, '25', NULL, 'int', 13, 1, NULL),
(%1\$d, @calendar_id, 'o_smtp_user', 1, NULL, NULL, 'string', 14, 1, NULL),
(%1\$d, @calendar_id, 'o_status_if_not_paid', 3, 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', 10, 1, NULL),
(%1\$d, @calendar_id, 'o_status_if_paid', 3, 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', 9, 1, NULL),
(%1\$d, @calendar_id, 'o_step', 3, '5|10|15|20|25|30|35|40|45|50|55|60::15', NULL, 'enum', 3, 1, NULL),
(%1\$d, @calendar_id, 'o_tax', 7, '10', NULL, 'float', 14, 1, NULL),
(%1\$d, @calendar_id, 'o_thankyou_page', 7, 'http://varaa.com/', NULL, 'string', 26, 1, NULL),
(%1\$d, @calendar_id, 'o_timezone', 1, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 10, 1, NULL),
(%1\$d, @calendar_id, 'o_time_format', 1, 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', 7, 1, NULL),
(%1\$d, @calendar_id, 'o_week_numbers', 1, '1|0::1', NULL, 'bool', 19, 1, NULL),
(%1\$d, @calendar_id, 'o_week_start', 1, '0|1|2|3|4|5|6::1', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', 9, 1, NULL),
(%1\$d, @calendar_id, 'private_key', 99, 'R9/Oloz+U9YjLmSfenqcRiISDlI09LDR1WViqtYe/vxshtThVDXLQFxA2U5mIkYd3vy42wtW1j6C33ndue/jpSe3DeV8NPZxVIS4B87R3cCCY7L1bGrLQL5P49l4FBfJzlncUYoE9dCq7h1EPZTjV7HS9mSvfiPnvdyXt0mE2PerPdl+LNFtmeefHkHpJei6FvELm01Cep3bVP5lq/fmTimq+gmj3SB92LbPdFQpYmAFn1+dTTOqb97zOpuMeqcf9J4+/vRwemasu1lx4nmeCH+h8j/f4FBdNZZbbJ7g7dmHF949qPpqE24kCP/YU3KgxDAhiy1m79qrqpnQE3Ey1A==', NULL, 'string', NULL, 1, 'string');
SQL;
