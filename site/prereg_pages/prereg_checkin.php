<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

$colname_rs_update = "-1";
if (isset($_GET['id'])) {
  $colname_rs_update = $_GET['id'];
}
mysql_select_db($database_kumo_conn, $kumo_conn);
$query_rs_update = sprintf("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_id = %s", mysql_real_escape_string($colname_rs_update));
$rs_update = mysql_query($query_rs_update, $kumo_conn) or die(mysql_error());
$row_rs_update = mysql_fetch_assoc($rs_update);
$totalRows_rs_update = mysql_num_rows($rs_update);

$Id = $row_rs_update['kumo_reg_data_id'];

$Birthdate = $row_rs_update['kumo_reg_data_bdate'];

$Birthdate_array = explode("-", $Birthdate);
$BirthYear = $Birthdate_array[0];
$BirthMonth = $Birthdate_array[1];
$BirthDay = $Birthdate_array[2];

$BDate = $BirthYear . "-" . $BirthMonth . "-" . $BirthDay;
//
//$year_diff = date("Y") - $_SESSION["BirthYear"];
//$month_diff = date("m") - $_SESSION["BirthMonth"];
//$day_diff = date("d") - $_SESSION["BirthDay"];
//if (($month_diff < 0) && (($month_diff < 0) && ($day_diff < 0)) || (($month_diff <= 0) && ($day_diff < 0)))
//$year_diff--;

$year_diff = floor( (strtotime(date('Y-m-d')) - strtotime($BDate)) / 31556926);
if ((date("m") == $BirthMonth) && (date("d") == $BirthDay) && ($BirthYear == "2012")) {
$year_diff++;
}

if ($year_diff <= 5) {
$Weekend = 0;
$Saturday = 0;
$Sunday = 0;
$Monday = 0;
} else if (($year_diff > 5) && ($year_diff <= 12)){
$Weekend = 25;
$Saturday = 20;
$Sunday = 20;
$Monday = 15;	
} else if ($year_diff > 12){
$Weekend = 45;
$Saturday = 30;
$Sunday = 30;
$Monday = 25;
}  

$BDate = $_POST["BirthYear"] . "-" . $_POST["BirthMonth"] . "-" . $_POST["BirthDay"];

switch ($_POST["PassType"]){
	case "Weekend":
		$PaidAmount = "45.00";
		break;
	case "Saturday":
		$PaidAmount = "30.00";
		break;	
	case "Sunday":
		$PaidAmount = "30.00";
		break;	
	case "Monday":
		$PaidAmount = "25.00";
		break;
}

if (isset($_POST["Update"])) {
regcheckin($_POST["Id"]);
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
</script>
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
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
}
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div> 
<div id="menu">
<ul>
<li><a href="/index.php">HOME</a></li>
</ul>
<?php if ($_SESSION['access']==0) { ?> 
<ul>
<li class="header_li">Ops</li>
<li><a href="/opssearch/attendee_list.php">SEARCH</a></li>
</ul>
<?php } ?>
<?php if ($_SESSION['access']!=0) { ?> 
<ul>
<li class="header_li">PRE-REGISTRATION</li>
<li><a href="/prereg_pages/prereg_checkin_list.php">CHECK IN</a></li>
</ul>
<ul>
<li class="header_li">REGISTRATION</li>
<li><a href="/reg_pages/reg_add.php">NEW</a></li>
<!--<li><a href="/reg_pages/reg_tablet_complete_list.php">TABLET</a></li>-->
<?php if ($_SESSION['access']>=2) { ?> 
<li><a href="/reg_pages/reg_update_list.php">UPDATE</a></li>
<?php } ?>
<?php if ($_SESSION['access']>=3) { ?> 
<li><a href="/reg_pages/reg_badge_reprint.php">REPRINT BADGE</a></li>
<?php } ?>
<!--<li><a href="/reg_pages/reg_quick_add.php">QUICK REG</a></li>
<li><a href="/reg_pages/reg_quick_complete_list.php">QUICK REG COMPLETE</a></li>-->
</ul>
<?php if ($_SESSION['access']>=3) { ?>
<ul>
<li class="header_li">USER ADMIN</li>
<li><a href="/staff/staff_add.php">ADD REGISTRATION USER</a></li>
<li><a href="/staff/staff_update_list.php">UPDATE REGISTRATION USER</a></li>
<li><a href="/staff/staff_contact_list.php">STAFF PHONE LIST</a></li>
</ul>
<?php } ?>
<ul>
<?php if ($_SESSION['access']>=3) { ?>
<li class="header_li">KUMORICON ADMIN</li>
<li><a href="/admin/admin_attendee_list.php">SEARCH ATTENDEE</a></li>
<?php } ?>
<?php if ($_SESSION['access']>=4) { ?>
<li><a href="/admin/csvimport.php">IMPORT CSV</a></li>
<li><a href="/admin/admin_report.php">REPORTS</a></li>
<?php } ?>
</ul>
<?php } ?>
<ul>
<li class="header_li"><a href="/logout.php">Logout</a></li>
</ul>
</div> 
<div id="content"><!-- InstanceBeginEditable name="Content" -->
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_fname']; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_lname']; ?></span>
<br />
<label>Badge Name: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_bname']; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php $badgenumber = $row_rs_update['kumo_reg_data_bnumber']; ?><?php echo $badgenumber; ?></span>
<br />
<label>Address : </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_address']; ?></span>
<br />
<label>City : </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_city']; ?></span>
<label>State : </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_state']; ?></span>
<label>Zip : </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_zip']; ?></span>
<label>Country : </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_country']; ?></span>
<br />
<label>E-Mail : </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_email']; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_phone']; ?></span>
<label>Birth Date: </label>
<span class="display_text"><?php echo $BirthMonth; ?>/<?php echo $BirthDay; ?>/<?php echo $BirthYear; ?></span>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_ecfullname']; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_ecphone']; ?></span>
<br />
</fieldset>
<?php if (($year_diff >= 13) && ($year_diff < 18)) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<label>Full Name: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_parent']; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_parentphone']; ?></span>
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($row_rs_update['kumo_reg_data_parentform'] == "Yes") { echo "value=\"Yes\" checked"; } else { echo "value=\"\""; } ?> id="Parent Contact Form Verification" class="checkbox" /><span class="display_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>
<?php $PassType = $row_rs_update['kumo_reg_data_passtype']; ?>
    <input type="radio" name="PassType" value="Weekend" id="PassType_0" disabled <?php if ($PassType == "Weekend") echo "checked=\"checked\""; ?> />
    All Weekend - $<?php echo $Weekend ?></label>
    <hr />
  <label>
    <input name="PassType" type="radio" id="PassType_1" disabled value="Saturday" <?php if ($PassType == "Saturday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $Saturday ?></label>
  <br />
  <label>
    <input type="radio" name="PassType" value="Sunday" id="PassType_2" disabled <?php if ($PassType == "Sunday") echo "checked=\"checked\""; ?> />
    Sunday Only - $<?php echo $Sunday ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_3" disabled value="Monday" <?php if ($PassType == "Monday") echo "checked=\"checked\""; ?> />
    Monday Only - $<?php echo $Monday ?></label>
  <input name="Amount" type="hidden" id="Amount" value="<?php echo $row_rs_update['kumo_reg_data_paidamount'] ?>" />
  <br />
</p>
</fieldset>
<fieldset id="paymentinfo">
<legend>PAYMENT TYPE</legend>
<p>
  <label>
    <input type="radio" name="PayType" value="Cash" disabled id="PayType_0" <?php if ($row_rs_update['kumo_reg_data_paytype'] == "Cash") echo "checked=\"checked\""; ?> />
    Cash</label>
      <br />
  <label>
    <input type="radio" name="PayType" value="Credit/Debit" disabled id="PayType_1" <?php if ($row_rs_update['kumo_reg_data_paytype'] == "Credit/Debit") echo "checked=\"checked\""; ?> />
    Credit/Debit</label>
  <br />
    <label>
    <input type="radio" name="PayType" value="Check" disabled id="PayType_1" <?php if ($row_rs_update['kumo_reg_data_paytype'] == "Check") echo "checked=\"checked\""; ?> />
    Check</label>
  <br />
  <label>
    <input type="radio" name="PayType" value="Money Order" disabled id="PayType_2" <?php if ($row_rs_update['kumo_reg_data_paytype'] == "Money Order") echo "checked=\"checked\""; ?>/>
    Money Order</label>
  <br />
</p>
</fieldset>
<fieldset id="notes">
<legend>Notes</legend>
<span class="display_text"><?php echo $row_rs_update['kumo_reg_data_notes']; ?></span>
</fieldset>
<fieldset id="checkin">
<legend>CHECK IN</legend>
<form action="/prereg_pages/prereg_checkin.php" method="post">
<?php if ($row_rs_update['kumo_reg_data_checkedin'] == "Yes") { echo "<span class='display_text'>CHECKED IN</span>"; } else { echo "<input name='checkin' type='checkbox' id='Information Verification' class='checkbox' /><span class='display_text'>VERIFIED INFO</span><br /><div class='centerbutton'><input name=Id type=hidden value='" . $Id . "' /><input name='Update' type='submit' value='update' class='submit_button' /></div>";} ?>

</form>
</fieldset>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_update);
?>
