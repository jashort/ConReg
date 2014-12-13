<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

// Configuration defaults. Will be overridden if they are set in environment variables
$db_hostname = "localhost";     // Database server hostname
$db_name = "registration";      // Database name
$db_user = "kumo_rw";           // Database username (requires read/write rights)
$db_password = "CHANGEME";      // Database password


// Override configuration with settings in environment variables
if (isset($_SERVER['REG_DB_SERVER']))
{
    $db_hostname = $_SERVER['REG_DB_SERVER'];
}

if (isset($_SERVER['REG_DB_NAME']))
{
    $db_name = $_SERVER['REG_DB_NAME'];
}
if (isset($_SERVER['REG_DB_USER']))
{
    $db_user = $_SERVER['REG_DB_USER'];
}
if (isset($_SERVER['REG_DB_PASS']))
{
    $db_password = $_SERVER['REG_DB_PASS'];
}


$kumo_conn = mysql_pconnect($db_hostname, $db_user, $db_password) or trigger_error(mysql_error(),E_USER_ERROR);

try {
    $conn = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>