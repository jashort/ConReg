<?php 
require('../includes/functions.php');
require('../includes/authcheck.php');

if (isset($_POST["Reset"])) {
passwordreset($_POST["username"],$_POST["password"]);
redirect("/index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kumoricon Registration</title>
<link href="/assets/css/kumoreg.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function verifyPassword(){
document.status = true;     
if ((document.passchange.password.value != document.passchange.password2.value) || (document.passchange.password.value == "")) {
alert("The password fields do not match or are blank.  Please retype them to make sure they are the same.");
document.status = false; 
}}
</script>
</head>
<body>
<div id="header"></div>
<div id="subheader">
<div id="subheader_left"><marquee></marquee></div>
<!--<div id="subheader_right"><input name="adamalert" type="button" class="adamalert_button" value="ADAM ALERT" /><input name="adminalert" type="button" class="adminalert_button" value="ADMIN ALERT" />--></div>
</div>
<div id="content_login">
<form action="staff_password_reset.php" id="passchange" name="passchange" method="post">
<fieldset id="list_table_search">
<input name="username" type="hidden" value="<?php echo $_GET["username"]; ?>" />
<label>New Password : </label><input id="password" name="password" type="password" class="input_20_150" /><br />
<label>Verify Password : </label><input id="password2" name="password2" type="password" class="input_20_150" /><br />
<input name="Reset" type="submit" class="submit_button" value="Change"  onclick="verifyPassword();return document.status" />
</fieldset>
</form>
</div>
<div id="footer">&copy; DEFINITIVE LLC</div>
</body>
</html>
