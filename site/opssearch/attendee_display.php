<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

require_right('ops_search');

$colname_rs_attendee = "-1";
if (isset($_GET['id'])) {
  $colname_rs_attendee = $_GET['id'];
}
mysql_select_db($db_name, $kumo_conn);
$query_rs_attendee = sprintf("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_id = %s", mysql_real_escape_string($colname_rs_attendee));
$rs_attendee = mysql_query($query_rs_attendee, $kumo_conn) or die(mysql_error());
$row_rs_attendee = mysql_fetch_assoc($rs_attendee);
$totalRows_rs_attendee = mysql_num_rows($rs_attendee);

$Birthdate = $row_rs_attendee['kumo_reg_data_bdate'];

$Birthdate_array = explode("-", $Birthdate);
$BirthYear = $Birthdate_array[0];
$BirthMonth = $Birthdate_array[1];
$BirthDay = $Birthdate_array[2];

$year_diff  = date("Y") - $BirthYear;
$month_diff = date("m") - $BirthMonth;
$day_diff   = date("d") - $BirthDay;
if ($day_diff < 0 || $month_diff < 0){
$year_diff--;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kumoricon Registration</title>
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="header"></div> 
<?php require '../includes/leftmenu.php' ?>
<div id="content">

<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_fname']; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_lname']; ?></span>
<br />
<label>Badge Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_bname']; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_bnumber']; ?></span>
<br />
<label>E-Mail : </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_email']; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_phone']; ?></span>
<label>Birth Date: </label>
<span class="display_text"><?php echo $BirthMonth; ?>/<?php echo $BirthDay; ?>/<?php echo $BirthYear; ?></span>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_ecfullname']; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_ecphone']; ?></span>
<br />
</fieldset>
<?php if ($year_diff < 18) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_parent']; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_parentphone']; ?></span>
<br />
<label>Parental Permission Form Submitted: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_parentform']; ?> </span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_passtype']; ?> - $<?php echo $row_rs_attendee['kumo_reg_data_paidamount']; ?></span>
</fieldset>
<fieldset id="paymentinfo">
<legend>PAYMENT TYPE</legend>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_paytype']; ?>
</fieldset>
</div>
<div id="footer">&copy; Tim Zuidema</div>
</body>
</html>
<?php
mysql_free_result($rs_attendee);
?>
