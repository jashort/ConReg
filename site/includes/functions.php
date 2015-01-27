<?php
require_once 'Attendee.php';

if (!isset($_SESSION)) {
	session_start();
}

$conn = getDBConnection();

/**
 * Get a database connection configured by environment variables
 *
 * @return PDO
 */
function getDBConnection() {
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

	try {
		$conn = new PDO('mysql:host=' . $db_hostname . ';dbname=' . $db_name, $db_user, $db_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
		die();
	}
	return $conn;
}

/**
 * Check that a given username and password are valid. If so, set session variables and redirect
 *
 * @param string $username
 * @param string $password
 */
function validateUser($username, $password) {
	global $conn;
	$redirectLoginSuccess = "/index.php";

	try {
		$stmt = $conn->prepare('SELECT staff_id, initials, username, password, access_level, enabled FROM reg_staff WHERE username = :username');
		$stmt->execute(array('username' => $username));

		$results = $stmt->fetch(PDO::FETCH_ASSOC);

		if(crypt($password, $results["password"])==$results["password"]) {
			$verified = true;
		} else {
			$verified = false;
		}

		if (($results) && ($verified) && ($results["enabled"] == "1")) {

			session_regenerate_id(true);

			//Declare session variables and assign them
			$_SESSION['username'] = $results["username"];
			$_SESSION['staffid'] = $results["staff_id"];
			$_SESSION['access'] = $results["access_level"];
			$_SESSION['initials'] = $results["initials"];
			$_SESSION['rights'] = get_rights($results["access_level"]);

			if ($results["password"] == crypt("password", $results["password"])) {
				redirect("/staff/staff_password_reset.php?username=" . $_SESSION['username']);
			} else {
				logMessage($username, 'Logged in');
				if (isset($_SESSION['PrevUrl'])) {
					redirect($_SESSION['PrevUrl']);
				} else {
					redirect($redirectLoginSuccess);
				}
			}
		}
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
}

/**
 * Write a message to history
 *
 * @param string $user Current username
 * @param string $message Message to log
 */
function logMessage($user, $message) {
	global $conn;

	try {
		$stmt = $conn->prepare("INSERT INTO history (username, description) VALUES (:username, :message)");
		$stmt->execute(array('username' => $user, 'message' => $message));
	} catch(PDOExecption $e) {
		die('ERROR: ' . $e->getMessage());
	}
}

/**
 * Returns the next available badge number for the given user and increments their badge count
 *
 * Each staff member has their own badge number count. So after they have created two attendees,
 * last_badge_number would be 2, and the next time this function is run it will return 3.
 *
 * @param int $staffId ID number of staff member
 * @return int
 */
function getBadgeNumber($staffId) {
	global $conn;

	try {
		$conn->beginTransaction();
		$stmt = $conn->prepare("SELECT last_badge_number FROM reg_staff WHERE staff_id = :id");
		$stmt->execute(array('id' => $staffId));
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt = $conn->prepare("UPDATE reg_staff SET last_badge_number = last_badge_number+1 WHERE staff_id= :id;");
		$stmt->execute(array('id' => $staffId));
		$conn->commit();
	} catch(PDOExecption $e) {
		$conn->rollback();
		die('ERROR: ' . $e->getMessage());
	}
	return $results['last_badge_number']+1;
}


/**
 * Set the badge number for the given staff member
 *
 * @param int $staffId ID number of staff member
 * @param int $number Last badge number created
 */
function badgeNumberSet($staffId, $number) {
	global $conn;

	$stmt = $conn->prepare("UPDATE reg_staff SET last_badge_number=:bnumber WHERE staff_id=:id");
	$stmt->execute(array('bnumber' => $number, 'id' => $staffId));
}


/**
 * Update an order record
 * @param int $id ID
 * @param float $amount Total amount paid
 * @param string $payType Payment Type
 * @param string $paid Paid? (yes/no)
 */
function orderUpdate($id, $amount, $payType, $paid) {
	global $conn;
	try {
		$stmt = $conn->prepare("UPDATE orders SET total_amount=:amount, paid=:paid, paytype=:paytype WHERE order_id=:id");
		$stmt->execute(array('amount' => $amount, 'paid' => $paid, 'paytype' => $payType, 'id' => $id));
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
}


/**
 * List attendees for a given order ID
 *
 * @param int $orderId
 * @return PDOStatement
 */
function orderListAttendees($orderId) {
	global $conn;

	$stmt = $conn->prepare("SELECT first_name, last_name, pass_type, paid_amount FROM attendees WHERE order_id = :orderid");
	$stmt->execute(array('orderid' => $orderId));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}


/**
 * Get a PDO statement for a given attendee ID
 *
 * @param $id int
 * @return PDOStatement
 */
function getAttendeePDO($id) {
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM attendees WHERE id = :id");
	$stmt->execute(array('id' => $id));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}


/**
 * Gets attendee information from the database
 *
 * @param int $id Attendee ID
 * @return Attendee
 */
function getAttendee($id) {
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM attendees WHERE id = :id");
	$stmt->execute(array('id' => $id));
	$attendee = $stmt->fetchObject('Attendee');
	return $attendee;
}


/**
 * Add one or more attendees to the database and create an order record for them
 *
 * @param Array $attendees Array containing Attendee objects
 * @return int The order ID that was created
 */
function regAddOrder($attendees) {
	global $conn;

	try {
		$conn->beginTransaction();
		$stmt = $conn->prepare("INSERT INTO orders (paid) VALUES (:paid)");
		$stmt->execute(array('paid' => 'no'));
		$orderId = $conn->lastInsertId();
		$stmt = $conn->prepare("INSERT INTO attendees (first_name, last_name, badge_number, phone, email, zip, birthdate, ec_fullname, ec_phone, ec_same, parent_fullname, parent_phone, parent_form, paid, paid_amount, pass_type, reg_type, order_id, checked_in, notes, added_by) VALUES (:firstname, :lastname, :bnumber, :phone, :email, :zip, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone, :pform, :paid, :amount, :passtype, :regtype, :orderId, :checked, :notes, :addedBy)");
		foreach ($attendees as $attendee) {
			$stmt->execute(array('firstname' => $attendee->first_name,
				'lastname' => $attendee->last_name,
				'bnumber' => $attendee->badge_number,
				'phone' => $attendee->phone,
				'email' => $attendee->email,
				'zip' => $attendee->zip,
				'bdate' => $attendee->birthdate,
				'ecname' => $attendee->ec_fullname,
				'ecphone' => $attendee->ec_phone,
				'same' => $attendee->ec_same,
				'pcname' => $attendee->parent_fullname,
				'pcphone' => $attendee->parent_phone,
				'pform' => $attendee->parent_form,
				'paid' => $attendee->paid,
				'amount' => $attendee->paid_amount,
				'passtype' => $attendee->pass_type,
				'orderId' => $orderId,
				'regtype' => $attendee->reg_type,
				'checked' => $attendee->checked_in,
				'notes' => $attendee->notes,
				'addedBy' => $attendee->added_by));
		}
		$conn->commit();
	} catch(PDOExecption $e) {
		$conn->rollback();
		echo 'ERROR: ' . $e->getMessage();
	}
	return $orderId;
}


/**
 * Mark attendees as checked in for the given order ID
 * 
 * @param int $orderId
 */
function orderCheckIn($orderId) {
	try {
		global $conn;

		$stmt = $conn->prepare("UPDATE attendees SET checked_in='Yes' WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $orderId));
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

}

/**
 * Mark the given order and all attendees in that order as paid
 *
 * @param int $orderId
 * @param string $paymentType
 * @param float $total
 * @param string $notes
 */
function orderPaid($orderId, $paymentType, $total, $notes) {
	global $conn;

	try {
		$conn->beginTransaction();

		$stmt = $conn->prepare("UPDATE attendees SET paid='Yes' WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $orderId));
		$stmt = $conn->prepare("UPDATE orders SET paid='Yes', paytype=:paymenttype, total_amount=:total, notes=:notes WHERE order_id= :orderid");
		$stmt->execute(array('orderid' => $orderId, 'paymenttype' => $paymentType, 'total' => $total, 'notes' => $notes));
		$conn->commit();
	} catch(PDOException $e) {
		$conn->rollBack();
		echo 'ERROR: ' . $e->getMessage();
	}

}


/**
 * Update the attendee represented by the given object in the database
 *
 * @param Attendee $attendee
 */
function regUpdate($attendee) {
	global $conn;

	try {
		$stmt = $conn->prepare("UPDATE attendees SET first_name=:firstname, last_name=:lastname, badge_number=:badgenumber, zip=:zip, phone=:phone, email=:email, birthdate=:bdate, ec_fullname=:ecname, ec_phone=:ecphone, ec_same=:same, parent_fullname=:pcname, parent_phone=:pcphone, parent_form=:pform, paid_amount=:amount, pass_type=:passtype, order_id=:orderid, notes=:notes WHERE id=:id");
		$stmt->execute(array('firstname' => $attendee->first_name,
			'lastname' => $attendee->last_name,
			'badgenumber' => $attendee->badge_number,
			'zip' => $attendee->zip,
			'phone' => $attendee->phone,
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


/**
 * Set the given attendee ID as checked in in the database
 *
 * @param int $id
 */
function regCheckIn($id) {
	global $conn;
	$stmt = $conn->prepare("UPDATE attendees SET checked_in='Yes' WHERE id= :id");
	$stmt->execute(array('id' => $id));
}


/**
 * Set the given attendee ID as having parental consent form received in the database
 * 
 * @param int $id
 */
function regCheckInParentFormReceived($id) {
	global $conn;
	$stmt = $conn->prepare("UPDATE attendees SET parent_form='Yes' WHERE id= :id");
	$stmt->execute(array('id' => $id));
}


/**
 * Search for attendees with the given last name (exact match, case insensitive),
 * whether or not they are checked in
 *
 * @param string $lastName
 * @return PDOStatement
 */
function attendeeSearchLastName($lastName) {
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM attendees WHERE last_name like :lname");
	$stmt->execute(array('lname' => $lastName));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}

/**
 * Search for attendees with the given badge number (exact match, case insensitive)
 * whether or not they are checked in
 *
 * @param string $badgeNumber
 * @return PDOStatement
 */
function attendeeSearchBadgeNumber($badgeNumber) {
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM attendees WHERE badge_number like :badge");
	$stmt->execute(array('badge' => $badgeNumber));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}


/**
 * Search pre-registered attendees by first name, last name, or order ID
 * 
 * @param string $name First name, last name, or order ID
 * @param string $field Field to search. fn = first name, ln = last name, ord = order id
 * @return PDOStatement
 */
function preRegSearch($name, $field) {
	global $conn;
	if ($field == 'fn') {
		$stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name, checked_in, order_id
								FROM attendees
								WHERE first_name LIKE :name AND reg_type LIKE 'PreReg'
								ORDER BY order_id");
	} elseif ($field == 'ord') {
		$stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name, checked_in, order_id
								FROM attendees
								WHERE order_id LIKE :name AND reg_type LIKE 'PreReg'
								ORDER BY order_id");
	} else {
		$stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name, checked_in, order_id
								FROM attendees
								WHERE last_name LIKE :name AND reg_type LIKE 'PreReg'
								ORDER BY order_id");
	}
	$stmt->execute(array('name' => $name));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}


/**
 * Insert the given staff member in to the database
 * 
 * @param string $firstName 
 * @param string $lastName
 * @param string $username
 * @param string $initials
 * @param string $phoneNumber
 * @param int $accessLevel Level (from roles.php)
 * @param int $enabled (1 or 0)
 */
function staffAdd($firstName, $lastName, $username, $initials, $phoneNumber, $accessLevel, $enabled) {
	global $conn;

	$password = crypt("password");

	$stmt = $conn->prepare("INSERT INTO reg_staff 
							(first_name, last_name, username, initials, password, phone_number, access_level, enabled) 
							VALUES 
							(:fname, :lname, :uname, :initials, :password, :cell, :access, :enabled)");
	$stmt->execute(array('fname' => $firstName,
		'lname' => $lastName,
		'uname' => $username,
		'initials' => $initials,
		'password' => $password,
		'cell' => $phoneNumber,
		'access' => $accessLevel,
		'enabled' => $enabled));
}

/**
 * Update the given staff member in the database
 * 
 * @param int $id int
 * @param string $firstName
 * @param string $lastName
 * @param string $initials
 * @param string $phoneNumber
 * @param int $accessLevel Level (from roles.php)
 * @param int $enabled (1 or 0) */
function staffUpdate($id, $firstName, $lastName, $initials, $phoneNumber, $accessLevel, $enabled) {
	global $conn;

	$stmt = $conn->prepare("UPDATE reg_staff SET first_name = :fname, last_name = :lname, initials = :initials, 
							phone_number = :cell, access_level=:access, enabled=:enabled WHERE staff_id=:id");
	$stmt->execute(array('fname' => $firstName,
		'lname' => $lastName,
		'initials' => $initials,
		'cell' => $phoneNumber,
		'access' => $accessLevel,
		'enabled' => $enabled,
		'id' => $id));
}

/**
 * List staff in database
 * 
 * @return PDOStatement
 */
function staffList(){
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM reg_staff ORDER BY first_name ASC");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt;
}


/**
 * Get staff record from database
 * 
 * @param string $username
 * @return Array
 */
function getStaff($username) {
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM reg_staff WHERE username = :user LIMIT 1");
	$stmt->execute(array('user'=>$username));
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch();
}


/**
 * @param int $number
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


/**
 * Get count/revenue of registrations by day
 * 
 * @return PDOStatement
 */
function registrationsByDay() {
	global $conn;

	$stmt = $conn->prepare("SELECT DAYNAME(created) as DAYNAME, DATE_FORMAT(created, '%m/%e/%Y') as DATE, 
							sum(paid_amount) AS DAYTOTAL, count(paid_amount) as DAYCOUNT
							FROM attendees
							WHERE reg_type = 'reg'
							GROUP BY DATE(created)
							ORDER BY DATE(created);");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt;
}


/**
 * Get registration statistics
 * 
 * @return Array
 */
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


/**
 * Set the password for the given username. Clear the session if resetting a password for the
 * logged in user.
 * 
 * @param string $username
 * @param string $password
 */
function passwordReset($username, $password) {
	global $conn;

	try {
		$passwordCrypt = crypt($password);
		//$passwordcrypt = password_hash($Password);

		$stmt = $conn->prepare("UPDATE reg_staff SET password=:pass WHERE username=:uname");
		$stmt->execute(array('pass' => $passwordCrypt, 'uname' => $username));

		if ($_SESSION['username']==$username) {
			$_SESSION['username'] = NULL;
			$_SESSION['access'] = NULL;
		}

	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
}


/**
 * Import attendees from the given file handle (used when a CSV file is uploaded. Use the given
 * staff ID for badge numbers. If there is a failure, roll back all imported items
 *
 * @param resource $handle
 * @param int $staffId
 * @return int Number of records imported
 */
function importPreRegCsvFile(&$handle, $staffId) {
	global $conn;

	$BNumber = getBadgeNumber($staffId);

	//loop through the csv file and insert into database
	$count = 0;
	$first = true;

	try {
		$conn->beginTransaction();

		while (($data = fgetcsv($handle,1000,"\t","'")) !== FALSE) {
			// Skip the first line
			if ($first == true) {
				$first = false;
				continue;
			}
			// Skip empty lines and lines where the first field starts with "#"
			if (count($data) != 18) {
				die("Error: Line " . $count . " does not have 18 columns.");

			}
			if (count($data) > 1 && substr($data[0], 0, 1) != '#') {
				if ($data[3] == "" || $data[3] == "NULL") {
					$data[3] = sprintf("ONL%1$04d", $BNumber);
				}

				$PhoneNumber = $data[6];
				$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

				$orderStmt = $conn->prepare("INSERT INTO orders (order_id, total_amount, paid, paytype)
									VALUES (:orderid, :amount, :paid, :paytype)
									ON DUPLICATE KEY UPDATE total_amount = total_amount + :amount");
				$attendeeStmt = $conn->prepare("
							INSERT INTO attendees (first_name, last_name, badge_number, badge_name, zip, country,
						   phone, email, birthdate, ec_fullname, ec_phone, ec_same, parent_fullname, parent_phone,
						   parent_form, paid, paid_amount, pass_type, reg_type, checked_in, added_by, order_id) VALUES
						   (:firstname, :lastname, :bnumber, :bname, :zip, :country,
						   :phone, :email, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone,
						   :parentform, :paid, :amount, :passtype, :regtype, :checkedin, :staffAdd, :orderid)");

				// Create order if it doesn't exist. If it does, increment the total amount
				$orderStmt->execute(array('orderid' => $data[17],
					'amount' => $data[15],
					'paid' => $data[14],
					'paytype' => 'ONLINE'));

				$attendeeStmt->execute(array('firstname' => $data[0],
					'lastname' => $data[1],
					'bname' => $data[2],
					'bnumber' => $data[3],
					'zip' => $data[4],
					'country' => $data[5],
					'phone' => $Phone_Stripped,
					'email' => $data[7],
					'bdate' => $data[8],
					'ecname' => $data[9],
					'ecphone' => $data[10],
					'same' => $data[11],
					'pcname' => $data[12],
					'pcphone' => $data[13],
					'parentform' => 'No',
					'paid' => $data[14],
					'amount' => $data[15],
					'passtype' => $data[16],
					'regtype' => 'PreReg',
					'checkedin' => 'No',
					'staffAdd' => 'ONLINE',
					'orderid' => $data[17]));
				$BNumber++;
				$count += 1;
			}
		}
		$conn->commit();
	} catch(Exception $e) {
		echo 'Error Importing line ' . $count . ':<br>';
		echo 'Message: ' . $e->getMessage();
		echo '<br><pre>';
		print_r($data);
		echo '</pre>';
		die();
	}

	// Update the given user's last-created badge number.
	badgeNumberSet($staffId, $BNumber-1);

	return $count;
}


/**
 * Redirect to the given URL and stop running the current page
 * @param string $location Location to redirect to
 * @param int $statusCode HTTP status code
 */
function redirect($location, $statusCode=303) {
	header(sprintf("Location: %s", $location, intval($statusCode)));
	die();
}

