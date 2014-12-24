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

$Birthdate = $results['kumo_reg_data_bdate'];

if ((isset($_POST["BirthYear"])) && ($_POST["BirthYear"] !="YYYY")) {
$BDate = $_POST["BirthYear"] . "-" . $_POST["BirthMonth"] . "-" . $_POST["BirthDay"];
}

if ($results['kumo_reg_data_bdate'] != "") {
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
regupdate($_POST["Id"], $_POST["FirstName"], $_POST["LastName"], $_POST["BadgeNumber"], $_POST["Address"], $_POST["City"], $_POST["State"], $_POST["Zip"], $_POST["Country"], $_POST["EMail"], $_POST["PhoneNumber"], $BDate, $_POST["ECFullName"], $_POST["ECPhoneNumber"], $_POST["Same"], $_POST["PCFullName"], $_POST["PCPhoneNumber"], $_POST["PCFormVer"], $PaidAmount, $_POST["PassType"], $_POST["PayType"], $_POST["Notes"]);

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
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name:
<input name="FirstName" type="text" class="input_20_200" id="First Name" value="<?php echo $results['kumo_reg_data_fname'] ?>" /></label>
<label>Last Name:
<input name="LastName" type="text" class="input_20_200" id="Last Name" value="<?php echo $results['kumo_reg_data_lname'] ?>" /></label>
<br />
<label>Badge Number:
<input name="BadgeNumber" type="text" class="input_20_200" id="Badge Number" value="<?php echo $results['kumo_reg_data_bnumber'] ?>" /></label>
<br />
<?php if($results['kumo_reg_data_address']!="") { ?>
<label>Address :
<input name="Address" type="text" class="input_20_550" id="Address" value="<?php echo $results['kumo_reg_data_address'] ?>" /></label>
<br />
<label>City :
<input name="City" type="text" class="input_20_200" id="City" value="<?php echo $results['kumo_reg_data_city'] ?>" /></label>
<label>State :
<select name="State" class="select_25_150" id="State">
<?php $State = $results['kumo_reg_data_state']; ?>
<option value="" <?php if ($State == "") echo "selected=\"selected\""; ?> >Select a State</option> 
<option value="AL" <?php if ($State == "AL") echo "selected=\"selected\""; ?> >Alabama</option> 
<option value="AK" <?php if ($State == "AK") echo "selected=\"selected\""; ?> >Alaska</option> 
<option value="AZ" <?php if ($State == "AZ") echo "selected=\"selected\""; ?> >Arizona</option> 
<option value="AR" <?php if ($State == "AR") echo "selected=\"selected\""; ?> >Arkansas</option> 
<option value="CA" <?php if ($State == "CA") echo "selected=\"selected\""; ?> >California</option> 
<option value="CO" <?php if ($State == "CO") echo "selected=\"selected\""; ?> >Colorado</option> 
<option value="CT" <?php if ($State == "CT") echo "selected=\"selected\""; ?> >Connecticut</option> 
<option value="DE" <?php if ($State == "DE") echo "selected=\"selected\""; ?> >Delaware</option> 
<option value="DC" <?php if ($State == "DC") echo "selected=\"selected\""; ?> >District Of Columbia</option>
<option value="FL" <?php if ($State == "FL") echo "selected=\"selected\""; ?> >Florida</option> 
<option value="GA" <?php if ($State == "GA") echo "selected=\"selected\""; ?> >Georgia</option> 
<option value="HI" <?php if ($State == "HI") echo "selected=\"selected\""; ?> >Hawaii</option> 
<option value="ID" <?php if ($State == "ID") echo "selected=\"selected\""; ?> >Idaho</option> 
<option value="IL" <?php if ($State == "IL") echo "selected=\"selected\""; ?> >Illinois</option> 
<option value="IN" <?php if ($State == "IN") echo "selected=\"selected\""; ?> >Indiana</option> 
<option value="IA" <?php if ($State == "IA") echo "selected=\"selected\""; ?> >Iowa</option> 
<option value="KS" <?php if ($State == "KS") echo "selected=\"selected\""; ?> >Kansas</option> 
<option value="KY" <?php if ($State == "KY") echo "selected=\"selected\""; ?> >Kentucky</option> 
<option value="LA" <?php if ($State == "LA") echo "selected=\"selected\""; ?> >Louisiana</option> 
<option value="ME" <?php if ($State == "ME") echo "selected=\"selected\""; ?> >Maine</option> 
<option value="MD" <?php if ($State == "MD") echo "selected=\"selected\""; ?> >Maryland</option> 
<option value="MA" <?php if ($State == "MA") echo "selected=\"selected\""; ?> >Massachusetts</option> 
<option value="MI" <?php if ($State == "MI") echo "selected=\"selected\""; ?> >Michigan</option> 
<option value="MN" <?php if ($State == "MN") echo "selected=\"selected\""; ?> >Minnesota</option> 
<option value="MS" <?php if ($State == "MS") echo "selected=\"selected\""; ?> >Mississippi</option> 
<option value="MO" <?php if ($State == "MO") echo "selected=\"selected\""; ?> >Missouri</option> 
<option value="MT" <?php if ($State == "MT") echo "selected=\"selected\""; ?> >Montana</option> 
<option value="NE" <?php if ($State == "NE") echo "selected=\"selected\""; ?> >Nebraska</option> 
<option value="NV" <?php if ($State == "NV") echo "selected=\"selected\""; ?> >Nevada</option> 
<option value="NH" <?php if ($State == "NH") echo "selected=\"selected\""; ?> >New Hampshire</option> 
<option value="NJ" <?php if ($State == "NJ") echo "selected=\"selected\""; ?> >New Jersey</option> 
<option value="NM" <?php if ($State == "NM") echo "selected=\"selected\""; ?> >New Mexico</option> 
<option value="NY" <?php if ($State == "NY") echo "selected=\"selected\""; ?> >New York</option> 
<option value="NC" <?php if ($State == "NC") echo "selected=\"selected\""; ?> >North Carolina</option> 
<option value="ND" <?php if ($State == "ND") echo "selected=\"selected\""; ?> >North Dakota</option> 
<option value="OH" <?php if ($State == "OH") echo "selected=\"selected\""; ?> >Ohio</option> 
<option value="OK" <?php if ($State == "OK") echo "selected=\"selected\""; ?> >Oklahoma</option> 
<option value="OR" <?php if ($State == "OR") echo "selected=\"selected\""; ?> >Oregon</option> 
<option value="PA" <?php if ($State == "PA") echo "selected=\"selected\""; ?> >Pennsylvania</option> 
<option value="RI" <?php if ($State == "RI") echo "selected=\"selected\""; ?> >Rhode Island</option> 
<option value="SC" <?php if ($State == "SC") echo "selected=\"selected\""; ?> >South Carolina</option> 
<option value="SD" <?php if ($State == "SD") echo "selected=\"selected\""; ?> >South Dakota</option> 
<option value="TN" <?php if ($State == "TN") echo "selected=\"selected\""; ?> >Tennessee</option> 
<option value="TX" <?php if ($State == "TX") echo "selected=\"selected\""; ?> >Texas</option> 
<option value="UT" <?php if ($State == "UT") echo "selected=\"selected\""; ?> >Utah</option> 
<option value="VT" <?php if ($State == "VT") echo "selected=\"selected\""; ?> >Vermont</option> 
<option value="VA" <?php if ($State == "VA") echo "selected=\"selected\""; ?> >Virginia</option> 
<option value="WA" <?php if ($State == "WA") echo "selected=\"selected\""; ?> >Washington</option> 
<option value="WV" <?php if ($State == "WV") echo "selected=\"selected\""; ?> >West Virginia</option> 
<option value="WI" <?php if ($State == "WI") echo "selected=\"selected\""; ?> >Wisconsin</option> 
<option value="WY" <?php if ($State == "WY") echo "selected=\"selected\""; ?> >Wyoming</option>
</select></label>
<?php } ?>
<label>Zip :
<input name="Zip" type="text" class="input_20_150" id="Zip" value="<?php echo $results['kumo_reg_data_zip'] ?>"  /></label>
<?php if($results['kumo_reg_data_address']!="") { ?>
	<label>Country :
	<input name="Country" type="text" class="input_20_150" id="Country" value="<?php echo $results['kumo_reg_data_country'] ?>"  />
<?php } ?></label>
<br />
<label>E-Mail :
<input name="EMail" type="text" class="input_20_200" id="E-Mail" value="<?php echo $results['kumo_reg_data_email'] ?>"  /></label>
<label>E-Mail Verification :
<input name="EMailV" type="text" class="input_20_200" onBlur="verifyEmail();" value="<?php echo $results['kumo_reg_data_email'] ?>"  /></label>
<br />
<label>Phone Number:
<input name="PhoneNumber" type="text" class="input_20_200" id="PhoneNumber" value="<?php echo $results['kumo_reg_data_phone'] ?>"  /></label>
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
<input name="ECFullName" type="text" class="input_20_200" id="Emergency Contact Full Name" value="<?php echo $results['kumo_reg_data_ecfullname'] ?>"  /></label>
<br />
<label>Phone Number:
<input name="ECPhoneNumber" type="text" class="input_20_200" id="ECPhoneNumber" value="<?php echo $results['kumo_reg_data_ecphone'] ?>"  /></label>
<br />
</fieldset>
<?php if ($year_diff < 18) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<input name="Same" type="checkbox" class="checkbox" onclick="sameInfo();" <?php if ($results['kumo_reg_data_same'] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
<br /><br />
<label>Full Name:
<input name="PCFullName" type="text" class="input_20_200" id="Parent Contact Full Name" value="<?php echo $results['kumo_reg_data_parent'] ?>"  /></label>
<br />
<label>Phone Number:
<input name="PCPhoneNumber" type="text" class="input_20_200" id="PCPhoneNumber" value="<?php echo $results['kumo_reg_data_parentphone'] ?>" /></label>
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($results['kumo_reg_data_parentform'] == "Yes") { echo "value=\"Yes\" checked"; } else { echo "value=\"\""; } ?> id="Parent Contact Form Verification" class="checkbox" onclick="verifyForm();" /><span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>
<?php $PassType = $results['kumo_reg_data_passtype']; ?>
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
    Manual Price - $<?php echo $results['kumo_reg_data_paidamount'] ?>
</span>
  <input name="Amount" type="hidden" id="Amount" value="<?php echo $results['kumo_reg_data_paidamount'] ?>" />
  <br />
</p>
</fieldset>
<?php if ($year_diff > 5) { ?>
<fieldset id="paymentinfo">
<legend>PAYMENT TYPE</legend>
<p>
  <label>
    <input type="radio" name="PayType" value="Cash" id="PayType_0" <?php if ($results['kumo_reg_data_paytype'] == "Cash") echo "checked=\"checked\""; ?> />
    Cash</label>
      <br />
  <label>
    <input type="radio" name="PayType" value="Check" id="PayType_1" <?php if ($results['kumo_reg_data_paytype'] == "Check") echo "checked=\"checked\""; ?> />
    Check</label>
  <br />
  <label>
    <input type="radio" name="PayType" value="Money Order" id="PayType_2" <?php if ($results['kumo_reg_data_paytype'] == "Money Order") echo "checked=\"checked\""; ?>/>
    Money Order</label>
  <br />
<label>
<input type="radio" name="PayType" value="Credit/Debit" id="PayType_3" onclick="creditauth()" <?php if ($results['kumo_reg_data_paytype'] == "Credit/Debit") echo "checked=\"checked\""; ?>/>
    Credit Card</label>
<br />
</p>
</fieldset>
<?php } else { 
echo "<input name='PayType' type='hidden' value='Free' />";
} ?>
<fieldset id="notes">
<label>Notes :
<textarea name="Notes" rows="5"><?php echo $results['kumo_reg_data_notes']; ?></textarea></label>
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
