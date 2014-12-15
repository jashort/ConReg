<?php
require('../Connections/kumo_conn.php');
require('../includes/cryptfunc.php');

if (!isset($_SESSION)) {
  session_start();
}

if ($_GET["action"] == "clear") {
regclear();
redirect("/index.php");
}

function regadd($FirstName, $LastName, $BadgeNumber, $PhoneNumber, $Zip, $BDate, $ECFullName, $ECPhoneNumber, $Same, $PCFullName, $PCPhoneNumber, $PForm, $Paid, $Amount, $PassType, $RegType, $PayType, $CheckedIn, $Notes) {
	
	global $conn;
	
	$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

	$stmt = $conn->prepare("INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_phone, kumo_reg_data_zip, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone, kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform, kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype, kumo_reg_data_paytype, kumo_reg_data_checkedin, kumo_reg_data_notes, kumo_reg_data_staff_add) VALUES (:firstname, :lastname, :bnumber, :phone, :zip, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone, :pform, :paid, :amount, :passtype, :regtype, :paytype, :checked, :notes, :add)");			   
    $stmt->execute(array('firstname' => $FirstName, 'lastname' => $LastName, 'bnumber' => $BadgeNumber, 'phone' => $Phone_Stripped, 'zip' => $Zip, 'bdate' => $BDate, 'ecname' => $ECFullName, 'ecphone' => $ECPhoneNumber, 'same' => $Same, 'pcname' => $PCFullName, 'pcphone' => $PCPhoneNumber, 'pform' => $PForm, 'paid' => $Paid, 'amount' => $Amount, 'passtype' => $PassType, 'paytype' => $PayType, 'regtype' => $RegType, 'checked' => $CheckedIn, 'notes' => $Notes, 'add' => $_SESSION["MM_Username"]));

unset ($_SESSION['var']);
unset ($_SESSION["FirstName"]);
unset ($_SESSION["LastName"]);
unset ($_SESSION["BadgeNumber"]);
unset ($_SESSION["Address"]);
unset ($_SESSION["City"]);
unset ($_SESSION["State"]);
unset ($_SESSION["Zip"]);
unset ($_SESSION["Country"]);
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
	
	global $conn;
					   
	$stmt = $conn->prepare("SELECT kumo_reg_staff_bnumber FROM kumo_reg_staff WHERE kumo_reg_staff_username = :uname");
    $stmt->execute(array('uname' => $_SESSION['username']));
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	return $results['kumo_reg_staff_bnumber']+1;
}

function badgeNumberUpdate() {
	
	global $conn;
	$badgeNumber = badgeNumberSelect();

	$stmt = $conn->prepare("UPDATE kumo_reg_staff SET kumo_reg_staff_bnumber=:bnumber WHERE kumo_reg_staff_username=:uname");
    $stmt->execute(array('bnumber' => $badgeNumber, 'uname' => $_SESSION['username']));

}

//Deprecated but leaving for time being
function regquickadd($FirstName, $LastName, $BadgeNumber) {
	
global $db_hostname, $db_name, $kumo_conn;
	
	$insertSQL = sprintf("INSERT INTO kumo_reg_quick_data (kumo_reg_quick_data_fname, kumo_reg_quick_data_lname, kumo_reg_quick_data_bnumber, kumo_reg_quick_data_staff_add, kumo_reg_quick_data_completed) VALUES ('%s', '%s', '%s', '%s', 'N')",
                       mysql_real_escape_string($FirstName),
                       mysql_real_escape_string($LastName),
                       mysql_real_escape_string($BadgeNumber),
                       $_SESSION["MM_Username"]);

  mysql_select_db($db_name, $kumo_conn);
  $Result1 = mysql_query($insertSQL, $kumo_conn) or die(mysql_error());

unset ($_SESSION['var']);
unset ($_SESSION["FirstName"]);
unset ($_SESSION["LastName"]);
unset ($_SESSION["BadgeNumber"]);
}

function regupdate($Id, $FirstName, $LastName, $BadgeNumber, $Address, $City, $State, $Zip, $Country, $EMail, $PhoneNumber, $BDate, $ECFullName, $ECPhoneNumber, $Same, $PCFullName, $PCPhoneNumber, $PForm, $Amount, $PassType, $PayType, $Notes) {

try {
	global $conn;
	$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);
				   
	$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_fname=:firstname, kumo_reg_data_lname=:lastname, kumo_reg_data_phone=:phone, kumo_reg_data_bdate=:bdate, kumo_reg_data_ecfullname=:ecname, kumo_reg_data_ecphone=:ecphone, kumo_reg_data_same=:same, kumo_reg_data_parent=:pcname, kumo_reg_data_parentphone=:pcphone, kumo_reg_data_parentform=:pform, kumo_reg_data_paidamount=:amount, kumo_reg_data_passtype=:passtype, kumo_reg_data_paytype=:paytype, kumo_reg_data_notes=:notes WHERE kumo_reg_data_id=:id");
    $stmt->execute(array('firstname' => $FirstName, 'lastname' => $LastName, 'phone' => $Phone_Stripped, 'bdate' => $BDate, 'ecname' => $ECFullName, 'ecphone' => $ECPhoneNumber, 'same' => $Same, 'pcname' => $PCFullName, 'pcphone' => $PCPhoneNumber, 'pform' => $PForm, 'amount' => $Amount, 'passtype' => $PassType, 'paytype' => $PayType, 'notes' => $Notes, 'id' => $Id));
	
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

}


function regcheckin($Id) {
	
	global $conn;
					   
	$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_checkedin='Yes' WHERE kumo_reg_data_id= :id");
    $stmt->execute(array('id' => $Id));

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
	
	$password = crypt("password");

  	global $conn;
					   
	$stmt = $conn->prepare("INSERT INTO kumo_reg_staff (kumo_reg_staff_fname, kumo_reg_staff_lname, kumo_reg_staff_username,  kumo_reg_staff_initials, kumo_reg_staff_password, kumo_reg_staff_phone_number, kumo_reg_staff_accesslevel, kumo_reg_staff_enabled) VALUES (:fname, :lname, :uname, :initials, :password, :cell, :access, :enabled)");
    $stmt->execute(array('fname' => $FirstName,'lname' => $LastName,'uname' => $Username,'initials' => $Initials,'password' => $password,'cell' => $Cell,'access' => $Accesslevel,'enabled' => $Enabled));
	
}

function staffupdate($Id, $FirstName, $LastName, $Initials, $Cell, $Accesslevel, $Enabled) {

  	global $conn;
					   
	$stmt = $conn->prepare("UPDATE kumo_reg_staff SET kumo_reg_staff_fname = :fname, kumo_reg_staff_lname = :lname, kumo_reg_staff_initials = :initials, kumo_reg_staff_phone_number = :cell, kumo_reg_staff_accesslevel=:access, kumo_reg_staff_enabled=:enabled WHERE kumo_reg_staff_id=:id");
    $stmt->execute(array('fname' => $FirstName,'lname' => $LastName,'initials' => $Initials,'cell' => $Cell,'access' => $Accesslevel,'enabled' => $Enabled,'id' => $Id));
	
}

function passwordreset($Username, $Password) {
	
	global $conn;
	
	try {
		
	$passwordcrypt = crypt($Password);

	//$passwordcrypt = password_hash($Password);
			   
	$stmt = $conn->prepare("UPDATE kumo_reg_staff SET kumo_reg_staff_password=:pass WHERE kumo_reg_staff_username=:uname");
    $stmt->execute(array('pass' => $passwordcrypt,'uname' => $Username));

	if ($_SESSION['username']==$Username) {
	$_SESSION['username'] = NULL;
	$_SESSION['access'] = NULL;
	}
	
	} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
	}
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