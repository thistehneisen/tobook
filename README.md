# VARAA

Currently this is a separate branch that incorporates Laravel 4 as the core
framework. Bug fixes and changes of old source code are still continued
developing in branch `master`. Developers are advised (or forced) to create a
new VHost pointing to `laravel/public` to facilitate development process.

### Prerequisites
- Apache
- PHP >= 5.4.0
- MySQL >= 5.6.0
- Composer
- Note: You can use any existing LAMP stack like
 `https://www.apachefriends.org/index.html`
  or `http://www.easyphp.org/` or vagrant if you are advanced user.

### PHP coding convention
- PSR-2 `http://www.php-fig.org/psr/psr-2/` for L4 or framework specific
convention.
- For Sublime Text 3 user, try [Editor Config](http://editorconfig.org/) 
(a `.editorconfig` is already included in the repo).

### Configuration
The site is using hostname-aware to detect environment. Please set your hostname
to `dev` or add a new hostname to line 29 of `bootstrap/start.php`.

After that, config values for local development are set in `app/config/local`.
This folder is ignored by default, so developers can safely set anything they
want without polluting others' environment. Please copy `config/database.php`
into the folder and change its settings. Some other useful settings:

###### `app/config/local/app.php`
```
return [
    'debug' => true,
    'locale' => 'fi',
];
```

###### `app/config/local/mail.php`
```
return [
    'pretend' => true
];
```

### HUOM!
Database schemas for tables of modules on site were changed, please run `php artisan varaa:fix-schema` (possible to run multiple times).

### Tests
- Create test config: `cp app/tests/acceptance.suite.yml.tpl app/tests/acceptance.suite` then modify to match your local config
- Read http://codeception.com/docs/01-Introduction and http://codeception.com/docs/04-AcceptanceTests
- Generate your acceptance tests in tests/acceptance/ folder: 
`php codecept.phar generate:cest acceptance YourTestName`
- Run the test: `php codecept.phar run`
- A best practice guide http://www.sitepoint.com/ruling-the-swarm-of-tests-with-codeception/

#### Run PHP Code Sniffer :gun: to check coding style
`./vendor/bin/phpcs -s --standard=./ruleset.xml app`

#### Run PHP CS Fixer to fix your code automatically
`./vendor/bin/php-cs-fixer fix app/lang/`

#### Move users from old table to new one
`php artisan varaa:move-users`

#### Add a user with given email to be an Admin
`php artisan varaa:admin <email>`

#### Get config values of L4 from other modules

* Find the path to `Bridge.php` from inside module
* `require/include` that file
* Call `Bridge::config($key)`

_See `/public/cashier/library/sma/config/database.php` for example. Try to avoid common names such as `$config`, `$cfg`, etc._

Feel free to add more features (sending email, storing cache, etc.) to that Bridge as you want. L4 libraries could be accessed via IoC container http://laravel.com/docs/ioc :dancers:
