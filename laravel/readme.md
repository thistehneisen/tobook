#### Run PHP Code Sniffer :gun: to check coding style
`./vendor/bin/phpcs -s --standard=./ruleset.xml app`

#### Run PHP CS Fixer to fix your code automatically
`./vendor/bin/php-cs-fixer fix app/lang/`

#### Move users from old table to new one
`php artisan varaa:move-users`

#### Get config values of L4 from other modules

* Find the path to `Bridge.php` from inside module
* `require/include` that file
* Call `Bridge::config($key)`

_See `/public/cashier/library/sma/config/database.php` for example. Try to avoid common names such as `$config`, `$cfg`, etc._

Feel free to add more features (sending email, storing cache, etc.) to that Bridge as you want. L4 libraries could be accessed via IoC container http://laravel.com/docs/ioc :dancers:
