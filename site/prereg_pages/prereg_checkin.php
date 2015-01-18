<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
require_right('prereg_checkin');

if (isset($_POST["Update"])) {
  // If a minor, check for parental consent form
  if (isset($_POST["Minor"]) && $_POST["Minor"] == true) {
    if (!isset($_POST["PCFormVer"]) || $_POST["PCFormVer"] != "on") {
      echo('Error: Parental consent form not verified. Click back and check "Parental Consent Form Received" after verifying attendee information');
      die();
    }
  }

  // Make sure information was verified
  if (isset($_POST["checkin"]) && $_POST["checkin"] == true) {
    regcheckin($_POST["id"]);
    if (isset($_POST["Minor"]) && $_POST["Minor"] == true && $_POST["PCFormVer"] == "on") {
      regCheckinParentFormReceived($_POST["Id"]);
    }
    // Display the print badge link below.

  } else {
    echo('Error: Attendee Information not verified. Click back and check "Verified Info" after verifying attendee information');
    die();
  }
}

if (isset($_POST["Done"])) {
  redirect("/prereg_pages/prereg_checkin_list.php?field=ord&id=".$_POST["oid"]);
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
  <? if (isset($_POST["checkin"]) && $_POST["checkin"] == true) { ?>
    <br><br>
    <form action="/reg_pages/badgeprint.php" method="post" target="_blank">
      <input type="hidden" name="print" value="<?php echo $_POST['id'] ?>" />
      <div class='centerbutton'>
        <input name='printbutton' type='submit' value='Print Badge' class='submit_button' />
      </div>
    </form>

    <form action="/prereg_pages/prereg_checkin.php" method="post">
        <div class='centerbutton'>
          <input name="oid" type="hidden" value="<?php echo $_POST["oid"]?>" />
          <input name='Done' type='submit' value='Done' class='submit_button' />
        </div>
        
      </form>
  
  <? } else { ?>

  
  <form action="/prereg_pages/prereg_checkin.php" method="post">
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
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($attendee->parent_form == "Yes") { echo "checked"; } ?> id="Parent Contact Form Verification" class="checkbox" /><span class="display_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>

  <fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>Pass Type: <?echo $attendee->pass_type; ?> -
    $<?echo $attendee->paid_amount; ?>
  </label>
</fieldset>
<fieldset id="notes">
<legend>Notes</legend>
<span class="display_text"><?php echo $attendee->notes; ?></span>
</fieldset>
<fieldset id="checkin">
<legend>CHECK IN</legend>
<?php if ($attendee->checked_in == "Yes") { ?>
  <span class='display_text'>CHECKED IN</span>
<? } else { ?>
  <input name='checkin' type='checkbox' id='Information Verification' class='checkbox' />
  <span class='display_text'>VERIFIED INFO</span><br />
  <input name="id" type="hidden" value="<?php echo $attendee->id ?>" />
  <input name="oid" type="hidden" value="<?php echo $attendee->order_id ?>" />
  <input name="Minor" type="hidden" value="<?php echo $attendee->isMinor(); ?>" />
  <div class='centerbutton'>
    <input name='Update' type='submit' value='update' class='submit_button' />
  </div>
<? } ?>

</form>
</fieldset>
<!-- InstanceEndEditable --></div>
<? } ?>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
