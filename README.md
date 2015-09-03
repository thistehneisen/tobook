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

```
cd /var/www && php artisan varaa:generate-configs
```
* Install ElasticSearch

```
wget wget https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.1.deb
```

```
chmod +X elasticsearch-1.7.1.deb
```

```
dpkg -i elasticsearch-1.7.1.deb
```

* Run command to index data

```bash
cd /var/www && php artisan varaa:haku-create-indexes
```

* Build assets

```
 ENV=local npm run build-dev
```
