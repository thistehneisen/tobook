# VARAA - Guide to setup local development environment with Vagrant


## Setup localhost
* Install vagrant

```
http://www.vagrantup.com/downloads
```

* Vagrant up

* Clone the code

```
 cd /var/wwww && git clone https://github.com/varaa/varaa.git .
```

* Install Laravel

```
cd /var/wwww && composer install
```

* Import database

```
 mysql -uroot -p varaa_stag < backup.sql
```

* Create local config
