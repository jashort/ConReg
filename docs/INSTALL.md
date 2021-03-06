Installation Instructions
=========================

These instructions are a work in progress. See [../install/example-deploy-script.sh](../install/example-deploy-script.sh)
for an example of deploying the site via SSH.

Manual Instructions
-------------------

1. Copy the files in site/ to a directory your web server can read.
2. Database information can be set via environment variables (usually set in .htaccess or /etc/apache2/conf.d/conreg.conf)
or set directly in /includes/functions.php

| Variable           |  Description                       |  
| ------------------ | ---------------------------------- |
| REG_DB_HOSTNAME    | Database server hostname           |
| REG_DB_NAME        | Database name                      |
| REG_DB_USER        | Database connection username       |
| REG_DB_PWD         | Database connection password       |

Example .htaccess file:

    # ConReg database connection information
    SetEnv REG_DB_HOSTNAME 'localhost'
    SetEnv REG_DB_NAME 'registration'
    SetEnv REG_DB_USER 'kumo-rw'
    SetEnv REG_DB_PASS 'CHANGEME'

3. Create database tables by running the script install/01-tables.sql
4. Create the initial user by running the SQL script install/02-defaultuser.sql
5. Log in to the site with the username "admin" and password "password"
6. Click Import CSV to import pre-registered attendees. See [example.csv](example.csv) for an example
   data file. (You will probably have to customize csvimport.php with the current format
   of the CSV file.)

Additional Configuration:
-------------------------
- Badge layout is defined in 
- Role settings are configured in [/includes/roles.php](../site/includes/roles.php)