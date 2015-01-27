<?php
require_once('../includes/functions.php');
require_once('../includes/passtypes.php');

require_once('../includes/authcheck.php');
requireRight('registration_add');

if ((!array_key_exists('part', $_POST) && !array_key_exists('part', $_GET)) || 
    (array_key_exists('action', $_GET) && $_GET['action'] == "clear")) {
  // Brand new attendee
  $_SESSION['current'] = new Attendee();
  if (!array_key_exists('currentOrder', $_SESSION)) { // Create order array if it doesn't exist
    $_SESSION['currentOrder'] = Array();
  }
  // Get it the next available badge number and set some default values
  $badge = $_SESSION['initials'] . str_pad(getBadgeNumber($_SESSION['staffid']), 4, '0', STR_PAD_LEFT);
  $_SESSION['current']->badge_number = $badge;
  $_SESSION['current']->added_by = $_SESSION['username'];
  $_SESSION['current']->reg_type = "Reg";
  $_SESSION['current']->checked_in = "N";
  $_SESSION['current']->paid = "N";
  $_SESSION['current']->parent_form = "N";
  $_SESSION['current']->ec_same = "N";
  redirect('/reg_pages/reg_add.php?part=1');
} elseif (array_key_exists('part', $_POST)) {
  // Handle posting form data and redirecting to the next section
  if ($_POST['part'] == 1) {
    $_SESSION['current']->first_name = $_POST["FirstName"];
    $_SESSION['current']->last_name = $_POST["LastName"];
    $_SESSION['current']->phone = $_POST["PhoneNumber"];
    $_SESSION['current']->email = $_POST["EMail"];
    $_SESSION['current']->zip = $_POST["Zip"];
    $_SESSION['current']->birthdate = $_POST["BirthYear"] . '-' . $_POST["BirthMonth"] . '-' . $_POST["BirthDay"];
    redirect('/reg_pages/reg_add.php?part=2');
  } elseif ($_POST['part'] == 2) {
    $_SESSION['current']->ec_fullname = $_POST["ECFullName"];
    $_SESSION['current']->ec_phone = $_POST["ECPhoneNumber"];
    if (array_key_exists("Same", $_POST)) {
      $_SESSION['current']->ec_same = $_POST["Same"];
    } else {
      $_SESSION['current']->ec_same = "N";
    }
    $_SESSION['current']->parent_fullname = $_POST["PCFullName"];
    $_SESSION['current']->parent_phone = $_POST["PCPhoneNumber"];
    $_SESSION['current']->parent_form = $_POST["PCFormVer"];
    redirect('/reg_pages/reg_add.php?part=3');
  } elseif ($_POST['part'] == 3) {
    $_SESSION['current']->pass_type = $_POST["PassType"];
    if ($_POST['PassType'] == "Manual Price") {
      if(preg_match('^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$', $_POST["MPAmount"])) {
        $_SESSION['current']->paid_amount = $_POST["MPAmount"];
      } else {
        die('Manual price amount must contain numbers only. Ex: 19.99');
      }
    } else {
      $_SESSION['current']->paid_amount = calculatePassCost($_SESSION['current']->getAge(), $_POST['PassType']);
    }
    $_SESSION['current']->notes = $_POST["Notes"];
    redirect('/reg_pages/reg_add.php?part=4');
  } elseif ($_POST['part'] == 4) {
    // Add the current attendee to the open order
    array_push($_SESSION['currentOrder'], $_SESSION['current']);
    unset($_SESSION['current']);
    redirect("/reg_pages/reg_order.php");
  }

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
        } else if (test.indexOf('isDate')!=-1) { var nulldate=new RegExp("(MM|DD|YYYY)"); p=nulldate.test(val);
          if (p==true) errors+='- '+nm+' is required.\n';
        } else if (test.indexOf('isState')!=-1) { p=val.indexOf('State');
          if (p>1 || p==(val.length-1)) errors+='- '+nm+' is required.\n';
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
    document.getElementById('PCPhoneNumber').value = document.getElementById('ECPhoneNumber').value;
  } else {
    document.getElementById('Same').value = "";
    document.getElementById('PCFullName').value = "";
    document.getElementById('PCPhoneNumber').value = "";
  }
}

function clearVerify() {
  var answer=confirm("Are you sure you want to clear?");
  if (answer==true) {
    window.location='/reg_pages/reg_add.php?action=clear';
  }
}
function manualPrice() {
  do { 
    var amount = prompt("Please enter the amount","ex 40.00");
    var currencyCheck = new RegExp("^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$");
    var currencyFormat = currencyCheck.test(amount);
  } while ((amount=="") || (currencyFormat==false));
  do {
    var reason = prompt("Please enter the reason for the manual pricing","");
  } while (reason=="");
  document.getElementById('MPAmount').value = amount;
  document.getElementById('Notes').value = reason;
}
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if (array_key_exists('part', $_GET) && $_GET["part"]=="1"){ ?>
  <form name="reg_add1" action="reg_add.php" method="post">
    <input name="part" type="hidden" value="1" />
  <fieldset id="personalinfo">
  <legend>Attendee Info</legend>
  <p>
    <label>First Name:
    <input name="FirstName" type="text" maxlength="60" class="input_20_200" id="First Name" value="<?php echo $_SESSION['current']->first_name; ?>" /></label>
    <label>Last Name:
    <input name="LastName" type="text" maxlength="60" class="input_20_200" id="Last Name" value="<?php echo $_SESSION['current']->last_name; ?>" /></label>
    <br />
    <label>Phone Number:
    <input name="PhoneNumber" type="text" maxlength="60" class="input_20_200" id="PhoneNumber" value="<?php echo $_SESSION['current']->phone; ?>" /></label>
    <br />
    <label>EMail:
      <input name="EMail" type="text" class="input_20_200" maxlength="250" id="EMail" value="<?php echo $_SESSION['current']->email; ?>" /></label>
    <br />
      <label>Zip:
    <input name="Zip" type="text" class="input_20_200" maxlength="10" id="Zip" value="<?php echo $_SESSION['current']->zip; ?>" /></label>
    <br />
    <span class="display_text_large">
    <label>Badge Number: <?php echo $_SESSION["current"]->badge_number ?>
    </span><br /><br />
    <label>Birth Date:
      <? // If a birthdate has been set, display it. Otherwise, display blank fields
      if ($_SESSION['current']->getAge() == -1) { ?>
        <input type="number" class="input_20_40" maxlength="2" name="BirthMonth" id="Birth Month" value="<?php echo $_SESSION['current']->getBirthMonth() ?>" min="1" max="12" placeholder="MM">
        <span class="bold_text">/</span>
        <input type="number" class="input_20_40" maxlength="2" name="BirthDay" id="Birth Day" value="<?php echo $_SESSION['current']->getBirthDay() ?>" min="1" max="31" placeholder="DD">
        <span class="bold_text">/</span>
        <input type="number" class="input_20_60" maxlength="4" name="BirthYear" id="Birth Year" value="<?php echo $_SESSION['current']->getBirthYear()?>" min="1900" max="2015" placeholder="YYYY">
        </label>(Month / Day / Year)
      <? } else { ?>
        <input type="number" class="input_20_40" maxlength="2" name="BirthMonth" id="Birth Month" min="1" max="12" placeholder="MM">
        <span class="bold_text">/</span>
        <input type="number" class="input_20_40" maxlength="2" name="BirthDay" id="Birth Day" min="1" max="31" placeholder="DD">
        <span class="bold_text">/</span>
        <input type="number" class="input_20_60" maxlength="4" name="BirthYear" id="Birth Year" min="1900" max="2015" placeholder="YYYY">
        </label>(Month / Day / Year)
      <? } ?>

  </p>
  </fieldset>
  <div class="centerbutton">
    <input name="Next" type="submit" class="next_button" onclick="MM_validateForm('First Name','','R','Last Name','','R','Phone Number','','R','Zip','','R');return document.MM_returnValue" value="Next" />
    <input name="Clear" type="button" class="next_button" onclick="clearVerify()" value="Clear" />
  </div>
  </form>
<?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="2") { ?>
  <br />
  <fieldset id="currentage">
  <span class="display_text">Current Age: <?php echo $_SESSION['current']->getAge() ?></span>
  </fieldset>
  <form name="reg_add2" action="reg_add.php" method="post">
    <input name="part" type="hidden" value="2" />
  <fieldset id="emergencyinfo">
  <legend>Emergency Contact Info</legend>
  <label>Full Name:
  <input name="ECFullName" id="ECFullName" type="text" class="input_20_200" maxlength="250" value="<?php echo $_SESSION["current"]->ec_fullname; ?>"  /></label>
  <br />
  <label>Phone Number:
  <input name="ECPhoneNumber" id="ECPhoneNumber" type="text" class="input_20_200" maxlength="60" value="<?php echo $_SESSION['current']->ec_phone ?>" /></label>
  <br />
  </fieldset>
  <?php if ($_SESSION['current']->isMinor()) { ?>
    <fieldset id="parentinfo">
    <legend>Parent Contact Info</legend>
    <input name="Same" id="Same" type="checkbox" class="checkbox" value="Y" onClick="sameInfo();" <?php if ($_SESSION['current']->ec_same == "Y") { echo "checked"; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
    <br /><br />
    <label>Full Name:
    <input name="PCFullName" id="PCFullName" type="text" class="input_20_200" maxlength="250" value="<?php echo $_SESSION['current']->ec_fullname; ?>"  /></label>
    <br />
    <label>Phone Number:
    <input name="PCPhoneNumber" id="PCPhoneNumber" type="text" class="input_20_200" maxlength="60" value="<?php echo $_SESSION['current']->ec_phone; ?>" /></label>
    <br /><br />
    <input name="PCFormVer" type="checkbox" value="Y" <?php if ($_SESSION['current']->parent_form == "Y") { echo "checked"; } ?> id="Parent Contact Form Verification" class="checkbox" /><span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
    </fieldset>
  <?php } else { ?>
      <input name="Same" value="N" type="hidden" />
      <input name="PCFullName" value="" type="hidden" />
      <input name="PCPhoneNumber" value="" type="hidden" />
      <input name="PCFormVer" value="N" type="hidden" />
  <? } ?>
  <div class="centerbutton">
    <!--<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=1');return document.MM_returnValue" value="Previous" />-->
    <input name="Submit" type="submit" class="next_button" 
      <?php if ($_SESSION['current']->isMinor()) { ?>onclick="MM_validateForm('Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R','Parent Contact Full Name','','R','Parent Contact Phone Number','','R','Parent Contact Form Verification','','R');return document.MM_returnValue"<?php } else { ?>onclick="MM_validateForm('Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R');return document.MM_returnValue"<?php } ?> value="Next" />
    <input name="Clear" type="button" class="next_button" onclick="clearVerify()" value="Clear" />
  </div>
  </form>
<?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="3") { ?>
  <?
  // Get pass costs based on age
  $weekendCost = calculatePassCost($_SESSION['current']->getAge(), "Weekend");
  $vipCost = calculatePassCost($_SESSION['current']->getAge(), "VIP");
  $fridayCost = calculatePassCost($_SESSION['current']->getAge(), "Friday");
  $saturdayCost = calculatePassCost($_SESSION['current']->getAge(), "Saturday");
  $sundayCost = calculatePassCost($_SESSION['current']->getAge(), "Sunday");
  $mondayCost = calculatePassCost($_SESSION['current']->getAge(), "Monday");
  ?>

<form name="reg_add3" action="reg_add.php" method="post">
  <input name="part" type="hidden" value="3" />

  <fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>
    <input type="radio" name="PassType" value="Weekend" id="PassType_0" <?php if ($_SESSION['current']->pass_type == "Weekend") echo "checked=\"checked\""; ?> />
    All Weekend - $<?php echo $weekendCost ?></label>
    <hr />
    <label>
      <input type="radio" name="PassType" value="VIP" id="PassType_1" <?php if ($_SESSION['current']->pass_type == "VIP") echo "checked=\"checked\""; ?> />
      VIP - $<?php echo $vipCost ?></label>
    <hr />
  <label>
    <input name="PassType" type="radio" id="PassType_2" value="Friday" <?php if ($_SESSION['current']->pass_type == "Friday") echo "checked=\"checked\""; ?> />
    Friday Only - $<?php echo $fridayCost ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_3" value="Saturday" <?php if ($_SESSION['current']->pass_type == "Saturday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $saturdayCost ?></label>
  <br />
  <label>
    <input type="radio" name="PassType" value="Sunday" id="PassType_4" <?php if ($_SESSION['current']->pass_type == "Sunday") echo "checked=\"checked\""; ?> />
    Sunday Only - $<?php echo $sundayCost ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_5" value="Monday" <?php if ($_SESSION['current']->pass_type == "Monday") echo "checked=\"checked\""; ?> />
    Monday Only - $<?php echo $mondayCost ?></label><br />
	<?php if (hasRight('registration_manual_price')) { ?>
      <span class="radio_button_left_margin">
    <input name="PassType" type="radio" id="PassType_6" onclick="manualPrice()" value="Manual Price" <?php if ($_SESSION['current']->pass_type == "Manual Price") echo "checked=\"checked\""; ?> />
    Manual Price - $
    <input name="MPAmount" type="text" class="input_20_150" id="MPAmount" value="<?php echo $_SESSION['current']->paid_amount ?>" />
      </span><?php } ?>

  <br />
</p>
</fieldset>
<fieldset id="notes">
<label>Notes : </label>
<textarea name="Notes" id="Notes" rows="5"><?php echo $_SESSION['current']->notes; ?></textarea>
</fieldset>
<div class="centerbutton">
<!--<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=2');return document.MM_returnValue" value="Previous" />-->
  <input name="Submit" type="submit" class="next_button" value="Next" />
  <input name="Clear" type="button" class="next_button" onclick="clearVerify()" value="Clear" />
</div>
</form>
<?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="4") { ?>
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $_SESSION['current']->first_name; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $_SESSION['current']->last_name; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION['current']->phone; ?></span>
<br />
<label>Email: </label>
<span class="display_text"><?php echo $_SESSION['current']->email; ?></span>
<br />
<label>Zip: </label>
<span class="display_text"><?php echo $_SESSION['current']->zip; ?></span>
<br />
<label>Badge Number: </label>
<span class="display_text"><?php echo $_SESSION['current']->badge_number; ?></span>
<br />
<label>Birth Date: </label>
<span class="display_text"><?php echo $_SESSION['current']->getBirthDate() ?></span>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $_SESSION['current']->ec_fullname ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION['current']->ec_phone ?></span>
<br />
</fieldset>
<?php if ($_SESSION['current']->isMinor()) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $_SESSION['current']->parent_fullname ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION['current']->parent_phone ?></span>
<br />
<label>Parental Permission Form Submitted: </label>
<span class="display_text"><?php echo $_SESSION['current']->parent_form; ?> </span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<span class="display_text"><?php echo $_SESSION['current']->pass_type ?> - $<?php echo $_SESSION['current']->paid_amount ?></span>
</fieldset>

  <fieldset id="paymentinfo">
<legend>NOTES</legend>
<span class="display_text"><?php echo $_SESSION['current']->notes; ?>
</fieldset>

<div class="centerbutton">
<form name="reg_add" action="reg_add.php" method="post">
  <input type="hidden" name="SubmitNow" value="Yes" />
  <input type="hidden" name="part" value="4" />
  <!--<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=3');return document.MM_returnValue" value="Previous" />-->
  <input name="Clear" type="button" class="next_button" onclick="clearVerify()" value="Clear" />
  <input name="Submit" type="submit" class="next_button" value="Confirm" />
</form>
</div><br />
<?php } ?>

  <!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
