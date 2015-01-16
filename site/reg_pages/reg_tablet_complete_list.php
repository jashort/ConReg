<?php require('../Connections/kumo_conn.php'); ?>
<?php require('../includes/authcheck.php'); ?>
<?php require('../includes/functions.php'); ?>
<?php

if (isset($_GET['phone'])) {
	
	$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$_GET['phone']);

	$stmt = $conn->prepare("SELECT * FROM kumo_reg_tablet WHERE phone = :phone");
    $stmt->execute(array('phone' => $Phone_Stripped));

}

if (isset($_GET['id'])) {
	
	$stmt = $conn->prepare("SELECT * FROM kumo_reg_tablet WHERE kumo_reg_data_id = :id");
    $stmt->execute(array('id' => $_GET['id']));
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$date = explode("-",$results['birthdate']);
	$Year = $date[0];
	$Month = str_pad($date[1], 2, "0", STR_PAD_LEFT);
	$Day = str_pad($date[2], 2, "0", STR_PAD_LEFT);
	$BDate = $Year . "-" . $Month . "-" . $Day;

$_SESSION["FirstName"] = $results['first_name'];
$_SESSION["LastName"] = $results['last_name'];
$_SESSION["BadgeNumber"] = $results['badge_number'];
$_SESSION["PhoneNumber"] = $results['phone'];
$_SESSION["Zip"] = $results['zip'];
$_SESSION["BDate"] = $BDate;
$_SESSION["BirthMonth"] = $Month;
$_SESSION["BirthDay"] = $Day;
$_SESSION["BirthYear"] = $Year;
$_SESSION["ECFullName"] = $results['ec_fullname'];
$_SESSION["ECPhoneNumber"] = $results['ec_phone'];
$_SESSION["Same"] = $results['ec_same'];
$_SESSION["PCFullName"] = $results['parent_fullname'];
$_SESSION["PCPhoneNumber"] = $results['parent_phone'];
$_SESSION["PassType"] = $results['pass_type'];

redirect("/reg_pages/reg_add.php");

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
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if (isset($_GET["phone"])) { // Show if search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
<!--    <th scope="col">Zip Code</th>-->
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="/reg_pages/reg_tablet_complete_list.php?id=<?php echo $results['kumo_reg_data_id']; ?>"><?php echo $results['first_name'] . " " . $results['last_name']; ?></a></td>
      <!--<td><?php echo $results['zip']; ?></td>-->
    </tr>
    <?php } while ($results = $stmt->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php } else { // Show if no search term ?>
  <form name="phone" action="/reg_pages/reg_tablet_complete_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Phone number : <input name="phone" type="text" class="input_20_200" /></label>
      <input name="Submit" type="submit" class="submit_button" value="Search" />
      </fieldset>
  </form>
<?php } ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_checkin_list);
mysql_free_result($rs_update_list);
?>
