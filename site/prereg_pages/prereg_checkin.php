<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

require_right('prereg_checkin');

if (isset($_POST["Update"])) {
  // If a minor, check for parental consent form
  if (isset($_POST["Minor"]) && $_POST["Minor"] == true) {
    if ($_POST["PCFormVer"] != "on") {
      echo('Error: Parental consent form not verified. Click back and check "Parental Consent Form Received" after verifying attendee information');
      die();
    }
  }

  // Make sure information was verified
  
  if (isset($_POST["checkin"]) && $_POST["checkin"] == true) {
    regcheckin($_POST["Id"]);
    if (isset($_POST["Minor"]) && $_POST["Minor"] == true && $_POST["PCFormVer"] == "on") {
      regCheckinParentFormReceived($_POST["Id"]);
    }    
    redirect("/index.php");
    die();
  } else {
    /*
     * TODO: Move error handing in to the form so the site is still displayed instead of
     * just showing an error message
     *
     */
    echo('Error: Attendee Information not verified. Click back and check "Verified Info" after verifying attendee information');
    die();
  }
}

if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
} else {
  $attendee = Array();
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
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<form action="/prereg_pages/prereg_checkin.php" method="post">
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_fname; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_lname; ?></span>
<br />
<label>Badge Name: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_bname; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_bnumber; ?></span>
<br />
<label>Zip : </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_zip; ?></span>
<label>Country : </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_country; ?></span>
<br />
<label>E-Mail : </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_email; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_phone; ?></span>
<label>Birth Date: </label>
<span class="display_text"><?php echo $attendee->getBirthDate(); ?></span>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_ecfullname; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_ecphone; ?></span>
<br />
</fieldset>
  
<?php if ($attendee->isMinor()) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_parent; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $attendee->kumo_reg_data_parentphone; ?></span>
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($attendee->kumo_reg_data_parentform == "Yes") { echo "checked"; } ?> id="Parent Contact Form Verification" class="checkbox" /><span class="display_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>

  <fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>Pass Type: <?echo $attendee->kumo_reg_data_passtype; ?> -
    $<?echo $attendee->kumo_reg_data_paidamount; ?>
  </label>
</fieldset>
<fieldset id="notes">
<legend>Notes</legend>
<span class="display_text"><?php echo $attendee->kumo_reg_data_notes; ?></span>
</fieldset>
<fieldset id="checkin">
<legend>CHECK IN</legend>
<?php if ($attendee->kumo_reg_data_checkedin == "Yes") { ?>
  <span class='display_text'>CHECKED IN</span>
<? } else { ?>
  <input name='checkin' type='checkbox' id='Information Verification' class='checkbox' />
  <span class='display_text'>VERIFIED INFO</span><br />
  <input name="Id" type="hidden" value="<? echo $attendee->kumo_reg_data_id ?>" />
  <input name="Minor" type="hidden" value="<? echo $attendee->isMinor(); ?>" />
  <div class='centerbutton'>
    <input name='Update' type='submit' value='update' class='submit_button' />
  </div>
<? } ?>

</form>
</fieldset>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
