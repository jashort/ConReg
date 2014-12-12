<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

$colname_rs_attendee = "-1";
if (isset($_GET['id'])) {
  $colname_rs_attendee = $_GET['id'];
}
mysql_select_db($database_kumo_conn, $kumo_conn);
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
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
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
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_fname']; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_lname']; ?></span>
<br />
<label>Badge Name: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_bname']; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_bnumber']; ?></span>
<br />
<label>Address : </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_address']; ?></span>
<br />
<label>City : </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_city']; ?></span>
<label>State : </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_state']; ?></span>
<label>Zip : </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_zip']; ?></span>
<label>Country : </label>
<span class="display_text"><?php echo $row_rs_attendee['kumo_reg_data_country']; ?></span>
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
<div class="centerbutton">
<input name="Update" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_update.php?id=<?php echo $row_rs_attendee['kumo_reg_data_id']; ?>');return document.MM_returnValue" value="update" />
</div>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_attendee);
?>
