<?php
require_once('includes/functions.php');
require_once('includes/roles.php');

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}
if (isset($_POST['username'])) {
	validateUser($_POST['username'], $_POST['password']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Kumoricon Registration</title>
	<link href="/assets/css/kumoreg.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="header"></div>
<div id="content_login">
	<form ACTION="login.php" name="login" method="POST">
		<fieldset id="login_fieldset">
			<label>Username : </label><input name="username" type="text" class="input_20_150"/><br/>
			<label>Password : </label><input name="password" type="password" class="input_20_150"/><br/>
			<input name="login" type="submit" class="submit_button" value="login"/>
		</fieldset>
	</form>
</div>
<div id="footer">&copy; Tim Zuidema</div>
</body>
</html>
