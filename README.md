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
chmod +x elasticsearch-1.7.1.deb
dpkg -i elasticsearch-1.7.1.deb
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start
```



* Run command to index data

```bash
cd /var/www && php artisan varaa:haku-create-indexes
```

* Install required packages and build assets
```
cd /var/www && npm install
```

```
 ENV=local npm run build-dev
```
