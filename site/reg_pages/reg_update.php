<?php
require('../includes/functions.php');
require('../includes/authcheck.php');
require('../includes/passtypes.php');

require_right('registration_update');

$Id = "-1";
if (isset($_GET['id'])) {
  $Id = $_GET['id'];
}

try {
$stmt = $conn->prepare("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_id= :id");
$stmt->execute(array('id' => $Id));
$results = $stmt->fetch(PDO::FETCH_ASSOC);

$Birthdate = $results['birthdate'];

if ((isset($_POST["BirthYear"])) && ($_POST["BirthYear"] !="YYYY")) {
$BDate = $_POST["BirthYear"] . "-" . $_POST["BirthMonth"] . "-" . $_POST["BirthDay"];
}

if ($results['birthdate'] != "") {
$Birthdate_array = explode("-", $Birthdate);
$BirthYear = $Birthdate_array[0];
$BirthMonth = $Birthdate_array[1];
$BirthDay = $Birthdate_array[2];

$BDate = $BirthYear . "-" . $BirthMonth . "-" . $BirthDay;
}

$year_diff = calculateAge($BDate);
// Get pass costs based on age
$Weekend = calculatePassCost($_SESSION["year_diff"], "Weekend");
$Friday = calculatePassCost($_SESSION["year_diff"], "Friday");
$Saturday = calculatePassCost($_SESSION["year_diff"], "Saturday");
$Sunday = calculatePassCost($_SESSION["year_diff"], "Sunday");
$Monday = calculatePassCost($_SESSION["year_diff"], "Monday");

$PaidAmount = calculatePassCost($year_diff, $_POST['PassType']);


} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

if (isset($_POST["Update"])) {
regupdate($_POST["Id"], $_POST["FirstName"], $_POST["LastName"], $_POST["BadgeNumber"], $_POST["Zip"], $_POST["EMail"], $_POST["PhoneNumber"], $BDate, $_POST["ECFullName"], $_POST["ECPhoneNumber"], $_POST["Same"], $_POST["PCFullName"], $_POST["PCPhoneNumber"], $_POST["PCFormVer"], $PaidAmount, $_POST["PassType"], $_POST["OrderId"], $_POST["Notes"]);

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
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
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
function verifyEmail(){
var status = false;     
if (document.reg_add1.EMail.value != document.reg_add1.EMailV.value) {
alert("Email addresses do not match.  Please retype them to make sure they are the same.");
}}
function sameInfo(){  
if (document.reg_update.Same.checked) {
document.reg_update.Same.value = "Y";
document.reg_update.PCFullName.value = document.reg_update.ECFullName.value;
document.reg_update.PCPhoneNumber.value = document.reg_update.ECPhoneNumber.value;
} else {
document.reg_update.Same.value = "";
document.reg_update.PCFullName.value = "";
document.reg_update.PCPhoneNumber.value = "";
}}
function verifyForm(){
if (document.reg_add2.PCFormVer.checked) {
document.reg_add2.PCFormVer.value = "Y";
} else {
document.reg_add2.PCFormVer.value = "";
}}

function setAmount() {
if (document.reg_add3.PassType_0.checked) {
	document.reg_add3.Amount.value = "<?php echo $Weekend ?>";
	} 
else if (document.reg_add3.PassType_1.checked) {
	document.reg_add3.Amount.value = "<?php echo $Saturday ?>";
	} 
else if (document.reg_add3.PassType_2.checked) {
	document.reg_add3.Amount.value = "<?php echo $Sunday ?>";
	} 
else if (document.reg_add3.PassType_3.checked) {
	document.reg_add3.Amount.value = "<?php echo $Monday ?>";
	}
else if (document.reg_add3.PassType_4.checked) {
	document.reg_add3.Amount.value = document.reg_add3.MPAmount.value;
	}
}
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<form name="reg_update" action="reg_update.php" method="post">
<input name="Id" type="hidden" value="<?php echo $results['kumo_reg_data_id'] ?>" />
<input name="OrderId" type="hidden" value="<?php echo $results['order_id'] ?>" />
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name:
<input name="FirstName" type="text" class="input_20_200" id="First Name" value="<?php echo $results['first_name'] ?>" /></label>
<label>Last Name:
<input name="LastName" type="text" class="input_20_200" id="Last Name" value="<?php echo $results['last_name'] ?>" /></label>
<br />
<label>Badge Number:
<input name="BadgeNumber" type="text" class="input_20_200" id="Badge Number" value="<?php echo $results['badge_number'] ?>" /></label>
<br />
<label>Zip :
<input name="Zip" type="text" class="input_20_150" id="Zip" value="<?php echo $results['zip'] ?>"  /></label>
<br />
<label>E-Mail :
<input name="EMail" type="text" class="input_20_200" id="E-Mail" value="<?php echo $results['email'] ?>"  /></label>
<label>E-Mail Verification :
<input name="EMailV" type="text" class="input_20_200" onBlur="verifyEmail();" value="<?php echo $results['email'] ?>"  /></label>
<br />
<label>Phone Number:
<input name="PhoneNumber" type="text" class="input_20_200" id="PhoneNumber" value="<?php echo $results['phone'] ?>"  /></label>
<label>Birth Date:
	<input type="number" class="input_20_40" maxlength="2" name="BirthMonth" id="Birth Month" value="<?php echo $BirthMonth?>" min="1" max="12" placeholder="MM">
	<span class="bold_text">/</span>
	<input type="number" class="input_20_40" maxlength="2" name="BirthDay" id="Birth Day" value="<?php echo $BirthDay?>" min="1" max="31" placeholder="DD">
	<span class="bold_text">/</span>
	<input type="number" class="input_20_60" maxlength="4" name="BirthYear" id="Birth Year" value="<?php echo $BirthYear?>" min="1900" max="2015" placeholder="YYYY">
	</label>(Month / Day / Year)
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name:
<input name="ECFullName" type="text" class="input_20_200" id="Emergency Contact Full Name" value="<?php echo $results['ec_fullname'] ?>"  /></label>
<br />
<label>Phone Number:
<input name="ECPhoneNumber" type="text" class="input_20_200" id="ECPhoneNumber" value="<?php echo $results['ec_phone'] ?>"  /></label>
<br />
</fieldset>
<?php if ($year_diff < 18) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<input name="Same" type="checkbox" class="checkbox" onclick="sameInfo();" <?php if ($results['ec_same'] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
<br /><br />
<label>Full Name:
<input name="PCFullName" type="text" class="input_20_200" id="Parent Contact Full Name" value="<?php echo $results['parent_fullname'] ?>"  /></label>
<br />
<label>Phone Number:
<input name="PCPhoneNumber" type="text" class="input_20_200" id="PCPhoneNumber" value="<?php echo $results['parent_phone'] ?>" /></label>
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($results['parent_form'] == "Yes") { echo "value=\"Yes\" checked"; } else { echo "value=\"\""; } ?> id="Parent Contact Form Verification" class="checkbox" onclick="verifyForm();" /><span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
<label>
<?php $PassType = $results['pass_type']; ?>
    <input type="radio" name="PassType" value="Weekend" id="PassType_0" onchange="setAmount();" <?php if ($PassType == "Weekend") echo "checked=\"checked\""; ?> />
    All Weekend - $<?php echo $Weekend ?></label>
    <hr />
  <label>
    <input name="PassType" type="radio" id="PassType_1" onchange="setAmount();" value="Saturday" <?php if ($PassType == "Saturday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $Saturday ?></label>
  <br />
  <label>
    <input type="radio" name="PassType" value="Sunday" id="PassType_2" onchange="setAmount();" <?php if ($PassType == "Sunday") echo "checked=\"checked\""; ?> />
    Sunday Only - $<?php echo $Sunday ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_3" onclick="setAmount()" value="Monday" <?php if ($PassType == "Monday") echo "checked=\"checked\""; ?> />
    Monday Only - $<?php echo $Monday ?></label>
  <br />
      <span class="radio_button_left_margin">
    <input name="PassType" type="radio" id="PassType_4" onblur="setAmount()" value="Manual Price" <?php if ($PassType == "Manual Price") echo "checked=\"checked\""; ?> />
    Manual Price - $<?php echo $results['paid_amount'] ?>
</span>
  <input name="Amount" type="hidden" id="Amount" value="<?php echo $results['paid_amount'] ?>" />
  <br />
</p>
</fieldset>
<fieldset id="notes">
<label>Notes :
<textarea name="Notes" rows="5"><?php echo $results['notes']; ?></textarea></label>
</fieldset>
<div class="centerbutton">
<input name="Update" type="submit" value="update" class="submit_button" />
</div>
</form>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_update);
?>
