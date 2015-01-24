#!/usr/bin/env bash

DB_HOSTNAME="localhost"       # Database Server Name
MYSQL_ROOT_USER="root"        # MySQL Root User
MYSQL_ROOT_PWD="CHANGEME"     # MySQL Root Passowrd
APP_DB_USER="kumoricon_rw"    # App level database username
APP_DB_PWD="CHANGEME"         # App level database password
APP_DATABASE="registration"   # Database Name

echo Updating package list
apt-get update > /dev/null
echo Instaling debconf-utils
apt-get install debconf-utils -y > /dev/null

echo Setting MySQL root password
debconf-set-selections <<< "mysql-server mysql-server/root_password password ${MYSQL_ROOT_PWD}"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${MYSQL_ROOT_PWD}"

echo Installing MySQL
apt-get install mysql-server -y > /dev/null

echo Dropping database
mysql -u${MYSQL_ROOT_USER} -p${MYSQL_ROOT_PWD} -h${DB_HOSTNAME} -e "DROP USER '${APP_DB_USER}'@'${DB_HOSTNAME}'"
mysql -u${MYSQL_ROOT_USER} -p${MYSQL_ROOT_PWD} -h${DB_HOSTNAME} -e "DROP DATABASE ${APP_DATABASE}"
echo Creating database
mysql -u${MYSQL_ROOT_USER} -p${MYSQL_ROOT_PWD} -h${DB_HOSTNAME} -e "CREATE DATABASE ${APP_DATABASE}"
mysql -u${MYSQL_ROOT_USER} -p${MYSQL_ROOT_PWD} -h${DB_HOSTNAME} -e "grant SELECT, INSERT, UPDATE, DELETE on ${APP_DATABASE}.* to '${APP_DB_USER}'@'${DB_HOSTNAME}' identified by '${APP_DB_PWD}'"


echo Creating tables
mysql -u${MYSQL_ROOT_USER} -p${MYSQL_ROOT_PWD} -h${DB_HOSTNAME} ${APP_DATABASE} < /vagrant/install/01-tables.sql
echo "Adding default user (Username: admin, Password: password)"
mysql -u${MYSQL_ROOT_USER} -p${MYSQL_ROOT_PWD} -h${DB_HOSTNAME} ${APP_DATABASE} < /vagrant/install/02-defaultuser.sql

echo Installing Apache and PHP
apt-get install -y apache2 libapache2-mod-php5 php5-mysql php-db > /dev/null


echo Mounting shared directories
if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant-site /var/www
fi


echo Writing configuration environment variables to /etc/apache2/conf.d/conreg.conf
echo "# ConReg database connection information" > /etc/apache2/conf.d/conreg.conf
echo "SetEnv REG_DB_HOSTNAME '$DB_HOSTNAME'" >> /etc/apache2/conf.d/conreg.conf
echo "SetEnv REG_DB_NAME '$APP_DATABASE'" >> /etc/apache2/conf.d/conreg.conf
echo "SetEnv REG_DB_USER '$APP_DB_USER'" >> /etc/apache2/conf.d/conreg.conf
echo "SetEnv REG_DB_PASS '$APP_DB_PWD'" >> /etc/apache2/conf.d/conreg.conf

apache2ctl restart
