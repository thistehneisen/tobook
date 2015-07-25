# VARAA

## Prerequisites
- Apache
- PHP >= 5.4.0
- MySQL >= 5.6.0
- Composer https://getcomposer.org/
- Note: You can use any existing LAMP stack like
 `https://www.apachefriends.org/index.html`
  or `http://www.easyphp.org/` or vagrant if you are advanced user.

## PHP coding convention
- Learn by heart [PSR-2](http://www.php-fig.org/psr/psr-2/)
- For Sublime Text 3 user, try [Editor Config](http://editorconfig.org/)
(a `.editorconfig` is already included in the repo).

#### Run PHP Code Sniffer :gun: to check coding style
`./vendor/bin/phpcs -s --standard=./ruleset.xml app`

#### Run PHP CS Fixer to fix your code automatically
`./vendor/bin/php-cs-fixer fix app/lang/`

## Setup localhost
- Clone this repo
- Create a virtualhost and point it to `public/` folder
- Install all needed packages: `composer install`
- Create a new local DB and import `db/dev_dump.sql`
- Generate your local config files with: `php artisan varaa:generate-configs`
and change your database config to match your local setup in `app/config/local/database.php`
- Run `fab r` (Python's Fabric required) to start all needed dependancies: redis-server, ElasticSearch, grunt...
- Start hacking!

## Project layout
We are not using the "standard" L4 project layout with `models` and `controllers` folder in `app/`. Instead, the main source code are stored in `app/src/` and separated into different modules. Each module contains its own models, controllers and commands, any shared controllers or models will be moved into `app/src/Core`.

_See `app/src/Appointment` for example since AS is the biggest module of this project._
_All the views are however still staying in standard folder `app/views`, all the folder names in there are already self-explanatory._

## Eco-system

#### CRUD builder
To reduce time working on boring stuffs like CRUD, we have a well written trait named `Olut`, please take a look at the [documentation](app/src/Olut/README.md) and use it.

#### Form builder
We have another module named `Lomake` to support developer build forms faster, [documentation](app/src/Lomake/README.md).

#### Make a user with given email an Admin
`php artisan varaa:admin <email>`

## Tests
- Create a blank database with `varaa_test` for database name, username and password in your localhost
- Read about Codeception docs: http://codeception.com/docs/
- Run the test: `./vendor/bin/codecept run` or `fab t`
- Command `fab t` provides shortcut to run tests, format is `fab t:{suite},{module},{debug}`
    + `fab t:unit` will run all unit tests
    + `fab t:functional,as` will run all functional tests belong to AS module
    + `fab t:unit,,1` will run all unit tests in debug mode
- NOTE: we are using Selenium to run acceptance tests, so there are some steps you have to do to prepare for that:
    + Setup a domain for testing: `varaa.test`
    + Run `fab ta` or `fab r` to download selenium and run it before running acceptance test suite


### Get config values of L4 in other external modules

- Add `require_once base_path().'/Bridge.php';`
- Call `Bridge::config($key)`, for example: `$varaaDb = Bridge::config('database.connection.mysql');` or `$varaaDb = Bridge::dbConfig();`

_See `public/cashier/library/sma/config/database.php` for example. Try to avoid common names such as `$config`, `$cfg`, etc._

Feel free to add more features (sending email, storing cache, etc.) to that Bridge as you want. L4 libraries could be accessed via IoC container http://laravel.com/docs/ioc :dancers:

### Build assets using gulp

- Install node.js (https://github.com/joyent/node/wiki/Installation)
- At the root folder of project, run `npm install` to install dependencies
- Run `gulp clean && gulp build`

## Development flow

- Use TDD as much as possible when developing new features
- Whenever you receive a bug report, write test to replicate that bug first then fix it and run the test suite for that module / group again to verify everything works
- Code without tests is considered incomplete

## Setup new server automatically

Run `fab new_server:<hostname> -H root@<new server IP>`

## Make a patch

Run `fab patch <branch_name>` to merge `<branch_name>` into `master` and `develop` and bump version in patch mode.
