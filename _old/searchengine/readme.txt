***********************************************************************
* SubmitForce v1.0						      +
* Search Engine autosubmission script.				      +
* Arun Vijayan <ifetchmaster@hotmail.com>			      +

INTRO:
SubmitForce is an automated Search engine submitter class. It submits
URLs to search engines and display the status of the submission.

* SF Supports GET and POST methods.
* Custom HTTP header formatting.
* optional client side form validation
* Easiest new engine addition
* Advanced troubleshooting functionality
* No database required.

SF submit URLs using the POST/GET form methods. Customized
HTTP headers can be framed so that headers like User-Agent,
Referer etc. can be easily changed and submitted. This makes it
comparable to manual submission. No Database required for SF and
new engines can be easily added in a text file. Currently supporting
engines like AllTheWeb, Google, WhatUseek, Voila[fr], Rediff[in],
ScrubTheWeb, ExactSeek, SplatSearch, TrueSearch, GigaBlast, SearchUK etc. SubmitForce has DEBUG and ONLINE modes of operation. "DEBUG" mode is for troublshooting and tweaking. It will show all status messages 
and engine respones. "ONLINE" mode only print status of each submission
which is suitable for a production site.

SubmitForce is a freeware. You can copy and re-distribute the code
as you want.

INSTALL:
Upload the files to your webserver directly. The engines.dat file must
be readable by the PHP files. No other configuration required.

ADDING MORE SEARCH ENGINES:
engines.dat file reserves one line for each engine. Each line containes
comma seperated datafields. It can be customized for the user preferences. To add a new listing, try submitting manually once. Find out submitting method, target url and the message shown if the submission was successfull. Arrange these data in the format given below.

Format of the dat file is;
enginename, fullengineurl, method, success string, user-agent, referer 

If the method is POST, then you have to look at the engine form source. write down each variable-value pairs and join them with '='  and attach it with target url using '?'. 

eg.
http://www.some-good-engine.com/add_url.php?url=[URL]&email=[EMAIL]

NOTE: the submitted url and email are replaced with [URL] and [email]

variables placeholders in the data file are
[url]	= full url that is submitted
[email] = email of the user
[ip]	= IP of the user         (not using in current version)
[description] = meta description (not added in current version)
[keywords] = meta keywords       (not added in current version)

Comment lines start with charecter #

TROUBLESHOOTING :



FILE INFO :
readme.txt		- this file
licence.txt             - GNU Public Licence
SubmitForce.class.php	- SubmitForce class lib
index.php		- submission starter
submit.php		- submitter
_config.php		- SF configurtion options
submitforce.gif		- logo
submitforce.css		- default stylesheet



Greetz:
Lot of people to say a thanx. anyway special regards to Snoopy at http://snoopy.sourceforge.net/ it really made me think not to write a network client anymore.

 

