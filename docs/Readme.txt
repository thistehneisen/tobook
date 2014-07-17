******************************************************************************************
                        iScripts EasyCreate 3.1 Readme File
                                 December, 2013
******************************************************************************************
(c) Copyright Armia Systems,Inc 2005-11. All rights reserved.

This file contains information that describes the installation of iScripts EasyCreate 3.1
You can find more help on the product and the installation in the /docs folder.


******************************************************************************************
Contents
******************************************************************************************
1.0 Introduction
2.0 Requirements
3.0 Installing iScripts EasyCreate 3.1
4.0 Upgrading iScripts EasyCreate from 3.0 to 3.1

******************************************************************************************
1.0 Introduction
******************************************************************************************
This file contains important information you should read before installing
iScripts EasyCreate 3.1

The iScripts EasyCreate 3.1 enables any web master to offer site creation
service for novice and expert users. The web master can offer templates for
the users to create their sites from.

The iScripts EasyCreate 3.1 relies on GD support and it requires GD
library to be enabled in your PHP.


******************************************************************************************
2.0 Requirements
******************************************************************************************
The iScripts EasyCreate 3.1 is developed in PHP and the database is
MySQL. Since the software uses a considerably large number of GD library
(A PHP add-on library used for image manipulation) functions, it requires your
version of PHP compiled with the GD library extension.

The requirements can be summarized as given below:
	1. PHP > 5.x.x with GD support.
		You can get the latest PHP version at http://www.php.net
	2. MySQL > 3.x.x
	3. Curl Support (For using Authorize.net, LinkPoint payment gateway)

   Other Requirements for Trouble free installation/working
	* GD complied with your PHP Build - (Yes).
	* SendMail - (Yes).
	* PHP safe mode - (OFF).
	* PHP open_basedir - (OFF).
	* CURL extension - (Yes).
	* DOMXML extension - (Yes).

******************************************************************************************
3.0 Installing iScripts EasyCreate 3.1
******************************************************************************************
3.1) Unzip the entire contents to a folder of your choice.

	a) Upload the contents to the server to the desired folder using an FTP client.If you do not
	have a FTP client we suggest  CoreFTP or FTPzilla

3.2) Set 'Write' permission (777 for linux server) to the following files/folders.

	a) The folder to which you have extracted your files to.
	b) workarea/
	c) workarea/sites/
	d) sites/
	e) uploads/
	f) uploads/siteimages/
	g) uploads/siteimages/banners/
	h) uploads/themes/
	i) uploads/thumb/
	j) templates
	k) samplelogos/
	l) categoryicons
	m) includes/install_config.php
	n) includes/configsettings.php
        0) systemgallery/
        p) usergallery/



3.3) Run the following URL in your browser and follow the instructions.

	http://folder_to_which_you_have_extracted_files/install/

	If you have uploaded the files in the root (home directory), you can
	access the site builder at http://www.yoursitename.

	You can also install the script in any directory under the root. For
	example if you have unzipped the zip file in a directory like
	http://www.yoursitename/sitebuilder then you can access the image
	gallery site at http://www.yoursitename/sitebuilder

	If you have no GD Library support in your PHP, recompile your PHP
	with GD Support to continue.

	Make the changes requested by the installer if any and then refresh
	the installation url mentioned above to continue.

3.4) Provide the database details. (The database should be created and
       appropriate permissions must be set before you continue)

3.5) Make sure you enter the same license key you received at the time of purchase,in the
	"License Key" field. The script would function only for the domain it is licensed.
	If you cannot recall the license its also included in the email you received with subject: 		“iScripts.com software download link” . You can also get the license key from your user panel
	at www.iscripts.com

3.6) Enter the site settings. This includes the Site Name, Admin Password,
	 Admin Email etc.
	The settings values are described as below:
	a. Site Name : This is the name of your site.
	b. Admin Password : The default admin name is 'admin' and it is not
		changeable. This field sets the administrator password.
		Choose a password which is hard to guess.
	c. Admin Email : The administrator's email address
	d. Site building mode : Paid/Free.
	e. Enable Paypal : Decide wheather to enable paypal.
	f. Paypal Email Address : Paypal Login ID.
	g. Paypal Identity Token : Identity Token issued from Paypal.
	h. Google Checkout : Decide wheather to enable google checkout.
	i. Google CheckOut ID : Google Merchant ID.
	j. Google CheckOut Key : Google Merchant Key.
	k. Payment Gateway : Decide which payment gateway needed.
	l. No of days temporary sites should be maintained : The sites created
		by users are stored as files in the web server. This value sets
		the number of days these temporary files will be kept in the
		web server. Higher this value, the more space you must reserve
		for your users.
	m. Site price : The charge in dollars for publishing a site.
	n. Authorize.net Account Transaction Key : Your authorize.net transaction
		key for payment gateway
	o. Authorize.net Account Login Id : Your authorize.net Login Id for
		payment gateway.
	p. Publish Option : Site publishing options for users.
	q. Default Root directory for FTP Uploads : The default root directory which
		will be used as the default value on site FTP upload by users.
	r. Logo : Your site logo.

3.7) Remove the 'Write' permission provided to the file 'includes/configsettings.php'
	and the folder to which you have extracted the files to.

3.8) Delete the 'install' folder.

3.9) EasyCreate using the htaccess in the root(home directory). Remove the htaccess files if its present in the other folders while installing EasyCreate.

3.10) Run the URL you have extracted the sitebuilder files in your browser to access the
	site. If you have uploaded the files in the root (home directory), you can
	access the site builder at http://www.yoursitename.


******************************************************************************************
4.0 Upgrading iScripts EasyCreate from 3.0 to 3.1
******************************************************************************************
Note: You will lose all customizations, that you have already done.

4.1) Download the new version of Easycreate and extract files (say easycreate).

4.2) Take the backup of the existing EasyCreate files and folders (say easycreate_bkp).

4.3) Take the backup of the existing EasyCreate Database.

4.4) Replace includes/configsettings.php, folders like systemgallery,usergallery,categoryicons,samplelogos,sites,uploads,workarea from the backed up folder(easycreate_bkp) to the new easycreate folder(easycreate)

4.5) Run the following URL in your browser and follow the instructions.

	http://folder_to_which_you_have_extracted_files/upgrade3.1/

	