FORMS_READ_ME

We include two demonstration form porcessing applications.

PHP_FORM_MIN.html sends a post to the PHP_FORM_MIN.php script 
which transacts with LSGS.  PHP_FORM_MAX.html posts to the 
PHP_FORM_MAX.php script.  Both of these scripts call functions
in the lphp.php module.

Depending on your server setup, the scripts that processes
this form, *MAX.php, *MIN.php and the LinkPoint PHP module
lphp.php, may need to be located in a cgi-bin directory,
and the path in the "action" method of the HTML forms will
need to be adjusted accordingly.

The form fields have been pre-filled with test data only
for convenience.  

