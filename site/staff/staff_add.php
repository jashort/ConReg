<?php
require_once('../includes/functions.php');
require_once('../includes/roles.php');

require_once('../includes/authcheck.php');
requireRight('manage_staff');

if (isset($_POST["create"])) {
  $staff = new Staff();
  $staff->fromArray($_POST);
  $staff->setPassword('password');  // New user password is just "password"
  staffAdd($staff);
  logMessage($_SESSION['username'], "Added user ". $_POST['username']);
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
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<form action="/staff/staff_add.php" method="post">
<fieldset id="list_table_search">
<label>First Name : <input name="first_name" id="First Name" type="text" class="input_20_150" /></label><br />
<label>Last Name : <input name="last_name" id="Last Name" type="text" class="input_20_150" /></label><br />
<label>Initials : <input name="initials" id="Initials" type="text" class="input_20_150" /></label><br />
<label>Username : <input name="username" id="Username" type="text" class="input_20_150" /></label><br />
<label>Cell Phone Number : <input id="cellnumber" name="phone_number" type="text" class="input_20_150" /></label><br />
<label>Access Level :
<select name="access_level" class="select_25_125" id="accesslevel">
<?php
  foreach (array_keys($ROLES) as $i) {
    echo "<option value='" . $i . "'>" . $ROLES[$i]['name'] . "</option>\n";
  }
?>
</select></label>
<br />
<label>Enabled : <select name="enabled" class="select_25_75">
<option value="1">Yes</option>
<option value="0">No</option>
</select></label><br />
<input name="create" type="submit" class="submit_button" value="create" onclick="MM_validateForm('First Name','','R','Last Name','','R','Initials','','R','Username','','R');return document.MM_returnValue" />
</fieldset>
</form>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
