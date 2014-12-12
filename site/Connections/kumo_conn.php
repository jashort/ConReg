<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_kumo_conn = "localhost";
$database_kumo_conn = "DATABASE_NAME";
$username_kumo_conn = "DATABASE_USERNAME_RW";
$password_kumo_conn = "DATABASE_PASSWORD";

$kumo_conn = mysql_pconnect($hostname_kumo_conn, $username_kumo_conn, $password_kumo_conn) or trigger_error(mysql_error(),E_USER_ERROR);

try {
    $conn = new PDO('mysql:host=localhost;dbname=' . $database_kumo_conn, $username_kumo_conn, $password_kumo_conn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>