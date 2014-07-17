<?php
/**
 * Locale
 *
 * @package tsbc
 * @subpackage tsbc.app.locale
 */
$TS_LANG = array();

# Login
$TS_LANG['login_username'] = "Käyttäjänimi";
$TS_LANG['login_password'] = "Salasana";
$TS_LANG['login_login']    = "Tunnus";
$TS_LANG['login_register'] = "Rekisteröidy";
$TS_LANG['login_err'][1] = "Väärä Käyttäjänimi ja salasana";
$TS_LANG['login_err'][2] = "Pääsy estetty";
$TS_LANG['login_err'][3] = "Käyttäjätili poistettu käytöstä";
$TS_LANG['login_error'] = "Virhe";

# Left menu
$TS_LANG['menu_calendar']   = "Kalenterit";
$TS_LANG['menu_bookings']   = "Varaukset";
$TS_LANG['menu_time']       = "Aika ja hinta";
$TS_LANG['menu_options'] = "Asetukset";
$TS_LANG['menu_install'] = "Asenna";
$TS_LANG['menu_preview'] = "Esikatsele";
$TS_LANG['menu_logout']  = "Kirjaudu ulos";
$TS_LANG['menu_users']   = "Käyttäjät";

$TS_LANG['menu_choose_calendar'] = "-- Valitse kalenteri --";

# Calendar
$TS_LANG['calendar_add'] = "Lisää kalenteri";
$TS_LANG['calendar_title']  = "Kalenteri";
$TS_LANG['calendar_update'] = "Päivitä kalenteri";
$TS_LANG['calendar_create'] = "Lisää kalenteri";
$TS_LANG['calendar_list']   = "Kalenterit";
$TS_LANG['calendar_owner']  = "Omistaja";
$TS_LANG['calendar_choose'] = "-- Valitse --";
$TS_LANG['calendar_empty'] = "Kalentereita ei löytynyt";
$TS_LANG['calendar_del_title'] = "Poista kalenteri?";
$TS_LANG['calendar_del_body'] = "Kaikki kalenterin tiedot häviävät mikäli poistat kalenterin";

$TS_LANG['cal_start'] = "Alkamisaika";
$TS_LANG['cal_end'] = "Päättymisaika";
$TS_LANG['cal_event'] = "Event Name";
$TS_LANG['cal_bookings'] = "Varaukset";
$TS_LANG['cal_date'] = "Päivämäärä";
$TS_LANG['cal_add'] = "Add an Event";
$TS_LANG['cal_color'] = "Päivän väri";
$TS_LANG['cal_booked_of'] = "%1\$u of %2\$u";
$TS_LANG['cal_del_title'] = "Poista tämä varaus";
$TS_LANG['cal_del_body'] = "Kaikki varaustiedot poistetaan jonka jälkeen ne eivät ole palautettavissa";
$TS_LANG['cal_del_ts_title'] = "Poista tämä aika varauksista?";
$TS_LANG['cal_del_ts_body'] = "Kaikki varausvälit poistetaan";
$TS_LANG['cal_available'] = "Vapaat ajat";

$TS_LANG['calendar_err'][1] = "Calendar has been added";
$TS_LANG['calendar_err'][2] = "Calendar has not been added";
$TS_LANG['calendar_err'][3] = "Calendar has been deleted";
$TS_LANG['calendar_err'][4] = "Calendar has not been deleted";
$TS_LANG['calendar_err'][5] = "Calendar has been updated";
$TS_LANG['calendar_err'][6] = "Calendar has not been updated";
$TS_LANG['calendar_err'][7] = "";
$TS_LANG['calendar_err'][8] = "Calendar doesn't exists";
$TS_LANG['calendar_err'][9] = "Calendar doesn't belongs to you";
$TS_LANG['calendar_err'][10] = "Day color saved";

# Category
$TS_LANG['category_add'] = "Lisää kategoria";
$TS_LANG['category_title']  = "Kategoria";
$TS_LANG['category_update'] = "Päivitä kategoria";
$TS_LANG['category_create'] = "Lisää kategoria";
$TS_LANG['category_list']   = "Kategoriat";
$TS_LANG['category_choose'] = "-- Valitse --";
$TS_LANG['category_empty'] = "Kategorioita ei löytynyt";
$TS_LANG['category_del_title'] = "Poista tämä kategoria";
$TS_LANG['category_del_body'] = "Kaikki kategoria tiedot poistetaan jonka jälkeen ne eivät ole palautettavissa";

$TS_LANG['category_err'][1] = "Kategoria on lisätty";
$TS_LANG['category_err'][2] = "Kategorian lisääminen epäonnistui";
$TS_LANG['category_err'][3] = "Kategoria on poistettu";
$TS_LANG['category_err'][4] = "Kategorian poistaminen epäonnistui";
$TS_LANG['category_err'][5] = "Kategoria on päivitetty";
$TS_LANG['category_err'][6] = "Kategorian päivittäminen epäonnistui";
$TS_LANG['category_err'][7] = "";
$TS_LANG['category_err'][8] = "Kategoriaa ei ole olemassa";

# Booking
$TS_LANG['booking_add'] = "Lisää varaus";
$TS_LANG['booking_calendar'] = "Kalenteri";
$TS_LANG['booking_title']  = "Varaus";
$TS_LANG['booking_update'] = "Päivitä varaus";
$TS_LANG['booking_create'] = "Lisää varaus";
$TS_LANG['booking_list']   = "Varaukset";
$TS_LANG['booking_export']   = "Export";
$TS_LANG['booking_choose'] = "-- Valitse --";
$TS_LANG['booking_empty'] = "Varauksia ei löytynyt";
$TS_LANG['booking_del_title'] = "Poista tämä varaus";
$TS_LANG['booking_del_body'] = "Kaikki varaukset poistetaan.";
$TS_LANG['booking_customer_name'] = "Nimi";
$TS_LANG['booking_customer_email'] = "Sähköposti";
$TS_LANG['booking_customer_phone'] = "Puhelin";
$TS_LANG['booking_customer_country'] = "Maa";
$TS_LANG['booking_customer_city'] = "Kaupunki";
$TS_LANG['booking_customer_address'] = "Osoite";
$TS_LANG['booking_customer_zip'] = "Postinumero";
$TS_LANG['booking_customer_notes'] = "Lisätiedot";
$TS_LANG['booking_customer_people'] = "Määrä";
$TS_LANG['booking_calc_price'] = "Laske hinta";
$TS_LANG['booking_time'] = "Varauksen päivä/aika";
$TS_LANG['booking_dayoff'] = "Päivä suljettu";
$TS_LANG['booking_total'] = "Yhteensä";
$TS_LANG['booking_deposit'] = "Etumaksu";
$TS_LANG['booking_tax'] = "Vero";
$TS_LANG['booking_date'] = "Varauspäivämäärä";
$TS_LANG['booking_event'] = "Tapahtuma";
$TS_LANG['booking_event_choose'] = "-- Valitse tapahtuma --";
$TS_LANG['booking_event_empty'] = "Tapahtumia ei löytynyt";
$TS_LANG['booking_cc_type'] = 'Maksukorttityyppi';
$TS_LANG['booking_cc_num'] = 'Maksukortinnumero';
$TS_LANG['booking_cc_exp'] = 'Maksukortin voimassaolopäivä';
$TS_LANG['booking_cc_code'] = 'Maksukortin turvakoodi';

$TS_LANG['booking_option'] = "Valitse maksutapa";
$TS_LANG['booking_options']['total'] = "Yhteensä";
$TS_LANG['booking_options']['deposit'] = "Etumaksu";

$TS_LANG['booking_booking_status'] = "Varauksen tila";
$TS_LANG['booking_booking_statuses']['confirmed'] = "Vahvistettu";
$TS_LANG['booking_booking_statuses']['pending'] = "Odottaa";
$TS_LANG['booking_booking_statuses']['cancelled'] = "Peruttu";

$TS_LANG['booking_payment_method'] = "Maksutapa";
$TS_LANG['booking_payment_methods']['paypal'] = "PayPal";
$TS_LANG['booking_payment_methods']['authorize'] = "Verkkopankki";
$TS_LANG['booking_payment_methods']['creditcard'] = "Luottokortti";

$TS_LANG['booking_export_from'] = "Pvm alkaen";
$TS_LANG['booking_export_to'] = "Pvm päättyen";
$TS_LANG['booking_export_calendar'] = "Kalenteri";
$TS_LANG['booking_export_all'] = "-- Kaikki --";
$TS_LANG['booking_export_format'] = "Formaatti";

$TS_LANG['booking_export_note_csv'] = '<abbr class="bold" title="Comma-Separated Values">CSV</abbr> stands for Comma-Separated Values, sometimes also called Comma Delimited. A CSV file is a specially formatted plain text file which stores spreadsheet or basic database-style information in a very simple format, with one record on each line, and each field within that record separated by a comma.';
$TS_LANG['booking_export_note_xml'] = '<abbr class="bold" title="Extensible Markup Language">XML</abbr> is a standard, simple, self-describing way of encoding both text and data so that content can be processed with relatively little human intervention and exchanged acros diverse hardware, operating systems, and applications. XML is nothing special. It is just plain text. Software that can handle plain text can also handle XML. However, XML-aware applications can handle the XML tags specially.';
$TS_LANG['booking_export_note_ical'] = '<abbr class="bold" title="">iCalendar</abbr> or iCal, format is a way of formatting calendar information for easy exchange between platforms and applications. It is a standard text file format for calendar files. iCalendar allows users to share event and meeting information via email and on web sites.';

$TS_LANG['booking_export_map'] = array();
$TS_LANG['booking_export_map']['calendar_id'] = "Calendar ID";
$TS_LANG['booking_export_map']['booking_total'] = "Total";
$TS_LANG['booking_export_map']['booking_deposit'] = "Deposit";
$TS_LANG['booking_export_map']['booking_tax'] = "Tax";
$TS_LANG['booking_export_map']['booking_status'] = "Status";
$TS_LANG['booking_export_map']['payment_method'] = "Payment Method";
$TS_LANG['booking_export_map']['payment_option'] = "Payment Option";
$TS_LANG['booking_export_map']['customer_name'] = "Nimi";
$TS_LANG['booking_export_map']['customer_email'] = "Sähköposti";
$TS_LANG['booking_export_map']['customer_phone'] = "Puhelinnumero";
$TS_LANG['booking_export_map']['customer_country'] = "Maa";
$TS_LANG['booking_export_map']['customer_city'] = "Kaupunki";
$TS_LANG['booking_export_map']['customer_address'] = "Osoite";
$TS_LANG['booking_export_map']['customer_zip'] = "Postinumero";
$TS_LANG['booking_export_map']['customer_notes'] = "Lisätiedot";
$TS_LANG['booking_export_map']['cc_type'] = "Maksukortin tyyppi";
$TS_LANG['booking_export_map']['cc_num'] = "Maksukortin numero";
$TS_LANG['booking_export_map']['cc_exp'] = "Maksukortin voimassaolopäivä";
$TS_LANG['booking_export_map']['cc_code'] = "Maksukortin turvakoodi";
$TS_LANG['booking_export_map']['txn_id'] = "ID";
$TS_LANG['booking_export_map']['processed_on'] = "Prosessoitu";

$TS_LANG['booking_export_map']['id'] = "ID";
$TS_LANG['booking_export_map']['booking_id'] = "Varaus ID";
$TS_LANG['booking_export_map']['booking_date'] = "Varauksen päivämäärä";
$TS_LANG['booking_export_map']['start_time'] = "Alkamisaika";
$TS_LANG['booking_export_map']['end_time'] = "Päättymisaika";

$TS_LANG['booking_detail_price'] = "Hinta";
$TS_LANG['booking_detail_cnt'] = "Määrä";
$TS_LANG['booking_detail_amount'] = "Hinta";
$TS_LANG['booking_detail_sub_total'] = "Hinta";
$TS_LANG['booking_detail_grand_total'] = "Yhteensä";
$TS_LANG['booking_detail_tax'] = "Vero";
$TS_LANG['booking_detail_title'] = "Titteli";
$TS_LANG['booking_detail_fully'] = "Täyteen varattu";

$TS_LANG['booking_err'][1] = "Uusi varaus lisätty";
$TS_LANG['booking_err'][2] = "Varauksen lisääminen epäonnistui";
$TS_LANG['booking_err'][3] = "Varaus on poistettu";
$TS_LANG['booking_err'][4] = "Varauksen poistaminen epäonnistui";
$TS_LANG['booking_err'][5] = "Varaus on päivitetty";
$TS_LANG['booking_err'][6] = "Varauksen päivittäminen epäonnistui";
$TS_LANG['booking_err'][7] = "";
$TS_LANG['booking_err'][8] = "Varausta ei ole olemassa";
$TS_LANG['booking_err'][9] = "Varaus ei kuulu sinulle";
$TS_LANG['booking_err'][10] = "Sinun on valittava aika varaukselle";
$TS_LANG['booking_err'][11] = "Varauksen lisääminen epäonnistui";

# Users
$TS_LANG['user_update'] = "Päivitä käyttäjä";
$TS_LANG['user_create'] = "Lisää käyttäjä";
$TS_LANG['user_list'] = "Käyttäjälista";
$TS_LANG['user_username'] = "Käyttäjänimi";
$TS_LANG['user_password'] = "Salasana";
$TS_LANG['user_role'] = "Rooli";
$TS_LANG['user_choose'] = "-- Valitse --";
$TS_LANG['user_status'] = "Tila";
$TS_LANG['user_statarr']['T'] = "Aktiivinen";
$TS_LANG['user_statarr']['F'] = "Ei aktiivinen";
$TS_LANG['user_empty'] = "Käyttäjiä ei löytynyt";
$TS_LANG['user_del_title'] = "Poista tämä käyttäjä";
$TS_LANG['user_del_body'] = "Kaikki käyttäjän tiedot poistetaan";

$TS_LANG['user_err'][1] = "Käyttäjä on lisätty";
$TS_LANG['user_err'][2] = "Käyttäjän lisääminen epäonnistui";
$TS_LANG['user_err'][3] = "Käyttäjä on poistettu";
$TS_LANG['user_err'][4] = "Käyttäjä on poistettu";
$TS_LANG['user_err'][5] = "Käyttäjä on päivitetty";
$TS_LANG['user_err'][6] = "Käyttäjän päivittäminen epäonnistui";
$TS_LANG['user_err'][7] = "";
$TS_LANG['user_err'][8] = "Käyttäjää ei ole olemassa";

# Working time
$TS_LANG['time_update'] = "Päivitä";
$TS_LANG['time_default'] = "Oletus";
$TS_LANG['time_custom'] = "Erikois";
$TS_LANG['time_date'] = "Päivä";
$TS_LANG['time_length'] = "Kesto";
$TS_LANG['time_limit'] = "Varauksia yhtäaikaa";
$TS_LANG['time_slot'] = "Kesto";
$TS_LANG['time_del_title'] = "Poista tämä päivä?";
$TS_LANG['time_del_body'] = "Kaikki tiedot poistetaan eivätkä ne ole palautettavissa";
$TS_LANG['time_empty'] = "Erikoispäiviä ei löytynyt";

$TS_LANG['time_title'] = "Työaika";
$TS_LANG['time_from'] = "Aloitusaika";
$TS_LANG['time_to'] = "Päättymisaika";
$TS_LANG['time_is'] = "Päivä on suljettu";
$TS_LANG['time_day'] = "Viikon päivä";
$TS_LANG['time_price'] = "Hinta";
$TS_LANG['time_single_price'] = "Käytä samaa hintaa koko päivälle";
$TS_LANG['time_err_4'] = "Työajan päivittäminen onnistui";
$TS_LANG['time_dp_title'] = "Aseta erityishintoja";
$TS_LANG['time_slot_length'] = array(
	10 => '10', 15 => '15', 30 => '30', 
	60 => '60', 120 => '120', 180 => '180', 240 => '240', 
	300 => '300', 360 => '360', 420 => '420', 480 => '480',
	540 => '540', 600 => '600', 660 => '660', 720 => '720'
);
$TS_LANG['time_slot_length_labels'] = array(
	10 => '10 minutes', 15 => '15 minutes', 30 => '30 minutes', 
	60 => '1 hour', 120 => '2 hours', 180 => '3 hours', 240 => '4 hours',
	300 => '5 hours', 360 => '6 hours', 420 => '7 hours', 480 => '8 hours',
	540 => '9 hours', 600 => '10 hours', 660 => '11 hours', 720 => '12 hours'
);

# Options
$TS_LANG['option_list'] = "Asetuslista";
$TS_LANG['option_key'] = "Asetusavain";
$TS_LANG['option_description'] = "Asetukset";
$TS_LANG['option_value'] = "Arvo";
$TS_LANG['option_install'] = "Asenna";
$TS_LANG['option_general'] = "Yleinen";
$TS_LANG['option_appearance'] = "Ulkoasu";
$TS_LANG['option_bookings'] = "Varaukset";
$TS_LANG['option_confirmation'] = "Vahvistukset";
$TS_LANG['option_booking_form'] = "Varauslomake";
$TS_LANG['option_reminder'] = "Muistutus";
$TS_LANG['option_username'] = "Käyttäjänimi";
$TS_LANG['option_password'] = "Salasana";
$TS_LANG['option_hours_before'] = "tuntia aiemmin";
$TS_LANG['option_get_key'] = "get key";
$TS_LANG['option_cron'] = "Cron script";
$TS_LANG['option_cron_info'] = "You need to set up a cron job using your hosting account control panel which should execute every hour. Depending on your web server you should use either the URL or script path.
<br /><br/>
Server path:<br /><span class=\"bold\">%1\$s</span>
<br /><br />
URL:<br /><span class=\"bold\">%2\$s</span>";

$TS_LANG['option_err'][5] = "Asetukset on päivitetty";

$TS_LANG['o_install_js'] = "Using JS code";
$TS_LANG['o_install']['js'][1] = "Step 1. (Required) Kopio alla oleva koodi ja liisä se HEAD tagiin.";
$TS_LANG['o_install']['js'][2] = "Step 2. (Required) Kopio allaoleva koodi verkkosivujen kohtaan jossa haluat kalenterin ilmestyvän";

# General
$TS_LANG['_yesno']['T'] = "Kyllä";
$TS_LANG['_yesno']['F'] = "Ei";

$TS_LANG['_switch'] = "Valitse kyllä/ei";
$TS_LANG['_search'] = "Hae";
$TS_LANG['_save']   = "Tallenna muutokset";
$TS_LANG['_cancel'] = "Peru";
$TS_LANG['_upload'] = "Lataa";
$TS_LANG['_edit']   = "muokkaa";
$TS_LANG['_view_calendar']   = "kalenteri";
$TS_LANG['_delete'] = "poista";
$TS_LANG['_delete_all'] = "poista kaikki";
$TS_LANG['_view']   = "esikatsele";
$TS_LANG['_back']   = "takaisin";
$TS_LANG['_never']  = "ei koskaan";
$TS_LANG['_empty']  = "ei tietueita";
$TS_LANG['_sure']   = "Haluatko poistaa valitutu tietueet";
$TS_LANG['_up']     = "ylös";
$TS_LANG['_down']   = "alas";

$TS_LANG['status'] = array(
	1 => 'You are not loged in.',
	2 => 'Access denied. You have not requisite rights to.',
	3 => 'Empty resultset.',
	4 => 'The operation has not been successful',
	7 => 'The operation is not allowed in demo mode.',
	8 => 'The operation is allowed only in Multi-calendar mode',
	9 => 'The operation is allowed only in Multi-user mode',
	20 => 'The operation has been successful',
	123 => "Your hosting account does not allow uploading such a large image."
);
$TS_LANG['err'] = array(
	1 => 'Operation has not been successful.'
);

$TS_LANG['month_name'][1] = "Tammikuu";
$TS_LANG['month_name'][2] = "Helmikuu";
$TS_LANG['month_name'][3] = "Maaliskuu";
$TS_LANG['month_name'][4] = "Huhtikuu";
$TS_LANG['month_name'][5] = "Toukokuu";
$TS_LANG['month_name'][6] = "Kesäkuu";
$TS_LANG['month_name'][7] = "Heinäkuu";
$TS_LANG['month_name'][8] = "Elokuu";
$TS_LANG['month_name'][9] = "Syyskuu";
$TS_LANG['month_name'][10] = "Lokakuu";
$TS_LANG['month_name'][11] = "Marraskuu";
$TS_LANG['month_name'][12] = "Joulukuu";

$TS_LANG['weekday_name'][0] = "Su";
$TS_LANG['weekday_name'][1] = "Ma";
$TS_LANG['weekday_name'][2] = "Ti";
$TS_LANG['weekday_name'][3] = "Ke";
$TS_LANG['weekday_name'][4] = "To";
$TS_LANG['weekday_name'][5] = "Pe";
$TS_LANG['weekday_name'][6] = "La";

# Days
$TS_LANG['days']['monday']    = "Maanantai";
$TS_LANG['days']['tuesday']   = "Tiistai";
$TS_LANG['days']['wednesday'] = "Keskiviikko";
$TS_LANG['days']['thursday']  = "Torstai";
$TS_LANG['days']['friday']    = "Perjantai";
$TS_LANG['days']['saturday']  = "Lauantai";
$TS_LANG['days']['sunday']    = "Sunnuntai";
$TS_LANG['days_short']['monday']    = "Ma";
$TS_LANG['days_short']['tuesday']   = "Ti";
$TS_LANG['days_short']['wednesday'] = "Ke";
$TS_LANG['days_short']['thursday']  = "To";
$TS_LANG['days_short']['friday']    = "Pe";
$TS_LANG['days_short']['saturday']  = "La";
$TS_LANG['days_short']['sunday']    = "Su";

$TS_LANG['prev_link'] = "&lt;";
$TS_LANG['next_link'] = "&gt;";
$TS_LANG['prev_title'] = "Edellinen";
$TS_LANG['next_title'] = "Seuraava";

# FRONT-END
$TS_LANG['legend_slot'] = "Slotti";
$TS_LANG['legend_partly'] = "Osittain varattu";
$TS_LANG['legend_fully'] = "Täyteen varattu";
$TS_LANG['legend_today'] = "Tänään";
$TS_LANG['legend_dayoff'] = "Päivä suljettu";

$TS_LANG['front']['event_empty'] = "Ei tapahtumia kyseiselle päivälle";
$TS_LANG['front']['event_fully'] = "Tapahtuma on täyteen varattu";
$TS_LANG['front']['event_past'] = "Events in the past can not be reserved";
$TS_LANG['front']['calendar_category'] = "-- Valitse kategoria --";

$TS_LANG['front']['bf_name']  = "Nimi";
$TS_LANG['front']['bf_phone'] = "Puhelinnumero";
$TS_LANG['front']['bf_country'] = "Maa";
$TS_LANG['front']['bf_city'] = "Kaupunki";
$TS_LANG['front']['bf_address'] = "Osoite";
$TS_LANG['front']['bf_zip'] = "Postinumero";
$TS_LANG['front']['bf_email'] = "Sähköposti";
$TS_LANG['front']['bf_people'] = "Henkilömäärä";
$TS_LANG['front']['bf_notes'] = "Lisätiedot";
$TS_LANG['front']['bf_captcha'] = "Todennus";
$TS_LANG['front']['bf_price'] = "Hinta";
$TS_LANG['front']['bf_payment_method'] = "Maksutapa";
$TS_LANG['front']['bf_cc_type'] = "Maksukorttityyppi";
$TS_LANG['front']['bf_cc_num'] = "Maksukortinnumero";
$TS_LANG['front']['bf_cc_exp'] = "Maksukortin voimassaolopäivä";
$TS_LANG['front']['bf_cc_code'] = "Maksukortin turvakoodi";
$TS_LANG['front']['bf_cc_types']['Visa'] = "Visa";
$TS_LANG['front']['bf_cc_types']['MasterCard'] = "MasterCard";
$TS_LANG['front']['bf_cc_types']['Maestro'] = "Maestro";
$TS_LANG['front']['bf_cc_types']['AmericanExpress'] = "AmericanExpress";

$TS_LANG['front']['cart']['basket'] = "Valitut ajat";
$TS_LANG['front']['cart']['checkout'] = "Varauslomake";
$TS_LANG['front']['cart']['summary'] = "Varauksen yhteenveto";
$TS_LANG['front']['cart']['item_name'] = "Varaus";
$TS_LANG['front']['cart']['date'] = "Päivmäärä";
$TS_LANG['front']['cart']['start_time'] = "Aloitusaika";
$TS_LANG['front']['cart']['end_time'] = "Päättymisaika";
$TS_LANG['front']['cart']['price'] = "Hinta";
$TS_LANG['front']['cart']['remove'] = "Poista";
$TS_LANG['front']['cart']['empty'] = "Ei yhtään aikaa varattuna";
$TS_LANG['front']['cart']['total'] = "Yhteensä";
$TS_LANG['front']['cart']['passed'] = "Passattu";

$TS_LANG['front']['v_name']  = "Nimi on pakollinen";
$TS_LANG['front']['v_phone'] = "Puhelinnumero on pakollinen";
$TS_LANG['front']['v_country'] = "Maa on pakollinen";
$TS_LANG['front']['v_city'] = "Kaupunki on pakollinen";
$TS_LANG['front']['v_address'] = "Osoite on pakollinen";
$TS_LANG['front']['v_zip'] = "Postinumero on pakollinen";
$TS_LANG['front']['v_people'] = "Henkilömäärä on pakollinen";
$TS_LANG['front']['v_email'] = "Sähköposti on pakollinen";
$TS_LANG['front']['v_notes'] = "Lisätiedot ovat pakolliset";
$TS_LANG['front']['v_payment_method'] = "Maksutapa on pakollinen";
$TS_LANG['front']['v_captcha'] = "Todennus on täytettävä";
$TS_LANG['front']['v_cc_type'] = 'Maksukorttityyppi on pakollinen';
$TS_LANG['front']['v_cc_num'] = 'Maksukortinnumero on pakollinen';
$TS_LANG['front']['v_cc_exp'] = 'Maksukortin voimassaolopäivä on pakollinen';
$TS_LANG['front']['v_cc_exp_month'] = 'Maksukortin voimassaolo kuukausi on pakollinen';
$TS_LANG['front']['v_cc_exp_year'] = 'Maksukortin voimassaolo vuosi on pakollinen';
$TS_LANG['front']['v_cc_code'] = 'Maksukortin turvakoodi on pakollinen';

$TS_LANG['front']['msg_1'] = "Ladataan aikoja";
$TS_LANG['front']['msg_2'] = "Ladataan Varauslomaketta";
$TS_LANG['front']['msg_4'] = "Ladataan yhteenveo sivua";
$TS_LANG['front']['msg_5'] = "Ladataan kalenteria";
$TS_LANG['front']['msg_6'] = "Prosessoidaan varausta..";
$TS_LANG['front']['msg_7'] = "Varaus on tallennettu";
$TS_LANG['front']['msg_8'] = "Varauksen tallentaminen epäonnistui";
$TS_LANG['front']['msg_9'] = "Ladataan koria";

$TS_LANG['front']['v_err_title'] = "Sinulla on seuraavat virheet:";
$TS_LANG['front']['v_err_email'] = "Sähköposti on väärä";
$TS_LANG['front']['v_err_captcha'] = "Todennus on väärä";
$TS_LANG['front']['v_err_payment'] = "Valitse maksutapa";
$TS_LANG['front']['v_err_max'] = "Maksimi varausmäärä [max]";
$TS_LANG['front']['v_err_min'] = "Sinun on valittava yksi aika";

$TS_LANG['front']['button_submit'] = "Jatka";
$TS_LANG['front']['button_cancel'] = "Peru";
$TS_LANG['front']['button_book'] = "Varaa";
$TS_LANG['front']['button_confirm'] = "Vahvista";
$TS_LANG['front']['button_checkout_basket'] = "Maksa";
$TS_LANG['front']['button_add_to_basket'] = "Lisää koriin";
$TS_LANG['front']['button_view_basket'] = "Esikatsele kori";
$TS_LANG['front']['button_empty_basket'] = "Tyhjennä kori";
$TS_LANG['front']['button_update_basket'] = "Päivitä kori";
$TS_LANG['front']['button_proceed'] = "Jatka varaukseen";

$TS_LANG['front']['summary_price'] = "Hinta";
$TS_LANG['front']['summary_security'] = "Vakuus";
$TS_LANG['front']['summary_tax'] = "Vero";
$TS_LANG['front']['summary_total'] = "Kokonaishinta";
$TS_LANG['front']['summary_deposit'] = "Vakuus";

$TS_LANG['front']['pay_deposit_paypal'] = "Maksa vakuus PayPalilla";
$TS_LANG['front']['pay_deposit_authorize'] = "Maksa verkkomaksuilla;";
$TS_LANG['front']['pay_deposit_creditcard'] = "Maksa vakuus luottokortilla";
$TS_LANG['front']['pay_total_paypal'] = "Maksa Paypalilla";
$TS_LANG['front']['pay_total_authorize'] = "Maksa verkkomaksuilla;";
$TS_LANG['front']['pay_total_creditcard'] = "Maksa luottokortilla";

$TS_LANG['front']['cancel_note'] = "Peru varaus";
$TS_LANG['front']['cancel_confirm'] = "Peru varaus";
$TS_LANG['front']['cancel_heading'] = "Varauksesi tiedot";
$TS_LANG['front']['cancel_title'] = "Titteli";
$TS_LANG['front']['cancel_description'] = "Kuvaus";
$TS_LANG['front']['cancel_datetime'] = "Päivä / Aika";
$TS_LANG['front']['cancel_name'] = "Nimi";
$TS_LANG['front']['cancel_email'] = "Sähköposti";
$TS_LANG['front']['cancel_phone'] = "Puhelin";
$TS_LANG['front']['cancel_country'] = "Maa";
$TS_LANG['front']['cancel_city'] = "Kaupunki";
$TS_LANG['front']['cancel_address'] = "Osoite";
$TS_LANG['front']['cancel_zip'] = "Postinumero";
$TS_LANG['front']['cancel_err'][1] = "Missing parameters";
$TS_LANG['front']['cancel_err'][2] = "Booking with such ID did not exists";
$TS_LANG['front']['cancel_err'][3] = "Security hash did not match";
$TS_LANG['front']['cancel_err'][4] = "Booking is already cancelled";
$TS_LANG['front']['cancel_err'][200] = "Booking has been cancelled successful";
?>