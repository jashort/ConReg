<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

if ($_GET["part"] == "2" && stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_quick_add.php' )) {
$_SESSION["FirstName"] = $_POST["FirstName"];
$_SESSION["LastName"] = $_POST["LastName"];
$_SESSION["BadgeNumber"] = $_SESSION['initials'] . str_pad(badgeNumberSelect(), 3, '0', STR_PAD_LEFT);

}

if ((isset($_POST["SubmitNow"])) && ($_POST["SubmitNow"] == "Yes")) {
regquickadd($_SESSION["FirstName"], $_SESSION["LastName"], $_SESSION["BadgeNumber"]);
badgeNumberUpdate();
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
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
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
if (!stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_quick_add.php')) { ?>
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
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if ($_GET["part"]==""){ ?>
<form name="reg_add1" action="reg_quick_add.php?part=2" method="post">
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name:
<input name="FirstName" type="text" class="input_20_200" id="First Name" value="<?php echo $_SESSION["FirstName"]; ?>" /></label>
<label>Last Name:
<input name="LastName" type="text" class="input_20_200" id="Last Name" value="<?php echo $_SESSION["LastName"]; ?>" /></label>
<br />
</fieldset>
<div class="centerbutton">
<input name="Next" type="submit" class="next_button" onclick="MM_validateForm('First Name','','R','Last Name','','R');return document.MM_returnValue" value="Next" /><input name="Clear" type="button" class="next_button" onclick="clearverify()" value="Clear" />
</div>
</form>
<?php } ?>
<?php if ($_GET["part"]=="2") { ?>
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<span class="display_text"><?php echo $_SESSION["FirstName"]; ?></span>
<label>Last Name: </label>
<span class="display_text"><?php echo $_SESSION["LastName"]; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php echo $_SESSION["BadgeNumber"]; ?></span>
</fieldset>
<div class="centerbutton">
<form name="reg_add" action="reg_quick_add.php" method="post"><input type="hidden" name="SubmitNow" value="Yes" /><input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_quick_add.php');return document.MM_returnValue" value="Previous" /><input name="Clear" type="button" class="next_button" onclick="clearverify()" value="Clear" /><input name="Submit" type="submit" class="next_button" value="Confirm" /></form>
</div>
<?php } ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
