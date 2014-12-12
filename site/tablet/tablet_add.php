<?php
require('../Connections/kumo_conn.php');

function tabletadd($FirstName, $LastName, $bnumber, $Email, $PhoneNumber, $BDate, $ECFullName, $ECPhoneNumber, $Same, $PCFullName, $PCPhoneNumber, $PassType) {
	
	global $conn;
	
	$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

	$stmt = $conn->prepare("INSERT INTO kumo_reg_tablet (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_email, kumo_reg_data_phone, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone, kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_passtype) VALUES (:firstname, :lastname, :bnumber, :email, :phone, :bdate, :ecname, :ecphone, :same, :pcname, :pcphone, :passtype)");			   
        $stmt->execute(array('firstname' => $FirstName, 
                             'lastname' => $LastName, 
                             'bnumber' => $bnumber,
                             'email' => $Email,
                             'phone' => $Phone_Stripped, 
                             'bdate' => $BDate, 
                             'ecname' => $ECFullName, 
                             'ecphone' => $ECPhoneNumber, 
                             'same' => $Same, 
                             'pcname' => $PCFullName, 
                             'pcphone' => $PCPhoneNumber, 
                             'passtype' => $PassType));
}

function getnextbadgenumber($username) {
    // Queries the staff table for the next badge number for <username> and increments the badge number
    // stored in that record.

    global $conn;

    // Get number
    $stmt = $conn->prepare("SELECT kumo_reg_staff_bnumber FROM kumo_reg_staff WHERE kumo_reg_staff_username = :uname");
    $stmt->execute(array('uname' => $username));
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    $BNumber = $results['kumo_reg_staff_bnumber']+1;

    // Save new number
    $stmt = $conn->prepare("UPDATE kumo_reg_staff SET kumo_reg_staff_bnumber = :bnumber WHERE kumo_reg_staff_username = :uname");
    $stmt->execute(array('bnumber' => $BNumber, 'uname' => $username));
    return $BNumber;    
}

$badgeNumber = sprintf('AC%1$05d', getnextbadgenumber('tablet'));

$birthdate = $_POST['BirthYear'] . '-' . $_POST['BirthMonth'] . '-' . $_POST['BirthDay'];
tabletadd($_POST['FirstName'], $_POST['LastName'], $badgeNumber, $_POST['EMail'], $_POST['PhoneNumber'], $birthdate, $_POST['ECFullName'], $_POST['ECPhoneNumber'], $_POST['Same'], $_POST['PCFullName'], $_POST['PCPhoneNumber'], $_POST['PassType']);



$response = array();
$response['success'] = True;
$response['message'] = "Thank you! Your information has been saved.";
// $response['message'] = $badgeNumber;

print(json_encode($response));

?>
