<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_kumo_conn1 = "10.0.0.25:3306";
$hostname_kumo_conn2 = "173.11.26.174:3306";
$database_kumo_conn = "kumo";
$username_kumo_conn = "root";
$password_kumo_conn = "";

$kumo_conn1 = mysql_pconnect($hostname_kumo_conn1, $username_kumo_conn, $password_kumo_conn); //Connect to the database.
$kumo_conn2 = mysql_pconnect($hostname_kumo_conn2, $username_kumo_conn, $password_kumo_conn); //Connect to the database.

if (!$kumo_conn1) {
  $Database1 = "DATABASE SERVER 1 IS DOWN"; 
  mysql_close($kumo_conn1);
}
else {
  $Database1 = "DATABASE SERVER 1 IS OK";
}
if (!$kumo_conn2) {
  $Database2 = "DATABASE SERVER 2 IS DOWN";
  mysql_close($kumo_conn2);
}
else {
  $Database2 = "DATABASE SERVER 2 IS OK";
}
?>