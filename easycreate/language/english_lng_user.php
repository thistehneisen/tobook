<?php
error_reporting(0);

/* -----------------------User ----------------------------- */
//login

define("USER_LOGIN","Väärä käyttäjänimi tai salasana");
define("VAL_LOGIN_NAME","Syötä käyttäjänimi");
define("VAL_PWD","Syötä salasana");
define("VAL_LOGIN_NAME_EMPTY","Käyttäjänimi ei voi olla tyhjä");
define("VAL_PWD_EMPTY","Salasana ei voi olla tyhjä");


//Signup validation

define("SIGNUP_PWDS","Syötä salasanat");
define("SIGNUP_SIMILAR_PWDS","Syötä salasanat");
define("SIGNUP_LOG_NAME","Syötä käyttäjänimi");
define("SIGNUP_LOGINEXISTS","Käyttäjänimi on jo olemassa. Yritä uudelleen!!");
define("SIGNUP_EMAILEXISTS","Sähköposti on jo käytössä. Yritä uudelleen!");

define("SIGNUP_WEL_MSG","Olemme iloisia, että olet rekisteröitynyt");
define("LOGIN_INFO","Ohessa sisäänkirjautumistietosi");
define("SIGNUP_USERNAME","Tunnus :");
define("SIGNUP_PWD"," Salasana :");
define("LOGIN_CLICK","Klikkaa");

define("TO_LOGIN","to login.");
define("THANKU","Kiitos kiinnostuksestasi ");
define("REGARDS"," Terveisin");
define("TEAM","Tiimi");
define("VAL_LOGINNAME","Täytä käyttäjänimi");

define("VAL_NAMELENGHTH","Käyttäjänimessä voi olla maksimissaan 15 merkkiä");
define("VAL_SIGNUP_PWD","Täytä salasana ");
define("VAL_SIGNUP_CFRMPWD","Salasana tulee vahvistaa");
define("VAL_SIGNUP_FNAME_EMPTY","Etunimi ei voi olla tyhjä");
define("VAL_SIGNUP_EMAIL_EMPTY","Täytä sähköposti");

define("VAL_SIGNUP_EMAIL_FORMAT","Väärä sähköposti väärin");
define("VAL_SIGNUP_LNAME_EMPTY","Täytä sukunimi");
define("VAL_SIGNUP_ADDRESS","Täytä osoite");
define("VAL_SIGNUP_ADDRESS2","Osoite 2 ei voi olla tyhjä");
define("VAL_SIGNUP_CITY","Täytä kaupunki");

define("VAL_SIGNUP_STATE","Täytä kunta");
define("VAL_SIGNUP_ZIP","Täytä postinumero");
define("VAL_SIGNUP_PHONE","Täytä puhelinnumero");
define("VAL_SIGNUP_FAX","Täytä fax");


//UserMain
 define("SITE_MANAGER","Kotisivujen hallinta");
 define("WELCOME","Tervetuloa");
 define("WELCOME_TO","Tervetuloa to");
 define("WELCOME_NOTE","Tämän sivun avulla voit hallinnoida liiketoimintaasi helposti. Voit luoda kotisivuja, hallinnoida gallerioita, vastaanottamaan varauksia verkosta, lähettämään markkinointikampanjoita ja optimoimaan löydettävyyttäsi.");
 define("GALLERY_MANAGER","Galleria");
 define("PROFILE_MANAGER","Profiilin hallinta");
 define("PROMOTION_MANAGER","Näkyvyyden hallinta");
 define("MY_SITES","Minun sivustoni");
 define("DASHBOARD_SITE_NAME","Sivun nimi");
 define("DATE_CREATED","Luomispäivämäärä");
 define("STATUS","Tila");
 define("OPERATIONS","Toiminnot");
 define("PREVIEW","Esikatsele");
 
 //usermain validation
 define("VAL_DELETE","Oletko varma, että haluat poistaa tämän sivun?");
 
 
 //gallery manager
 define("GALLERY_DESCRIPTION","Gallerian kautta voit hallinnoida kuvagalleriaasi joita voit käyttää esimerkiksi kotisivuillasi.");
 define("SELECT_IMAGE","Valitse kuva");
 define("UPLOAD_IMAGE","Lataa kuva");
 define("UPLOADING","Ladataan..");
 define("IMAGE","Kuva");
 define("TEXT","Teksti");
 define("ROTATE","Käännä");
 define("DEGREES","Degrees");
 define("BRIGHTNESS","Kirkkaus");
 define("CONTRAST","Kontrasti");
 define("WIDTH","Leveys");
 define("HEIGHT","Korkeus");
 define("CROP","Rajaa");
 define("FLIP","Käännä ympäri");
 define("EFFECTS","Efektit");
 define("SELECT","Valitse");
 define("NEGATIVE","Negatiivi");
 define("GREYSCALE","Harmaa");
 define("SMOOTH","Smooth");
 define("BLUR","Blur");
 define("CROP_IMAGE","Rajaa");
 define("CLICK","Klikkaa");
 define("APPLY","Suorita");
 define("SAVING_IMAGE","Tallenna kuva");
 define("CANCEL","Peru");
 define("RESET_SELECTION","Poistaaksesi valinta");
 define("FONT","Fontti");
 define("SIZE","Koko");
 define("COLOR","Väri");
 define("LOADING","Lataa..");
 define("REPLACE_FILE","Korvaa aiempi tiedosto");
 define("SAVE_NEWFILE","Tallenna uutena tiedostona");
 
 //gallary validaton
 
 define("VAL_IMAGE","Järjestelmä tukee vain jpg | gif | png formaatteja!");
 define("VAL_NOIMAGE","Kuvia ei löytynyt.");
 define("VAL_IMAGE_SELECT","Valitse kuva");
 define("VAL_IMAGE_SUCC","Kuvan lataaminen onnistui");
 define("VAL_IMAGE_SAVE","Kaikki tallentamaton data häviää mikäli et tallenna.");
 
 //Profile Manager
 
 define("EDIT_PROFILE","Muokkaa profiilia");
 define("EDIT_PROFILE_DESC","Olet antanut oheiset tiedot rekisteröintivaiheessa. Voit muokata tietoja tarvittaessa.");
 define("EDIT_PASSWORD","Muokkaa salasanaa");
 define("EDIT_PASSWORD_DESC","Täällä voit vaihtaa salasanaasi. Mikäli uskot kolmannen osapuolen tietävän salasanasi suosittelemme salasanan vaihtamista.");
 define("PAYMENT_DETAILS","Maksutiedot");
 define("PAYMENT_DETAILS_DESC","Esikatsele kaikki tiedot jotka liittyvät maksuihisi.");

 //Edit Profile
 
 define("MANDATORY_PART1","Täytä oheiset tiedot (Oheiset tiedot");
 define("MANDATORY_PART2","ovat pakollisia ).");
 define("USER_LOGIN_NAME","Käyttäjänimi");
 define("USER_FIRST_NAME","Etunimi");
 define("USER_LAST_NAME","Sukunimi");
 define("USER_EMAIL","Salasana");
 define("USER_ADDRESS1","Osoite");
 define("USER_CITY","Kaupunki");
 define("USER_STATE","Kunta");
 define("USER_COUNTRY","Maa");
 define("USER_ZIP","Postinumero");
 define("USER_PHONE","Puhelinnumero");
 
 //EDIT PROFILE VALIDATION
 
 define("VAL_FNAME","Nimi ei voi olla tyhjä");
 define("VAL_LNAME","Sukunimi ei voi olla tyhjä");
 define("VAL_ADDRESS","Osoite ei voi olla tyhjä");
 define("VAL_EMAIL","Sähköposti ei voi olla tyhjä");
 define("VAL_INVALID_MAIL","Sähköposti väärässä muodossa");

 
 //Edit Password
 
 define("USER_NEW_PASSWORD","Uusi salasana");
 define("USER_CONFIRM_PASSWORD","Vahvista salasana");
 
 //edit password validation
 
 define("VAL_PASSWORD_UPDATE","Olet päivittänyt salasanan onnistuneesti ja uusi salasanasi on: :");
 define("REGARDS","Terveisin");
 define("THE","The");
 define("TEAM","Tiimi");
 define("VAL_PWD","Päivitetty salasana");
 define("VAL_NEW_PASSWORD","Uusi salasana ei voi olla tyhjä");
 define("VAL_CONFIRM_PASSWORD","Confirm password cannot be empty");
 define("VAL_PASSWORD_MISMATCH","Password mismatch");
 
 //Promotion Manager
 
 define("SEARCH_ENGINE_SUBMISSION","Toimita hakukoneille");
 define("SEARCH_ENGINE_DESCRIPTION","To enable your site be to searched out of a search engine results, 
				you would require to submit your site to the search engine. 
				Use 'Search Engine Submission' to submit your site to search engines.");
 define("TELL_FRIEND","Tell a Friend");
 define("TELL_FRIEND_DESCRIPTION","Another method to make your site popular is to tell more people about your site and make them visit your site. 
				To enable you to mail your friends about your site use 'Tell a Friend'.");
 define("META_TAG_GENERATOR","Meta Tag Generator");
 define("META_TAG_GENERATOR_DESCRIPTION","Meta tags are few lines of code that is placed in your website pages. 
				The search engines identify the nature of your site from these tags. Use 'Meta Tag Generator' to create metatags for your site.");
 define("META_TAG_ANALYZER","Meta Tag Analyzer");
 define("META_TAG_ANALYZER_DESCRIPTION","Meta tag analyzer  will allow you to get the meta tags used by your competitors websites. This will help you create much competitive metatags.");
 
 //validation site submission
 
 define("VAL_INDEX_SHORTLY","indeksoidaan pian");
 define("VAL_DOC_REMOVED","dokumentti on siirretty");
 
 
 //Search Engine Submission
 
 define("SEARCH_ENGINE_SUBMISSION_DESC","Search engine submission is an attempt to make a search engine aware of a site or page.
     It is often a way to promote a website. It ensures that the web page gets spidered and indexed. You can submit your site using the form given below.");
 define("SEARCH_ENGINE_SITENAME","Enter your site name/URL to submit to search engines");
 define("SEARCH_ENGINE_EG","Eg: http://www.yoursite.com");
 define("SEARCH_ENGINE_TO_SUBMIT","Search engines you wish to submit your site");
 
 //Tell Friend
 
 define("TELL_FRIEND_DESCRIPTION","Tell others about your site. 
        A mail will be sent to your friend informing him/her about your site. 
        Always send mails to your known friends only. 
        You can use the below form to intimate your friends about your site.");
 define("YOUR_INFORMATION","Your Information");
 define("YOUR_NAME","Sinun nimi");
 define("YOUR_EMAIL","Sinun sähköposti");
 define("FRIEND_INFO","Ystäväsi tiedot");
 define("FRIEND_NAME","Ystäväsi nimi");
 define("FRIEND_EMAIL","Ystäväsi sähköposti");
 define("FRIEND_MAILCONTENT","Sähköposti sisältö");
 define("FRIEND_SUBJECT","Otsikko");
 define("FRIEND_CONTENT","Viesti");
 define("FRIEND_REPLACEINFO","Vaihda 'yoursite.com' sivustosi osoitteeseen.");
 define("SITE_LINK","Ole ystävällinen ja tutustu oheiseen sivustoon. Ohessa linkki: http://www.yoursite.com ");
 
 //VALIDATION TELLFRIEND
 
 define("VAL_MSG_SENT","Viesti on lähetetty ystävällesi.");
 define("VAL_NAME","Täytä nimesi");
 define("VAL_EMAIL","Täytä sähköpostisi");
 define("VAL_INVALIDMAIL","Väärä sähköposti formaatti");
 define("VAL_FRNDNAME","Täytä ystäväsi nimi");
 define("VAL_FRNDMAIL","Täytä ystäväsi sähköposti");
 define("VAL_FRIEND_EMAIL","Täytä aihe");
 define("VAL_FRIEND_MAILCONTENT","Täytä sisältö");
 
 
 //Meta Tag Generator
 
 define("META_DESCRIPTION1","Meta tags provide information about your web page.
This meta tag generator will generate meta tags based on the inputs .
 They provide information such as who created the page, how often it is updated and what the page is about. 
  Search engines use this information to build indices.
    It is placed between the");
 define("META_DESCRIPTION2","HEAD");
 define("META_DESCRIPTION3","and");
 define("META_DESCRIPTION4","/HEAD");
 define("META_DESCRIPTION5","tags of the document.");
 define("META_INFORMATION","Meta Information");
 define("SITE_DESCRIPTION","Site Description");
 define("SITE_DESCRIPTION_EG","A little, plain language description of the site/page. It is used by search engines to describe your document. Particularly important if your document has very little text. (E.g.: Yoursite.com is a place to sell roses.)");
 define("SITE_KEYWORDS","Site Keywords");
 define("SITE_KEYWORDS_EG","The keywords is useful as a way to reinforce the terms you think your site page is important for. (E.g.: roses, red roses, yellow roses, buy roses, purchase roses.)");
 define("ADDITIONAL_INFO","Lisätiedot");
 define("OPTIONAL","[Optional]");
 define("ROBOTS_OPTION","Robootti optiot");
 define("ROBOTS_OPTION1","Indeksoi sivut sekä seuraa linkkejä");
 define("ROBOTS_OPTION2","Älä indeksoi sivuja äläkä seuraa linkkejä");
 define("ROBOTS_OPTION3","Index this page, but don't follow any links");
 define("ROBOTS_OPTION4","Don't index this page, but follow links");
 define("ROBOTS_DESCRIPTION","(Robots meta tag kertoo hakurobotille voiko robootti indeksoida sivut sekä käydä sivuston läpi kokonaisuudessaan parempia hakutuloksia varten.)");
 define("REFRESH_RATE","Päivitysaika sekunneissa.");
 define("REFRESH_RATE_DESCRIPTION","(Specifies a delay in seconds before the browser automatically reloads the document.)");
 define("COPYRIGHT","Copyright line:");
 define("COPYRIGHT_DESCIPTION","(It tells the unqualified copyright statement for the site.)");
 define("AUTHOR","Author:");
 define("AUTHOR_DESCRIPTION","(It represents the unqualified author's name who created the site. )");
 define("GENERATOR","Generator:");
 define("GENERATOR_DESCRIPTION","(It tells the unqualified copyright statement for the site.)");
 define("LANGUAGE","Kieli:");
 define("BULGARIAN","Bulgarian");
 define("CZECH","Czech");
 define("DANISH","Danish");
 define("GERMAN","German");
 define("GREEK","Greek");
 define("ENGLISH","English");
 define("ENGLISH_UK","English (UK)");
 define("ENGLISH_US","English (US)");
 define("SPANISH","Spanish");
 define("SPANISH_SPAIN","Spanish (Spain)");
 define("FINNISH","Finnish");
 define("CROATIAN","Croatian");
 define("ITALIAN","Italian");
 define("FRENCH","French");
 define("FRENCH_QUEBEC","French (Quebec)");
 define("FRENCH_FRANCE","French (France)");
 define("JAPANESE","Japanese");
 define("KOREAN","Korean");
 define("DUTCH","Dutch");
 define("NORWEGIAN","Norwegian");
 define("POLISH","Polish");
 define("RUSSIAN","Russian");
 define("SWEDISH","Swedish");
 define("CHINESE","Chinese");
 define("LANG_DESCRIPTION","(This tag is to indicate to search engines the languages supported by your site.)");
 define("SEARCHENGINE_REVISITRATE","Search engines revisit rate");
 define("IN_DAYS","in days");
 define("REVISIT_DESCRIPTION","(The revisit meta tag is used by search engines as a means to indicate how often a web page should be revisited for re-indexing)");
 define("PASTE","Please paste the following code between your HEAD tag as given below");
 
 //VALIDATION META TAG
 
 define("VAL_SITE_DESC","Please provide site description");
 define("VAL_SITE_KEYWORDS","Please provide site keywords");
 
 
 //Meta Analyzer
 
 define("META_ANALYSER_PAGEDESCRIPTION","A meta tag provides information about a web page. The meta tag analyzer will extract all meta tag content attributes from a site. You can check out some of your competitors meta tags and modify your meta tags as required.");
 define("TAG_INFORMATION","Meta Information");
 define("TAG_SITE_DESCRIPTION","Site Description:");
 define("TAG_SITE_KEYWORDS","Site Keywords: ");
 define("TAG_COMMA","(Seperated by commas):");
 define("TAG_ROBOT_OPTION","Robots Option");
 define("TAG_COPYRIGHT","Copyright line");
 define("TAG_AUTHOR","Author");
 define("TAG_GENERATOR","Generator");
 define("TAG_LANGUAGE","Language");
 define("TAG_SEARCH_REVISIT","Search engines revisit rate in days");
 define("TAG_SEARCH_REFRESHRATE","Refresh rate in seconds");
 define("TAG_DOMAIN_NAME","Domain name");
 define("TAG_ENTER_DOMAIN_NAME","Please Enter a Valid Domain Name");
 
 //VALIDATION META ANALYSER
 
 define("VALTAG_SITE_MSG","Sorry, Invalid entry!Action cannot be performed .");
 define("VALTAG_DOMAIN","Enter a valid domain name");
 define("VALTAG_NO_TAGS","No metatags available for copying.");
 
 
 //Site manager
 define("SORRY_NO_RECORDS"," Sorry! No records Found.");
 
 //editorheader
 
 define("EDITOR_LOADING","Ladataan editoria");
 define("EDITOR","(Editori)");
 define("EDITOR_WIDGETS","Lisäominaisuudet");
 define("EDITOR_PREVIEW","Esikatsele");
 define("EDITOR_HELP","Apua?");
 define("EDITOR_PAGES","Sivut");
 define("EDITOR_ADDEDIT","Lisää / Muokkaa sivua");
 define("EDITOR_FORM","Lomake");
 define("EDITOR_ADSENSE","Google Adsense");
 define("EDITOR_SLIDER","Slideri");
 define("EDITOR_CONTACT","Yhteydenottolomake");
 define("EDITOR_MENU","Valikko");
 define("EDITOR_SOCIALSHARE","Sosiaalinen media");
 define("EDITOR_HTML","HTML");
 define("EDITOR_DRAG","Vedä lisäominaisuus haluaamasi paikkaan.");
 
 define("EDITOR_PANEL","Muokkaa paneelin sisältöä");
 define("EDITOR_UPDATE","Päivitä");
 define("EDITOR_WIDGETS","Lisäominaisuudet");
 define("EDITOR_PREVIEW","Esikatsele");
 define("EDITOR_HELP","Apua?");
 define("EDITOR_PAGES","Sivut");
 define("EDITOR_ADDEDIT","Lisää / Muokkaa sivua");
 define("EDITOR_FORM","Lomake");
 
 
 //edit template
 
 define("EDITOR_HTML_VIEW","HTML näkymä");
 define("EDITOR_DESIGN","Suunnittelu näkymä");
 define("EDITOR_MENU_LABEL","Menu label");
 define("EDITOR_ADD_MENU","Lisää valikkoon");
 define("EDITOR_VIEW_ITEMS","Tarkistele valikkoa");
 define("EDITOR_LINK_TO","Linkitetty");
 define("EDITOR_POP_CANCEL","Peru");
 define("EDITOR_MENU_MANAGEMENT","Valikon hallinnointi");
 define("EDITOR_LABEL","Label");
 define("EDITOR_ACTIONS","Toiminnot");
 define("EDITOR_CONTACTDETAILS","Yhteydenottolomakkeen tiedot");
 define("EDITOR_GUEST","Vieraskirja");
 define("EDITOR_IMAGE_SLIDE","Slideri");
 define("EDITOR_HTML","Html laatikko");
 define("EDITOR_PAGESETTINGS","Sivun asetukset");
 define("EDITOR_GOOGLE_ADSENSE","Google Adsense asetukset");
 define("EDITOR_CUSTOM_FORM","Räätälöidyn lomakkeen tiedot");
 define("EDITOR_FORMSETTINGS","Lomakkeiden asetukset");
 define("EDITOR_MENU_SETTINGS","Valikon asetukset");
 define("EDITOR_MANAGESOCIAL_SHARE","Sosiaalinen jako");
 define("EDITOR_SOCIAL_SHARE","Muokkaa sosiaalista jakoa");
 define("EDITOR_SLIDE_SHOW","Muokkaa slideria");
 define("EDITOR_IMAGE_SETTINGS","Kuva asetukset");
 define("EDITOR_MANGE_IMAGES","Muokkaa kuvia");
 
 
 $uservalidation=array('EDITOR_MENU_MANAGEMENT'=>EDITOR_MENU_MANAGEMENT,
 		'EDITOR_LABEL'=>EDITOR_LABEL,
 		'EDITOR_ACTIONS'=>EDITOR_ACTIONS,
 		'EDITOR_CONTACTDETAILS'=>EDITOR_CONTACTDETAILS,
 		'EDITOR_GUEST'=>EDITOR_GUEST,
 		'EDITOR_IMAGE_SLIDE'=>EDITOR_IMAGE_SLIDE,
 		'EDITOR_HTML'=>EDITOR_HTML,
 		'EDITOR_PAGESETTINGS'=>EDITOR_PAGESETTINGS,
 		'EDITOR_GOOGLE_ADSENSE'=>EDITOR_GOOGLE_ADSENSE,
 		'EDITOR_CUSTOM_FORM'=>EDITOR_CUSTOM_FORM,
 		'EDITOR_FORMSETTINGS'=>EDITOR_FORMSETTINGS,
 		'EDITOR_MENU_SETTINGS'=>EDITOR_MENU_SETTINGS,
 		'EDITOR_MANAGESOCIAL_SHARE'=>EDITOR_MANAGESOCIAL_SHARE,
 		'EDITOR_SOCIAL_SHARE'=>EDITOR_SOCIAL_SHARE,
 		'EDITOR_SLIDE_SHOW'=>EDITOR_SLIDE_SHOW,
 		'EDITOR_IMAGE_SETTINGS'=>EDITOR_IMAGE_SETTINGS,
 		'EDITOR_MANGE_IMAGES'=>EDITOR_MANGE_IMAGES
 		
 );
 //FORGOT PASSWORD
 
 define("FORGOT_PWD","Unohditko salanasi?");
 define("FORGOT_VAL_MSG","Täytä käyttäjänimi nollataksesi salasana");
 define("FORGOT_USERNAME","Käyttäjänimi");
 define("FORGOT_MSG","Käyttäjänimi ei voi olla tyhjä");

 
 //Show templates
 
 define("SELECT_TEMPLATE"," Valitse teema haluamastasi kategoriasta.");
 define("TEMPLATE_SELECT_CATEGORY"," Valitse kategoria");
 define("TEMPLATE_SELECTALL"," Valitse kaikki");
 define("TEMPLATE_CONTINUE","Jatka");
 define("TEMPLATE_SAVE","Takaisin");
 define("TEMP_SKIP","Skippaa");
 define("TEMP_PREVIEW","Esikatsele");
 define("TEMP_PUBLISH","Julkaise");
 define("TEMP_SAVE","Tallenna");
 //VALIDATION SHOW TEMPLATES
 
 define("VAL_TEMPLATE_CHOOSE"," Valitse teema");
 
 
 //getsitedetails
 define("CUSTOM_APPEARANCE"," Muokkaa ulkoasua");
 define("CUSTOM_SITE_NAME","Sivuston nimi");
 define("CUSTOM_SITE_DESCRIPTION"," Nimeä sivustosi joka auttaa sinua jatkossa tunnistamaan sivustosi.");
 define("CUSTOM_SELECT_LOGO","Valitse logo");
 define("CUSTOM_NO_LOGOS","Valitse logo");
 define("CUSTOM_UPLOAD_LOGO","Lataa logosi");
 define("CUSTOM_UPLOAD_LOGO_DESC","Tämä logo tulee olemaan logosi sivustolla");
 define("CUSTOM_SELECT_SAMPLE"," Valitse malli logoista");
 define("CUSTOM_SELECT_SAMPLE_LOGO","Valitse logo valmiista malleistamme.");
 define("CUSTOM_YOUR_LOGO"," Sivun logo");
 define("CUSTOM_WEBSITE_TITLE","Sivuston otsikko");
 define("CUSTOM_SITE_TITLE"," Sivun otsikko");
 define("CUSTOM_SITE_TILEDESCRIPTION","Sivuston otsikko näkyy selaimen yläosassa ja on tärkeässä roolissa hakukone löydettävyydessä");
 define("CUSTOM_FONT"," Fontti");
 define("CUSTOM_SIZE","Koko");
 define("CUSTOM_COLOR","Väri");
 define("CUSTOM_STYLE4SITE","Käytä oheisiä tyylejä sivuillasi.");
 define("CUSTOM_SITE_COLOR","Sivun väri");
 define("CUSTOM_SELECT_BACKGROUND","Taustan väri");
 define("CUSTOM_BACKGROUND_DESCRIPTION","Sivun taustan väriä voit käytä personoidaksesi sivut yrityksesi näköisiksi.");
 define("CUSTOM_DESCRIPTION","Kuvaus");
 define("CUSTOM_SITE_DESC","Sivuston kuvaus");
 define("CUSTOM_DESCRIPTION_DETAIL","Kirjoita lyhyt kuvaus yrityksesi toiminnasta hakukoneita (Googlea yms.) varten.");
 define("CUSTOM_STYLE4DESCRIPTION","Käytä oheisiä tyylejä kotisivujesi kuvauksessa.");
 define("CUSTOM_OTHERS","Muut");
 define("CUSTOM_ANALYTICS","Google Analytics Koodi");
 define("CUSTOM_ANALYTICS_DESCRIPTION","Google Analytics (GA) Google Analyticsin avulla voit mitata myyntiä ja tuloksia sekä saada tuoreita tietoja siitä, miten kävijät käyttävät sivustoasi, miten he saapuvat sivustollesi ja miten saat heidät palaamaan takaisin. Koodi näyttää esim tältä: "."UA-XXXXX-Y"."");
 define("CUSTOM_GET_ANALYTICS","Hanki Google Analytics koodi");
 define("CUSTOM_META_TITLE","Meta otsikko");
 define("CUSTOM_META_TITLE_DESCRIPTION"," Etusivusi title-tunnisteessa voi lukea verkkosivustosi/yrityksesi nimi sekä muita tärkeitä tietoja, kuten yrityksen fyysinen sijainti tai ehkä muutamia sen pääaihealueista tai tuotteista (3)");
 define("CUSTOM_META_DESCRIPTION","Meta kuvaus");
 define("CUSTOM_META_DETAIL","Meta kuvauksessa tulee käytä ilmi lyhyesti yrityksesi toimintaa.");
 define("CUSTOM_META_KEYWORDS","Meta avainsanat");
 define("CUSTOM_META_KEYWORDS_DESCRIPTION"," Meta avainsanoihin tulee lisätät yrityksellesi tärkeimmät hakusanat jotka esiintyvät myös meta otsikossa ja meta kuvauksessa. ");

 //VALIDATION SITE DETAILS
 
 define("VAL_ERRMSG1","Sivustosi vie tilaa yli sallitun määrän. Käytetty tila: ");
 define("VAL_ERRMSG2","<br>Sallittu tila:");
 define("VAL_ERRMSG3","<br>Poista käyttämäsi kuvat järjestelmästä.<br>&nbsp;<br>");
 define("VAL_IMAGETYPE","Lataa vain .jpg,.gif,.png tyyppisiä kuvia");
 
 
 //PUBLISHPAGE
 
 define("BACK_EDITOR","Takaisin editoriin");
 define("BACK_PAYMENT","Takaisin maksuun");
 define("PUBLISH_AMOUNT","Summa :");
 define("PUBLISH_NOTE","Lisätiedot:");
 define("PUBLISH_WARNING","Varmista, että palaat sivustolle viimeistelläksesi sivuston julkaisun.");
 define("PUBLISH_WARNING2","Maksutapa ei ole käytössä");
 define("PUBLISH_WARNING3","Ota yhteyttä sivuston ylläpitäjään");
 define("PUBLISH_WAITING_INTERFACE","Odotetaan maksunpalvelun tarjoajaa ....");
 define("PUBLISH_CHECKOUT","While using Google CheckOut as payment option, you have to wait for the approval from Administrator of the site for further steps even after successful payment. ");
 define("PUBLISH_FNAME","Etunimi :");
 define("PUBLISH_LNAME","Sukunimi :");
 define("PUBLISH_CNO","Luottokortin numero :");
 define("PUBLISH_VALID_CODE","Varmuuskoodi :");
 define("PUBLISH_VALID_YEAR","Voimassaoloaika :");
 define("PUBLISH_VALID_YEAR_EG","(MM/YYYY)");
 define("BILLING_ADDRESS","Laskutustiedot");
 define("PUBLISH_COMPANY","Yritys:");
 define("PUBLISH_ADDRESS","Osoite:");
 define("PUBLISH_CITY","Kaupunki:");
 define("PUBLISH_STATE","Kunta : ");
 define("PUBLISH_PH","Puhelin :");
 define("PUBLISH_POSTAL","Postinumero :");
 define("PUBLISH_COUNTRY","Maa : ");
 define("PUBLISH_EMAIL","Sähköposti : ");
 define("KOREAN","Osoite:");
 define("DUTCH","Kunta : ");
 define("NORWEGIAN","Puhelin :");
 define("POLISH","Postinumero :");
 define("RUSSIAN","Maa : ");
 define("SWEDISH","Sähköposti : ");
 define("CHINESE","Laskutusosoite");
 
 //VALIDATION PUBLISH PAGE
 
 define("VAL_INPUT","Incorrect user id as input. Please do not manipulate the url.");
 define("VAL_CREDITCARD","Invalid credit card details.");
 define("VAL_PAYMENT","Invalid payment.");
 define("VAL_FNAME","Please enter first name");
 define("VAL_CARD_NO","Please enter a valid credit card number");
 define("VAL_CODE","Please enter validation code");
 define("VAL_EXPIRY","Please enter expiry date.");
 define("VAL_EMAIL","Please enter a valid email address.");
 define("PUBLISH_CNO","Invalid payment.");
 define("PUBLISH_VALID_CODE","Please enter first name");
 define("PUBLISH_VALID_YEAR","Please enter a valid credit card number");
 define("PUBLISH_VALID_YEAR_EG","Please enter validation code");
 
 //Download site
 
 define("DOWNLOAD_OPTION","Select the option to publish your site.");
 define("DOWNLOAD_ZIP","Download as Zip");
 define("DOWNLOAD_OPTION_DESCRIPTION","The site will be in a compressed file format and the folder contains the pages, styles and images.");
 define("DOWNLOAD_PUBLISH_SERVER","The site will be published to your server location.");
 define("DOWNLOAD_PUBLISH_FTP","Publish to your own location through FTP");
 define("DOWNLOAD_PUBLISH_SUBLOCATION","Publish site to a sublocation (eg:");
 define("DOWNLOAD_PUBLISH_SUBLOCATION2","/sites/mysite)");
 define("DOWNLOAD_PUBLISH_SUBLOCATION_DESC","Your site will be published to a sublocation specified in the site.");
 define("DOWNLOAD_PUBLISH_UPLOAD","Upload site to ' ");
 
 define("DOWNLOAD_PUBLISH_UPLOAD_LOCATION","' location .");
 define("DOWNLOAD_PUBLISH_UPLOAD_LOCATION_DESC","Your site will be published to the location specified by admin.");
 define("DOWNLOAD_PUBLISH_FB","Publish to Facebook fanpage ");
 define("DOWNLOAD_PUBLISH_FB_DESIRED","Your site will be published in your desired Facebook fanpage.");
 define("DOWNLOAD_PUBLISH_AUTHORIZATION","You are not authorized to view this page!");
 define("DOWNLOAD_SITE_REMOVED","Site temporarily removed. Please contact administrator to resolve the issue!");
 
 define("DOWNLOAD_PUBLISH_WRONGSITE","Wrong site id in request.  Please contact admin for details.");
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY1","You can either upload the file directly to your server by providing the FTP information given to you by the hosting company or take it as a zip file which you can upload yourself to your server or store in our server under a directory.");
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY2","You can either upload the file directly to your server by providing the FTP information given to you by the hosting company or take it as a zip file which you can upload yourself to your server.");
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY3","You can either upload the file directly to your server by providing the FTP information given to you by the hosting company or store in our server under a directory.");
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY4","You can either take it as a zip file which you can upload yourself to your server or store in our server under a directory.");
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY5","You can  upload the file directly to your server by providing the FTP information given to you by the hosting company");
 
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY6","You can  take the site files as a zip file which you can upload yourself to your server.");
 define("DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY7","You can  take the site files as a subfolder which you can upload yourself to your server.");
 define("DOWNLOAD_PUBLISHED","Publish Your Site");
 define("DOWNLOAD_SUCCESS","Your site has been successfully published to the following location. ");
 define("DOWNLOAD_CLICK","Click the following link to view the site..");

 
 //custom form validation
 
 define("CUSTOMFRM_MAIL","Email Address is required!");
 define("CUSTOMFRM_INVALID_MAIL","Invalid Email! Please enter a valid email address!");
 define("CUSTOMFRM_ADDELMTS"," Please add elements to your form!");
 define("CUSTOMFRM_CORRECTELMNTS"," Please correct the following errors to continue!");
 
 define("CUSTOMFRM_TEXT_DISPALYTEXT","Textbox Display Text is required!");
 define("CUSTOMFRM_VALID_DISPALYTEXT","Please enter a valid Display Text for the Textbox!");
 define("CUSTOMFRM_TEXTNAME","Textbox Name is required!");
 define("CUSTOMFRM_VALID_TEXTNAME","Please enter a valid field name for the Textbox!");
 define("CUSTOMFRM_TEXTVALUE","Please enter a valid value for the Textbox (Only digits, characters and space allowed)!");
 define("CUSTOMFRM_POSVALUE","Please enter a positive numeric value for Textbox Size!");
 define("CUSTOMFRM_POSMAXVALUE","Please enter a positive numeric value for Textbox Max Length!");
 
 define("CUSTOMFRM_CONTINUE","Please correct the following errors to continue!");
 
 
 
 
 define("CUSTOMFRM_TEXT_UNIQUE","An element with the same name already exists! Please enter a unique Field Name for the Textbox!");
 define("CUSTOMFRM_TEXTAREA","Textarea Display Text is required! ");
 define("CUSTOMFRM_DISPLAY_TEXTAREA","Please enter a valid Display Text for the Textarea!");
 define("CUSTOMFRM_TEXTAREANAME","Textarea Name is required!");
 define("CUSTOMFRM_VALID_TEXTAREANAME","Please enter a valid field name for the Textarea");
 define("CUSTOMFRM_POS_TEXTAREAROW","Please enter a positive numeric value for Textarea Rows! ");
 define("CUSTOMFRM_POS_TEXTARECOL","Please enter a positive numeric value for Textarea Columns!");
 //define("CUSTOMFRM_POS_TEXTARECOL","Please correct the following errors to continue! ");
 define("CUSTOMFRM_TEXTAREA_UNIQUE","An element with the same name already exists! Please enter a unique Field Name for the Textarea!");
 define("CUSTOMFRM_DISPLAYTEXT","Item Display Text is required! ");
 define("CUSTOMFRM_VALID_TEXT_SELECT","Please enter a valid display text for the Selectbox Item (Only digits, characters and space allowed)!");
 define("CUSTOMFRM_VALID_VALUE_SELECT","Please enter a valid display value for the Selectbox Item (Only digits, characters and space allowed)!");
 define("CUSTOMFRM_ITEMPRESENT","Item already present!");
 define("CUSTOMFRM_SELECT_DISPLAYTEXT","Selectbox Display Text is required!");
 define("CUSTOMFRM_SELECT_DISPLAYTEXT_VALID","Please enter a valid Display Text for the Selectbox! ");
 define("CUSTOMFRM_SELECTBOX"," Selectbox Name is required!");
 define("CUSTOMFRM_VALID_SELECTBOX","Please enter a valid field name for the Selectbox! ");
 define("CUSTOMFRM_SELECTBOX_ITEMS","Selectbox Items required! Please add the selectbox items!");
 define("CUSTOMFRM_SELECTBOX_UNIQUE","An element with the same name already exists! Please enter a unique Field Name for the Selectbox!");
 define("CUSTOMFRM_CHECKBOX_TEXT"," Checkbox Display Text is required!");
 define("CUSTOMFRM_VALID_CHECKBOX_TEXT","Please enter a valid Display Text for the Checkbox! ");
 define("CUSTOMFRM_CHECKBOX_NAME"," Checkbox Name is required!");
 define("CUSTOMFRM_VALIDCHECKBOX_NAME","Please enter a valid field name for the Checkbox! ");
  define("CUSTOMFRM_VALIDCHECKBOX_ITEMS"," Checkbox Items required! Please add the checkbox items!");
 define("CUSTOMFRM_VALIDCHECKBOX_UNIQUE","An element with the same name already exists! Please enter a unique Field Name for the Checkbox! ");
 define("CUSTOMFRM_VALIDCHECKBOX_DESC","  Please enter a valid display text for the Checktbox Item (Only digits, characters and space allowed)!");
 define("CUSTOMFRM_VALIDCHECKBOX_VALUE","  Please enter a valid display value for the Checktbox Item (Only digits, characters and space allowed)!");
 define("CUSTOMFRM_CHECKBOX_PRESENT"," Checkbox Item already present/Duplicate value!");
 define("CUSTOMFRM_CHECKBOX_VALID_DISPLAY","Please enter a valid display text for the Radiobutton Item (Only digits, characters and space allowed)! ");
 define("CUSTOMFRM_CHECKBOX_VALID_VALUE","Please enter a valid display value for the Radiobutton Item (Only digits, characters and space allowed)! ");
 define("CUSTOMFRM_RADIO"," Radiobutton Item already present/Duplicate value!");
 define("CUSTOMFRM_RADIOGROUP","The radio group contains a checked item! A radio group cannot have more than one checked item! ");
 
 define("CUSTOMFRM_RADIOTEXT"," Radiobutton Display Text is required!");
 define("CUSTOMFRM_RADIO_VALID","Please enter a valid Display Text for the Radiobutton!");
 define("CUSTOMFRM_RADIONAME","Radiobutton Name is required!");
 define("CUSTOMFRM_RADIO_VALIDNAME","Please enter a valid field name for the Radiobutton!");
 define("CUSTOMFRM_RADIO_UNIQUE","An element with the same name already exists! Please enter a unique Field Name for the Radiobutton!");
 define("CUSTOMFRM_RADIO_ITEMS"," Radiobutton Items required! Please add the radio button items!");
 //define("CUSTOMFRM_RADIO_UNIQUE","An element with the same name already exists! Please enter a unique Field Name for the Radiobutton!");
 
 //CUSTOMFORM TOOLTIP
 
 define("VALID_CFRM_DISPLAY"," Text to be displayed in textbox");
 define("VALID_CUSTOMFRM_TEXTNAME","Textbox name");
 define("VALID_CUSTOMFRM_TEXTVALUE","Textbox value");
 define("VALID_CUSTOMFRM_TEXTSIZE","Textbox size");
 define("VALID_CUSTOMFRM_TEXTLEN","Textbox maximum length");
 define("VALID_CUSTOMFRM_TEXTAREA","Text to be displayed in textarea");
 define("VALID_CUSTOMFRM_TEXTAREA_NAME","Textarea name");
 define("VALID_CUSTOMFRM_TEXTAREA_VALUE","Textarea value");
 define("VALID_CUSTOMFRM_TEXTAREA_ROWS","Number of rows needed ");
 
 define("VALID_CUSTOMFRM_TEXTAREA_COLS"," Number of cloumns needed");
 define("VALID_CUSTOMFRM_SELECT","Text to be displayed in SelectBox");
 define("VALID_CUSTOMFRM_SELECT_NAME","Selectbox Name");
 define("VALID_CUSTOMFRM_SELECT_MULTIPLE","Multiple values can be selected");
 
 define("VALID_CUSTOMFRM_ADD"," Add a new value");
 define("VALID_CUSTOMFRM_ITEM","Item display field");
 define("VALID_CUSTOMFRM_VALUE","Value of Item");
 define("VALID_CUSTOMFRM_CHECKBOX","Text to be displayed in Checkbox");
 
 define("VALID_CUSTOMFRM_CHECKBOX_NAME"," Checkbox name");
 define("VALID_CUSTOMFRM_CHECKBOX_VALUE"," Checkbox value");
 define("VALID_CUSTOMFRM_CHECKBOX_TEXT","Text displayed in item");
 define("VALID_CUSTOMFRM_RADIO","Text to be displayed in radiobutton");
 
 define("VALID_CUSTOMFRM_RADIONAME"," Radiobutton name");
 define("VALID_CUSTOMFRM_RADIOVALUE"," Radiobutton value");

 //custom form
 
 define("CUSTOM_EMAIL"," Your Email Address");
 define("CUSTOM_REQUIREDFIELDS"," Required fields");
 define("CUSTOM_NEW","Add New Field");
 define("CUSTOM_TXTBOX","Create Textbox");
 define("CUSTOM_TXTBOX_TEXT","Textbox Display Text");
 
 define("CUSTOM_TXTNAME"," Textbox Name");
 define("CUSTOM_VALUE","Value");
 define("CUSTOM_SIZE","Size");
 define("CUSTOM_LEN","Max Length");
 define("CUSTOM_TXTAREA","Create Textarea");
 define("CUSTOM_TXTAREA_TEXT","Textarea Display Text ");
 define("CUSTOM_TXTAREA_NAME","Textarea Name");
 define("CUSTOM_TXTAREA_ROWS","Rows");
 define("CUSTOM_TXTAREA_COLS","Columns");
 
 define("CUSTOM_SELECT","Create SelectBox");
 define("CUSTOM_SELECT_TXT","Selectbox Display Text ");
 define("CUSTOM_SELECT_NAME","Selectbox Name ");
 define("CUSTOM_SELECT_MULTI","Multiselect?");
 define("CUSTOM_SELECT_ADD","Add New Value ");
 define("CUSTOM_ITEM_DISPLAY"," Item Display Field:");
 define("CUSTOM_ITEM_ACTUAL","Item Actual Value:");
 define("CUSTOM_ITEM_NOTE","Note ");
 define("CUSTOM_ITEM_NOTE2"," : if Item Actual Value field is empty, Item Display Field will take it as the value");
  
 define("CUSTOM_SELECTED"," Selected");
 define("CUSTOM_EXISTING","Existing items");
 define("CUSTOM_CHECKBOX","Create CheckBox");
 define("CUSTOM_ITEM_DISPLAYTXT"," Item Display Text:");
 define("CUSTOM_CHECKBOX_TEXT","Checkbox Display Text ");
 define("CUSTOM_CHECKBOX_NAME","Checkbox Name ");
 
 define("CUSTOM_CHECKED"," Checked");
 define("CUSTOM_CHECKBOX_GROUP","Create RadioButton Group ");
 define("CUSTOM_RADIO","RadioButton Display Text ");
 define("CUSTOM_RADIO_NAME","RadioButton Name ");
 define("CUSTOM_OUTPUT","Your Output Form");
 
 //Google adsense widget
 
 define("GOOGLE_AD_ID","Google Ad Client ID");
 define("GOOGLE_AD_CID","Enter your Google adsense client ID");
 define("GOOGLE_AD_SLOT","Google Ad Slot");
 define("GOOGLE_AD_SLOTNO"," Enter your Google adsense slot number");
 define("GOOGLE_AD_DIMENSION","Google Ad Dimension");
 define("GOOGLE_VALID_AD_ID","Google adsense client ID ");
 define("GOOGLE_VALID_SLOT","Google adsense slot number ");
 define("GOOGLE_VALID_ADSENSE","Google Ad Dimension");
 
 //slider management
 
 define("SLIDER_SHOW","Slide Show");
 define("SLIDER_SHOW_GALLERY","View Gallery");
 define("SLIDER_UPLOADS","My Uploads");
 define("SLIDER_UPLOAD_IMAGE","Upload Image");
 define("SLIDER_SETTINGS","Settings");
 define("SLIDER_CHOOSE_IMAGE","Choose your image");
 
 //custom form widget
 
 define("FORM_EMAIL","Valid mail address");
 define("FORM_FIELDS","Fields required in feedback form");
 
 
 
 $customform = array('CUSTOMFRM_TEXT_DISPALYTEXT'=>CUSTOMFRM_TEXT_DISPALYTEXT,
 		'CUSTOMFRM_VALID_DISPALYTEXT'=>CUSTOMFRM_VALID_DISPALYTEXT,
 		'CUSTOMFRM_TEXTNAME'=>CUSTOMFRM_TEXTNAME,
 		'CUSTOMFRM_VALID_TEXTNAME'=>CUSTOMFRM_VALID_TEXTNAME,
 		'CUSTOMFRM_TEXTVALUE'=>CUSTOMFRM_TEXTVALUE,
 		'CUSTOMFRM_POSVALUE'=>CUSTOMFRM_POSVALUE,
 		'CUSTOMFRM_POSMAXVALUE'=>CUSTOMFRM_POSMAXVALUE,
 		'CUSTOMFRM_CONTINUE'=>CUSTOMFRM_CONTINUE,
 		'CUSTOMFRM_TEXT_UNIQUE'=>CUSTOMFRM_TEXT_UNIQUE,
 		'CUSTOMFRM_TEXTAREA'=>CUSTOMFRM_TEXTAREA,
 		'CUSTOMFRM_DISPLAY_TEXTAREA'=>CUSTOMFRM_DISPLAY_TEXTAREA,
 		'CUSTOMFRM_TEXTAREANAME'=>CUSTOMFRM_TEXTAREANAME,
 		'CUSTOMFRM_VALID_TEXTAREANAME'=>CUSTOMFRM_VALID_TEXTAREANAME,
 		'CUSTOMFRM_POS_TEXTAREAROW'=>CUSTOMFRM_POS_TEXTAREAROW,
 		'CUSTOMFRM_POS_TEXTARECOL'=>CUSTOMFRM_POS_TEXTARECOL,
 		'CUSTOMFRM_TEXTAREA_UNIQUE'=>CUSTOMFRM_TEXTAREA_UNIQUE,
 		'CUSTOMFRM_DISPLAYTEXT'=>CUSTOMFRM_DISPLAYTEXT,
 		'CUSTOMFRM_VALID_TEXT_SELECT'=>CUSTOMFRM_VALID_TEXT_SELECT,
 		'CUSTOMFRM_VALID_VALUE_SELECT'=>CUSTOMFRM_VALID_VALUE_SELECT,
 		'CUSTOMFRM_ITEMPRESENT'=>CUSTOMFRM_ITEMPRESENT,
 		'CUSTOMFRM_SELECT_DISPLAYTEXT'=>CUSTOMFRM_SELECT_DISPLAYTEXT,
 		'CUSTOMFRM_SELECT_DISPLAYTEXT_VALID'=>CUSTOMFRM_SELECT_DISPLAYTEXT_VALID,
 			
 		'CUSTOMFRM_SELECTBOX'=>CUSTOMFRM_SELECTBOX,
 		'CUSTOMFRM_VALID_SELECTBOX'=>CUSTOMFRM_VALID_SELECTBOX,
 		'CUSTOMFRM_SELECTBOX_ITEMS'=>CUSTOMFRM_SELECTBOX_ITEMS,
 		'CUSTOMFRM_SELECTBOX_UNIQUE'=>CUSTOMFRM_SELECTBOX_UNIQUE,
 		'CUSTOMFRM_CHECKBOX_TEXT'=>CUSTOMFRM_CHECKBOX_TEXT,
 		'CUSTOMFRM_VALID_CHECKBOX_TEXT'=>CUSTOMFRM_VALID_CHECKBOX_TEXT,
 		'CUSTOMFRM_CHECKBOX_NAME'=>CUSTOMFRM_CHECKBOX_NAME,
 			
 		'CUSTOMFRM_VALIDCHECKBOX_NAME'=>CUSTOMFRM_VALIDCHECKBOX_NAME,
 		'CUSTOMFRM_VALIDCHECKBOX_ITEMS'=>CUSTOMFRM_VALIDCHECKBOX_ITEMS,
 		'CUSTOMFRM_VALIDCHECKBOX_UNIQUE'=>CUSTOMFRM_VALIDCHECKBOX_UNIQUE,
 		'CUSTOMFRM_VALIDCHECKBOX_DESC'=>CUSTOMFRM_VALIDCHECKBOX_DESC,
 		'CUSTOMFRM_VALIDCHECKBOX_VALUE'=>CUSTOMFRM_VALIDCHECKBOX_VALUE,
 		'CUSTOMFRM_CHECKBOX_PRESENT'=>CUSTOMFRM_CHECKBOX_PRESENT,
 		'CUSTOMFRM_CHECKBOX_VALID_DISPLAY'=>CUSTOMFRM_CHECKBOX_VALID_DISPLAY,
 		'CUSTOMFRM_CHECKBOX_VALID_VALUE'=>CUSTOMFRM_CHECKBOX_VALID_VALUE,
 		'CUSTOMFRM_RADIO'=>CUSTOMFRM_RADIO,
 		'CUSTOMFRM_RADIOGROUP'=>CUSTOMFRM_RADIOGROUP,
 			
 		'CUSTOMFRM_RADIOTEXT'=>CUSTOMFRM_RADIOTEXT,
 		'CUSTOMFRM_RADIO_VALID'=>CUSTOMFRM_RADIO_VALID,
 		'CUSTOMFRM_RADIONAME'=>CUSTOMFRM_RADIONAME,
 		'CUSTOMFRM_RADIO_VALIDNAME'=>CUSTOMFRM_RADIO_VALIDNAME,
 		'CUSTOMFRM_RADIO_UNIQUE'=>CUSTOMFRM_RADIO_UNIQUE,
 		'CUSTOMFRM_RADIO_ITEMS'=>CUSTOMFRM_RADIO_ITEMS,
 		'CUSTOMFRM_MAIL'=>CUSTOMFRM_MAIL,
 		'CUSTOMFRM_INVALID_MAIL'=>CUSTOMFRM_INVALID_MAIL,
 		'CUSTOMFRM_ADDELMTS'=>CUSTOMFRM_ADDELMTS,
 		'CUSTOMFRM_CORRECTELMNTS'=>CUSTOMFRM_CORRECTELMNTS
 );

?>