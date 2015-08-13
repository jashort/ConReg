<?php
require_once 'classes.php';

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
				logMessage($username, 0, 'Logged in');
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
 * @param int $typeId Action type ID (from history_types table)
 * @param string $message Message to log
 */
function logMessage($user, $typeId, $message) {
	global $conn;

	try {
		$stmt = $conn->prepare("INSERT INTO history (username, type_id, description) VALUES (:username, :type, :day_text)");
		$stmt->execute(array('username' => $user, 'type' => $typeId, 'day_text' => $message));
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

	$stmt = $conn->prepare("SELECT * FROM attendees WHERE order_id = :orderid");
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
 * Generates a random 32 character string containing a-z, 0-9
 *
 * @return string
 */
function generateOrderId() {
	$id = "";
	$characters = "abcdefghijklmnopqrstuvwxyz0123456789";
	for ($i = 0; $i < 32; $i++) {
		$x = mt_rand(0, 35);
		$id .= $characters[$x];
	}
	return $id;
}


/**
 * Add the given attendee to the database
 */
function addAttendee($attendee) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO attendees (first_name, last_name, badge_name, badge_number, zip, country, phone,
                            email, birthdate, ec_fullname, ec_phone, ec_same, parent_fullname, parent_phone,
                            parent_form, paid, paid_amount, pass_type, pass_type_id, reg_type, order_id, checked_in,
                            notes, added_by) VALUES (:firstname, :lastname, :bname, :bnumber, :zip, :country, :phone,
                            :email, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone, :pform, :paid, :amount,
                            :passtype, :passtypeid, :regtype, :orderId, :checked, :notes, :addedBy)");
    try {
        $stmt->execute(array('firstname' => $attendee->first_name,
                'lastname' => $attendee->last_name,
                'bname' => $attendee->badge_name,
                'bnumber' => $attendee->badge_number,
                'zip' => $attendee->zip,
                'country' => $attendee->country,
                'phone' => $attendee->phone,
                'email' => $attendee->email,
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
                'passtypeid' => $attendee->pass_type_id,
                'orderId' => $attendee->orderId,
                'regtype' => $attendee->reg_type,
                'checked' => $attendee->checked_in,
                'notes' => $attendee->notes,
                'addedBy' => $attendee->added_by));
    } catch(PDOExecption $e) {
        $conn->rollback();
        echo 'ERROR: ' . $e->getMessage();
    }

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
		$orderId = generateOrderId();
		$stmt = $conn->prepare("INSERT INTO orders (order_id, paid) VALUES (:id, :paid)");
		$stmt->execute(array('id' => $orderId, 'paid' => 'no'));
		foreach ($attendees as $attendee) {
            $attendee->orderId = $orderId;
            addAttendee($attendee);
        }
		$conn->commit();
	} catch(Execption $e) {
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
		$stmt = $conn->prepare("UPDATE attendees SET first_name=:firstname, last_name=:lastname, badge_name=:badge_name,
 								badge_number=:badgenumber, zip=:zip, phone=:phone, email=:email, country=:country,
 								birthdate=:bdate, ec_fullname=:ecname, ec_phone=:ecphone, ec_same=:same,
 								parent_fullname=:pcname, parent_phone=:pcphone, parent_form=:pform, paid=:paid,
 								paid_amount=:amount, pass_type=:passtype, pass_type_id=:passtypeid, reg_type=:regtype,
 								order_id=:orderid, notes=:notes, checked_in=:checkedin, added_by=:addedby
 								WHERE id=:id");
		$stmt->execute(array('firstname' => $attendee->first_name,
			'lastname' => $attendee->last_name,
			'badge_name' => $attendee->badge_name,
			'badgenumber' => $attendee->badge_number,
			'zip' => $attendee->zip,
			'phone' => $attendee->phone,
			'email' => $attendee->email,
			'country' => $attendee->country,
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
			'passtypeid' => $attendee->pass_type_id,
            'regtype' => $attendee->reg_type,
			'orderid' => $attendee->order_id,
			'notes' => $attendee->notes,
			'checkedin' => $attendee->checked_in,
            'addedby' => $attendee->added_by,
			'id' => $attendee->id));
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage());
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
 * Search for attendees. Handles searching by full name, last name, first name, or badge number
 * Note that you can use "%" for partial matches. For example, "Bill%" would match "Bill" and "Billy"
 * @param string $searchString
 * @return PDOStatement
 */
function attendeeSearch($searchString) {
    global $conn;
    $searchString = trim($searchString);
    if (count(explode(" ", $searchString)) > 1) {
        // If more than one word was passed in, search for a full name as well as
        // First and last names (to handle people with multiple names, like "Mary Jane Watson")
        // The full name search is pretty slow because it's concatenating the names in the SQL
        // query, but this should still be relatively fast with a normal number of attendees
        $stmt = $conn->prepare("SELECT * FROM attendees WHERE
                                CONCAT_WS(' ', first_name, last_name) = :search OR
                                first_name LIKE :search OR
                                last_name LIKE :search
                                ORDER BY first_name, last_name");
        $stmt->execute(array('search' => $searchString));
    } else {
        $stmt = $conn->prepare("SELECT * FROM attendees WHERE
                                badge_number LIKE :search OR
                                last_name LIKE :search OR
                                first_name LIKE :search
                                ORDER BY first_name, last_name");
        $stmt->execute(array('search' => $searchString));
    }
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
 * Search pre-registered attendees by phone number. Returns all attendees with the given phone number. Will strip
 * any non-numeric characters before searching.
 *
 * @param string $phone Phone Number
 * @return PDOStatement
 */
function preRegPhoneSearch($phone) {
    $phone = preg_replace('/[^0-9]/','',$phone);
	global $conn;
    $stmt = $conn->prepare("SELECT id, first_name, last_name, badge_name, checked_in, order_id
								FROM attendees
								WHERE phone LIKE :phone AND reg_type='PreReg'
								ORDER BY first_name");

	$stmt->execute(array('phone' => $phone));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Attendee");
	return $stmt;
}



/**
 * Insert the given staff member in to the database
 * 
 * @param Staff $staff 
 */
function staffAdd($staff) {
	global $conn;

	try {
		$stmt = $conn->prepare("INSERT INTO reg_staff
							(first_name, last_name, username, initials, password, phone_number, access_level, enabled)
							VALUES
							(:fname, :lname, :uname, :initials, :password, :cell, :access, :enabled)");
		$stmt->execute(array('fname' => $staff->first_name,
			'lname' => $staff->last_name,
			'uname' => $staff->username,
			'initials' => $staff->initials,
			'password' => $staff->password,
			'cell' => $staff->phone_number,
			'access' => $staff->access_level,
			'enabled' => $staff->enabled));
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage());
	}
}

/**
 * Update the given staff member in the database
 * 
 * @param Staff $staff
 */
function staffUpdate($staff) {
	global $conn;

	try {
		$stmt = $conn->prepare("UPDATE reg_staff SET first_name = :fname, last_name = :lname, initials = :initials,
								phone_number = :cell, access_level=:access, enabled=:enabled WHERE staff_id=:id");
		$stmt->execute(array('fname' => $staff->first_name,
			'lname' => $staff->last_name,
			'initials' => $staff->initials,
			'cell' => $staff->phone_number,
			'access' => $staff->access_level,
			'enabled' => $staff->enabled,
			'id' => $staff->staff_id));
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage());
	}
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
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Staff");
	return $stmt;
}


/**
 * Get staff record from database
 * 
 * @param int $id Staff ID number
 * @return Staff
 */
function getStaff($id) {
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM reg_staff WHERE staff_id = :id LIMIT 1");
	$stmt->execute(array('id'=>$id));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "Staff");
	return $stmt->fetch();
}


/**
 * Return entries from the history table
 *
 * @param int $number Number of items to return
 * @param string $type Return only type (from history_types table), defaults to all types
 * @return PDOStatement
 */
function historyList($number=50, $type=''){
	global $conn;

	if ($type == '') {
		$stmt = $conn->prepare("SELECT history.changed_at, history_types.type, history.username, history.description
							FROM history, history_types
							WHERE history.type_id = history_types.id ORDER BY history.id DESC LIMIT :number");
	} else {
		$stmt = $conn->prepare("SELECT history.changed_at, history_types.type, history.username, history.description
							FROM history, history_types
							WHERE history.type_id = history_types.id AND history_types.type = :type
							ORDER BY history.id DESC LIMIT :number");
		$stmt->bindValue('type', $type);
	}
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
			(SELECT count(badge_number) FROM attendees WHERE pass_type = 'Weekend' OR pass_type = 'VIP' OR pass_type = 'Panelist') AS passtypeweekend,
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
		die('ERROR: ' . $e->getMessage());
	}
}


/**
 * Given a pass type and birthdate, finds the appropriate pass type ID. This is hard coded for
 * now, and depends on the pass types being the default ones defined in install/01-tables.sql
 * @param Attendee $attendee
 * @return int
 */
function findPassTypeId($attendee) {

    if (strtolower($attendee->pass_type) == "panelist") {
        if ($attendee->getAgeAtCon() >= 18) {
            return 17;      // Panelist - Adult
        } elseif ($attendee->getAgeAtCon() >=13 && $attendee->getAgeAtCon() <= 17) {
            return 18;      // Panelist - Youth
        } else {
            return 19;      // Panelist - Child
        }
    } elseif (strtolower($attendee->pass_type) == "vip") {
        if ($attendee->getAgeAtCon() >= 18) {
            return 20;      // VIP - Adult
        } elseif ($attendee->getAgeAtCon() >=13 && $attendee->getAgeAtCon() <= 17) {
            return 21;      // VIP - Youth
        } else {
            return 22;      // VIP - Child
        }
    } elseif (strtolower($attendee->pass_type) == "weekend") {
        if ($attendee->getAgeAtCon() >= 18) {
            return 1;       // Weekend - Adult
        } elseif ($attendee->getAgeAtCon() >=13 && $attendee->getAgeAtCon() <= 17) {
            return 6;       // Weekend - Youth
        } else {
            return 11;      // Weekend - Child
        }

    } else {
        return 16;          // Child under 5
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

		while (($data = fgetcsv($handle,1000,"\t")) !== FALSE) {
			// Skip the first line
			if ($first == true) {
				$first = false;
				continue;
			}
			// Skip empty lines and lines where the first field starts with "#"
			if (count($data) != 19) {
				die("Error: Line " . $count . " does not have 19 columns.");
			}
			if (count($data) > 1 && substr($data[0], 0, 1) != '#') {
				if ($data[3] == "" || $data[3] == "NULL") {
					$data[3] = sprintf("ONL%1$04d", $BNumber);
				}

				$PhoneNumber = $data[6];
				$Phone_Stripped = preg_replace("/[^0-9s]/","",$PhoneNumber);

                $attendee = new Attendee();
                $attendee->first_name = $data[0];
                $attendee->last_name = $data[1];
                $attendee->badge_name = $data[2];
                $attendee->badge_number = $data[3];
                $attendee->zip = $data[4];
                $attendee->country = $data[5];
                $attendee->phone = $Phone_Stripped;
                $attendee->email = $data[7];
                $attendee->birthdate = $data[8];
                $attendee->ec_fullname = $data[9];
                $attendee->ec_phone = $data[10];
                $attendee->ec_same = $data[11];
                $attendee->parent_fullname = $data[12];
                $attendee->parent_phone = $data[13];
                $attendee->parent_form = 'No';
                $attendee->paid = $data[14];
                $attendee->paid_amount = $data[15];
                $attendee->pass_type = $data[16];
                $attendee->reg_type = 'PreReg';
                $attendee->checked_in = 'No';
                $attendee->order_id = $data[17];
                $attendee->notes = $data[18];
                $attendee->added_by = $_SESSION['username'];
                $attendee->pass_type_id = findPassTypeId($attendee);

				$orderStmt = $conn->prepare("INSERT INTO orders (order_id, total_amount, paid, paytype)
									VALUES (:orderid, :amount, :paid, :paytype)
									ON DUPLICATE KEY UPDATE total_amount = total_amount + :amount");
				// Create order if it doesn't exist. If it does, increment the total amount
				$orderStmt->execute(array('orderid' => $data[17],
					'amount' => $data[15],
					'paid' => $data[14],
					'paytype' => 'ONLINE'));
                addAttendee($attendee);

				$BNumber++;
				$count += 1;
			}
		}
		$conn->commit();
	} catch(Exception $e) {
		$conn->rollBack();
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
 * Insert the given pass type in to the database
 *
 * @param PassType $passType
 */
function passTypeAdd($passType) {
	global $conn;

	try {
		$stmt = $conn->prepare("INSERT INTO pass_types
							(name, stipe_color, stripe_text, day_text, visible, min_age, max_age, cost)
							VALUES
							(:name, :stripe_color, :stripe_text, :day_text, :visible, :min_age, :max_age, :cost)");
		$stmt->execute(array('name' => $passType->name,
			'stripe_color' => $passType->stripe_color,
			'stripe_text' => $passType->stripe_text,
			'day_text' => $passType->day_text,
			'visible' => $passType->visible,
			'min_age' => $passType->min_age,
			'max_age' => $passType->max_age,
			'cost' => $passType->cost));
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage());
	}
}

/**
 * Update the given pass type in the database
 *
 * @param PassType $passType
 */
function passTypeUpdate($passType) {
	global $conn;

	try {
		$stmt = $conn->prepare("UPDATE pass_types SET name = :name, stripe_color = :stripe_color,
 								stripe_text = :stripe_text, day_text = :day_text,
								visible = :visible, min_age = :min_age, max_age = :max_age, cost = :cost
								WHERE id=:id");
		$stmt->execute(array('name' => $passType->name,
			'stripe_color' => $passType->stripe_color,
			'stripe_text' => $passType->stripe_text,
			'day_text' => $passType->day_text,
			'visible' => $passType->visible,
			'min_age' => $passType->min_age,
			'max_age' => $passType->max_age,
			'cost' => $passType->cost,
			'id' => $passType->id));
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage());
	}
}

/**
 * List pass types in database
 *
 * @return PDOStatement
 */
function passTypeList(){
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM pass_types ORDER BY min_age DESC, name");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_CLASS, "PassType");
	return $stmt;
}


/**
 * Returns visible pass types in database
 *
 * @return PDOStatement
 */
function passTypeVisibleList(){
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM pass_types WHERE visible = 'Y' ORDER BY min_age DESC, name");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_CLASS, "PassType");
	return $stmt;
}

/**
 * Returns visible pass types for a given birthdate (based on age)
 *
 * @param int $age_in_years
 * @return PDOStatement
 */
function passTypeForAgeList($age_in_years){
	global $conn;

	$stmt = $conn->prepare("SELECT * from pass_types WHERE visible='Y' and min_age <= :age_in_years and :age_in_years <= max_age;");
	$stmt->execute(array('age_in_years'=>$age_in_years));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "PassType");
	return $stmt;
}


/**
 * Get pass type record from database
 *
 * @param int $id pass type ID number
 * @return PassType
 */
function getPassType($id) {
	global $conn;

	$stmt = $conn->prepare("SELECT * FROM pass_types WHERE id = :id LIMIT 1");
	$stmt->execute(array('id'=>$id));
	$stmt->setFetchMode(PDO::FETCH_CLASS, "PassType");
	return $stmt->fetch();
}


/**
 * Remove pass type record from database
 *
 * @param int $id pass type ID number
 */
function passTypeDelete($id) {
	global $conn;

	$stmt = $conn->prepare("DELETE FROM pass_types WHERE id = :id LIMIT 1");
	$stmt->execute(array('id'=>$id));
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


