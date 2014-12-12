#!/usr/bin/env bash

apt-get update
apt-get install -y apache2
apt-get install -y libapache2-mod-php5 php5-mysql mysql-server php-db


if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant-site /var/www
fi

