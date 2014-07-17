<?php

/* -----------------------Admin ----------------------------- */
//Login
define("ADMIN_LOGIN","Kirjautuminen");
define("BTN_LOGIN","kirjautuminen");
define("MSG_INVALID_LOGIN","Virheellinen tunnus/salasana!. Yritä uudelleen");
define("CONF_LOGIN_NAME_EMP","Tunnus kenttä ei voi olla tyhjä");
define("CONF_PSWD_EMP","Salasana kenttä ei voi olla tyhjä");

//Admin Header
define("LOGOUT","Kirjaudu ulos");
define("WELCOME","Tervetuloa");
define("ADMIN","Pääkäyttäjä");
define("HELP","Apua");


define("BANNER_LINK","Banner Link");
define("ENABLE_FB","Aktivoi Facebook");

//Menu
define("PAYMENTS","Maksut");
define("USERS","Käyttäjät");
define("TEMPLATES","Teemat");

//Settings
define("SETTINGS","Asetukset");

// General Settings
define("SEND_SITE_DOMAIN","Lähetä sivusto toiselle verkkotunnukselle, jonka pääkäyttäjä on asettanut");
define("FTP_DIR","FTP Kansio");
define("FTP_HOST","FTP Ylläpitäjä");
define("FTP_UNAME","FTP Tunnus");
define("FTP_PASSWD","FTP Salasana");
define("FTP_ROOT_URL","FTP Juuri osoite");
define("FB_APP_DTLS","Facebook App tiedot");
define("FB_APPLN_ID","Facebook App tunnus");
define("FB_APPLN_SECRET","Facebook App salaisuus");
define("ENABLE_SOCIAL_SHARE","Aktivoi sosiaalinen jakaminen");
define("FACEBOOK","Facebook");
define("TWITTER","Twitter");
define("GOOGLE_PLUS","Google Plus");
define("CURRENCY","Valuutta");
define("YES","Kyllä");
define("NO","Ei");

// Payment Settings

define("START_NEW_ACC","Aseta uusi käyttäjä");
define("ENABLE_PAYPAL","Aktivoi PayPal");
define("ENABLE_PAYPAL_MSG","Aktivoidassesi PayPalin, sinun täytyy asettaa palaamis sivu sivustollesi, kun PayPal maksuprosessi on valmis PayPalin ostos paneelissa.");
define("GIVEN_BELOW_DTLS","Alla on yksityiskohdat sen tekemisestä");
define("SET_AUTO_RETURN","Aseta automaattinen palautus");
define("TO_SET_AUTO_RETURN","Automaattiseen palautukseen");
define("CLICK_PROFILE_TAB","Click the Profile tab");
define("CLICK_AUTO_RETURN","Click the Auto Return link under Selling Preferences");
define("CLICK_RADIO_BTN","Click the On radio button to enable Auto Return");
define("ENTER_RET_URL","Aseta palautumis sivun URL osoite");
define("RETURN_URL","Palautumis sivuosoitteet ovat auki");
define("SUCCESS","Menestys");
define("FAILURE","Epäonnistuminen");
define("PAYPAL_SANDBOX","PayPal Sandbox/Testi tila (Poista valinta vaihtaaksesi oikeaan tilaan)");
define("PAYPAL_EMAIL_ADDRESS","PayPal sähköpostiosoite");
define("ENABLE_GOOGLE_CHKOUT","Aktivoi Google checkout");
define("ENABLE_GOOGLE_CHKOUT_MSG","Kun aktivoit Googlen checkoutin, sinun tulee valita the &quot;Automatically authorize and <b>charge</b> the buyer's credit card&quot; option on the seller page under Settings-&gt;Preferences");
define("GOOGLE_CHKOUT_SANDBOX","Google uloskirjauminen Sandbox/testi tila (poista valinta vaihtaaksesi oikeaan tilaan)");
define("GC_ID","Google uloskirjautumis id");
define("GC_KEY",'Google uloskirjautumis avain');
define("GC_MSG","Kun aktivoit Google uloskirjautumisen, sinun tulee asettaa takaisin soitto tunnus sinun Google kauppias paneelista");
define("SET_CALLBACK_URL","Aseta takaisin soitto verkkotunnus");
define("LOGIN_GOOGLE_MERCHNT_PANEL","Kirjaudu sisään Googlen kauppias paneeliin");

define("CLICK_SET_TAB","Paina asetukset nappia");
define("VLICK_INTERGRATION_LINK","Paina integraatio linkkiä' link");
define("ENTER_API_CALLBACK_URL","Enter the API callback URL");
define("API_CALLBACK_URL","The API callback URL");
define("PAYMNT_GATEWAY","Maksu väylä");
define("NONE","Ei mitään");
define("AUTH_NET"," Authorize.net");
define("TWOCHECKOUT","2 kirjaudu ulos");
define("LINK_POINT","Linkki piste");
define("LOGIN_ID","Sisäänkirjautumis tunnus");
define("TRANSACTION_KEY","Siirto avain");
define("MERCHNT_EMAIL","Kauppiaan osoite");
define("TEST_MODE","Testi tila (poista valinta vaihtaaksesi oikeaan tilaan)");
define("TWOCHECKOUT_MSG"," To set up your account to passback or link back to your site after a payment has been processed, you need to set up the parameters within your 2Checkout Admin Panel");
define("RET_URLS_ARE",'The return urls are');
define("APPR_URL","Vahvistettu verkkotunnus");
define("PENDING_URL","Odottava verkkotunnus");
define("CHECKOUT_DEMO_MODE","Kirjaudu ulos demo tilasta (poista valinta vaihtaaksesi oikeaan tilaan) ");

define("PRODUCT_ID","Tuote tunnus");
define("TWOCHECKOUT_MSG",'Sinun tulee lisätä tuote, jotta voit kirjautua ulos hallinta paneelista tuotteen hinta mukana ja lisätä "tuote tunnus" kyseisestä tuotteesta yllä olevaan teksti kenttään.');
define("LP_STORE_NO","Link Point Store Number");
define("TO_SETUP_ACC_MSG","To set up your account you have to replace the content of the file");
define("WITH_DIG_CERT_MSG",'with the digital certificate (cert and key including dashes) included at the bottom of "Welcome to LinkPoint Select API" e-mail');
define("LP_DEMO_MODE","Link Point Demo mode <br>(Uncheck to change to real mode) ");
define("PAYMENT_STATUS_UPADATED_MSG","Maksun tila päivitetty");
//Account settings
define("ACC_SETTINGS","Käyttäjä asetukset");
define("REG_FORM_DETAILS","Rekisteröinti lomakkeen kenttien asetukset");
define("ENABLE_FORM_FIELDS","The following enabled form fields will show in the the site registration page");
define("FORM_FIELD","Lomake kenttä");
define("ENABLE_DISABLE","Aktivoi/Estä");
define("ENABLED","Aktivoitu");
define("MANDATORY","Pakollinen");

//Password Settings
define("PASSWORD_SETTINGS","Salasana asetukset");
define("NEW_PASSWD","Uusi salasana");

// Site Settings
define("SITE_SETTINGS","Sivusto asetukset");
define("ENABLE_GOOGLE_ANALYTICS","Aktivoi Google Analytics tehdylle sivulle?");
define("ENABLE_GOOGLE_ADDSENSE","Aktivoi Google Adsense?");
define("ENABLE_FORM","Aktivoi lomake?");
define("ENABLE_SLIDER","Aktivoi Slider?");
define("ENABLE_CONTACT_FORM","Aktivoi yhteydenotto lomake?");
define("ENABLE_MENU","Aktivoi Menu? ");
define("ENABLE_SOCIAL_SHARE","Aktivoi sosiaalinen jakaminen? ");
define("ENABLE_FB","Aktivoi Facebook?");

define("ENABLE_TWITTER","Aktivoi Twitter?");
define("ENABLE_LINKEDIN","Aktivoi LinkedIn?");
define("ENABLE_HTML_CONTENT","Aktivoi Html Content?");
define("ADD_BANNER","Lisää banneri luodulle sivulle?");
define("UPLOAD_BANNER","Lataa banneri");
define("BANNER","Banner");
define("BANNER_LINK","Banneri linkki");
define("ENABLE_FB","Aktivoi Facebook");
define("CONF_SITE_DELETE","Haluatko varmasti poistaa sivun?");
define("CONF_SITE_PAID","Haluatko varmasti merkata sivun maksetuksi");

//--------Settings---------------
define("MSG_PSWD_UPDATED","Salasana lisätty onnistuneesti");
define("MSG_REGFORM_UPDATED","Rekisteröinti lomakkeen ulkoasu päivitetty");
define("MSG_FTP_LOC_EMP","FTP Paikka on tyhjä");
define("MSG_FTP_HOST_EMP","FTP Host on tyhjä");
define("MSG_FTP_UNAME_EMP","FTP Käyttäjänimi on tyhjä");
define("MSG_FTP_PSWD_EMP","FTP Salasana on tyhjä");
define("MSG_FTP_ROOTURL_EMP","FTP Root Url is empty");
define("MSG_FB_APPID_EMP","Facebook App tunnus on tyhjä");
define("MSG_FB_APPSECRET_EMP","Facebook App Salaisuus on tyhjä");
define("MSG_FB_DIR_EXIST","FTP Hakua ei ole");
define("MSG_FTP_CREDENTIALS","Sorry, could not connect to the server. Please check the given FTP credentials");
define("MSG_GOOGLE_ADSENSE_EMP","Google Adsense Koodi on tyhjä");

define("MSG_ERROR_FOUND","Virheitä löytynyt! Korjaa seuraavat virheet jatkaaksesi");
define("MSG_SETTINGS_UPDATED","Asetukset onnistuneesti päivitetty");
define("MSG_LOGO_FORMATS","Only JPG, GIF and PNG Formats are allowed as logo");
define("MSG_LOGO_UPDATE","Settings successfully updated<br>(Logo update will be visible on other pages or on refresh)&nbsp;");
define("MSG_BANNER_FORMATS","Only JPG, GIF and PNG Formats are allowed as banner");

//------Payment Settings----------
define("MSG_PP_ADDRESS_EMP","Paypal sähköposti puuttuu");
define("MSG_PP_EMAIL_INVALID","Väärä Paypal sähköposti osoite");
define("MSG_GC_MERCHNT_ID_EMP","Google Checkout kauppias tunnus on tyhjä");
define("MSG_GC_MERCHNT_KEY_EMP","Google Checkout kauppias avain on tyhjä");
define("MSG_CURL_REQU","Curl tuki tarvittu. Please recompile your php with curl support");
define("MSG_CHOOSE_PAYMNT","Since Site's building mode is Paid, you have to choose atleast one payment method");
define("MSG_CLEAN_UP","Puhdistus onnistuneesti tehty");
define("MSG_FTP_HOST_EMP","FTP Host on tyhjä");
define("MSG_FTP_UNAME_EMP","FTP Käyttäjänimi on tyhjä");
define("MSG_FTP_PSWD_EMP","FTP salasana on tyhjä");
define("MSG_FTP_ROOTURL_EMP","FTP Root Url on tyhjä");
define("MSG_FB_APPID_EMP","Facebook App tunnus on tyhjä");


//Contents
define("SEARCH","Etsi");
define("SECTION_NAME","Osa-alueen nimi");
define("SECTION_TITLE","Osa-alueen titteli");
define("EDIT","Edit");
define("NO_RESULT_FOUND","No Results Found");
define("OPTIONS","Options");
define("CONTENTS"," Contents");
define("SECTION_CONTENT","Section Content");
define("EDIT_CONTENTS","Edit Contents");
define("MSG_UPDATED_CONTENTS","Successfully updated the contents");

//Template manager
define("TEMP_MANAGER","Teema hallinnointi");
define("TEMP_CATEGORIES","Template Categories");
define("ADD_REMOVE_CATEGORY","Lisää tai poista teema kategoria");
define("ADD_NEW_TEMP","Lisää teema");
define("UPLOAD_TEMP_ZIP","Lisää uusi teema tälle sivustolle. Lataa teema zip tiedosto muodossa");
define("MANAGE_TEMP",'Hallitse teemoja');
define("VIEW_DELETE_TEMP","näytä tai poista olemassa olevat teemat");
define("DESCRIPTION","kuvaus");
define("ADD_NEW_CATGORY","Lisää uusi kategoria");
define("THUMBNAIL","Luonnos");
define("DELETE","Poista");

//Edit template category
define("ADD_EDIT_TEMP_CAT","Lisää/muokkaa teeman kategoria");
define("PLS_FILL_DETAILS","Täytä seuraavat yksityiskohdat. (Fields marked <font color=red>*</font> are mandatory)");
define("CAT_NAME","Kategoria nimi");
define("IMAGE","kuva");
define("UPLOAD_IMG_MSG","Lataa kuvia(.png,.jpg,.gif) muodoissa");
define("DESCRIPTION_MSG","Tämä esittely tulee olemaan näkyvillä käyttäjille kun he valitsevat teemoja");
define("SELECT_CATEGORY_TEMP","Valitse kategoria teemalle ");

define("TEMP_NAME","Temman nimi");
define("TEMP_TYPE","Teeman tyyppi");
define("JOOMLA","Joomla");
define("NORMAL","Normaali");
define("ZIP_FILE_FOR_TEMP","Zip File of the template");
define("DOWNLOAD_SAMPLE","Download sample");
define("CATEGORY","Kategoria");
define("DATE","Päivämäärä");
define("PREVIEW","Esikatselu");
define("NO_DATA_FOUND","Ei löydy tietoa");
define("VIEW_ALL","Näytä kaikki");

define("MSG_UPLOAD_THUMNAIL_IMG","Lataa thumbnaul kuva");
define("MSG_FILENOT_SUPPORTED","Tiedostoa ei tueta. Lataa vain gif, png tai jpg -kuva");
define("MSG_DUPLI_CATNAME","Kategorianimi jo olemassa. Valitse toinen nimi");
define("MSG_CAT_CREATED","Kategoria luotu onnistuneesti");
define("MSG_CHANGES_SAVED","Muutokset luotu onnistuneesti");
define("VALMSG_CATNAME","Kategorianimi ei voi olla tyhjä");
define("CONF_DELETE_CAT","Oletko varma että haluat poistaa tämän kategorian");
define("MSG_TEMPL_UPDATE_SUCC","Päivitit sapluunan");
define("VALMSG_TEMPL_EMP","Sapluunan nimi ei voi olla tyhjä");


//Add Template
define("MSG_FILE_ISSUE","Virhe ladatussa tiedostossa. Yritä uudestaan");
define("MSG_TEMP_UPLOAD_SUCC","Latasit sapluunan!");
define("MSG_INVALID_TEMP","Väärä sapluuna");
define("VALMSG_TEMP_NAME","Kirjoita sapluunan nimi");
define("MSG_SELECT_ZIP","Valitse tiedosto sapluunalle");

//Template Listing
define("VALMSG_DELETE_TEMP","Yrität poistaa sapluunan. Haluatko jatkaa? ");
define("MSG_TEMPL_IN_USE","Sapluunaa ei voida poistaa koska se on käytössä");
define("MSG_TEMPL_DEL_SUCC","Sapluuna poistettu!");

//View Template
define("HOME_PAGE","Kotisivu");
define("INNER_PAGE","Sisempi sivu");

//User Manager
define("ADD_USER","Lisää käyttäjä");
define("MAIL_FROM","From");
define("MAIL_TO","To");
define("USERNAME","Käyttäjänimi");
define("JOIN_DATE","Liitetty päivä");
define("CREATE_SITE","Luo sivu");
define("EDIT_USER","Editoi käyttäjää");
define("CONF_USER_DELETE","Olet poistamassa hänen sivujaan. Haluatko jatkaa? ");

//Edit User
define("MSG_PROFILE_UPDATE","Profiili päivitetty");
define("VALMSG_LOGIN_NAME_EMP","Kirjautumisnimi ei voi olla tyhjä");
define("VALMSG_LOGIN_NAME_LENGTH","Kirjautumisnimen pituus täytyy olla vähemmän kuin 15 kirjainta");
define("VALMSG_FIRST_NAME_EMP","Etunimi ei voi olla tyhjä");
define("VALMSG_EMAIL_EMP","Sähköposti ei voi olla tyhjä");
define("VALMSG_EMAIL_INVALID","Väärä postiformaatti");
define("VALMSG_LAST_NAME_EMP","Sukunimi ei voi olla tyhjä");
define("VALMSG_ADDRESS_EMP","Osoite ei voi olla tyhjä");

define("VALMSG_CITY_EMP","Kaupunki ei voi olla tyhjä");
define("VALMSG_STATE_EMP","Kunta ei voi olla tyhjä");
define("VALMSG_ZIP_EMP","Postinumero ei voi olla tyhjä");
define("VALMSG_PHONE_EMP","Phone cannot be empty");
define("VALMSG_FAX_EMP","Faksi ei voi olla tyhjä");

//Sites
define("SITES","Sivut");
define("SM_CREATE_FROM","Luo tallennetuista artikkeleista");
define("SM_SITE_NAME","Sivunimi");
define("PAID","Maksettu");
define("SM_STATUS","Status");
define("SM_DATE_CREATED","Päivämäärä luotu");
define("SM_STATUS","Status");
define("AMOUNT","Määrä");
define("SM_DELETE","Poista");
define("SM_EDIT","Editoi");
define("USER","Käyttäjä");
define("TRANSACTION_ID","Siirron ID");
define("PAYMENT_TYPE","Maksutapa");
define("DELETE_MSG","Samaa dataa ei löydy sivulta mitä yrität poistaa");
define("DELETED_SUCCESS_MSG","on poistettu");
define("PUBLISHED","Julkaistu");
define("DRAFT","Luonnos");

//User Details
define("USER_DETAILS","Käyttäjän yksityiskohdat");

//Dashboard
define("DASHBOARD","Kojelauta");
define("SITE_CREATED","Sivut luotu");
define("LATEST_PAID_ORDERS","Viimeisimmät maksetut tilaukset");
define("LATEST_UNPUBLISHED_SITE","Viimeisimmät ei Julkaistut sivut");
define("SITE","Sivu");
define("EXPORT","Siirrä");
define("BTN_BACK","Takaisin");
define("BTN_UPDATE","Päivitä");
define("BTN_ADD","Lisää");
define("MAKE_PAID","Tee maksettu");
define("FREE","Vapaa");
define("CREDITCARD","Luottokortti");
define("PHONEORDER","Puhelintilaus");
define("PAYPAL","PayPal");
define("LOG_IN","Kirjaudu");
define("BTN_SAVE","Tallenna");
define("BTN_RESET","Resetoi");

define("EDIT_TEMPL","Editoi sapluuna");
define("BTN_UPDATE_FORM_FIELDS","Päivitä lomakekentät");
define("BTN_CHANGE_PSWD","Vaihda salasana");

//Admin Footer
define("MSG_POWERED_BY","Powered by iScripts EasyCreate . A premium product from");
define("MSG_ISCRIPT","iScripts.com");

//-------------Tooltips--------------------
define("TOOLTIP_LICENSEKEY","Lisenssiavaimesi lähetään sinulle oston jälkeen. Lisenssiavaimesi täytyy kirjoittaa tähän varmistaaksesi ja aktivoidaksesi ohjelman");
define("TOOLTIP_NO_DAYS","Päivien määrä milloin haluaisit tukea väliaikasesti rakennettuja sivuja");
define("TOOLTIP_OPTN_MODE","Operaatio -moodia käytetty julkaisuun");
define("TOOLTIP_MAX_SPACE_MB","Kirjoita maksimitila jota käyttäjä voi käyttää");
define("TOOLTIP_EMAIL_ALERTS","Sähköposti johon kaikki ilmoitukset, hälytykset ja suostumukset lähetetään");
define("TOOLTIP_COLOR_SCHEME","Voit valita sivusi väriskaalan tässä. Valitse yksi jo ladatuista sapluunoista vaihtaaksesi sivun ulkoasua heti");

define("TOOLTIP_FREE","Sivun operointimoodi: Jos tämä on asennettu Vapaa -asentoon, ei sivun luonnista vaadita maksua");
define("TOOLTIP_PAID","Jos tämä on asennettu Maksullinen, sivun käyttäjien täytyy maksaa sivun luonnista");
define("TOOLTIP_SITE_NAME","Sivusi nimi");
define("TOOLTIP_ROOT_DIR","Kirjoita root directory -serverin polku mihin FTP on sallittu");
define("TOOLTIP_CHANGE_LOGO","Tässä voit vaihtaa sivusi logon. Klikkaa Selaa lataaksesi valitun tiedoston tietokoneellesi");
define("TOOLTIP_GOOGLE_ADS","Klikkaa tästä jos haluat lisätä mainoksia Google Ads:sistä");
define("TOOLTIP_ADVT_CODE","Kirjoita koodi mainoksesta. Tämä tulee Google tunnukseltasi");
define("TOOLTIP_GOOGLE_ANALYTICS","Kirjoita Google Analytics koodisi tähän");
define("TOOLTIP_PUBLSH_OPT","Tarkista laatikko jos tarjoat käyttäjille julkaisuasetuksia ESIM. : Julkaista FTP sivulla sinun sijainnissasi ja lisätä luotuja sivuja fani-sivuilleen");
define("TOOLTIP_FTP_DIR_PATH","Kirjoita FTP-polku. eg:");

define("TOOLTIP_FTP_HOST_ADD","Kirjoita FTP Host-osoite");
define("TOOLTIP_FTP_UNAME","Kirjoita FTP käyttäjänimi");
define("TOOLTIP_FTP_PSWD","Kirjoita FTP salasana");
define("TOOLTIP_WEBPATH_DOMAIN","Enter Webpath of the domain. eg: http://mysite.com/samplesites/");
define("TOOLTIP_SOCIAL_SHARE","Tarkista tämä laatikko jos haluat kytkeä sosiaalisen jakamisen kuten Facebookissa, Twitterissä ja Google+ päälle! ");
define("TOOLTIP_FACEBOOK","Kirjoita linkki Facebookkiin jos");
define("TOOLTIP_TWITTER","Kirjoita linkki Twitteriin jos");
define("TOOLTIP_GOOGLE_PLUS","Kirjoita linkki Google plussaan jos");
define("TOOLTIP_SECURE_URL","Kirjoita varmistaaksesi sivusi URL:n    eg:");
define("TOOLTIP_SITE_LANGUAGE_OPTION","Jos sivu luodaan englanniksi tai miksi tahansa muuksi kieleksi");

//-------------------Settings Validations----------------------
define("VALMSG_TEMP_DAYS_EMP","Väliaikaiset päivät ei voi olla tyhjä");
define("VALMSG_ENTER_NUM_DIGITS","Kirjoita vain numeroita");
define("VALMSG_SITE_NAME_EMP","Sivun nimi ei voi olla tyhjä");
define("VALMSG_ADMIN_MAIL_EMP","Ylläpitäjän sähköpostiosoite ei voi olla tyhjä");
define("VALMSG_THEME_EMP","Teema ei voi olla tyhjä");
define("VALMSG_ROOT_DIR","Root directory ei voi olla tyhjä");
define("VALMSG_PRICE_EMP","Hinta ei voi olla tyhjä");

define("VALMSG_ERROR_FOUND","Virheitä löytynyt");
define("VALMSG_SEL_BANNER_UPLOAD","Valitse ladattava banneri");
define("VALMSG_ENTER_BANNER_LINK","Kirjoita bannerin linkki");
define("VALMSG_SEL_PAYMNT_METHOD","Valitse joku näistä maksutavoista");
define("VALMSG_PP_EMAIL_ADDRES","Paypal sähköpostiosoite on tyhjä");
define("VALMSG_SERVER_URL_EMP","Secure server URL ei voi olla tyhjä");
define("VALMSG_PP_EMAIL_INVALID","Väärä Paypal sähköpostiosoite");
define("VALMSG_PP_TOKEN_EMP","Paypal Kolikot on tyhjä");

define("VALMSG_GC_MERCHNT_ID","Googlecheckout Merchant ID on tyhjä");
define("VALMSG_GC_MERCHNT_KEY","Googlecheckout Merchant -Avain on tyhjä");
define("VALMSG_AUTH_LOGIN_ID","Vahvista kirjautumis ID on tyhjä");
define("VALMSG_AUTH_TRANS_KEY","Vahvista siirto avain on tyhjä");
define("VALMSG_AUTH_PSWD","Vahvista salasana on tyhjä");
define("VALMSG_AUTH_EMAIL_ADDR","Vahvista sähköposti on tyhjä");
define("VALMSG_TC_TRANS_KEY","2 Checkout Siirto Avain on tyhjä");
define("VALMSG_TC_PRODUCT_ID","2 Checkout Product ID on tyhjä");

define("VALMSG_LP_STORE_NUM","Linkki maksu on tyhjä");
define("VALMSG_ENTER_NEW_PSWD","Kirjoita Salasana");
define("VALMSG_ENTER_CONF_PSWD","Kirjoita Vahvista salasana");
define("VALMSG_PSWD_MATCH","Molempien salasanojen täytyy täsmätä");
define("VALMSG_DELETE_OLD_FILES","Haluatko poistaa vanhat kansiot(Jotkin käyttäjät voivat saada ongelmia)");
define("VALMSG_ENABLE_FIELD","Kytke kenttä päälle");

$adminvalidation=array('VALMSG_ENTER_NEW_PSWD'=>VALMSG_ENTER_NEW_PSWD,
		'VALMSG_ENTER_CONF_PSWD'=>VALMSG_ENTER_CONF_PSWD,
 		'VALMSG_PSWD_MATCH'=>VALMSG_PSWD_MATCH,
		
 		'VALMSG_TEMP_DAYS_EMP' => VALMSG_TEMP_DAYS_EMP,
 		'VALMSG_ENTER_NUM_DIGITS'=>VALMSG_ENTER_NUM_DIGITS,
		'VALMSG_SITE_NAME_EMP'=>VALMSG_SITE_NAME_EMP,
		'VALMSG_ADMIN_MAIL_EMP'=>VALMSG_ADMIN_MAIL_EMP,
 		'VALMSG_EMAIL_INVALID'=>VALMSG_EMAIL_INVALID,
 		'VALMSG_ROOT_DIR'=>VALMSG_ROOT_DIR,
		'VALMSG_PRICE_EMP'=>VALMSG_PRICE_EMP,
 		'VALMSG_ERROR_FOUND'=>VALMSG_ERROR_FOUND,
 		'VALMSG_SEL_BANNER_UPLOAD'=>VALMSG_SEL_BANNER_UPLOAD,
		
 		'VALMSG_ENTER_BANNER_LINK'=>VALMSG_ENTER_BANNER_LINK,
		'VALMSG_SEL_PAYMNT_METHOD'=>VALMSG_SEL_PAYMNT_METHOD,
 		'VALMSG_PP_EMAIL_ADDRES' =>VALMSG_PP_EMAIL_ADDRES,
 		'VALMSG_SERVER_URL_EMP'=>VALMSG_SERVER_URL_EMP,
		'VALMSG_PP_EMAIL_INVALID'=>VALMSG_PP_EMAIL_INVALID,
 		'VALMSG_PP_TOKEN_EMP'=>VALMSG_PP_TOKEN_EMP,
		'VALMSG_GC_MERCHNT_ID'=>VALMSG_GC_MERCHNT_ID,
		'VALMSG_GC_MERCHNT_KEY'=>VALMSG_GC_MERCHNT_KEY,
 		'VALMSG_AUTH_LOGIN_ID'=>VALMSG_AUTH_LOGIN_ID,
 		'VALMSG_AUTH_TRANS_KEY'=>VALMSG_AUTH_TRANS_KEY,
		'VALMSG_AUTH_PSWD'=>VALMSG_AUTH_PSWD,
 		'VALMSG_AUTH_EMAIL_ADDR'=>VALMSG_AUTH_EMAIL_ADDR,
 		'VALMSG_TC_TRANS_KEY'=>VALMSG_TC_TRANS_KEY,
		'VALMSG_TC_PRODUCT_ID'=>VALMSG_TC_PRODUCT_ID,
		'VALMSG_LP_STORE_NUM'=>VALMSG_LP_STORE_NUM,
 		'VALMSG_DELETE_OLD_FILES'=>VALMSG_DELETE_OLD_FILES,
 		'VALMSG_ENABLE_FIELD'=>VALMSG_ENABLE_FIELD
 );


$paymnttype=array(
		'CreditCard'=>CREDITCARD,
		'Phoneorder'=>PHONEORDER,
		'PayPal' =>PAYPAL
);

?>
