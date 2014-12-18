Installation Instructions
=========================

These instructions are a work in progress.

1. Copy the files in site/ to a directory your web server can read.
2. Database information can be set via environment variables (usually set in /etc/apache2/conf.d/conreg.conf)
or set directly in /Connections/kumo_conn.php

| Variable           |  Description                       |  
| ------------------ | ---------------------------------- |
| REG_DB_HOSTNAME    | Database server hostname           |
| REG_DB_NAME        | Database name                      |
| REG_DB_USER        | Database connection username       |
| REG_DB_PWD         | Database connection password       |


3. Create database tables by running the script install/01-tables.sql
4. Create the initial user by running the SQL script install/02-defaultuser.sql
5. Log in to the site with the username "admin" and password "password"


Additional Configuration:
-------------------------
- Badge layout is defined in 
- 