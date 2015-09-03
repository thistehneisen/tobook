## VARAA - Guide to setup local development environment with Vagrant


### Setup localhost
* Install vagrant

```
http://www.vagrantup.com/downloads
```

* Vagrant up

```bash
vagrant up
```

Puppet will automatically install apache2, php5, mysql, redis, java and composer for you. A empty database `varaa_stag` is also created.

* Clone the code

```
 cd /var/www && git clone https://github.com/varaa/varaa.git .
```

* Install Laravel

```bash
cd /var/www && composer install
```

* Import database

```bash
 mysql -uroot -p varaa_stag < backup.sql
```

* Create local config

```php
//app/config/local/app.php
<?php

return [
    'debug' => true,
    'url' => 'http://varaa.dev',
    'key' => $_ENV['SECRET_KEY'],
];
```

* Run command to index data

```bash
cd /var/www && php artisan varaa:haku-create-indexes
```
