<?php
require('../Connections/kumo_conn.php');
require('../includes/cryptfunc.php');
require('Attendee.php');

if (!isset($_SESSION)) {
  session_start();
}

if (array_key_exists('action',$_GET) && $_GET["action"] == "clear") {
	regclear();
	redirect("/index.php");
}

function regadd($FirstName, $LastName, $BadgeNumber, $PhoneNumber, $Email, $Zip, $BDate, $ECFullName, $ECPhoneNumber, $Same, $PCFullName, $PCPhoneNumber, $PForm, $Paid, $Amount, $PassType, $RegType, $CheckedIn, $OrderId, $Notes) {
	global $conn;
	
	$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

	$stmt = $conn->prepare("INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_phone, kumo_reg_data_email, kumo_reg_data_zip, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone, kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform, kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype, kumo_reg_data_orderid, kumo_reg_data_checkedin, kumo_reg_data_notes, kumo_reg_data_staff_add) VALUES (:firstname, :lastname, :bnumber, :phone, :email, :zip, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone, :pform, :paid, :amount, :passtype, :regtype, :orderid, :checked, :notes, :staffadd)");
    $stmt->execute(array('firstname' => $FirstName,
		'lastname' => $LastName,
		'bnumber' => $BadgeNumber,
		'phone' => $Phone_Stripped,
		'email' => $Email,
		'zip' => $Zip,
		'bdate' => $BDate,
		'ecname' => $ECFullName,
		'ecphone' => $ECPhoneNumber,
		'same' => $Same,
		'pcname' => $PCFullName,
		'pcphone' => $PCPhoneNumber,
		'pform' => $PForm,
		'paid' => $Paid,
		'amount' => $Amount,
		'passtype' => $PassType,
		'orderid' => $OrderId,
		'regtype' => $RegType,
		'checked' => $CheckedIn,
		'notes' => $Notes,
		'staffadd' => $_SESSION['username']));

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
	unset ($_SESSION["EMail"]);
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
	unset ($_SESSION["Notes"]);
}

/**
 * Returns the next available badge number for the logged in user
 *
 * Each staff member has their own badge number count. So after they have created two attendees,
 * their badge number count would be 2.
 *
 * @return int
 */
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

/**
 * Set the badge number for the logged in user to the given number
 *
 * @param $Number
 */
function badgeNumberSet($Number) {
	global $conn;

	$stmt = $conn->prepare("UPDATE kumo_reg_staff SET kumo_reg_staff_bnumber=:bnumber WHERE kumo_reg_staff_username=:uname");
	$stmt->execute(array('bnumber' => $Number, 'uname' => $_SESSION['username']));

}


/**
 * Create a new order record
 * @return int Created order's ID number
 */
function orderadd() {
	global $conn;

	$orderid = -1;

	try {
		$conn->beginTransaction();
		$stmt = $conn->prepare("INSERT INTO kumo_reg_orders (paid) VALUES (:paid)");
		$stmt->execute(array('paid' => 'no'));
		$orderid = $conn->lastInsertId();
		$conn->commit();
	} catch(PDOExecption $e) {
		$conn->rollback();
		echo 'ERROR: ' . $e->getMessage();
	}
	return $orderid;
}


/**
 * Update an order record
 * @param $Id	int ID
 * @param $Amount 	Decimal Total amount paid
 * @param $PayType	Payment Type
 * @param $Paid		Paid? (yes/no)
 */
function orderupdate($Id, $Amount, $PayType, $Paid) {
	global $conn;
	try {
		$stmt = $conn->prepare("UPDATE kumo_reg_orders SET total_amount=:amount, paid=:paid, paytype=:paytype WHERE order_id=:id");
		$stmt->execute(array('amount' => $Amount, 'paid' => $Paid, 'paytype' => $PayType, 'id' => $Id));
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
}


function orderlistattendees($OrderId) {

	global $conn;

	$stmt = $conn->prepare("SELECT kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_passtype, kumo_reg_data_paidamount FROM kumo_reg_data WHERE kumo_reg_data_orderid = :orderid");
	$stmt->execute(array('orderid' => $OrderId));
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Gets attendee information from the database
 * @param $Id Attendee ID
 * @return mixed
 */
function getAttendee($Id) {

	global $conn;

	$stmt = $conn->prepare("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_id = :id");
	$stmt->execute(array('id' => $Id));
	$attendee = $stmt->fetchObject('Attendee');
	return $attendee;
}


/**
 * Mark attendees as checked in for the given order ID
 * @param $OrderId
 */
function ordercheckin($OrderId) {
	try {
		global $conn;

		$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_checkedin='Yes' WHERE kumo_reg_data_orderid= :orderid");
		$stmt->execute(array('orderid' => $OrderId));
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

}

function orderpaid($OrderId, $PaymentType, $Total) {
	global $conn;
	try {
		$conn->beginTransaction();

		$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_paid='Yes' WHERE kumo_reg_data_orderid= :orderid");
		$stmt->execute(array('orderid' => $OrderId));
		$stmt = $conn->prepare("UPDATE kumo_reg_orders SET paid='Yes', paytype=:paymenttype, total_amount=:total WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $OrderId, 'paymenttype' => $PaymentType, 'total' => $Total));
		$conn->commit();
	} catch(PDOException $e) {
		$conn->rollBack();
		echo 'ERROR: ' . $e->getMessage();
	}

}


function regupdate($Id, $FirstName, $LastName, $BadgeNumber, $Zip, $EMail, $PhoneNumber, $BDate, $ECFullName, $ECPhoneNumber, $Same, $PCFullName, $PCPhoneNumber, $PForm, $Amount, $PassType, $OrderId, $Notes) {

	try {
		global $conn;
		$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

		$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_fname=:firstname, kumo_reg_data_lname=:lastname, kumo_reg_data_bnumber=:badgenumber, kumo_reg_data_zip=:zip, kumo_reg_data_phone=:phone, kumo_reg_data_email=:email, kumo_reg_data_bdate=:bdate, kumo_reg_data_ecfullname=:ecname, kumo_reg_data_ecphone=:ecphone, kumo_reg_data_same=:same, kumo_reg_data_parent=:pcname, kumo_reg_data_parentphone=:pcphone, kumo_reg_data_parentform=:pform, kumo_reg_data_paidamount=:amount, kumo_reg_data_passtype=:passtype, kumo_reg_data_orderid=:orderid, kumo_reg_data_notes=:notes WHERE kumo_reg_data_id=:id");
		$stmt->execute(array('firstname' => $FirstName, 'lastname' => $LastName, 'badgenumber' => $BadgeNumber, 'zip' => $Zip, 'phone' => $Phone_Stripped, 'email' => $EMail, 'bdate' => $BDate, 'ecname' => $ECFullName, 'ecphone' => $ECPhoneNumber, 'same' => $Same, 'pcname' => $PCFullName, 'pcphone' => $PCPhoneNumber, 'pform' => $PForm, 'amount' => $Amount, 'passtype' => $PassType, 'orderid' => $OrderId, 'notes' => $Notes, 'id' => $Id));

	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

}


function regcheckin($Id) {
	global $conn;
	$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_checkedin='Yes' WHERE kumo_reg_data_id= :id");
	$stmt->execute(array('id' => $Id));
}

function regCheckinParentFormReceived($Id) {
	global $conn;
	$stmt = $conn->prepare("UPDATE kumo_reg_data SET kumo_reg_data_parentform='Yes' WHERE kumo_reg_data_id= :id");
	$stmt->execute(array('id' => $Id));
}


/**
 * @param $name	First or Lost Name
 * @param $field Field to search. fn = first name, ln = last name
 * @return array Array of attendee arrays
 */
function preRegSearch($name, $field) {
	global $conn;
	if ($field == 'fn') {
		$stmt = $conn->prepare("SELECT kumo_reg_data_id, kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bname,
								kumo_reg_data_checkedin, kumo_reg_data_orderid
								FROM kumo_reg_data
								WHERE kumo_reg_data_fname LIKE :name
								ORDER BY kumo_reg_data_orderid");
	} else {
		$stmt = $conn->prepare("SELECT kumo_reg_data_id, kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bname,
								kumo_reg_data_checkedin, kumo_reg_data_orderid
								FROM kumo_reg_data
								WHERE kumo_reg_data_lname LIKE :name
								ORDER BY kumo_reg_data_orderid");
	}
	$stmt->execute(array('name' => $name));
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

