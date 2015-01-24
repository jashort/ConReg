<?php
require_once('Connections/kumo_conn.php');
require_once('includes/roles.php');

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}
if (isset($_POST['username'])) {
	try {	
		
	$stmt = $conn->prepare('SELECT staff_id, initials, username, password, access_level, enabled FROM reg_staff WHERE username = :username');
    $stmt->execute(array('username' => $_POST["username"]));

	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(crypt($_POST["password"], $results["password"])==$results["password"]) {
		$verified = TRUE;
	}

	$redirectLoginSuccess = "/index.php";
	$redirectLoginFailed = "/login.php";
	
	if (($results) && ($verified) && ($results["enabled"] == "1")) {
    
	session_regenerate_id(true);
	
    //Declare session variables and assign them
    $_SESSION['username'] = $results["username"];
	$_SESSION['staffid'] = $results["staff_id"];
	$_SESSION['access'] = $results["access_level"];
	$_SESSION['initials'] = $results["initials"];
	$_SESSION['rights'] = get_rights($results["access_level"]);	// Get array of rights for
															    // the user's role from
															    // includes/roles.php


	if ($results["password"] == crypt("password", $results["password"])) {
		header("Location: /staff/staff_password_reset.php?username=". $_SESSION['username']);
	} else {
    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $redirectLoginSuccess );
	} 
	}
	} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kumoricon Registration</title>
<link href="/assets/css/kumoreg.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header"></div>
<!--<div id="subheader">
<div id="subheader_left"><marquee></marquee></div>
<div id="subheader_right"><input name="adamalert" type="button" class="adamalert_button" value="ADAM ALERT" /><input name="adminalert" type="button" class="adminalert_button" value="ADMIN ALERT" /></div>
</div>-->
<div id="content_login">
<form ACTION="login.php" name="login" method="POST">
<fieldset id="login_fieldset">
<label>Username : </label><input name="username" type="text" class="input_20_150" /><br />
<label>Password : </label><input name="password" type="password" class="input_20_150" /><br />
<input name="login" type="submit" class="submit_button" value="login"  />
</fieldset>
</form>
</div>
<div id="footer">&copy; Tim Zuidema</div>
</body>
</html>
