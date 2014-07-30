VARAA
=====
The 1 ton solution for all small businesses.

Instances
---------
- Dev / staging: http://dev.varaa.co/
- Prod: http://varaa.co/
- Old prod (DNS will be updated to be prod soon): http://klikkaaja.com/


Prerequisites
-------------
- Apache
- PHP >= 5.4.0
- MySQL >= 5.6.0
- Note: You can use any existing LAMP stack like
 `https://www.apachefriends.org/index.html`
  or `http://www.easyphp.org/` or vagrant if you are advanced user.


Setup in localhost
------------------
- Clone this repo to your localhost
- Create a local domain and point to above folder, for example: `yourlocaldomain.loc`
- Create a new DB in your localhost
- Import the DB dump: `db/dev_dump.sql`
- Create local `config.php` and configure to match your localhost settings:
 `cp config.php.tpl config.php`
- Create local config for Appointment Scheduler:
  `php runner config yourlocaldomain.loc`
- Start hacking (or messing)!


PHP coding convention
---------------------
- PSR-2 `http://www.php-fig.org/psr/psr-2/` for L4 or framework specific
convention.
- To be consistent with the rest of the project, let's use 1TBS
(One True Brace Style: http://en.wikipedia.org/wiki/Indent_style#Variant:_1TBS) 
style for braces
- For Sublime Text 3 user, try [Editor Config](http://editorconfig.org/) 
(a `.editorconfig` is already included in the repo).


Tests
-----
- Read http://codeception.com/docs/01-Introduction and http://codeception.com/docs/04-AcceptanceTests
- Create and change test config: 
`cp tests/acceptance.suite.yml.tpl tests/acceptance.suite.yml`
- Generate your acceptance tests in tests/acceptance/ folder: 
`php codecept.phar generate:cest acceptance YourTestName`
- Run the test: `php codecept.phar run`

Migrate modules with new database schema
----------------------------------------
- Import the corresponding database schema in `db` folder, for example: 
`db/cashier-schema.sql`
- Run `composer update` to install dependencies
- Run `php runner migrate <module_name>` to import old data. Module name must be
in CamelCase, i.e. `php runner migrate Cashier`.
- Done (hopefully)

How to write new command to migrate data
----------------------------------------
- Have a look at `cmd\Migrate\Cashier.php`
- Create new class in `cmd\Migrate`, and it should extend from `Cmd\Migrate\Base`
- Implement `run()`
