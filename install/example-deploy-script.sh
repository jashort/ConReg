#!/usr/bin/env bash

# Example deploy script that will connect to the destination server, optionally
# create the databases, and upload site files. Set the configuration options below.
# Note that the .htaccess file in the remote directory will be overwritten.

# Run with the parameter --database to deploy the database as well (it will be 
# cleared if it already exists)

DB_HOSTNAME="localhost"                               # Database Server Name (from destination server)
MYSQL_ROOT_USER="root"                                # MySQL Root User
MYSQL_ROOT_PWD="CHANGEME"                             # MySQL Root Passowrd
APP_DB_USER="appuser"                                 # App level database username (read/write to database only)
APP_DB_PWD="CHANGEME"                                 # App level database password
APP_DATABASE="registration"                           # Database Name
DEST_PATH="/absolute/path/to/site/directory/"         # Path to site files. Must have trailing slash
DEST_USER="remoteuser"                                # Destination server login name for SSH
DEST_HOST="hostname.example.com"                      # Destination server hostname

if [ "$1" == "--database" ]; then
    CLEAR_DATABASE=true
    echo "Database will be deployed"
elif [ "$1" == "" ]; then
    CLEAR_DATABASE=false
    echo "Database will not be changed (use --database to argument to deploy)"
else
    # If run with any argument besides --database, display help
    echo " "
    echo "Usage:"
    echo "  ${0} [--database]"
    echo " "
    echo " --database (optional) Clear and redeploy the database"
    echo " "
    echo "Removes all files from the destination site and redeploys them"
    echo " "
fi

echo Clearing temp files used for deployment
rm -f /tmp/conreg.tgz /tmp/conreg.conf

echo Compressing site files to upload
tar -czvf /tmp/conreg.tgz --directory ../site/ . > /dev/null

echo Clearing destination
ssh "${DEST_USER}"@"${DEST_HOST}" rm -rf "${DEST_PATH}"* "${DEST_PATH}".htaccess
if [ "${CLEAR_DATABASE}" = true ] ; then
    echo "Recreating database"
    ssh "${DEST_USER}"@"${DEST_HOST}" mysql -u"${MYSQL_ROOT_USER}" -p"${MYSQL_ROOT_PWD}" -h"${DB_HOSTNAME}" "${APP_DATABASE}" < ../install/01-tables.sql
    ssh "${DEST_USER}"@"${DEST_HOST}" mysql -u"${MYSQL_ROOT_USER}" -p"${MYSQL_ROOT_PWD}" -h"${DB_HOSTNAME}" "${APP_DATABASE}" < ../install/02-defaultuser.sql
fi

echo Writing configuration environment variables to /tmp/conreg.conf
echo "# ConReg database connection information" > /tmp/conreg.conf
echo "SetEnv REG_DB_HOSTNAME '${DB_HOSTNAME}'" >> /tmp/conreg.conf
echo "SetEnv REG_DB_NAME '${APP_DATABASE}'" >> /tmp/conreg.conf
echo "SetEnv REG_DB_USER '${APP_DB_USER}'" >> /tmp/conreg.conf
echo "SetEnv REG_DB_PASS '${APP_DB_PWD}'" >> /tmp/conreg.conf
echo Uploading config file to remote .htaccess file
scp /tmp/conreg.conf "${DEST_USER}"@"${DEST_HOST}":"${DEST_PATH}".htaccess

echo Uploading site files
scp /tmp/conreg.tgz "${DEST_USER}"@"${DEST_HOST}":"${DEST_PATH}" > /dev/null

echo Extracting site files
ssh "${DEST_USER}"@"${DEST_HOST}" tar -xzvf "${DEST_PATH}"conreg.tgz --directory "${DEST_PATH}"
ssh "${DEST_USER}"@"${DEST_HOST}" rm "${DEST_PATH}"conreg.tgz

echo Cleaning up local files
rm /tmp/conreg.tgz /tmp/conreg.conf
