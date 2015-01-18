<?php
require_once('../Connections/kumo_conn.php');
require_once('../includes/cryptfunc.php');
require_once('Attendee.php');

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

	$stmt = $conn->prepare("INSERT INTO attendees (first_name, last_name, badge_number, phone, email, zip, birthdate, ec_fullname, ec_phone, ec_same, parent_fullname, parent_phone, parent_form, paid, paid_amount, pass_type, reg_type, order_id, checked_in, notes, added_by) VALUES (:firstname, :lastname, :bnumber, :phone, :email, :zip, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone, :pform, :paid, :amount, :passtype, :regtype, :orderid, :checked, :notes, :staffadd)");
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
					   
	$stmt = $conn->prepare("SELECT last_badge_number FROM reg_staff WHERE username = :uname");
    $stmt->execute(array('uname' => $_SESSION['username']));
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	return $results['last_badge_number']+1;
}

function badgeNumberUpdate() {
	
	global $conn;
	$badgeNumber = badgeNumberSelect();

	$stmt = $conn->prepare("UPDATE reg_staff SET last_badge_number=:bnumber WHERE username=:uname");
    $stmt->execute(array('bnumber' => $badgeNumber, 'uname' => $_SESSION['username']));

}

/**
 * Set the badge number for the logged in user to the given number
 *
 * @param $Number
 */
function badgeNumberSet($Number) {
	global $conn;

	$stmt = $conn->prepare("UPDATE reg_staff SET last_badge_number=:bnumber WHERE username=:uname");
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
		$stmt = $conn->prepare("INSERT INTO orders (paid) VALUES (:paid)");
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
		$stmt = $conn->prepare("UPDATE orders SET total_amount=:amount, paid=:paid, paytype=:paytype WHERE order_id=:id");
		$stmt->execute(array('amount' => $Amount, 'paid' => $Paid, 'paytype' => $PayType, 'id' => $Id));
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
}


function orderlistattendees($OrderId) {

	global $conn;

	$stmt = $conn->prepare("SELECT first_name, last_name, pass_type, paid_amount FROM attendees WHERE order_id = :orderid");
	$stmt->execute(array('orderid' => $OrderId));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}

function getAttendeePDO($Id) {

	global $conn;

	$stmt = $conn->prepare("SELECT * FROM attendees WHERE id = :id");
	$stmt->execute(array('id' => $Id));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}


/**
 * Gets attendee information from the database
 * @param $Id Attendee ID
 * @return mixed
 */
function getAttendee($Id) {

	global $conn;

	$stmt = $conn->prepare("SELECT * FROM attendees WHERE id = :id");
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

		$stmt = $conn->prepare("UPDATE attendees SET checked_in='Yes' WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $OrderId));
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

}

function orderpaid($OrderId, $PaymentType, $Total) {
	global $conn;
	try {
		$conn->beginTransaction();

		$stmt = $conn->prepare("UPDATE attendees SET paid='Yes' WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $OrderId));
		$stmt = $conn->prepare("UPDATE orders SET paid='Yes', paytype=:paymenttype, total_amount=:total WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $OrderId, 'paymenttype' => $PaymentType, 'total' => $Total));
		$conn->commit();
	} catch(PDOException $e) {
		$conn->rollBack();
		echo 'ERROR: ' . $e->getMessage();
	}

}


function regupdate($attendee) {

	try {
		global $conn;
		$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","", $attendee->phone);

		$stmt = $conn->prepare("UPDATE attendees SET first_name=:firstname, last_name=:lastname, badge_number=:badgenumber, zip=:zip, phone=:phone, email=:email, birthdate=:bdate, ec_fullname=:ecname, ec_phone=:ecphone, ec_same=:same, parent_fullname=:pcname, parent_phone=:pcphone, parent_form=:pform, paid_amount=:amount, pass_type=:passtype, order_id=:orderid, notes=:notes WHERE id=:id");
		$stmt->execute(array('firstname' => $attendee->first_name, 
							 'lastname' => $attendee->last_name, 
			                 'badgenumber' => $attendee->badge_number,
							 'zip' => $attendee->zip,
							 'phone' => $Phone_Stripped,
							 'email' => $attendee->email,
							 'bdate' => $attendee->birthdate,
							 'ecname' => $attendee->ec_fullname,
							 'ecphone' => $attendee->ec_phone,
							 'same' => $attendee->ec_same,
							 'pcname' => $attendee->parent_fullname,
							 'pcphone' => $attendee->parent_phone,
							 'pform' => $attendee->parent_form,
							 'amount' => $attendee->paid_amount,
							 'passtype' => $attendee->pass_type,
							 'orderid' => $attendee->order_id,
 							 'notes' => $attendee->notes,
 							 'id' => $attendee->id));

	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

}


function regcheckin($Id) {
	global $conn;
	$stmt = $conn->prepare("UPDATE attendees SET checked_in='Yes' WHERE id= :id");
	$stmt->execute(array('id' => $Id));
}

function regCheckinParentFormReceived($Id) {
	global $conn;
	$stmt = $conn->prepare("UPDATE attendees SET parent_form='Yes' WHERE id= :id");
	$stmt->execute(array('id' => $Id));
}

function attendeeSearchLastName($name) {
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM attendees WHERE last_name like :lname");
	$stmt->execute(array('lname' => $name));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}

function attendeeSearchBadgeNumber($badgeNumber) {
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM attendees WHERE badge_number like :badge");
	$stmt->execute(array('badge' => $badgeNumber));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}


/**
 * @param $name	First or Lost Name
 * @param $field Field to search. fn = first name, ln = last name, ord = order id
 * @return array Array of attendee arrays
 */
function preRegSearch($name, $field) {
	global $conn;
	if ($field == 'fn') {
		$stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name,
								checked_in, order_id
								FROM attendees
								WHERE first_name LIKE :name
								ORDER BY order_id");
	} elseif ($field == 'ord') {
		$stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name,
								checked_in, order_id
								FROM attendees
								WHERE order_id LIKE :name
								ORDER BY order_id");
	} else {
		$stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name,
								checked_in, order_id
								FROM attendees
								WHERE last_name LIKE :name
								ORDER BY order_id");
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
					   
	$stmt = $conn->prepare("INSERT INTO reg_staff (first_name, last_name, username, initials, password, phone_number, access_level, enabled) VALUES (:fname, :lname, :uname, :initials, :password, :cell, :access, :enabled)");
    $stmt->execute(array('fname' => $FirstName,'lname' => $LastName,'uname' => $Username,'initials' => $Initials,'password' => $password,'cell' => $Cell,'access' => $Accesslevel,'enabled' => $Enabled));
	
}

function staffupdate($Id, $FirstName, $LastName, $Initials, $Cell, $Accesslevel, $Enabled) {

  	global $conn;
					   
	$stmt = $conn->prepare("UPDATE reg_staff SET first_name = :fname, last_name = :lname, initials = :initials, phone_number = :cell, access_level=:access, enabled=:enabled WHERE staff_id=:id");
    $stmt->execute(array('fname' => $FirstName,'lname' => $LastName,'initials' => $Initials,'cell' => $Cell,'access' => $Accesslevel,'enabled' => $Enabled,'id' => $Id));
	
}

function staffList(){
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM reg_staff ORDER BY first_name ASC");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt;
}


function getStaff($username) {
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM reg_staff WHERE username = :user LIMIT 1");
	$stmt->execute(array('user'=>$username));
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch();
}


/**
 * Returns most recent items from history
 * @param int $number Number of items to return (default: 50)
 * @return PDOStatement
 */
function historyList($number=50){
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM history ORDER BY changed_at DESC LIMIT :number");
	$stmt->bindValue('number', (int)$number, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt;
}


function registrationsByDay() {
	global $conn;

	$stmt = $conn->prepare("SELECT DAYNAME(created) as DAYNAME, DATE_FORMAT(created, '%m/%e/%Y') as DATE, sum(paid_amount) AS DAYTOTAL, count(paid_amount) as DAYCOUNT
							FROM attendees
							WHERE reg_type = 'reg'
							GROUP BY DATE(created)
							ORDER BY DATE(created);");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt;
}

function registrationStatistics() {
	global $conn;

	$stmt = $conn->prepare("SELECT DISTINCT
 			(SELECT count(*) FROM attendees WHERE checked_in = 'yes' AND reg_type='prereg') AS preregcheckedincount,
			(SELECT count(*) FROM attendees WHERE checked_in = 'no' AND reg_type='prereg') AS preregnotcheckedincount,
			(SELECT count(*) FROM attendees WHERE reg_type = 'reg' AND created > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)) AS reginlasthour,
			(SELECT count(badge_number) FROM attendees WHERE pass_type = 'Weekend' OR pass_type = 'VIP') AS passtypeweekend,
			(SELECT count(badge_number) FROM attendees WHERE pass_type = 'Friday') AS passtypefriday,
			(SELECT count(badge_number) FROM attendees WHERE pass_type = 'Saturday') AS passtypesaturday,
			(SELECT count(badge_number) FROM attendees WHERE pass_type = 'Sunday') AS passtypesunday,
			(SELECT count(badge_number) FROM attendees WHERE pass_type = 'Monday') AS passtypemonday,
			(SELECT count(*) FROM attendees WHERE reg_type like 'reg') AS regtotal,
			(SELECT count(*) FROM attendees WHERE checked_in = 'Yes') AS checkedintotal,
			(SELECT sum(paid_amount) FROM attendees WHERE reg_type = 'reg') AS sumregtotal
			FROM attendees;");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch();
}


function passwordreset($Username, $Password) {
	
	global $conn;
	
	try {
		
	$passwordcrypt = crypt($Password);

	//$passwordcrypt = password_hash($Password);
			   
	$stmt = $conn->prepare("UPDATE reg_staff SET password=:pass WHERE username=:uname");
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

