<?php require('../includes/functions.php'); ?>
<?php require('../includes/authcheck.php'); ?>
<?php

try {
$username = "-1";
if (isset($_GET['username'])) {
  $username = $_GET['username'];
}
if (isset($_POST['username'])) {
  $username = $_POST['username'];
}
	
	$stmt = $conn->prepare("SELECT * FROM kumo_reg_staff WHERE kumo_reg_staff_username like :uname");
    $stmt->execute(array('uname' => $username));
	$results = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST["create"])) {
staffupdate($_POST["id"],$_POST["fname"],$_POST["lname"],$_POST["initials"],$_POST["cellnumber"],$_POST["accesslevel"],$_POST["enabled"]);
redirect("/index.php");
}
if (isset($_POST["passwordreset"])) {
passwordreset($_POST["username"],$_POST["password"]);
redirect("/index.php");
}
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Kumoricon Registration</title>
<!-- InstanceEndEditable -->
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" /> 
</script>
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
<!-- InstanceBeginEditable name="head" -->
<script src="/assets/javascript/jquery-1.8.0.js" type="text/javascript"></script>
<script src="/assets/javascript/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<script>
jQuery(function($){
   $("#cellnumber").mask("(999) 999-9999");
});
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<fieldset id="list_table_search">
<form name="staffupdate" action="/staff/staff_update.php" method="post">
<label>
<input name="id" type="hidden" value="<?php echo $results['kumo_reg_staff_id']; ?>" />
Username : </label>
<span class="display_text"><?php echo $results['kumo_reg_staff_username']; ?></span><br /><br />
<label>First Name : </label><input name="fname" type="text" class="input_20_150" value="<?php echo $results['kumo_reg_staff_fname']; ?>" /><br />
<label>Last Name : </label><input name="lname" type="text" class="input_20_150" value="<?php echo $results['kumo_reg_staff_lname']; ?>" /><br />
<label>Initials : </label><input name="initials" type="text" class="input_20_150" value="<?php echo $results['kumo_reg_staff_initials']; ?>" /><br />
<label>Cell Phone Number : </label><input id="cellnumber" name="cellnumber" type="text" class="input_20_150"  value="<?php echo $results['kumo_reg_staff_phone_number']; ?>" /><br />
<label>Access Level : </label>
<select name="accesslevel" class="select_25_125" id="accesslevel">
<?php 
for ($i=1; $i<=$_SESSION['access']; $i++)
{
if ($i == $results['kumo_reg_staff_accesslevel']){
	$selected = 'selected';
}
else {
	$selected = '';
}
if ($i == 1){
	$Access = 'User';
}
if ($i == 2){
	$Access = 'Super User';
}
if ($i == 3){
	$Access = 'Manager';
}
if ($i == 4){
	$Access = 'Super Admin';
}
  echo "<option value='" . $i . "' " . $selected . " >" . $Access . "</option>";
}
?>
</select><br />
<label>Enabled : </label><select name="enabled" class="select_25_75">
<option value="1" <?php if ($results['kumo_reg_staff_enabled']=="1"){echo 'selected';}?>>Yes</option>
<option value="0" <?php if ($results['kumo_reg_staff_enabled']=="0"){echo 'selected';}?>>No</option>
</select><br />
<input name="create" type="submit" class="submit_button" value="Update" />
</form>
<form name="password" action="/staff/staff_update.php" method="post">
<input name="username" type="hidden" value="<?php echo $results['kumo_reg_staff_username']; ?>" />
<input name="password" type="hidden" value="password" />
<input name="passwordreset" type="submit" class="submit_button" value="Password Reset" /><br />
</form>
</fieldset>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>