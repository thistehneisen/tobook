# VARAA

### Prerequisites
- Apache
- PHP >= 5.4.0
- MySQL >= 5.6.0
- Composer https://getcomposer.org/
- Note: You can use any existing LAMP stack like
 `https://www.apachefriends.org/index.html`
  or `http://www.easyphp.org/` or vagrant if you are advanced user.

### PHP coding convention
- Learn by heart [PSR-2](http://www.php-fig.org/psr/psr-2/)
- For Sublime Text 3 user, try [Editor Config](http://editorconfig.org/)
(a `.editorconfig` is already included in the repo).

### Setup localhost
- Clone this repo
- Create a virtualhost and point it to `public/` folder
- Install all needed packages: `composer install`
- Create a new local DB and import `db/dev_dump.sql`
- Generate your local config files with: `php artisan varaa:generate-configs`
and change your database config to match your local setup in `app/config/local/database.php`
- Run command to do needed fixes to DB: `php artisan varaa:install`

### Tests
- Create test config: `cp app/tests/acceptance.suite.yml.tpl app/tests/acceptance.suite.yml` then modify to match your local config
- Read http://codeception.com/docs/01-Introduction and http://codeception.com/docs/04-AcceptanceTests
- Generate your acceptance tests in `app/tests/acceptance/` folder:
`./vendor/bin/codecept generate:cest acceptance YourTestName`
- Run the test: `./vendor/bin/codecept run`
- A best practice guide http://www.sitepoint.com/ruling-the-swarm-of-tests-with-codeception/

#### Run PHP Code Sniffer :gun: to check coding style
`./vendor/bin/phpcs -s --standard=./ruleset.xml app`

#### Run PHP CS Fixer to fix your code automatically
`./vendor/bin/php-cs-fixer fix app/lang/`

#### Make a user with given email an Admin
`php artisan varaa:admin <email>`

#### HUOM!
Database schemas for tables of modules on site were changed, please run `php artisan varaa:fix-schema` (possible to run multiple times).

#### Get config values of L4 from other modules

* Add `require_once base_path().'/Bridge.php';`
* Call `Bridge::config($key)`, for example: `$varaaDb = Bridge::config('database.connection.mysql');` or `$varaaDb = Bridge::dbConfig();`

_See `/public/cashier/library/sma/config/database.php` for example. Try to avoid common names such as `$config`, `$cfg`, etc._

Feel free to add more features (sending email, storing cache, etc.) to that Bridge as you want. L4 libraries could be accessed via IoC container http://laravel.com/docs/ioc :dancers:
