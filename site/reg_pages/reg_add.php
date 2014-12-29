<?php
require('../includes/functions.php');
require('../includes/authcheck.php');
require('../includes/passtypes.php');

require_right('registration_add');

if (array_key_exists('part', $_GET) && $_GET["part"] == "2" && stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_add.php' ) && !stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_add.php?part' )) {

$_SESSION["FirstName"] = $_POST["FirstName"];
$_SESSION["LastName"] = $_POST["LastName"];
if(array_key_exists('part', $_GET) && $_SESSION["BadgeNumber"]=="") {$_SESSION["BadgeNumber"] = $_SESSION['initials'] . str_pad(badgeNumberSelect(), 3, '0', STR_PAD_LEFT);}
$_SESSION["PhoneNumber"] = $_POST["PhoneNumber"];
$_SESSION["EMail"] = $_POST["EMail"];
$_SESSION["Zip"] = $_POST["Zip"];
$_SESSION["BirthMonth"] = $_POST["BirthMonth"];
$_SESSION["BirthDay"] = $_POST["BirthDay"];
$_SESSION["BirthYear"] = $_POST["BirthYear"];
}
elseif (array_key_exists('part', $_GET) && $_GET["part"] == "3" && stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_add.php?part=2' )) {
$_SESSION["ECFullName"] = $_POST["ECFullName"];
$_SESSION["ECPhoneNumber"] = $_POST["ECPhoneNumber"];
$_SESSION["Same"] = $_POST["Same"];
$_SESSION["PCFullName"] = $_POST["PCFullName"];
$_SESSION["PCPhoneNumber"] = $_POST["PCPhoneNumber"];	
$_SESSION["PCFormVer"] = $_POST["PCFormVer"];	
}
elseif (array_key_exists('part', $_GET) && $_GET["part"] == "4" && stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_add.php?part=3' )) {
$_SESSION["PassType"] = $_POST["PassType"];
$_SESSION["Amount"] = $_POST["Amount"];
$_SESSION["Notes"] = $_POST["Notes"];	

}

if ((isset($_POST["BirthYear"]))&&($_POST["BirthYear"]!="YYYY")) {
  $BDate = $_SESSION["BirthYear"] . "-" . $_SESSION["BirthMonth"] . "-" . $_SESSION["BirthDay"];
  $_SESSION["BDate"] = $BDate;
  $_SESSION["year_diff"] = calculateAge($BDate);
} else {
  $year_message = "Please go back and enter a birthdate!";
}

if (isset($_SESSION["year_diff"])&&($_POST["BirthYear"]!="YYYY")) { $year_message = NULL; }

// Get pass costs based on age
$Weekend = calculatePassCost($_SESSION["year_diff"], "Weekend");
$Friday = calculatePassCost($_SESSION["year_diff"], "Friday");
$Saturday = calculatePassCost($_SESSION["year_diff"], "Saturday");
$Sunday = calculatePassCost($_SESSION["year_diff"], "Sunday");
$Monday = calculatePassCost($_SESSION["year_diff"], "Monday");

if ($_SESSION['PassType'] != 'Manual') {
  $_SESSION['Amount'] = calculatePassCost($_SESSION["year_diff"], $_SESSION["PassType"]);
}

if (($_SESSION["year_diff"] > 12) && ($_SESSION["year_diff"] < 18)){
  $ParentForm = "Yes";
} else {
  $ParentForm = "No";
}

if ((isset($_POST["SubmitNow"])) && ($_POST["SubmitNow"] == "Yes")) {
  // Create an order record if it doesn't exist
  if (!isset($_SESSION["OrderId"])) {
    $_SESSION["OrderId"] = orderadd();
  }

  regadd($_SESSION["FirstName"], $_SESSION["LastName"], $_SESSION["BadgeNumber"], $_SESSION["PhoneNumber"], $_SESSION["EMail"],  $_SESSION["Zip"], $_SESSION["BDate"], $_SESSION["ECFullName"], $_SESSION["ECPhoneNumber"], $_SESSION["Same"], $_SESSION["PCFullName"], $_SESSION["PCPhoneNumber"], $ParentForm, "Yes", $_SESSION["Amount"], $_SESSION["PassType"], "Reg", "Yes", $_SESSION["OrderId"], $_SESSION["Notes"]);

  if ($_SESSION["QuickReg"] != "True") {
    badgeNumberUpdate();
  }
  unset ($_SESSION["QuickReg"]);
  redirect("/reg_pages/reg_order.php");
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
function verifyEmail(){
var status = false;     
if (document.reg_add1.EMail.value != document.reg_add1.EMailV.value) {
alert("Email addresses do not match.  Please retype them to make sure they are the same.");
}}
function sameInfo(){  
if (document.reg_add2.Same.checked) {
document.reg_add2.Same.value = "Y";
document.reg_add2.PCFullName.value = document.reg_add2.ECFullName.value;
document.reg_add2.PCPhoneNumber.value = document.reg_add2.ECPhoneNumber.value;
} else {
document.reg_add2.Same.value = "";
document.reg_add2.PCFullName.value = "";
document.reg_add2.PCPhoneNumber.value = "";
}}
function verifyForm(){
if (document.reg_add2.PCFormVer.checked) {
document.reg_add2.PCFormVer.value = "Y";
} else {
document.reg_add2.PCFormVer.value = "";
}}
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
function setAmount() {
if (document.reg_add3.PassType_0.checked) {
	document.reg_add3.Amount.value = "<?php echo $Weekend ?>";
	}
else if (document.reg_add3.PassType_1.checked) {
	document.reg_add3.Amount.value = "<?php echo $Friday; ?>";
	}  
else if (document.reg_add3.PassType_2.checked) {
	document.reg_add3.Amount.value = "<?php echo $Saturday ?>";
	} 
else if (document.reg_add3.PassType_3.checked) {
	document.reg_add3.Amount.value = "<?php echo $Sunday ?>";
	} 
else if (document.reg_add3.PassType_4.checked) {
	document.reg_add3.Amount.value = "<?php echo $Monday ?>";
	}
}
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function clearverify() {
var answer=confirm("Are you sure you want to clear?");
if (answer==true)
  {
  MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue;
  }
else
  {
  } 
}
<?php 
if (isset($_SESSION["FirstName"])) {
if (!((stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_tablet_complete_list.php')) || (stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_add.php')) || (stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_lastyear_list.php')))) { ?>
(function() {
var answer=confirm("Attendee information is set in this form and hasn't been submitted. If you are continuing please press Cancel, otherwise press Ok and the form will be cleared.");
if (answer==true)
  {
  MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue;
  }
else
  {
  } 
})();
<?php } }?>
function manualprice() {

do { 
var amount=prompt("Please enter the amount","ex 40.00");
var currencycheck=new RegExp("^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$");
var currencyformat = currencycheck.test(amount);
} while ((amount=="") || (currencyformat==false));

do {
var reason=prompt("Please enter the reason for the manual pricing","");
} while (reason=="");

document.reg_add3.MPAmount.value = amount;
document.reg_add3.Amount.value = amount;
document.reg_add3.Notes.value = reason;

}
function creditauth() {

do { 
var number=prompt("Please enter the authorization number","ex 123456");
} while ((number=="") || (number=="ex 123456"));

if (document.reg_add3.Notes.value == "") {
document.reg_add3.Notes.value = "The Credit Card Authorization Number is: " + number;
} else {
document.reg_add3.Notes.value = document.reg_add3.Notes.value + "---" + "The Credit Card Authorization Number is: " + number;	
}

document.reg_add3.AuthDisplay.value = number;
}
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if ($_GET["part"]==""){ ?>
<form name="reg_add1" action="reg_add.php?part=2" method="post">
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<p>
  <label>First Name:
  <input name="FirstName" type="text" class="input_20_200" id="First Name" value="<?php echo $_SESSION["FirstName"]; ?>" /></label>
  <label>Last Name:
  <input name="LastName" type="text" class="input_20_200" id="Last Name" value="<?php echo $_SESSION["LastName"]; ?>" /></label>
  <br />
  <label>Phone Number:
  <input name="PhoneNumber" type="text" class="input_20_200" id="PhoneNumber" value="<?php echo $_SESSION["PhoneNumber"]; ?>" /></label>
  <br />
  <label>EMail:
    <input name="EMail" type="text" class="input_20_200" id="EMail" value="<?php echo $_SESSION["EMail"]; ?>" /></label>
  <br />
    <label>Zip:
  <input name="Zip" type="text" class="input_20_200" id="Zip" value="<?php echo $_SESSION["Zip"]; ?>" /></label>
  <br />
  <span class="display_text_large">
  <label>Badge Number:
  <?php if($_SESSION["BadgeNumber"]=="") {echo $_SESSION['initials'] . str_pad(badgeNumberSelect(), 3, '0', STR_PAD_LEFT);} else { echo $_SESSION["BadgeNumber"];} ?></label>
  </span><br /><br />
  <label>Birth Date:
    <input type="number" class="input_20_40" maxlength="2" name="BirthMonth" id="Birth Month" value="<?php echo $_SESSION["BirthMonth"]?>" min="1" max="12" placeholder="MM">
    <span class="bold_text">/</span>
    <input type="number" class="input_20_40" maxlength="2" name="BirthDay" id="Birth Day" value="<?php echo $_SESSION["BirthDay"]?>" min="1" max="31" placeholder="DD">
    <span class="bold_text">/</span>
    <input type="number" class="input_20_60" maxlength="4" name="BirthYear" id="Birth Year" value="<?php echo $_SESSION["BirthYear"]?>" min="1900" max="2015" placeholder="YYYY">
  </label>(Month / Day / Year)
</p>
</fieldset>
<div class="centerbutton">
<input name="Next" type="submit" class="next_button" onclick="MM_validateForm('First Name','','R','Last Name','','R','Phone Number','','R','Zip','','R');return document.MM_returnValue" value="Next" /><input name="Clear" type="button" class="next_button" onclick="clearverify()" value="Clear" />
</div>
</form>
<?php } ?>
<?php if ($_GET["part"]=="2") { ?>
<br />
<fieldset id="currentage">
<span class="display_text">Current Age: <?php if (!isset($year_message)) { echo $_SESSION["year_diff"]; } else { echo $year_message;} ?></span>
</fieldset>
<form name="reg_add2" action="reg_add.php?part=3" method="post">
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name:
<input name="ECFullName" type="text" class="input_20_200" id="Emergency Contact Full Name" value="<?php echo $_SESSION["ECFullName"]; ?>"  /></label>
<br />
<label>Phone Number:
<input name="ECPhoneNumber" type="text" class="input_20_200" id="ECPhoneNumber" value="<?php echo $_SESSION["ECPhoneNumber"]; ?>"  /></label>
<br />
</fieldset>
<?php if (($_SESSION["year_diff"] >= 13) && ($_SESSION["year_diff"] < 18)) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<input name="Same" type="checkbox" class="checkbox" onClick="sameInfo();" <?php if ($_SESSION["Same"] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
<br /><br />
<label>Full Name:
<input name="PCFullName" type="text" class="input_20_200" id="Parent Contact Full Name" value="<?php echo $_SESSION["PCFullName"]; ?>"  /></label>
<br />
<label>Phone Number:
<input name="PCPhoneNumber" type="text" class="input_20_200" id="PCPhoneNumber" value="<?php echo $_SESSION["PCPhoneNumber"]; ?>" /></label>
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($_SESSION["PCFormVer"] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> id="Parent Contact Form Verification" class="checkbox" onclick="verifyForm();" /><span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>
<div class="centerbutton">
<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php');return document.MM_returnValue" value="Previous" /><?php if($_POST["BirthYear"]!="YYYY") { ?><input name="Submit" type="submit" class="next_button" <?php if ($_SESSION["BirthYear"]>1994) { ?>onclick="MM_validateForm('Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R','Parent Contact Full Name','','R','Parent Contact Phone Number','','R','Parent Contact Form Verification','','R');return document.MM_returnValue"<?php } else { ?>onclick="MM_validateForm('Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R');return document.MM_returnValue"<?php } ?> value="Next" /><?php } ?><input name="Clear" type="button" class="next_button" onclick="clearverify()" value="Clear" />
</div>
</form>
<?php } ?>
<?php if ($_GET["part"]=="3") { ?>
<form name="reg_add3" action="reg_add.php?part=4" method="post">
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>
    <input type="radio" name="PassType" value="Weekend" id="PassType_0" onchange="setAmount();" <?php if ($_SESSION["PassType"] == "Weekend") echo "checked=\"checked\""; ?> />
    All Weekend - $<?php echo $Weekend ?></label>
    <hr />
  <label>
    <input name="PassType" type="radio" id="PassType_1" onchange="setAmount();" value="Friday" <?php if ($_SESSION["PassType"] == "Friday") echo "checked=\"checked\""; ?> />
    Friday Only - $<?php echo $Friday ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_2" onchange="setAmount();" value="Saturday" <?php if ($_SESSION["PassType"] == "Saturday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $Saturday ?></label>
  <br />
  <label>
    <input type="radio" name="PassType" value="Sunday" id="PassType_3" onchange="setAmount();" <?php if ($_SESSION["PassType"] == "Sunday") echo "checked=\"checked\""; ?> />
    Sunday Only - $<?php echo $Sunday ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_4" onclick="setAmount()" value="Monday" <?php if ($_SESSION["PassType"] == "Monday") echo "checked=\"checked\""; ?> />
    Monday Only - $<?php echo $Monday ?></label><br />
	<?php if (has_right('registration_manual_price')) { ?>
      <span class="radio_button_left_margin">
    <input name="PassType" type="radio" id="PassType_5" onclick="manualprice()" value="Manual Price" <?php if ($_SESSION["PassType"] == "Manual Price") echo "checked=\"checked\""; ?> />
    Manual Price - $
    <input name="MPAmount" type="text" class="input_20_150" id="Manual Price Amount" value="<?php echo $_SESSION["Amount"] ?>" disabled="disabled"/>
      </span><?php } ?>
      <?php 
	  
	  switch ($_SESSION["PassType"]) {
    	case "Weekend":
        $_SESSION["Amount"] = $Weekend;
        break;
    	case "Friday":
        $_SESSION["Amount"] = $Friday;
        break;
    	case "Saturday":
        $_SESSION["Amount"] = $Saturday;
        break;
    	case "Sunday":
        $_SESSION["Amount"] = $Sunday;
        break;
    	case "Monday":
        $_SESSION["Amount"] = $Monday;
        break;
}
	  
	  ?>
  <input name="Amount" type="hidden" id="Amount" value="<?php echo $_SESSION["Amount"] ?>" />
  <br />
</p>
</fieldset>
<fieldset id="notes">
<label>Notes : </label>
<textarea name="Notes" rows="5"><?php echo $_SESSION["Notes"]; ?></textarea>
</fieldset>
<div class="centerbutton">
<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=2');return document.MM_returnValue" value="Previous" /><input name="Submit" type="submit" class="next_button" value="Next" onclick="return radiobutton();" /><input name="Clear" type="button" class="next_button" onclick="clearverify()" value="Clear" />
</div>
</form>
<?php } ?>
<?php if ($_GET["part"]=="4") { ?>
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $_SESSION["FirstName"]; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $_SESSION["LastName"]; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION["PhoneNumber"]; ?></span>
<br />
<label>Email: </label>
<span class="display_text"><?php echo $_SESSION["EMail"]; ?></span>
<br />
<label>Zip: </label>
<span class="display_text"><?php echo $_SESSION["Zip"]; ?></span>
<br />
<label>Badge Number: </label>
<span class="display_text"><?php echo $_SESSION["BadgeNumber"]; ?></span>
<br />
<label>Birth Date: </label>
<span class="display_text"><?php echo $_SESSION["BirthMonth"]; ?>/<?php echo $_SESSION["BirthDay"] ?>/<?php echo $_SESSION["BirthYear"] ?></span>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $_SESSION["ECFullName"]; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION["ECPhoneNumber"]; ?></span>
<br />
</fieldset>
<?php if (($year_diff < 18) && ($year_diff > 12)) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $_SESSION["PCFullName"]; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION["PCPhoneNumber"]; ?></span>
<br />
<label>Parental Permission Form Submitted: </label>
<span class="display_text"><?php echo $_SESSION["PCFormVer"]; ?> </span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<span class="display_text"><?php echo $_SESSION["PassType"]; ?> - $<?php echo $_SESSION["Amount"]; ?></span>
</fieldset>
  <!--<fieldset id="paymentinfo">
<legend>PAYMENT TYPE</legend>
<span class="display_text"><?php if ($_SESSION["PayType"]=="") { echo "Please enter a payment type!"; } else { echo $_SESSION["PayType"]; } ?>
</fieldset>-->
<fieldset id="paymentinfo">
<legend>NOTES</legend>
<span class="display_text"><?php echo $_SESSION["Notes"]; ?>
</fieldset>

<div class="centerbutton">
<form name="reg_add" action="reg_add.php" method="post"><input type="hidden" name="SubmitNow" value="Yes" /><input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=3');return document.MM_returnValue" value="Previous" /><input name="Clear" type="button" class="next_button" onclick="clearverify()" value="Clear" /><input name="Submit" type="submit" class="next_button" value="Confirm" /></form>
</div><br />
<?php } ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
