<?php
require_once('../includes/functions.php');
require_once('../includes/passtypes.php');

require_once('../includes/authcheck.php');
requireRight('registration_modify');

if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
} elseif (isset($_POST["Update"])) {
  $attendee = new Attendee();
  $attendee->fromArray($_POST);
  regUpdate($attendee);
  logMessage($_SESSION['username'], 60, "Modified attendee " .
      $attendee->first_name . ' ' . $attendee->last_name . "(ID " . $attendee->id . ")");

  redirect("/index.php");
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
<script src="/assets/javascript/jquery-1.8.0.js" type="text/javascript"></script>
<script src="/assets/javascript/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<script>
	jQuery(function($){
		$("#PhoneNumber").mask("(999) 999-9999");
		$("#ECPhoneNumber").mask("(999) 999-9999");
		$("#PCPhoneNumber").mask("(999) 999-9999");
	});
</script>
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.id; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }

function sameInfo() {

  if (document.getElementById('Same').checked) {
    document.getElementById('Same').value = "Y";
    document.getElementById('PCFullName').value = document.getElementById('ECFullName').value;
    document.getElementById('PCPhone').value = document.getElementById('ECPhone').value;
  } else {
    document.getElementById('Same').value = "";
    document.getElementById('PCFullName').value = "";
    document.getElementById('PCPhone').value = "";
  }
}
  
  function setAmount(amt) {
    document.getElementById('paid_amount').value = amt;
  }
  
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<form name="reg_update" action="reg_update.php" method="post">
<input name="id" type="hidden" value="<?php echo $attendee->id ?>" />
<input name="order_id" type="hidden" value="<?php echo $attendee->order_id ?>" />
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name:
<input name="first_name" type="text" maxlength="60" class="input_20_200" id="First Name" value="<?php echo $attendee->first_name ?>" /></label>
<label>Last Name:
<input name="last_name" type="text" maxlength="60" class="input_20_200" id="Last Name" value="<?php echo $attendee->last_name ?>" /></label>
<br />
<label>Badge Name:
<input name="badge_name" type="text" maxlength="15" class="input_20_200" id="Last Name" value="<?php echo $attendee->badge_name ?>" /></label>
<br />
<label>Badge Number:
<input name="badge_number" type="text" maxlength="10" class="input_20_200" id="Badge Number" value="<?php echo $attendee->badge_number ?>" /></label>
<br />
<label>Zip :
<input name="zip" type="text" class="input_20_150" maxlength="10" id="Zip" value="<?php echo $attendee->zip ?>"  /></label>
<br />
<label>E-Mail :
<input name="email" type="text" class="input_20_200" maxlength="250" id="E-Mail" value="<?php echo $attendee->email ?>"  /></label>
<br />
<label>Phone Number:
<input name="phone" type="text" class="input_20_200" id="PhoneNumber" maxlength="60" value="<?php echo $attendee->phone ?>"  /></label>
<label>Birth Date:
	<input type="number" class="input_20_40" maxlength="2" name="birth_month" id="Birth Month" value="<?php echo $attendee->getBirthMonth() ?>" min="1" max="12" placeholder="MM">
	<span class="bold_text">/</span>
	<input type="number" class="input_20_40" maxlength="2" name="birth_day" id="Birth Day" value="<?php echo $attendee->getBirthDay() ?>" min="1" max="31" placeholder="DD">
	<span class="bold_text">/</span>
	<input type="number" class="input_20_60" maxlength="4" name="birth_year" id="Birth Year" value="<?php echo $attendee->getBirthYear() ?>" min="1900" max="2015" placeholder="YYYY">
	</label>(Month / Day / Year)
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name:
<input name="ec_fullname" id="ECFullName" type="text" class="input_20_200"  maxlength="250" id="Emergency Contact Full Name" value="<?php echo $attendee->ec_fullname ?>"  /></label>
<br />
<label>Phone Number:
<input name="ec_phone" id="ECPhone" type="text" class="input_20_200" maxlength="60" id="ECPhoneNumber" value="<?php echo $attendee->ec_phone ?>"  /></label>
<br />
</fieldset>
<?php if ($attendee->isMinor()) { ?>
  <fieldset id="parentinfo">
  <legend>Parent Contact Info</legend>
  <input name="ec_same" id="Same" type="checkbox" class="checkbox" onclick="sameInfo();" <?php if ($attendee->ec_same == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
  <br /><br />
  <label>Full Name:
  <input name="parent_fullname" id="PCFullName" type="text" class="input_20_200" maxlength="250" id="Parent Contact Full Name" value="<?php echo $attendee->parent_fullname ?>" /></label>
  <br />
  <label>Phone Number:
  <input name="parent_phone" id="PCPhone" type="text" class="input_20_200" maxlength="60" id="PCPhoneNumber" value="<?php echo $attendee->parent_phone ?>" /></label>
  <br /><br />
  <input name="parent_form" id="ParentForm" type="checkbox" <?php if ($attendee->parent_form == "Yes") { echo "value=\"Yes\" checked"; } else { echo "value=\"\""; } ?> id="Parent Contact Form Verification" class="checkbox" /><span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
  </fieldset>
<?php } else { ?>
  <input type="hidden" name="ec_same" value="<?php echo $attendee->ec_same?>" />
  <input type="hidden" name="parent_fullname" value="<?php echo $attendee->parent_fullname ?>" />
  <input type="hidden" name="parent_phone" value="<?php echo $attendee->parent_phone ?>" />
  <input type="hidden" name="parent_form" value="<?php echo $attendee->parent_form ?>" />
<? } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>
    <? $amount = calculatePassCost($attendee->getAge(), 'Weekend') ?>
    <input type="radio" name="pass_type" value="Weekend" id="PassType_0" onchange="setAmount(<?php echo $amount?>);" <?php if ($attendee->pass_type == "Weekend") echo "checked=\"checked\""; ?> />
    All Weekend - $<?php echo $amount ?></label>
  <hr />
  <label>
    <? $amount = calculatePassCost($attendee->getAge(), 'VIP') ?>
    <input type="radio" name="pass_type" value="VIP" id="PassType_1" onchange="setAmount(<?php echo $amount?>);" <?php if ($attendee->pass_type == "VIP") echo "checked=\"checked\""; ?> />
    VIP - $<?php echo $amount ?></label>
  <br />
  <label>
    <? $amount = calculatePassCost($attendee->getAge(), 'Friday') ?>
    <input name="pass_type" type="radio" id="PassType_2" onchange="setAmount(<?php echo $amount ?>);" value="Friday" <?php if ($attendee->pass_type == "Friday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $amount ?></label>
  <br />
  <label>
    <? $amount = calculatePassCost($attendee->getAge(), 'Saturday') ?>
    <input name="pass_type" type="radio" id="PassType_3" onchange="setAmount(<?php echo $amount ?>);" value="Saturday" <?php if ($attendee->pass_type == "Saturday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $amount ?></label>
  <br />
  <label>
    <? $amount = calculatePassCost($attendee->getAge(), 'Sunday') ?>
    <input type="radio" name="pass_type" value="Sunday" id="PassType_4" onchange="setAmount(<?php echo $amount ?>);" <?php if ($attendee->pass_type == "Sunday") echo "checked=\"checked\""; ?> />
    Sunday Only - $<?php echo $amount ?></label>
  <br />
  <label>
    <? $amount = calculatePassCost($attendee->getAge(), 'Monday') ?>
    <input name="pass_type" type="radio" id="PassType_5" onclick="setAmount(<?php echo $amount ?>)" value="Monday" <?php if ($attendee->pass_type == "Monday") echo "checked=\"checked\""; ?> />
    Monday Only - $<?php echo $amount ?></label>
  <br />
  <?php if (hasRight('registration_manual_price')) { ?>
    <span class="radio_button_left_margin">
      <input name="pass_type" type="radio" id="PassType_6" onclick="setAmount(<?php echo $attendee->paid_amount ?>)" value="Manual Price" <?php if ($attendee->pass_type == "Manual Price") echo "checked=\"checked\""; ?> />
      Manual Price - $<?php echo $attendee->paid_amount ?>
    </span><br /><br />
    <label>Amount Paid: $
      <input name="paid_amount" type="text" maxlength="8" class="input_20_200" id="paid_amount" value="<?php echo $attendee->paid_amount ?>" /></label>
    <br />
  <?php } ?>
</fieldset>
<fieldset id="notes">
<label>Notes :
<textarea name="notes" rows="5"><?php echo $attendee->notes ?></textarea></label>
</fieldset>
  <input type="hidden" name="reg_type" value="<?php echo $attendee->reg_type?>" />
  <input type="hidden" name="checked_in" value="<?php echo $attendee->checked_in ?>" />
  <input type="hidden" name="paid" value="<?php echo $attendee->paid ?>" />
  <input type="hidden" name="id" value="<?php echo $attendee->id ?>" />
  <input type="hidden" name="country" value="<?php echo $attendee->country ?>" />
<div class="centerbutton">
<input name="Update" type="submit" value="update" class="submit_button" />
</div>
</form>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
