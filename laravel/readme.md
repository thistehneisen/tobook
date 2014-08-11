#### Run PHP Code Sniffer :gun: to check coding style
`./vendor/bin/phpcs -s --standard=./ruleset.xml app`

#### Run PHP CS Fixer to fix your code automatically
`./vendor/bin/php-cs-fixer fix app/lang/`

#### Move users from old table to new one
`php artisan varaa:move-users`
