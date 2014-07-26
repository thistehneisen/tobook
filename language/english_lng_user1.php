<?php
error_reporting(0);

/* -----------------------User ----------------------------- */
//login

define("USER_LOGIN","Invalid Login Name / Password!");
define("VAL_LOGIN_NAME","Enter login name");
define("VAL_PWD","Enter password");
define("VAL_LOGIN_NAME_EMPTY","Login Name cannot be empty");
define("VAL_PWD_EMPTY","Password cannot be empty");


//Signup validation

define("SIGNUP_PWDS","Please enter the passwords");
define("SIGNUP_SIMILAR_PWDS","Please enter similar passwords");
define("SIGNUP_LOG_NAME","Enter valid login name");
define("SIGNUP_LOGINEXISTS","Login name already exists. Please try again!");
define("SIGNUP_EMAILEXISTS","Email already exists. Please try again!");

define("SIGNUP_WEL_MSG","We're excited that you have chosen to signup with");
define("LOGIN_INFO","Your login information are as follows");
define("SIGNUP_USERNAME","username :");
define("SIGNUP_PWD"," password :");
define("LOGIN_CLICK","Click");

define("TO_LOGIN","to login.");
define("THANKU","Thank you again for your interest shown on ");
define("REGARDS"," Regards");
define("TEAM","Team");
define("VAL_LOGINNAME","Login name cannot be empty");

define("VAL_NAMELENGHTH","Login name length should be less than 15 characters");
define("VAL_SIGNUP_PWD","Password cannot be empty ");
define("VAL_SIGNUP_CFRMPWD","Confirm password cannot be empty");
define("VAL_SIGNUP_FNAME_EMPTY","First Name cannot be empty");
define("VAL_SIGNUP_EMAIL_EMPTY","Email cannot be empty");

define("VAL_SIGNUP_EMAIL_FORMAT","Invalid mail format");
define("VAL_SIGNUP_LNAME_EMPTY","Last name cannot be empty");
define("VAL_SIGNUP_ADDRESS","Address  cannot be empty");
define("VAL_SIGNUP_ADDRESS2","Address 2 cannot be empty");
define("VAL_SIGNUP_CITY","City cannot be empty");

define("VAL_SIGNUP_STATE","State cannot be empty");
define("VAL_SIGNUP_ZIP","Zip cannot be empty");
define("VAL_SIGNUP_PHONE","Phone cannot be empty");
define("VAL_SIGNUP_FAX","Fax cannot be empty");


//UserMain
 define("SITE_MANAGER","Site Manager");
 define("WELCOME","Welcome");
 define("WELCOME_TO","Welcome to");
 define("WELCOME_NOTE","This site allows you to create your website with advanced components like  image gallery, social plugins, contact form etc.
        It supports the promotion of your website by friend invitations, search engine submissions etc.");
 define("GALLERY_MANAGER","Gallery Manager");
 define("PROFILE_MANAGER","Profile Manager");
 define("PROMOTION_MANAGER","Promotion Manager");
 define("CASHIER_MANAGER","Cashier Manager");
 define("RESTAURANT_BOOKING_MANAGER","Restaurant Booking");
 define("TIMESLOT_BOOKING_MANAGER","Timeslot Booking");
 define("APPOINTMENT_SCHEDULE_MANAGER","Appointment Scheduler");
 define("MARKETING_TOOL","Marketing Tool");
 define("MY_SITES","My Sites");
 define("DASHBOARD_SITE_NAME","Site Name");
 define("DATE_CREATED","Date Created");
 define("STATUS","Status");
 define("OPERATIONS","Operations");
 define("PREVIEW","Preview");
 
 //usermain validation
 define("VAL_DELETE","Are you sure you want to delete this site?");
 
 
 //gallery manager
 define("GALLERY_DESCRIPTION","The Gallery Manager holds your collection of images. Here you can change the image properties while creating your site.");
 define("SELECT_IMAGE","Select Image");
 define("UPLOAD_IMAGE","Upload Image");
 define("UPLOADING","Uploading..");
 define("IMAGE","Image");
 define("TEXT","Text");
 define("ROTATE","Rotate");
 define("DEGREES","Degrees");
 define("BRIGHTNESS","Brightness");
 define("CONTRAST","Contrast");
 define("WIDTH","Width");
 define("HEIGHT","Height");
 define("CROP","Crop");
 define("FLIP","Flip");
 define("EFFECTS","Effects");
 define("SELECT","Select");
 define("NEGATIVE","Negative");
 define("GREYSCALE","Greyscale");
 define("SMOOTH","Smooth");
 define("BLUR","Blur");
 define("CROP_IMAGE","Crop Image");
 define("CLICK","Click");
 define("APPLY","Apply");
 define("SAVING_IMAGE","To Save Your Image.");
 define("CANCEL","Cancel");
 define("RESET_SELECTION","To reset Your Selection.");
 define("FONT","Font");
 define("SIZE","Size");
 define("COLOR","Color");
 define("LOADING","Loading..");
 define("REPLACE_FILE","Replace Existing File");
 define("SAVE_NEWFILE","Save as New File");
 
 //gallary validaton
 
 define("VAL_IMAGE","Only jpg | gif | png formats are supported!");
 define("VAL_NOIMAGE","No Images Found.");
 define("VAL_IMAGE_SELECT","Please select an image.");
 define("VAL_IMAGE_SUCC","Image uploaded successfully");
 define("VAL_IMAGE_SAVE","Any unsaved changes will be lost, do you wish to save your changes?");
 
 //Profile Manager
 
 define("EDIT_PROFILE","Edit Profile");
 define("EDIT_PROFILE_DESC","This is the information you provided to the site at the time of registration.  Edit this information as you see fit.");
 define("EDIT_PASSWORD","Edit Password");
 define("EDIT_PASSWORD_DESC","Edit your password here. If you feel that your current password is known by someone else, please change your password as a precaution.");
 define("PAYMENT_DETAILS","Payment Details");
 define("PAYMENT_DETAILS_DESC","Review all payment details pertaining to your site.");

 //Edit Profile
 
 define("MANDATORY_PART1","Please fill in the following details ( Fields marked");
 define("MANDATORY_PART2","are mandatory ).");
 define("USER_LOGIN_NAME","Login Name");
 define("USER_FIRST_NAME","First Name");
 define("USER_LAST_NAME","Last Name");
 define("USER_EMAIL","Email");
 define("USER_ADDRESS1","Address1");
 define("USER_CITY","City");
 define("USER_STATE","State");
 define("USER_COUNTRY","Country");
 define("USER_ZIP","ZIP");
 define("USER_PHONE","Phone");
 
 //EDIT PROFILE VALIDATION
 
 define("VAL_FNAME","Name cannot be empty");
 define("VAL_LNAME","Lastname cannot be empty");
 define("VAL_ADDRESS","Address cannot be empty");
 define("VAL_EMAIL","Email cannot be empty");
 define("VAL_INVALID_MAIL","Invalid mail format");

 
 //Edit Password
 
 define("USER_NEW_PASSWORD","New Password");
 define("USER_CONFIRM_PASSWORD","Confirm Password");
 
 //edit password validation
 
 define("VAL_PASSWORD_UPDATE","You have successfully updated your password & the new password is :");
 define("REGARDS","Regards,");
 define("THE","The");
 define("TEAM","Team");
 define("VAL_PWD","Updated Password");
 define("VAL_NEW_PASSWORD","New password cannot be empty");
 define("VAL_CONFIRM_PASSWORD","Confirm password cannot be empty");
 define("VAL_PASSWORD_MISMATCH","Password mismatch");
 
 //Promotion Manager
 
 define("SEARCH_ENGINE_SUBMISSION","Search Engine Submission");
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
 
 define("VAL_INDEX_SHORTLY","will be indexed shortly");
 define("VAL_DOC_REMOVED","The document has moved");
 
 
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
 define("YOUR_NAME","Your Name");
 define("YOUR_EMAIL","Your Email");
 define("FRIEND_INFO","Friend's Information");
 define("FRIEND_NAME","Friend's Name");
 define("FRIEND_EMAIL","Friend's Email");
 define("FRIEND_MAILCONTENT","Mail Content");
 define("FRIEND_SUBJECT","Subject");
 define("FRIEND_CONTENT","Content");
 define("FRIEND_REPLACEINFO","Please replace 'yoursite.com' with your site address");
 define("SITE_LINK","Please have a look at the following site.Here is the Link: http://www.yoursite.com ");
 
 //VALIDATION TELLFRIEND
 
 define("VAL_MSG_SENT","Message has been sent to your friend.");
 define("VAL_NAME","Your name cannot be empty");
 define("VAL_EMAIL","Your mail cannot be empty");
 define("VAL_INVALIDMAIL","Invalid mail format");
 define("VAL_FRNDNAME","Friend's name cannot be empty");
 define("VAL_FRNDMAIL","Friend's mail cannot be empty");
 define("VAL_FRIEND_EMAIL","Subject cannot be empty");
 define("VAL_FRIEND_MAILCONTENT","Matter cannot be empty");
 
 
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
 define("ADDITIONAL_INFO","Additional Information");
 define("OPTIONAL","[Optional]");
 define("ROBOTS_OPTION","Robots Option");
 define("ROBOTS_OPTION1","Index this page and follow all links");
 define("ROBOTS_OPTION2","Don't index this page and don't follow any links");
 define("ROBOTS_OPTION3","Index this page, but don't follow any links");
 define("ROBOTS_OPTION4","Don't index this page, but follow links");
 define("ROBOTS_DESCRIPTION","(The Robots META tag is a tag to tell a robot if it is ok to index this page or not. It also is used to invite a spider to walk down through all your pages.)");
 define("REFRESH_RATE","Refresh rate in seconds");
 define("REFRESH_RATE_DESCRIPTION","(Specifies a delay in seconds before the browser automatically reloads the document.)");
 define("COPYRIGHT","Copyright line:");
 define("COPYRIGHT_DESCIPTION","(It tells the unqualified copyright statement for the site.)");
 define("AUTHOR","Author:");
 define("AUTHOR_DESCRIPTION","(It represents the unqualified author's name who created the site. )");
 define("GENERATOR","Generator:");
 define("GENERATOR_DESCRIPTION","(It tells the unqualified copyright statement for the site.)");
 define("LANGUAGE","Language:");
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
 
 define("EDITOR_LOADING"," Loading editor...");
 define("EDITOR","(Editor)");
 define("EDITOR_WIDGETS","Widgets");
 define("EDITOR_PREVIEW","Preview");
 define("EDITOR_HELP","Help");
 define("EDITOR_PAGES","Pages");
 define("EDITOR_ADDEDIT","Add/Edit Page");
 define("EDITOR_FORM","Form");
 define("EDITOR_ADSENSE","Google Adsense");
 define("EDITOR_SLIDER","Slider");
 define("EDITOR_CONTACT","Contact Form");
 define("EDITOR_MENU","Menu");
 define("EDITOR_SOCIALSHARE","Social share");
 define("EDITOR_HTML","HTML");
 define("EDITOR_DRAG","Drag a widget into the layout to include it");
 
 define("EDITOR_PANEL","Edit Panel Content");
 define("EDITOR_UPDATE","Update");
 define("EDITOR_WIDGETS","Widgets");
 define("EDITOR_PREVIEW","Preview");
 define("EDITOR_HELP","Help");
 define("EDITOR_PAGES","Pages");
 define("EDITOR_ADDEDIT","Add/Edit Page");
 define("EDITOR_FORM","Form");
 
 
 //edit template
 
 define("EDITOR_HTML_VIEW","HTML View");
 define("EDITOR_DESIGN","Design View");
 define("EDITOR_MENU_LABEL","Menu label");
 define("EDITOR_ADD_MENU","Add Menu Item");
 define("EDITOR_VIEW_ITEMS","View Menu Items");
 define("EDITOR_LINK_TO","Linked to");
 define("EDITOR_POP_CANCEL","cancel");
 define("EDITOR_MENU_MANAGEMENT","Menu Management");
 define("EDITOR_LABEL","Label");
 define("EDITOR_ACTIONS","Actions");
 define("EDITOR_CONTACTDETAILS","Contact Form Details");
 define("EDITOR_GUEST","Guest Book");
 define("EDITOR_IMAGE_SLIDE","Image Slide Show");
 define("EDITOR_HTML","Html Widget");
 define("EDITOR_PAGESETTINGS","Page Settings");
 define("EDITOR_GOOGLE_ADSENSE","Google Adsense Settings");
 define("EDITOR_CUSTOM_FORM","Custom Form Settings");
 define("EDITOR_FORMSETTINGS","Form Settings");
 define("EDITOR_MENU_SETTINGS","Menu Settings");
 define("EDITOR_MANAGESOCIAL_SHARE","Social Share");
 define("EDITOR_SOCIAL_SHARE","Edit social share");
 define("EDITOR_SLIDE_SHOW","Manage Slide Show");
 define("EDITOR_IMAGE_SETTINGS","Image Settings");
 define("EDITOR_MANGE_IMAGES","Manage Images");
 
 
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
 
 define("FORGOT_PWD","Forgot Password?");
 define("FORGOT_VAL_MSG","Please enter your username to reset your password");
 define("FORGOT_USERNAME","Username");
 define("FORGOT_MSG","Username name cannot be empty");

 
 //Show templates
 
 define("SELECT_TEMPLATE"," Select a template for your site from the given list.");
 define("TEMPLATE_SELECT_CATEGORY"," Select category");
 define("TEMPLATE_SELECTALL"," Select All");
 define("TEMPLATE_CONTINUE","Continue");
 define("TEMPLATE_SAVE","Back");
 define("TEMP_SKIP","Skip");
 define("TEMP_PREVIEW","Preview");
 define("TEMP_PUBLISH","Publish");
 define("TEMP_SAVE","Save");
 //VALIDATION SHOW TEMPLATES
 
 define("VAL_TEMPLATE_CHOOSE"," Please choose a template");
 
 
 //getsitedetails
 define("CUSTOM_APPEARANCE"," Customize Appearance");
 define("CUSTOM_SITE_NAME","Site Name");
 define("CUSTOM_SITE_DESCRIPTION"," Name your site. This will help to uniquely identify your site.");
 define("CUSTOM_SELECT_LOGO","Select Logo");
 define("CUSTOM_NO_LOGOS","No Logos");
 define("CUSTOM_UPLOAD_LOGO","Upload Your Logo");
 define("CUSTOM_UPLOAD_LOGO_DESC","This will be your logo of the site. Click 'Browse' to upload a new site logo.");
 define("CUSTOM_SELECT_SAMPLE"," Select From Sample Logos");
 define("CUSTOM_SELECT_SAMPLE_LOGO","Select your site logo from our sample logos.");
 define("CUSTOM_YOUR_LOGO"," Your Logo");
 define("CUSTOM_WEBSITE_TITLE","Website Title");
 define("CUSTOM_SITE_TITLE"," Site Title");
 define("CUSTOM_SITE_TILEDESCRIPTION","The site title might be the name of your company or organization. This title will appear at the top of your site and can be changed at any time.");
 define("CUSTOM_FONT"," Font");
 define("CUSTOM_SIZE","Size");
 define("CUSTOM_COLOR","Color");
 define("CUSTOM_STYLE4SITE","Use the above styles for the site title.");
 define("CUSTOM_SITE_COLOR","Site Color");
 define("CUSTOM_SELECT_BACKGROUND","Select Background Color");
 define("CUSTOM_BACKGROUND_DESCRIPTION","This will be the background color of your site. Site color can be changed to make variations in the overall look and feel of the site. This effect can be viewed in site preview, not in Editor");
 define("CUSTOM_DESCRIPTION","Description");
 define("CUSTOM_SITE_DESC","Site Description");
 define("CUSTOM_DESCRIPTION_DETAIL","Give a brief description of what your website is about. This will display in your site below the website title.");
 define("CUSTOM_STYLE4DESCRIPTION","Use the above styles for the site description.");
 define("CUSTOM_OTHERS","Others");
 define("CUSTOM_ANALYTICS","Google Analytics Code");
 define("CUSTOM_ANALYTICS_DESCRIPTION","Google Analytics (GA) is a free service offered by Google that generates detailed statistics about the visitors to a website.Code might look like "."UA-XXXXX-Y"."");
 define("CUSTOM_GET_ANALYTICS","Get Google Analytics Code");
 define("CUSTOM_META_TITLE","Meta Title");
 define("CUSTOM_META_TITLE_DESCRIPTION"," Search engines will look at your meta titles to help decide what category your provision falls into. Your meta title should provide a description in a set of 3 or so phrases and will show in search engine results and will also be displayed at the top of the computer screen when visitors are browsing that page.");
 define("CUSTOM_META_DESCRIPTION","Meta Description");
 define("CUSTOM_META_DETAIL","The meta description tag is intented to be a brief summary of your page's content.");
 define("CUSTOM_META_KEYWORDS","Meta Keywords");
 define("CUSTOM_META_KEYWORDS_DESCRIPTION"," A meta keywords tag is supposed to be a brief and concise list of the most important terms of your page. Keep your list of keywords or keyword phrases down to 10 - 15 unique words or phrases and separate the words or phrases using a comma. ");

 //VALIDATION SITE DETAILS
 
 define("VAL_ERRMSG1","The space taken by you has exceeded the allowed limit.<br>(Space taken by you: ");
 define("VAL_ERRMSG2","<br>Allowed space:");
 define("VAL_ERRMSG3","<br>Delete unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>");
 define("VAL_IMAGETYPE","Please upload only .jpg,.gif,.png image types");
 
 
 //PUBLISHPAGE
 
 define("BACK_EDITOR","Back To Editor");
 define("BACK_PAYMENT","Back To Payment");
 define("PUBLISH_AMOUNT","Amount :");
 define("PUBLISH_NOTE","Note:");
 define("PUBLISH_WARNING","Pleae make sure that you return back to this site, it is necessary to finish your order.");
 define("PUBLISH_WARNING2","Payment Interface currently unavailable. Please");
 define("PUBLISH_WARNING3","contact administrator");
 define("PUBLISH_WAITING_INTERFACE","Waiting for the secure payment interface ....");
 define("PUBLISH_CHECKOUT","While using Google CheckOut as payment option, you have to wait for the approval from Administrator of the site for further steps even after successful payment. ");
 define("PUBLISH_FNAME","First Name :");
 define("PUBLISH_LNAME","Last Name :");
 define("PUBLISH_CNO","Card Number :");
 define("PUBLISH_VALID_CODE","Validation Code :");
 define("PUBLISH_VALID_YEAR","Expiration Date :");
 define("PUBLISH_VALID_YEAR_EG","(MM/YYYY)");
 define("BILLING_ADDRESS","Billing Address Details");
 define("PUBLISH_COMPANY","Company:");
 define("PUBLISH_ADDRESS","Address:");
 define("PUBLISH_CITY","City:");
 define("PUBLISH_STATE","State/Province : ");
 define("PUBLISH_PH","Phone No :");
 define("PUBLISH_POSTAL","Postal Code :");
 define("PUBLISH_COUNTRY","Country : ");
 define("PUBLISH_EMAIL","Email : ");
 define("KOREAN","Address:");
 define("DUTCH","State/Province : ");
 define("NORWEGIAN","Phone No :");
 define("POLISH","Postal Code :");
 define("RUSSIAN","Country : ");
 define("SWEDISH","Email : ");
 define("CHINESE","Billing Address Details");
 
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