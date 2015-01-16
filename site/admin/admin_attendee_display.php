<?php
require('../includes/functions.php');
require('../includes/authcheck.php');
require_once('../includes/Attendee.php');

require_right('attendee_search');

$attendee = new Attendee();
if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
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
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>
<div id="content"><!-- InstanceBeginEditable name="Content" -->

<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $attendee->first_name; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $attendee->last_name; ?></span>
<br />
<label>Badge Name: </label>
<span class="display_text"><?php echo $attendee->badge_name; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php echo $attendee->badge_number; ?></span>
<br />
<label>Zip : </label>
<span class="display_text"><?php echo $attendee->zip; ?></span>
<label>Country : </label>
<span class="display_text"><?php echo $attendee->country; ?></span>
<br />
<label>E-Mail : </label>
<span class="display_text"><?php echo $attendee->email; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $attendee->phone; ?></span>
<label>Birth Date: </label>
<span class="display_text"><?php echo $attendee->getBirthDate(); ?></span>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $attendee->ec_fullname; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $attendee->ec_phone; ?></span>
<br />
</fieldset>
<?php if ($attendee->isMinor()) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $attendee->parent_fullname; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $attendee->parent_phone; ?></span>
<br />
<label>Parental Permission Form Submitted: </label>
<span class="display_text"><?php echo $attendee->parent_form; ?> </span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<span class="display_text"><?php echo $attendee->pass_type; ?> - $<?php echo $attendee->paid_amount; ?></span>
</fieldset>
<fieldset id="paymentinfo">
<legend>PAID</legend>
<span class="display_text"><?php echo $attendee->paid; ?>
</fieldset>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
