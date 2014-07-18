VARAA
=====

Prerequisites:
-------------
- Apache
- PHP >= 5.4.0
- MySQL >= 5.6.0
- Note: You can use any existing LAMP stack like https://www.apachefriends.org/index.html or http://www.easyphp.org/ or vagrant if you are advanced user.


Setup in localhost:
-------------------
- Clone this repo to your localhost
- Create a local domain and point to above folder, for example: varaa.loc
- Create a new DB in your localhost
- Import the DB dump: db/dev_dump.sql
- Create local config.php and configure to match your localhost settings: cp config.php.tpl config.php


PHP coding convention:
----------------------
- Let's follow: http://www.php-fig.org/psr/psr-2/
- To be consistent with the rest of the project, let's use 1TBS (One True Brace Style: http://en.wikipedia.org/wiki/Indent_style#Variant:_1TBS) style for braces