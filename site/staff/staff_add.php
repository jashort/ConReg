<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

if (isset($_POST["create"])) {
staffadd($_POST["fname"],$_POST["lname"],$_POST["username"],$_POST["initials"],$_POST["cellnumber"],$_POST["accesslevel"],$_POST["enabled"]);
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
<script src="/assets/javascript/jquery-1.8.0.js" type="text/javascript"></script>
<script src="/assets/javascript/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<script>
jQuery(function($){
   $("#cellnumber").mask("(999) 999-9999");
});
</script>
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
<form action="/staff/staff_add.php" method="post">
<fieldset id="list_table_search">
<label>First Name : </label><input name="fname" id="First Name" type="text" class="input_20_150" /><br />
<label>Last Name : </label><input name="lname" id="Last Name" type="text" class="input_20_150" /><br />
<label>Initials : </label><input name="initials" id="Initials" type="text" class="input_20_150" /><br />
<label>Username : </label><input name="username" id="Username" type="text" class="input_20_150" /><br />
<label>Cell Phone Number : </label><input id="cellnumber" name="cellnumber" type="text" class="input_20_150" /><br />
<label>Access Level : </label>
<select name="accesslevel" class="select_25_125" id="accesslevel">
<?php 
for ($i=1; $i<=$_SESSION['access']; $i++)
{
if ($i == $row_rs_staff_update['kumo_reg_staff_accesslevel']){
	$selected = 'selected';
}
else {
	$selected = '';
}
if ($i == 1){
	$Access = 'User';
}
if ($i == 2){
	$Access = 'Super User';
}
if ($i == 3){
	$Access = 'Manager';
}
if ($i == 4){
	$Access = 'Super Admin';
}
  echo "<option value='" . $i . "' " . $selected . " >" . $Access . "</option>";
}
?>
</select>
<br />
<label>Enabled : </label><select name="enabled" class="select_25_75">
<option value="1">Yes</option>
<option value="0">No</option>
</select><br />
<input name="create" type="submit" class="submit_button" value="create" onclick="MM_validateForm('First Name','','R','Last Name','','R','Initials','','R','Username','','R');return document.MM_returnValue" />
</fieldset>
</form>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
