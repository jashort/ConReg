<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

if ($_GET["part"] == "2" && $_SERVER['HTTP_REFERER'] == "http://regdev.definitivellc.net/prereg_pages/prereg_add.php") {
$_SESSION["FirstName"] = $_POST["FirstName"];
$_SESSION["LastName"] = $_POST["LastName"];
$_SESSION["BadgeName"] = $_POST["BadgeName"];
$_SESSION["BadgeNumber"] = $_POST["BadgeNumber"];
$_SESSION["Address"] = $_POST["Address"];
$_SESSION["City"] = $_POST["City"];
$_SESSION["State"] = $_POST["State"];
$_SESSION["Zip"] = $_POST["Zip"];
$_SESSION["Country"] = $_POST["Country"];
$_SESSION["EMail"] = $_POST["EMail"];
$_SESSION["PhoneNumber"] = $_POST["PhoneNumber"];
$_SESSION["BirthMonth"] = $_POST["BirthMonth"];
$_SESSION["BirthDay"] = $_POST["BirthDay"];
$_SESSION["BirthYear"] = $_POST["BirthYear"];
}
elseif ($_GET["part"] == "3" && $_SERVER['HTTP_REFERER'] == "http://regdev.definitivellc.net/prereg_pages/prereg_add.php?part=2") {
$_SESSION["ECFullName"] = $_POST["ECFullName"];
$_SESSION["ECPhoneNumber"] = $_POST["ECPhoneNumber"];
$_SESSION["Same"] = $_POST["Same"];
$_SESSION["PCFullName"] = $_POST["PCFullName"];
$_SESSION["PCPhoneNumber"] = $_POST["PCPhoneNumber"];	
$_SESSION["PCFormVer"] = $_POST["PCFormVer"];	
}
elseif ($_GET["part"] == "4" && $_SERVER['HTTP_REFERER'] == "http://regdev.definitivellc.net/prereg_pages/prereg_add.php?part=3") {
$_SESSION["PassType"] = $_POST["PassType"];
$_SESSION["Amount"] = $_POST["Amount"];
$_SESSION["PayType"] = $_POST["PayType"];
$_SESSION["Notes"] = $_POST["Notes"];	
}

$BDate = $_SESSION["BirthYear"] . "-" . $_SESSION["BirthMonth"] . "-" . $_SESSION["BirthDay"];

$year_diff  = date("Y") - $_SESSION["BirthYear"];
$month_diff = date("m") - $_SESSION["BirthMonth"];
$day_diff   = date("d") - $_SESSION["BirthDay"];
if ($day_diff < 0 || $month_diff < 0)
$year_diff--;

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

if ((isset($_POST["SubmitNow"])) && ($_POST["SubmitNow"] == "Yes")) {
regadd($_SESSION["FirstName"], $_SESSION["LastName"], $_SESSION["BadgeNumber"], $_SESSION["BadgeName"], $_SESSION["Address"], $_SESSION["City"], $_SESSION["State"], $_SESSION["Zip"], $_SESSION["Country"], $_SESSION["EMail"], $_SESSION["PhoneNumber"], $BDate, $_SESSION["ECFullName"], $_SESSION["ECPhoneNumber"], $_SESSION["PCFullName"], $_SESSION["PCPhoneNumber"], "Yes", "Yes", $_SESSION["Amount"], $_SESSION["PassType"], "Prereg", $_SESSION["PayType"], "No", $_SESSION["Notes"]);

redirect("/index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Kumoricon Registration - Pre-Registration Add New</title>
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
if (document.prereg_add1.EMail.value != document.prereg_add1.EMailV.value) {
alert("Email addresses do not match.  Please retype them to make sure they are the same.");
}}
function sameInfo(){  
if (document.prereg_add2.Same.checked) {
document.prereg_add2.Same.value = "Y";
document.prereg_add2.PCFullName.value = document.prereg_add2.ECFullName.value;
document.prereg_add2.PCPhoneNumber.value = document.prereg_add2.ECPhoneNumber.value;
} else {
document.prereg_add2.Same.value = "";
document.prereg_add2.PCFullName.value = "";
document.prereg_add2.PCPhoneNumber.value = "";
}}
function verifyForm(){
if (document.prereg_add2.PCFormVer.checked) {
document.prereg_add2.PCFormVer.value = "Y";
} else {
document.prereg_add2.PCFormVer.value = "";
}}
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
function setAmount() {
if (document.prereg_add3.PassType_0.checked) {
	document.prereg_add3.Amount.value = "<?php echo $Weekend; ?>";
	} 
else if (document.prereg_add3.PassType_1.checked) {
	document.prereg_add3.Amount.value = "<?php echo $Saturday; ?>";
	} 
else if (document.prereg_add3.PassType_2.checked) {
	document.prereg_add3.Amount.value = "<?php echo $Sunday; ?>";
	} 
else if (document.prereg_add3.PassType_3.checked) {
	document.prereg_add3.Amount.value = "<?php echo $Monday; ?>";
	}
else if (document.prereg_add3.PassType_4.checked) {
	document.prereg_add3.Amount.value = document.prereg_add3.manual_price.value;
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
<?php if ($_GET["part"]==""){ ?>
<form name="prereg_add1" action="prereg_add.php?part=2" method="post">
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<input name="FirstName" type="text" class="input_20_200" id="First Name" value="<?php echo $_SESSION["FirstName"]; ?>" />
<label>Last Name: </label>
<input name="LastName" type="text" class="input_20_200" id="Last Name" value="<?php echo $_SESSION["LastName"]; ?>" />
<br />
<label>Badge Name: </label>
<input name="BadgeName" type="text" class="input_20_200" id="Badge Name" value="<?php echo $_SESSION["BadgeName"]; ?>" />
<label>Badge Number: </label>
<input name="BadgeNumber" type="text" class="input_20_200" id="Badge Number" value="<?php echo $_SESSION["BadgeNumber"]; ?>" />
<br />
<label>Address : </label>
<input name="Address" type="text" class="input_20_550" id="Address" value="<?php echo $_SESSION["Address"]; ?>" />
<br />
<label>City : </label>
<input name="City" type="text" class="input_20_200" id="City" value="<?php echo $_SESSION["City"]; ?>" />
<label>State : </label>
<select name="State" class="select_25_150" id="State"> 
<option value="" <?php if ($_SESSION["State"] == "") echo "selected=\"selected\""; ?> >Select a State</option> 
<option value="AL" <?php if ($_SESSION["State"] == "AL") echo "selected=\"selected\""; ?> >Alabama</option> 
<option value="AK" <?php if ($_SESSION["State"] == "AK") echo "selected=\"selected\""; ?> >Alaska</option> 
<option value="AZ" <?php if ($_SESSION["State"] == "AZ") echo "selected=\"selected\""; ?> >Arizona</option> 
<option value="AR" <?php if ($_SESSION["State"] == "AR") echo "selected=\"selected\""; ?> >Arkansas</option> 
<option value="CA" <?php if ($_SESSION["State"] == "CA") echo "selected=\"selected\""; ?> >California</option> 
<option value="CO" <?php if ($_SESSION["State"] == "CO") echo "selected=\"selected\""; ?> >Colorado</option> 
<option value="CT" <?php if ($_SESSION["State"] == "CT") echo "selected=\"selected\""; ?> >Connecticut</option> 
<option value="DE" <?php if ($_SESSION["State"] == "DE") echo "selected=\"selected\""; ?> >Delaware</option> 
<option value="DC" <?php if ($_SESSION["State"] == "DC") echo "selected=\"selected\""; ?> >District Of Columbia</option> 
<option value="FL" <?php if ($_SESSION["State"] == "FL") echo "selected=\"selected\""; ?> >Florida</option> 
<option value="GA" <?php if ($_SESSION["State"] == "GA") echo "selected=\"selected\""; ?> >Georgia</option> 
<option value="HI" <?php if ($_SESSION["State"] == "HI") echo "selected=\"selected\""; ?> >Hawaii</option> 
<option value="ID" <?php if ($_SESSION["State"] == "ID") echo "selected=\"selected\""; ?> >Idaho</option> 
<option value="IL" <?php if ($_SESSION["State"] == "IL") echo "selected=\"selected\""; ?> >Illinois</option> 
<option value="IN" <?php if ($_SESSION["State"] == "IN") echo "selected=\"selected\""; ?> >Indiana</option> 
<option value="IA" <?php if ($_SESSION["State"] == "IA") echo "selected=\"selected\""; ?> >Iowa</option> 
<option value="KS" <?php if ($_SESSION["State"] == "KS") echo "selected=\"selected\""; ?> >Kansas</option> 
<option value="KY" <?php if ($_SESSION["State"] == "KY") echo "selected=\"selected\""; ?> >Kentucky</option> 
<option value="LA" <?php if ($_SESSION["State"] == "LA") echo "selected=\"selected\""; ?> >Louisiana</option> 
<option value="ME" <?php if ($_SESSION["State"] == "ME") echo "selected=\"selected\""; ?> >Maine</option> 
<option value="MD" <?php if ($_SESSION["State"] == "MD") echo "selected=\"selected\""; ?> >Maryland</option> 
<option value="MA" <?php if ($_SESSION["State"] == "MA") echo "selected=\"selected\""; ?> >Massachusetts</option> 
<option value="MI" <?php if ($_SESSION["State"] == "MI") echo "selected=\"selected\""; ?> >Michigan</option> 
<option value="MN" <?php if ($_SESSION["State"] == "MN") echo "selected=\"selected\""; ?> >Minnesota</option> 
<option value="MS" <?php if ($_SESSION["State"] == "MS") echo "selected=\"selected\""; ?> >Mississippi</option> 
<option value="MO" <?php if ($_SESSION["State"] == "MO") echo "selected=\"selected\""; ?> >Missouri</option> 
<option value="MT" <?php if ($_SESSION["State"] == "MT") echo "selected=\"selected\""; ?> >Montana</option> 
<option value="NE" <?php if ($_SESSION["State"] == "NE") echo "selected=\"selected\""; ?> >Nebraska</option> 
<option value="NV" <?php if ($_SESSION["State"] == "NV") echo "selected=\"selected\""; ?> >Nevada</option> 
<option value="NH" <?php if ($_SESSION["State"] == "NH") echo "selected=\"selected\""; ?> >New Hampshire</option> 
<option value="NJ" <?php if ($_SESSION["State"] == "NJ") echo "selected=\"selected\""; ?> >New Jersey</option> 
<option value="NM" <?php if ($_SESSION["State"] == "NM") echo "selected=\"selected\""; ?> >New Mexico</option> 
<option value="NY" <?php if ($_SESSION["State"] == "NY") echo "selected=\"selected\""; ?> >New York</option> 
<option value="NC" <?php if ($_SESSION["State"] == "NC") echo "selected=\"selected\""; ?> >North Carolina</option> 
<option value="ND" <?php if ($_SESSION["State"] == "ND") echo "selected=\"selected\""; ?> >North Dakota</option> 
<option value="OH" <?php if ($_SESSION["State"] == "OH") echo "selected=\"selected\""; ?> >Ohio</option> 
<option value="OK" <?php if ($_SESSION["State"] == "OK") echo "selected=\"selected\""; ?> >Oklahoma</option> 
<option value="OR" <?php if ($_SESSION["State"] == "OR") echo "selected=\"selected\""; ?> >Oregon</option> 
<option value="PA" <?php if ($_SESSION["State"] == "PA") echo "selected=\"selected\""; ?> >Pennsylvania</option> 
<option value="RI" <?php if ($_SESSION["State"] == "RI") echo "selected=\"selected\""; ?> >Rhode Island</option> 
<option value="SC" <?php if ($_SESSION["State"] == "SC") echo "selected=\"selected\""; ?> >South Carolina</option> 
<option value="SD" <?php if ($_SESSION["State"] == "SD") echo "selected=\"selected\""; ?> >South Dakota</option> 
<option value="TN" <?php if ($_SESSION["State"] == "TN") echo "selected=\"selected\""; ?> >Tennessee</option> 
<option value="TX" <?php if ($_SESSION["State"] == "TX") echo "selected=\"selected\""; ?> >Texas</option> 
<option value="UT" <?php if ($_SESSION["State"] == "UT") echo "selected=\"selected\""; ?> >Utah</option> 
<option value="VT" <?php if ($_SESSION["State"] == "VT") echo "selected=\"selected\""; ?> >Vermont</option> 
<option value="VA" <?php if ($_SESSION["State"] == "VA") echo "selected=\"selected\""; ?> >Virginia</option> 
<option value="WA" <?php if ($_SESSION["State"] == "WA") echo "selected=\"selected\""; ?> >Washington</option> 
<option value="WV" <?php if ($_SESSION["State"] == "WV") echo "selected=\"selected\""; ?> >West Virginia</option> 
<option value="WI" <?php if ($_SESSION["State"] == "WI") echo "selected=\"selected\""; ?> >Wisconsin</option> 
<option value="WY" <?php if ($_SESSION["State"] == "WY") echo "selected=\"selected\""; ?> >Wyoming</option>
</select>
<label>Zip : </label>
<input name="Zip" type="text" class="input_20_150" id="Zip" value="<?php echo $_SESSION["Zip"]; ?>"  />
<label>Country : </label>
<input name="Country" type="text" class="input_20_150" id="Country" value="<?php echo $_SESSION["Country"]; ?>"  />
<br />
<label>E-Mail : </label>
<input name="EMail" type="text" class="input_20_200" id="E-Mail" value="<?php echo $_SESSION["EMail"]; ?>"  />
<label>E-Mail Verification : </label>
<input name="EMailV" type="text" class="input_20_200" onBlur="verifyEmail();" value="<?php echo $_SESSION["EMail"]; ?>"  />
<br />
<label>Phone Number: </label>
<input name="PhoneNumber" type="text" class="input_20_200" id="Phone Number" value="<?php echo $_SESSION["PhoneNumber"]; ?>"  />
<label>Birth Date: </label>
<select name="BirthMonth" class="select_25_50" id="Birth Month" >
<option value="" <?php if ($_SESSION["BirthMonth"] == "") echo "selected=\"selected\""; ?> >MM</option>
<option value="01" <?php if ($_SESSION["BirthMonth"] == "01") echo "selected=\"selected\""; ?> >01</option>
<option value="02" <?php if ($_SESSION["BirthMonth"] == "02") echo "selected=\"selected\""; ?> >02</option>
<option value="03" <?php if ($_SESSION["BirthMonth"] == "03") echo "selected=\"selected\""; ?> >03</option>
<option value="04" <?php if ($_SESSION["BirthMonth"] == "04") echo "selected=\"selected\""; ?> >04</option>
<option value="05" <?php if ($_SESSION["BirthMonth"] == "05") echo "selected=\"selected\""; ?> >05</option>
<option value="06" <?php if ($_SESSION["BirthMonth"] == "06") echo "selected=\"selected\""; ?> >06</option>
<option value="07" <?php if ($_SESSION["BirthMonth"] == "07") echo "selected=\"selected\""; ?> >07</option>
<option value="08" <?php if ($_SESSION["BirthMonth"] == "08") echo "selected=\"selected\""; ?> >08</option>
<option value="09" <?php if ($_SESSION["BirthMonth"] == "09") echo "selected=\"selected\""; ?> >09</option>
<option value="10" <?php if ($_SESSION["BirthMonth"] == "10") echo "selected=\"selected\""; ?> >10</option>
<option value="11" <?php if ($_SESSION["BirthMonth"] == "11") echo "selected=\"selected\""; ?> >11</option>
<option value="12" <?php if ($_SESSION["BirthMonth"] == "12") echo "selected=\"selected\""; ?> >12</option>
</select>
<span class="bold_text">/</span>
<select name="BirthDay" class="select_25_50" id="Birth Day"  >
	<option value="">DD</option>
<option value="01" <?php if ($_SESSION["BirthDay"] == "01") echo "selected=\"selected\""; ?> >01</option>
<option value="02" <?php if ($_SESSION["BirthDay"] == "02") echo "selected=\"selected\""; ?> >02</option>
<option value="03" <?php if ($_SESSION["BirthDay"] == "03") echo "selected=\"selected\""; ?> >03</option>
<option value="04" <?php if ($_SESSION["BirthDay"] == "04") echo "selected=\"selected\""; ?> >04</option>
<option value="05" <?php if ($_SESSION["BirthDay"] == "05") echo "selected=\"selected\""; ?> >05</option>
<option value="06" <?php if ($_SESSION["BirthDay"] == "06") echo "selected=\"selected\""; ?> >06</option>
<option value="07" <?php if ($_SESSION["BirthDay"] == "07") echo "selected=\"selected\""; ?> >07</option>
<option value="08" <?php if ($_SESSION["BirthDay"] == "08") echo "selected=\"selected\""; ?> >08</option>
<option value="09" <?php if ($_SESSION["BirthDay"] == "09") echo "selected=\"selected\""; ?> >09</option>
<option value="10" <?php if ($_SESSION["BirthDay"] == "10") echo "selected=\"selected\""; ?> >10</option>
<option value="11" <?php if ($_SESSION["BirthDay"] == "11") echo "selected=\"selected\""; ?> >11</option>
<option value="12" <?php if ($_SESSION["BirthDay"] == "12") echo "selected=\"selected\""; ?> >12</option>
<option value="13" <?php if ($_SESSION["BirthDay"] == "13") echo "selected=\"selected\""; ?> >13</option>
<option value="14" <?php if ($_SESSION["BirthDay"] == "14") echo "selected=\"selected\""; ?> >14</option>
<option value="15" <?php if ($_SESSION["BirthDay"] == "15") echo "selected=\"selected\""; ?> >15</option>
<option value="16" <?php if ($_SESSION["BirthDay"] == "16") echo "selected=\"selected\""; ?> >16</option>
<option value="17" <?php if ($_SESSION["BirthDay"] == "17") echo "selected=\"selected\""; ?> >17</option>
<option value="18" <?php if ($_SESSION["BirthDay"] == "18") echo "selected=\"selected\""; ?> >18</option>
<option value="19" <?php if ($_SESSION["BirthDay"] == "19") echo "selected=\"selected\""; ?> >19</option>
<option value="20" <?php if ($_SESSION["BirthDay"] == "20") echo "selected=\"selected\""; ?> >20</option>
<option value="21" <?php if ($_SESSION["BirthDay"] == "21") echo "selected=\"selected\""; ?> >21</option>
<option value="22" <?php if ($_SESSION["BirthDay"] == "22") echo "selected=\"selected\""; ?> >22</option>
<option value="23" <?php if ($_SESSION["BirthDay"] == "23") echo "selected=\"selected\""; ?> >23</option>
<option value="24" <?php if ($_SESSION["BirthDay"] == "24") echo "selected=\"selected\""; ?> >24</option>
<option value="25" <?php if ($_SESSION["BirthDay"] == "25") echo "selected=\"selected\""; ?> >25</option>
<option value="26" <?php if ($_SESSION["BirthDay"] == "26") echo "selected=\"selected\""; ?> >26</option>
<option value="27" <?php if ($_SESSION["BirthDay"] == "27") echo "selected=\"selected\""; ?> >27</option>
<option value="28" <?php if ($_SESSION["BirthDay"] == "28") echo "selected=\"selected\""; ?> >28</option>
<option value="29" <?php if ($_SESSION["BirthDay"] == "29") echo "selected=\"selected\""; ?> >29</option>
<option value="30" <?php if ($_SESSION["BirthDay"] == "30") echo "selected=\"selected\""; ?> >30</option>
<option value="31" <?php if ($_SESSION["BirthDay"] == "31") echo "selected=\"selected\""; ?> >31</option>
</select>
<span class="bold_text">/</span>
<select name="BirthYear" class="select_25_75"  id="Birth Year" >
	<option <?php if ($_SESSION["BirthYear"] == "") echo "selected=\"selected\""; ?> >YYYY</option>
	<option value="2012" <?php if ($_SESSION["BirthYear"] == "2012") echo "selected=\"selected\""; ?> >2012</option>
	<option value="2011" <?php if ($_SESSION["BirthYear"] == "2011") echo "selected=\"selected\""; ?> >2011</option>
	<option value="2010" <?php if ($_SESSION["BirthYear"] == "2010") echo "selected=\"selected\""; ?> >2010</option>
	<option value="2009" <?php if ($_SESSION["BirthYear"] == "2009") echo "selected=\"selected\""; ?> >2009</option>
	<option value="2008" <?php if ($_SESSION["BirthYear"] == "2008") echo "selected=\"selected\""; ?> >2008</option>
	<option value="2007" <?php if ($_SESSION["BirthYear"] == "2007") echo "selected=\"selected\""; ?> >2007</option>
	<option value="2006" <?php if ($_SESSION["BirthYear"] == "2006") echo "selected=\"selected\""; ?> >2006</option>
	<option value="2005" <?php if ($_SESSION["BirthYear"] == "2005") echo "selected=\"selected\""; ?> >2005</option>
	<option value="2004" <?php if ($_SESSION["BirthYear"] == "2004") echo "selected=\"selected\""; ?> >2004</option>
	<option value="2003" <?php if ($_SESSION["BirthYear"] == "2003") echo "selected=\"selected\""; ?> >2003</option>
	<option value="2002" <?php if ($_SESSION["BirthYear"] == "2002") echo "selected=\"selected\""; ?> >2002</option>
	<option value="2001" <?php if ($_SESSION["BirthYear"] == "2001") echo "selected=\"selected\""; ?> >2001</option>
	<option value="2000" <?php if ($_SESSION["BirthYear"] == "2000") echo "selected=\"selected\""; ?> >2000</option>
	<option value="1999" <?php if ($_SESSION["BirthYear"] == "1999") echo "selected=\"selected\""; ?> >1999</option>
	<option value="1998" <?php if ($_SESSION["BirthYear"] == "1998") echo "selected=\"selected\""; ?> >1998</option>
	<option value="1997" <?php if ($_SESSION["BirthYear"] == "1997") echo "selected=\"selected\""; ?> >1997</option>
	<option value="1996" <?php if ($_SESSION["BirthYear"] == "1996") echo "selected=\"selected\""; ?> >1996</option>
	<option value="1995" <?php if ($_SESSION["BirthYear"] == "1995") echo "selected=\"selected\""; ?> >1995</option>
	<option value="1994" <?php if ($_SESSION["BirthYear"] == "1994") echo "selected=\"selected\""; ?> >1994</option>
	<option value="1993" <?php if ($_SESSION["BirthYear"] == "1993") echo "selected=\"selected\""; ?> >1993</option>
	<option value="1992" <?php if ($_SESSION["BirthYear"] == "1992") echo "selected=\"selected\""; ?> >1992</option>
	<option value="1991" <?php if ($_SESSION["BirthYear"] == "1991") echo "selected=\"selected\""; ?> >1991</option>
	<option value="1990" <?php if ($_SESSION["BirthYear"] == "1990") echo "selected=\"selected\""; ?> >1990</option>
	<option value="1989" <?php if ($_SESSION["BirthYear"] == "1989") echo "selected=\"selected\""; ?> >1989</option>
	<option value="1988" <?php if ($_SESSION["BirthYear"] == "1988") echo "selected=\"selected\""; ?> >1988</option>
	<option value="1987" <?php if ($_SESSION["BirthYear"] == "1987") echo "selected=\"selected\""; ?> >1987</option>
	<option value="1986" <?php if ($_SESSION["BirthYear"] == "1986") echo "selected=\"selected\""; ?> >1986</option>
	<option value="1985" <?php if ($_SESSION["BirthYear"] == "1985") echo "selected=\"selected\""; ?> >1985</option>
	<option value="1984" <?php if ($_SESSION["BirthYear"] == "1984") echo "selected=\"selected\""; ?> >1984</option>
	<option value="1983" <?php if ($_SESSION["BirthYear"] == "1983") echo "selected=\"selected\""; ?> >1983</option>
	<option value="1982" <?php if ($_SESSION["BirthYear"] == "1982") echo "selected=\"selected\""; ?> >1982</option>
	<option value="1981" <?php if ($_SESSION["BirthYear"] == "1981") echo "selected=\"selected\""; ?> >1981</option>
	<option value="1980" <?php if ($_SESSION["BirthYear"] == "1980") echo "selected=\"selected\""; ?> >1980</option>
	<option value="1979" <?php if ($_SESSION["BirthYear"] == "1979") echo "selected=\"selected\""; ?> >1979</option>
	<option value="1978" <?php if ($_SESSION["BirthYear"] == "1978") echo "selected=\"selected\""; ?> >1978</option>
	<option value="1977" <?php if ($_SESSION["BirthYear"] == "1977") echo "selected=\"selected\""; ?> >1977</option>
	<option value="1976" <?php if ($_SESSION["BirthYear"] == "1976") echo "selected=\"selected\""; ?> >1976</option>
	<option value="1975" <?php if ($_SESSION["BirthYear"] == "1975") echo "selected=\"selected\""; ?> >1975</option>
	<option value="1974" <?php if ($_SESSION["BirthYear"] == "1974") echo "selected=\"selected\""; ?> >1974</option>
	<option value="1973" <?php if ($_SESSION["BirthYear"] == "1973") echo "selected=\"selected\""; ?> >1973</option>
	<option value="1972" <?php if ($_SESSION["BirthYear"] == "1972") echo "selected=\"selected\""; ?> >1972</option>
	<option value="1971" <?php if ($_SESSION["BirthYear"] == "1971") echo "selected=\"selected\""; ?> >1971</option>
	<option value="1970" <?php if ($_SESSION["BirthYear"] == "1970") echo "selected=\"selected\""; ?> >1970</option>
	<option value="1969" <?php if ($_SESSION["BirthYear"] == "1969") echo "selected=\"selected\""; ?> >1969</option>
	<option value="1968" <?php if ($_SESSION["BirthYear"] == "1968") echo "selected=\"selected\""; ?> >1968</option>
	<option value="1967" <?php if ($_SESSION["BirthYear"] == "1967") echo "selected=\"selected\""; ?> >1967</option>
	<option value="1966" <?php if ($_SESSION["BirthYear"] == "1966") echo "selected=\"selected\""; ?> >1966</option>
	<option value="1965" <?php if ($_SESSION["BirthYear"] == "1965") echo "selected=\"selected\""; ?> >1965</option>
	<option value="1964" <?php if ($_SESSION["BirthYear"] == "1964") echo "selected=\"selected\""; ?> >1964</option>
	<option value="1963" <?php if ($_SESSION["BirthYear"] == "1963") echo "selected=\"selected\""; ?> >1963</option>
	<option value="1962" <?php if ($_SESSION["BirthYear"] == "1962") echo "selected=\"selected\""; ?> >1962</option>
	<option value="1961" <?php if ($_SESSION["BirthYear"] == "1961") echo "selected=\"selected\""; ?> >1961</option>
	<option value="1960" <?php if ($_SESSION["BirthYear"] == "1960") echo "selected=\"selected\""; ?> >1960</option>
	<option value="1959" <?php if ($_SESSION["BirthYear"] == "1959") echo "selected=\"selected\""; ?> >1959</option>
	<option value="1958" <?php if ($_SESSION["BirthYear"] == "1958") echo "selected=\"selected\""; ?> >1958</option>
	<option value="1957" <?php if ($_SESSION["BirthYear"] == "1957") echo "selected=\"selected\""; ?> >1957</option>
	<option value="1956" <?php if ($_SESSION["BirthYear"] == "1956") echo "selected=\"selected\""; ?> >1956</option>
	<option value="1955" <?php if ($_SESSION["BirthYear"] == "1955") echo "selected=\"selected\""; ?> >1955</option>
	<option value="1954" <?php if ($_SESSION["BirthYear"] == "1954") echo "selected=\"selected\""; ?> >1954</option>
	<option value="1953" <?php if ($_SESSION["BirthYear"] == "1953") echo "selected=\"selected\""; ?> >1953</option>
	<option value="1952" <?php if ($_SESSION["BirthYear"] == "1952") echo "selected=\"selected\""; ?> >1952</option>
	<option value="1951" <?php if ($_SESSION["BirthYear"] == "1951") echo "selected=\"selected\""; ?> >1951</option>
	<option value="1950" <?php if ($_SESSION["BirthYear"] == "1950") echo "selected=\"selected\""; ?> >1950</option>
	<option value="1949" <?php if ($_SESSION["BirthYear"] == "1949") echo "selected=\"selected\""; ?> >1949</option>
	<option value="1948" <?php if ($_SESSION["BirthYear"] == "1948") echo "selected=\"selected\""; ?> >1948</option>
	<option value="1947" <?php if ($_SESSION["BirthYear"] == "1947") echo "selected=\"selected\""; ?> >1947</option>
	<option value="1946" <?php if ($_SESSION["BirthYear"] == "1946") echo "selected=\"selected\""; ?> >1946</option>
	<option value="1945" <?php if ($_SESSION["BirthYear"] == "1945") echo "selected=\"selected\""; ?> >1945</option>
	<option value="1944" <?php if ($_SESSION["BirthYear"] == "1944") echo "selected=\"selected\""; ?> >1944</option>
	<option value="1943" <?php if ($_SESSION["BirthYear"] == "1943") echo "selected=\"selected\""; ?> >1943</option>
	<option value="1942" <?php if ($_SESSION["BirthYear"] == "1942") echo "selected=\"selected\""; ?> >1942</option>
	<option value="1941" <?php if ($_SESSION["BirthYear"] == "1941") echo "selected=\"selected\""; ?> >1941</option>
	<option value="1940" <?php if ($_SESSION["BirthYear"] == "1940") echo "selected=\"selected\""; ?> >1940</option>
	<option value="1939" <?php if ($_SESSION["BirthYear"] == "1939") echo "selected=\"selected\""; ?> >1939</option>
	<option value="1938" <?php if ($_SESSION["BirthYear"] == "1938") echo "selected=\"selected\""; ?> >1938</option>
	<option value="1937" <?php if ($_SESSION["BirthYear"] == "1937") echo "selected=\"selected\""; ?> >1937</option>
	<option value="1936" <?php if ($_SESSION["BirthYear"] == "1936") echo "selected=\"selected\""; ?> >1936</option>
	<option value="1935" <?php if ($_SESSION["BirthYear"] == "1935") echo "selected=\"selected\""; ?> >1935</option>
	<option value="1934" <?php if ($_SESSION["BirthYear"] == "1934") echo "selected=\"selected\""; ?> >1934</option>
	<option value="1933" <?php if ($_SESSION["BirthYear"] == "1933") echo "selected=\"selected\""; ?> >1933</option>
	<option value="1932" <?php if ($_SESSION["BirthYear"] == "1932") echo "selected=\"selected\""; ?> >1932</option>
	<option value="1931" <?php if ($_SESSION["BirthYear"] == "1931") echo "selected=\"selected\""; ?> >1931</option>
	<option value="1930" <?php if ($_SESSION["BirthYear"] == "1930") echo "selected=\"selected\""; ?> >1930</option>
	<option value="1929" <?php if ($_SESSION["BirthYear"] == "1929") echo "selected=\"selected\""; ?> >1929</option>
	<option value="1928" <?php if ($_SESSION["BirthYear"] == "1928") echo "selected=\"selected\""; ?> >1928</option>
	<option value="1927" <?php if ($_SESSION["BirthYear"] == "1927") echo "selected=\"selected\""; ?> >1927</option>
	<option value="1926" <?php if ($_SESSION["BirthYear"] == "1926") echo "selected=\"selected\""; ?> >1926</option>
	<option value="1925" <?php if ($_SESSION["BirthYear"] == "1925") echo "selected=\"selected\""; ?> >1925</option>
	<option value="1924" <?php if ($_SESSION["BirthYear"] == "1924") echo "selected=\"selected\""; ?> >1924</option>
	<option value="1923" <?php if ($_SESSION["BirthYear"] == "1923") echo "selected=\"selected\""; ?> >1923</option>
	<option value="1922" <?php if ($_SESSION["BirthYear"] == "1922") echo "selected=\"selected\""; ?> >1922</option>
	<option value="1921" <?php if ($_SESSION["BirthYear"] == "1921") echo "selected=\"selected\""; ?> >1921</option>
	<option value="1920" <?php if ($_SESSION["BirthYear"] == "1920") echo "selected=\"selected\""; ?> >1920</option>
	<option value="1919" <?php if ($_SESSION["BirthYear"] == "1919") echo "selected=\"selected\""; ?> >1919</option>
	<option value="1918" <?php if ($_SESSION["BirthYear"] == "1918") echo "selected=\"selected\""; ?> >1918</option>
	<option value="1917" <?php if ($_SESSION["BirthYear"] == "1917") echo "selected=\"selected\""; ?> >1917</option>
	<option value="1916" <?php if ($_SESSION["BirthYear"] == "1916") echo "selected=\"selected\""; ?> >1916</option>
	<option value="1915" <?php if ($_SESSION["BirthYear"] == "1915") echo "selected=\"selected\""; ?> >1915</option>
	<option value="1914" <?php if ($_SESSION["BirthYear"] == "1914") echo "selected=\"selected\""; ?> >1914</option>
	<option value="1913" <?php if ($_SESSION["BirthYear"] == "1913") echo "selected=\"selected\""; ?> >1913</option>
	<option value="1912" <?php if ($_SESSION["BirthYear"] == "1912") echo "selected=\"selected\""; ?> >1912</option>
</select>
</fieldset>
<div class="centerbutton">
<input name="Next" type="submit" class="next_button" onclick="MM_validateForm('First Name','','R','Last Name','','R','Badge Name','','R','Badge Number','','R','Address','','R','City','','R','Zip','','R','Country','','R','E-Mail','','RisEmail','Phone Number','','R');return document.MM_returnValue" value="Next" /><input name="Clear" type="button" class="next_button" onclick="MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue" value="Clear" />
</div>
</form>
<?php } ?>
<?php if ($_GET["part"]=="2") { ?>
<form name="prereg_add2" action="prereg_add.php?part=3" method="post">
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<input name="ECFullName" type="text" class="input_20_200" id="Emergency Contact Full Name" value="<?php echo $_SESSION["ECFullName"]; ?>"  />
<br />
<label>Phone Number: </label>
<input name="ECPhoneNumber" type="text" class="input_20_200" id="Emergency Contact Phone Number" value="<?php echo $_SESSION["ECPhoneNumber"]; ?>"  />
<br />
</fieldset>
<?php if ($year_diff >= 18) { ?>
<fieldset id="parentinfo" class="disabled">
<legend class="disabled">Parent Contact Info</legend>
<input type="checkbox" disabled="disabled" class="checkbox" /><span class="bold_gray_text"> SAME AS EMERGENCY CONTACT INFO</span>
<br /><br />
<label class="disabled">Full Name: </label>
<input type="text" disabled="disabled" class="input_20_200" />
<br />
<label class="disabled">Phone Number: </label>
<input name="" type="text" disabled="disabled" class="input_20_200" />
<br /><br />
<input name="" type="checkbox" disabled="disabled" class="checkbox" /><span class="bold_gray_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>
<?php if ($year_diff < 18) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<input name="Same" type="checkbox" class="checkbox" onClick="sameInfo();" <?php if ($_SESSION["Same"] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
<br /><br />
<label>Full Name: </label>
<input name="PCFullName" type="text" class="input_20_200" id="Parent Contact Full Name" value="<?php echo $_SESSION["PCFullName"]; ?>"  />
<br />
<label>Phone Number: </label>
<input name="PCPhoneNumber" type="text" class="input_20_200" id="Parent Contact Phone Number" value="<?php echo $_SESSION["PCPhoneNumber"]; ?>" />
<br /><br />
<input name="PCFormVer" type="checkbox" <?php if ($_SESSION["PCFormVer"] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> id="Parent Contact Form Verification" class="checkbox" onclick="verifyForm();" /><span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
</fieldset>
<?php } ?>
<div class="centerbutton">
<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/prereg_pages/prereg_add.php');return document.MM_returnValue" value="Previous" /><input name="Submit" type="submit" class="next_button" <?php if ($_SESSION["BirthYear"]>1994) { ?>onclick="MM_validateForm('Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R','Parent Contact Full Name','','R','Parent Contact Phone Number','','R','Parent Contact Form Verification','','R');return document.MM_returnValue"<?php } else { ?>onclick="MM_validateForm('Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R');return document.MM_returnValue"<?php } ?> value="Next" /><input name="Clear" type="button" class="next_button" onclick="MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue" value="Clear" />
</div>
</form>
<?php } ?>
<?php if ($_GET["part"]=="3") { ?>
<form name="prereg_add3" action="prereg_add.php?part=4" method="post">
<fieldset id="paymentinfo">
<legend>PASS TYPE</legend>
<p>
  <label>
    <input type="radio" name="PassType" value="Weekend" id="PassType_0" onchange="setAmount();" <?php if ($_SESSION["PassType"] == "Weekend") echo "checked=\"checked\""; ?> />
    All Weekend - $<?php echo $Weekend; ?></label>
    <hr />
  <label>
    <input name="PassType" type="radio" id="PassType_1" onchange="setAmount();" value="Saturday" <?php if ($_SESSION["PassType"] == "Saturday") echo "checked=\"checked\""; ?> />
    Saturday Only - $<?php echo $Saturday; ?></label>
  <br />
  <label>
    <input type="radio" name="PassType" value="Sunday" id="PassType_2" onchange="setAmount();" <?php if ($_SESSION["PassType"] == "Sunday") echo "checked=\"checked\""; ?> />
    Sunday Only - $<?php echo $Sunday; ?></label>
  <br />
  <label>
    <input name="PassType" type="radio" id="PassType_3" onclick="setAmount()" value="Monday" <?php if ($_SESSION["PassType"] == "Monday") echo "checked=\"checked\""; ?> />
    Monday Only - $<?php echo $Monday; ?></label>
  <input name="Amount" type="hidden" id="Amount" value="<?php echo $_SESSION["Amount"]; ?>" />
  <br />
</p>
</fieldset>
<fieldset id="paymentinfo">
<legend>PAYMENT TYPE</legend>
<p>
  <label>
    <input type="radio" name="PayType" value="Cash" id="PayType_0" <?php if ($_SESSION["PayType"] == "Cash") echo "checked=\"checked\""; ?> />
    Cash</label>
      <br />
  <label>
    <input type="radio" name="PayType" value="Credit/Debit" id="PayType_0" <?php if ($_SESSION["PayType"] == "Credit/Debit") echo "checked=\"checked\""; ?> />
    Credit/Debit</label>
      <br />
  <label>
    <input type="radio" name="PayType" value="Check" id="PayType_1" <?php if ($_SESSION["PayType"] == "Check") echo "checked=\"checked\""; ?> />
    Check</label>
  <br />
  <label>
    <input type="radio" name="PayType" value="Money Order" id="PayType_2" <?php if ($_SESSION["PayType"] == "Money Order") echo "checked=\"checked\""; ?>/>
    Money Order</label>
  <br />
</p>
</fieldset>
<fieldset id="notes">
<label>Notes : </label>
<textarea name="Notes" rows="5"></textarea>
</fieldset>
<div class="centerbutton">
<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/prereg_pages/prereg_add.php?part=2');return document.MM_returnValue" value="Previous" /><input name="Submit" type="submit" class="next_button" onclick="MM_validateForm('First Name','','R','Last Name','','R','Badge Name','','R','Badge Number','','R','Address','','R','City','','R','Zip','','R','Country','','R','E-Mail','','RisEmail','Phone Number','','R','Emergency Contact Full Name','','R','Emergency Contact Phone Number','','R');return document.MM_returnValue" value="Next" /><input name="Clear" type="button" class="next_button" onclick="MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue" value="Clear" />
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
<label>Badge Name: </label>
<span class="display_text"><?php echo $_SESSION["BadgeName"]; ?></span>
<label>Badge Number: </label>
<span class="display_text"><?php echo $_SESSION["BadgeNumber"]; ?></span>
<br />
<label>Address : </label>
<span class="display_text"><?php echo $_SESSION["Address"]; ?></span>
<br />
<label>City : </label>
<span class="display_text"><?php echo $_SESSION["City"]; ?></span>
<label>State : </label>
<span class="display_text"><?php echo $_SESSION["State"]; ?></span>
<label>Zip : </label>
<span class="display_text"><?php echo $_SESSION["Zip"]; ?></span>
<label>Country : </label>
<span class="display_text"><?php echo $_SESSION["Country"]; ?></span>
<br />
<label>E-Mail : </label>
<span class="display_text"><?php echo $_SESSION["EMail"]; ?></span>
<br />
<label>Phone Number: </label>
<span class="display_text"><?php echo $_SESSION["PhoneNumber"]; ?></span>
<label>Birth Date: </label>
<span class="display_text"><?php echo $_SESSION["BirthMonth"]; ?>/<?php echo $_SESSION["BirthDay"]; ?>/<?php echo $_SESSION["BirthYear"]; ?></span>
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
<?php if ($year_diff < 18) { ?>
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
<fieldset id="paymentinfo">
<legend>PAYMENT TYPE</legend>
<span class="display_text"><?php echo $_SESSION["PayType"]; ?>
</fieldset>
<fieldset id="paymentinfo">
<legend>NOTES</legend>
<span class="display_text"><?php echo $_SESSION["Notes"]; ?>
</fieldset>
<div class="centerbutton">
<form name="prereg_add" action="prereg_add.php" method="post"><input type="hidden" name="SubmitNow" value="Yes" /><input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/prereg_pages/prereg_add.php?part=3');return document.MM_returnValue" value="Previous" /><input name="Clear" type="button" class="next_button" onclick="MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue" value="Clear" /><input name="Submit" type="submit" class="next_button" value="Confirm" /></form>
</div>
<?php } ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
