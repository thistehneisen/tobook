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
