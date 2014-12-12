<?php
require('../Connections/kumo_conn.php');

if (!isset($_SESSION)) {
  session_start();
}

if ($_GET["action"] == "clear") {
regclear();
redirect("/index.php");
}

if (isset($_POST["adminalertsubmit"])) {
adminalertupdate($_POST["adminalerttext"]);
redirect("/index.php");
}


function regadd($FirstName, $LastName, $BadgeNumber, $Address, $City, $State, $Zip, $Country, $EMail, $PhoneNumber, $BDate, $ECFullName, $ECPhoneNumber, $Same, $PCFullName, $PCPhoneNumber, $PForm, $Paid, $Amount, $PassType, $RegType, $PayType, $CheckedIn, $Notes) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
	
	$insertSQL = sprintf("INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_address, kumo_reg_data_city, kumo_reg_data_state, kumo_reg_data_zip, kumo_reg_data_country, kumo_reg_data_email, kumo_reg_data_phone, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone,kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform, kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype, kumo_reg_data_paytype, kumo_reg_data_checkedin, kumo_reg_data_notes, kumo_reg_data_staff_add) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                       mysql_real_escape_string($FirstName),
                       mysql_real_escape_string($LastName),
                       mysql_real_escape_string($BadgeNumber),
                       mysql_real_escape_string($Address),
                       mysql_real_escape_string($City),
                       mysql_real_escape_string($State),
                       mysql_real_escape_string($Zip),
                       mysql_real_escape_string($Country),
                       mysql_real_escape_string($EMail),
                       mysql_real_escape_string($PhoneNumber),
                       mysql_real_escape_string($BDate),
                       mysql_real_escape_string($ECFullName),
                       mysql_real_escape_string($ECPhoneNumber),
                       mysql_real_escape_string($Same),
                       mysql_real_escape_string($PCFullName),
                       mysql_real_escape_string($PCPhoneNumber),
                       mysql_real_escape_string($PForm),
                       mysql_real_escape_string($Paid),
                       mysql_real_escape_string($Amount),
                       mysql_real_escape_string($PassType),
                       mysql_real_escape_string($RegType),
                       mysql_real_escape_string($PayType),
                       mysql_real_escape_string($CheckedIn),
                       mysql_real_escape_string($Notes),
                       $_SESSION["MM_Username"]);

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());

unset ($_SESSION['var']);
unset ($_SESSION["FirstName"]);
unset ($_SESSION["LastName"]);
unset ($_SESSION["BadgeNumber"]);
unset ($_SESSION["Address"]);
unset ($_SESSION["City"]);
unset ($_SESSION["State"]);
unset ($_SESSION["Zip"]);
unset ($_SESSION["Country"]);
unset ($_SESSION["EMail"]);
unset ($_SESSION["PhoneNumber"]);
unset ($_SESSION["BirthMonth"]);
unset ($_SESSION["BirthDay"]);
unset ($_SESSION["BirthYear"]);
unset ($_SESSION["ECFullName"]);
unset ($_SESSION["ECPhoneNumber"]);
unset ($_SESSION["Same"]);
unset ($_SESSION["PCFullName"]);
unset ($_SESSION["PCPhoneNumber"]);	
unset ($_SESSION["PCFormVer"]);
unset ($_SESSION["PassType"]);
unset ($_SESSION["Amount"]);
unset ($_SESSION["PayType"]);
unset ($_SESSION["Notes"]);
}

function badgeNumberSelect() {
	
	global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;

	mysql_select_db($database_kumo_conn, $kumo_conn);
	$query_rs_bnumberselect_list = sprintf("SELECT kumo_reg_staff_bnumber FROM kumo_reg_staff WHERE kumo_reg_staff_username = '%s'", mysql_real_escape_string($_SESSION['username']));
	$rs_bnumberselect_list = mysql_query($query_rs_bnumberselect_list, $kumo_conn) or die(mysql_error());
	$row_rs_bnumberselect_list = mysql_fetch_assoc($rs_bnumberselect_list);
	$totalRows_rs_bnumberselect_list = mysql_num_rows($rs_bnumberselect_list);
	
	return $row_rs_bnumberselect_list['kumo_reg_staff_bnumber']+1;
}

function badgeNumberUpdate() {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;

	$badgeNumber = badgeNumberSelect();
	
	$insertSQL = sprintf("UPDATE kumo_reg_staff SET kumo_reg_staff_bnumber='%s' WHERE kumo_reg_staff_username='%s'",
					   mysql_real_escape_string($badgeNumber),
					   mysql_real_escape_string($_SESSION['username']));

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());

  //echo $insertSQL;
}

function regquickadd($FirstName, $LastName, $BadgeNumber) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
	
	$insertSQL = sprintf("INSERT INTO kumo_reg_quick_data (kumo_reg_quick_data_fname, kumo_reg_quick_data_lname, kumo_reg_quick_data_bnumber, kumo_reg_quick_data_staff_add, kumo_reg_quick_data_completed) VALUES ('%s', '%s', '%s', '%s', 'N')",
                       mysql_real_escape_string($FirstName),
                       mysql_real_escape_string($LastName),
                       mysql_real_escape_string($BadgeNumber),
                       $_SESSION["MM_Username"]);

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());

unset ($_SESSION['var']);
unset ($_SESSION["FirstName"]);
unset ($_SESSION["LastName"]);
unset ($_SESSION["BadgeNumber"]);
}

function regupdate($Id, $FirstName, $LastName, $BadgeNumber, $Address, $City, $State, $Zip, $Country, $EMail, $PhoneNumber, $BDate, $ECFullName, $ECPhoneNumber, $PCFullName, $PCPhoneNumber, $PForm, $Amount, $PassType, $PayType, $Notes) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;

$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

	$insertSQL = sprintf("UPDATE kumo_reg_data SET kumo_reg_data_fname='%s', kumo_reg_data_lname='%s', kumo_reg_data_address='%s', kumo_reg_data_city='%s', kumo_reg_data_state='%s', kumo_reg_data_zip='%s', kumo_reg_data_country='%s', kumo_reg_data_email='%s', kumo_reg_data_phone='%s', kumo_reg_data_bdate='%s', kumo_reg_data_ecfullname='%s', kumo_reg_data_ecphone='%s', kumo_reg_data_parent='%s', kumo_reg_data_parentphone='%s', kumo_reg_data_parentform='%s', kumo_reg_data_paidamount='%s', kumo_reg_data_passtype='%s', kumo_reg_data_paytype='%s', kumo_reg_data_notes='%s' WHERE kumo_reg_data_id=%s",
                       mysql_real_escape_string($FirstName),
                       mysql_real_escape_string($LastName),
                       mysql_real_escape_string($Address),
                       mysql_real_escape_string($City),
                       mysql_real_escape_string($State),
                       mysql_real_escape_string($Zip),
                       mysql_real_escape_string($Country),
                       mysql_real_escape_string($EMail),
                       mysql_real_escape_string($PhoneNumber),
                       mysql_real_escape_string($BDate),
                       mysql_real_escape_string($ECFullName),
                       mysql_real_escape_string($ECPhoneNumber),
                       mysql_real_escape_string($PCFullName),
                       mysql_real_escape_string($PCPhoneNumber),
                       mysql_real_escape_string($PForm),
                       mysql_real_escape_string($Amount),
                       mysql_real_escape_string($PassType),
                       mysql_real_escape_string($PayType),
                       mysql_real_escape_string($Notes),
					   mysql_real_escape_string($Id));

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());
  
  trackupdate($_SESSION['username'], $BadgeNumber, $insertSQL);

  //echo $insertSQL;
}


function regcheckin($Id) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
	
	$insertSQL = sprintf("UPDATE kumo_reg_data SET kumo_reg_data_checkedin='Yes' WHERE kumo_reg_data_id='%s'",
					   mysql_real_escape_string($Id));

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());

  //echo $insertSQL;
}

function regclear() {

unset ($_SESSION['var']);
unset ($_SESSION["FirstName"]);
unset ($_SESSION["LastName"]);
unset ($_SESSION["BadgeNumber"]);
unset ($_SESSION["Address"]);
unset ($_SESSION["City"]);
unset ($_SESSION["State"]);
unset ($_SESSION["Zip"]);
unset ($_SESSION["Country"]);
unset ($_SESSION["EMail"]);
unset ($_SESSION["PhoneNumber"]);
unset ($_SESSION["BirthMonth"]);
unset ($_SESSION["BirthDay"]);
unset ($_SESSION["BirthYear"]);
unset ($_SESSION["ECFullName"]);
unset ($_SESSION["ECPhoneNumber"]);
unset ($_SESSION["Same"]);
unset ($_SESSION["PCFullName"]);
unset ($_SESSION["PCPhoneNumber"]);	
unset ($_SESSION["PCFormVer"]);
unset ($_SESSION["PassType"]);
unset ($_SESSION["Amount"]);
unset ($_SESSION["PayType"]);
unset ($_SESSION["Notes"]);

}


function staffadd($FirstName, $LastName, $Username, $Initials, $Cell, $Accesslevel, $Enabled) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
	
$cryptpass = crypt('password','password');	
	
	$insertSQL = sprintf("INSERT INTO kumo_reg_staff (kumo_reg_staff_fname, kumo_reg_staff_lname, kumo_reg_staff_username,  kumo_reg_staff_initials, kumo_reg_staff_bnumber, kumo_reg_staff_password, kumo_reg_staff_phone_number, kumo_reg_staff_accesslevel, kumo_reg_staff_enabled) VALUES ('%s', '%s', '%s', '%s', 0, '" . $cryptpass . "', '%s', %s, %s)",
                       mysql_real_escape_string($FirstName),
                       mysql_real_escape_string($LastName),
                       mysql_real_escape_string($Username),
                       mysql_real_escape_string($Initials),
                       mysql_real_escape_string($Cell),
                       mysql_real_escape_string($Accesslevel),
					   mysql_real_escape_string($Enabled));

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());
}

function staffupdate($Id, $FName, $LName, $Initials, $Cell, $Accesslevel, $Enabled) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
	
	$insertSQL = sprintf("UPDATE kumo_reg_staff SET kumo_reg_staff_fname = '%s', kumo_reg_staff_lname = '%s', kumo_reg_staff_initials = '%s', kumo_reg_staff_phone_number = '%s', kumo_reg_staff_accesslevel=%s, kumo_reg_staff_enabled=%s WHERE kumo_reg_staff_id=%s",
                       mysql_real_escape_string($FName),
                       mysql_real_escape_string($LName),
					   mysql_real_escape_string($Initials),
                       mysql_real_escape_string($Cell),
                       mysql_real_escape_string($Accesslevel),
                       mysql_real_escape_string($Enabled),
					   mysql_real_escape_string($Id));

	mysql_select_db($database_kumo_conn, $kumo_conn);
	$Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());
	
	//echo $insertSQL;
}

function passwordreset($Username, $Password) {

$passwordcrypt = crypt($Password,$Password);

global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
	
	$insertSQL = sprintf("UPDATE kumo_reg_staff SET kumo_reg_staff_password='%s' WHERE kumo_reg_staff_username='%s'",
					   mysql_real_escape_string($passwordcrypt),
					   mysql_real_escape_string($Username));

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());

if ($_SESSION['username']==$Username) {
	$_SESSION['username'] = NULL;
	$_SESSION['access'] = NULL;
}
  //echo $insertSQL;
}

function trackupdate($User, $Badgenumber, $SQLstring) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
		
	$insertSQL = sprintf("INSERT INTO kumo_reg_changehistory (kumo_reg_changehistory_user, kumo_reg_changehistory_badgenumber, kumo_reg_changehistory_sqlstring) VALUES ('%s', '%s', '%s')",
                       mysql_real_escape_string($User),
                       mysql_real_escape_string($Badgenumber),
                       mysql_real_escape_string($SQLstring));

  mysql_select_db($database_kumo_conn, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());
}

function adminalertupdate($Text) {
	
global $hostname_kumo_conn, $database_kumo_conn, $kumo_conn;
		
	$insertSQL = sprintf("INSERT INTO kumo_reg_admin (kumo_reg_admin_text, kumo_reg_admin_agent, kumo_reg_admin_timestamp) VALUES ('%s', '%s', NOW())",
                       mysql_real_escape_string($Text),
					   $_SESSION["MM_Username"]);

	mysql_select_db($database_kumo_conn, $kumo_conn);
	$Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());
 
}

function redirect($location){
  $insertGoTo = $location;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>