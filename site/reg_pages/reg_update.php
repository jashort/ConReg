<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

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

$date = new DateTime($BDate);
$now = new DateTime();
$interval = $now->diff($date);
$year_diff = $interval->y;


if ($year_diff <= 5) {
$Weekend = 0;
$Friday = 0;
$Saturday = 0;
$Sunday = 0;
$Monday = 0;
} else if (($year_diff > 5) && ($year_diff <= 12)){
$Weekend = 35;
$Friday = 15;
$Saturday = 25;
$Sunday = 25;
$Monday = 15;	
} else if ($year_diff > 12){
$Weekend = 55;
$Friday = 25;
$Saturday = 35;
$Sunday = 35;
$Monday = 25;
}  

switch ($_POST["PassType"]){
	case "Weekend":
		$PaidAmount = $Weekend;
		break;
	case "Friday":
		$PaidAmount = $Friday;
		break;	
	case "Saturday":
		$PaidAmount = $Saturday;
		break;	
	case "Sunday":
		$PaidAmount = $Sunday;
		break;	
	case "Monday":
		$PaidAmount = $Monday;
		break;
}

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
	document.reg_add3.Amount.value = "<?php echo $Monday ?>";MPAmount
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
<form name="reg_update" action="reg_update.php" method="post">
<input name="Id" type="hidden" value="<?php echo $results['kumo_reg_data_id'] ?>" />
<fieldset id="personalinfo">
<legend>Attendee Info</legend>
<label>First Name: </label>
<input name="FirstName" type="text" class="input_20_200" id="First Name" value="<?php echo $results['kumo_reg_data_fname'] ?>" />
<label>Last Name: </label>
<input name="LastName" type="text" class="input_20_200" id="Last Name" value="<?php echo $results['kumo_reg_data_lname'] ?>" />
<br />
<label>Badge Number: </label>
<input name="BadgeNumber" type="text" class="input_20_200" id="Badge Number" value="<?php echo $results['kumo_reg_data_bnumber'] ?>" />
<br />
<?php if($results['kumo_reg_data_address']!="") { ?>
<label>Address : </label>
<input name="Address" type="text" class="input_20_550" id="Address" value="<?php echo $results['kumo_reg_data_address'] ?>" />
<br />
<label>City : </label>
<input name="City" type="text" class="input_20_200" id="City" value="<?php echo $results['kumo_reg_data_city'] ?>" />
<label>State : </label>
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
</select>
<?php } ?>
<label>Zip : </label>
<input name="Zip" type="text" class="input_20_150" id="Zip" value="<?php echo $results['kumo_reg_data_zip'] ?>"  />
<label>Country : </label>
<?php if($results['kumo_reg_data_address']!="") { ?>
<input name="Country" type="text" class="input_20_150" id="Country" value="<?php echo $results['kumo_reg_data_country'] ?>"  />
<br />
<?php } ?>
<label>E-Mail : </label>
<input name="EMail" type="text" class="input_20_200" id="E-Mail" value="<?php echo $results['kumo_reg_data_email'] ?>"  />
<label>E-Mail Verification : </label>
<input name="EMailV" type="text" class="input_20_200" onBlur="verifyEmail();" value="<?php echo $results['kumo_reg_data_email'] ?>"  />
<br />
<label>Phone Number: </label>
<input name="PhoneNumber" type="text" class="input_20_200" id="Phone Number" value="<?php echo $results['kumo_reg_data_phone'] ?>"  />
<label>Birth Date: </label>
<select name="BirthMonth" class="select_25_50" id="Birth Month" >
<option value="" <?php if ($BirthMonth == "") echo "selected=\"selected\""; ?> >MM</option>
<option value="01" <?php if ($BirthMonth == "01") echo "selected=\"selected\""; ?> >01</option>
<option value="02" <?php if ($BirthMonth == "02") echo "selected=\"selected\""; ?> >02</option>
<option value="03" <?php if ($BirthMonth == "03") echo "selected=\"selected\""; ?> >03</option>
<option value="04" <?php if ($BirthMonth == "04") echo "selected=\"selected\""; ?> >04</option>
<option value="05" <?php if ($BirthMonth == "05") echo "selected=\"selected\""; ?> >05</option>
<option value="06" <?php if ($BirthMonth == "06") echo "selected=\"selected\""; ?> >06</option>
<option value="07" <?php if ($BirthMonth == "07") echo "selected=\"selected\""; ?> >07</option>
<option value="08" <?php if ($BirthMonth == "08") echo "selected=\"selected\""; ?> >08</option>
<option value="09" <?php if ($BirthMonth == "09") echo "selected=\"selected\""; ?> >09</option>
<option value="10" <?php if ($BirthMonth == "10") echo "selected=\"selected\""; ?> >10</option>
<option value="11" <?php if ($BirthMonth == "11") echo "selected=\"selected\""; ?> >11</option>
<option value="12" <?php if ($BirthMonth == "12") echo "selected=\"selected\""; ?> >12</option>
</select>
<span class="bold_text">/</span>
<select name="BirthDay" class="select_25_50" id="Birth Day"  >
	<option value="">DD</option>
<option value="01" <?php if ($BirthDay == "01") echo "selected=\"selected\""; ?> >01</option>
<option value="02" <?php if ($BirthDay == "02") echo "selected=\"selected\""; ?> >02</option>
<option value="03" <?php if ($BirthDay == "03") echo "selected=\"selected\""; ?> >03</option>
<option value="04" <?php if ($BirthDay == "04") echo "selected=\"selected\""; ?> >04</option>
<option value="05" <?php if ($BirthDay == "05") echo "selected=\"selected\""; ?> >05</option>
<option value="06" <?php if ($BirthDay == "06") echo "selected=\"selected\""; ?> >06</option>
<option value="07" <?php if ($BirthDay == "07") echo "selected=\"selected\""; ?> >07</option>
<option value="08" <?php if ($BirthDay == "08") echo "selected=\"selected\""; ?> >08</option>
<option value="09" <?php if ($BirthDay == "09") echo "selected=\"selected\""; ?> >09</option>
<option value="10" <?php if ($BirthDay == "10") echo "selected=\"selected\""; ?> >10</option>
<option value="11" <?php if ($BirthDay == "11") echo "selected=\"selected\""; ?> >11</option>
<option value="12" <?php if ($BirthDay == "12") echo "selected=\"selected\""; ?> >12</option>
<option value="13" <?php if ($BirthDay == "13") echo "selected=\"selected\""; ?> >13</option>
<option value="14" <?php if ($BirthDay == "14") echo "selected=\"selected\""; ?> >14</option>
<option value="15" <?php if ($BirthDay == "15") echo "selected=\"selected\""; ?> >15</option>
<option value="16" <?php if ($BirthDay == "16") echo "selected=\"selected\""; ?> >16</option>
<option value="17" <?php if ($BirthDay == "17") echo "selected=\"selected\""; ?> >17</option>
<option value="18" <?php if ($BirthDay == "18") echo "selected=\"selected\""; ?> >18</option>
<option value="19" <?php if ($BirthDay == "19") echo "selected=\"selected\""; ?> >19</option>
<option value="20" <?php if ($BirthDay == "20") echo "selected=\"selected\""; ?> >20</option>
<option value="21" <?php if ($BirthDay == "21") echo "selected=\"selected\""; ?> >21</option>
<option value="22" <?php if ($BirthDay == "22") echo "selected=\"selected\""; ?> >22</option>
<option value="23" <?php if ($BirthDay == "23") echo "selected=\"selected\""; ?> >23</option>
<option value="24" <?php if ($BirthDay == "24") echo "selected=\"selected\""; ?> >24</option>
<option value="25" <?php if ($BirthDay == "25") echo "selected=\"selected\""; ?> >25</option>
<option value="26" <?php if ($BirthDay == "26") echo "selected=\"selected\""; ?> >26</option>
<option value="27" <?php if ($BirthDay == "27") echo "selected=\"selected\""; ?> >27</option>
<option value="28" <?php if ($BirthDay == "28") echo "selected=\"selected\""; ?> >28</option>
<option value="29" <?php if ($BirthDay == "29") echo "selected=\"selected\""; ?> >29</option>
<option value="30" <?php if ($BirthDay == "30") echo "selected=\"selected\""; ?> >30</option>
<option value="31" <?php if ($BirthDay == "31") echo "selected=\"selected\""; ?> >31</option>
</select>
<span class="bold_text">/</span>
<select name="BirthYear" class="select_25_75"  id="Birth Year" >
	<option <?php if ($BirthYear == "") echo "selected=\"selected\""; ?> >YYYY</option>
	<option value="2012" <?php if ($BirthYear == "2012") echo "selected=\"selected\""; ?> >2012</option>
	<option value="2011" <?php if ($BirthYear == "2011") echo "selected=\"selected\""; ?> >2011</option>
	<option value="2010" <?php if ($BirthYear == "2010") echo "selected=\"selected\""; ?> >2010</option>
	<option value="2009" <?php if ($BirthYear == "2009") echo "selected=\"selected\""; ?> >2009</option>
	<option value="2008" <?php if ($BirthYear == "2008") echo "selected=\"selected\""; ?> >2008</option>
	<option value="2007" <?php if ($BirthYear == "2007") echo "selected=\"selected\""; ?> >2007</option>
	<option value="2006" <?php if ($BirthYear == "2006") echo "selected=\"selected\""; ?> >2006</option>
	<option value="2005" <?php if ($BirthYear == "2005") echo "selected=\"selected\""; ?> >2005</option>
	<option value="2004" <?php if ($BirthYear == "2004") echo "selected=\"selected\""; ?> >2004</option>
	<option value="2003" <?php if ($BirthYear == "2003") echo "selected=\"selected\""; ?> >2003</option>
	<option value="2002" <?php if ($BirthYear == "2002") echo "selected=\"selected\""; ?> >2002</option>
	<option value="2001" <?php if ($BirthYear == "2001") echo "selected=\"selected\""; ?> >2001</option>
	<option value="2000" <?php if ($BirthYear == "2000") echo "selected=\"selected\""; ?> >2000</option>
	<option value="1999" <?php if ($BirthYear == "1999") echo "selected=\"selected\""; ?> >1999</option>
	<option value="1998" <?php if ($BirthYear == "1998") echo "selected=\"selected\""; ?> >1998</option>
	<option value="1997" <?php if ($BirthYear == "1997") echo "selected=\"selected\""; ?> >1997</option>
	<option value="1996" <?php if ($BirthYear == "1996") echo "selected=\"selected\""; ?> >1996</option>
	<option value="1995" <?php if ($BirthYear == "1995") echo "selected=\"selected\""; ?> >1995</option>
	<option value="1994" <?php if ($BirthYear == "1994") echo "selected=\"selected\""; ?> >1994</option>
	<option value="1993" <?php if ($BirthYear == "1993") echo "selected=\"selected\""; ?> >1993</option>
	<option value="1992" <?php if ($BirthYear == "1992") echo "selected=\"selected\""; ?> >1992</option>
	<option value="1991" <?php if ($BirthYear == "1991") echo "selected=\"selected\""; ?> >1991</option>
	<option value="1990" <?php if ($BirthYear == "1990") echo "selected=\"selected\""; ?> >1990</option>
	<option value="1989" <?php if ($BirthYear == "1989") echo "selected=\"selected\""; ?> >1989</option>
	<option value="1988" <?php if ($BirthYear == "1988") echo "selected=\"selected\""; ?> >1988</option>
	<option value="1987" <?php if ($BirthYear == "1987") echo "selected=\"selected\""; ?> >1987</option>
	<option value="1986" <?php if ($BirthYear == "1986") echo "selected=\"selected\""; ?> >1986</option>
	<option value="1985" <?php if ($BirthYear == "1985") echo "selected=\"selected\""; ?> >1985</option>
	<option value="1984" <?php if ($BirthYear == "1984") echo "selected=\"selected\""; ?> >1984</option>
	<option value="1983" <?php if ($BirthYear == "1983") echo "selected=\"selected\""; ?> >1983</option>
	<option value="1982" <?php if ($BirthYear == "1982") echo "selected=\"selected\""; ?> >1982</option>
	<option value="1981" <?php if ($BirthYear == "1981") echo "selected=\"selected\""; ?> >1981</option>
	<option value="1980" <?php if ($BirthYear == "1980") echo "selected=\"selected\""; ?> >1980</option>
	<option value="1979" <?php if ($BirthYear == "1979") echo "selected=\"selected\""; ?> >1979</option>
	<option value="1978" <?php if ($BirthYear == "1978") echo "selected=\"selected\""; ?> >1978</option>
	<option value="1977" <?php if ($BirthYear == "1977") echo "selected=\"selected\""; ?> >1977</option>
	<option value="1976" <?php if ($BirthYear == "1976") echo "selected=\"selected\""; ?> >1976</option>
	<option value="1975" <?php if ($BirthYear == "1975") echo "selected=\"selected\""; ?> >1975</option>
	<option value="1974" <?php if ($BirthYear == "1974") echo "selected=\"selected\""; ?> >1974</option>
	<option value="1973" <?php if ($BirthYear == "1973") echo "selected=\"selected\""; ?> >1973</option>
	<option value="1972" <?php if ($BirthYear == "1972") echo "selected=\"selected\""; ?> >1972</option>
	<option value="1971" <?php if ($BirthYear == "1971") echo "selected=\"selected\""; ?> >1971</option>
	<option value="1970" <?php if ($BirthYear == "1970") echo "selected=\"selected\""; ?> >1970</option>
	<option value="1969" <?php if ($BirthYear == "1969") echo "selected=\"selected\""; ?> >1969</option>
	<option value="1968" <?php if ($BirthYear == "1968") echo "selected=\"selected\""; ?> >1968</option>
	<option value="1967" <?php if ($BirthYear == "1967") echo "selected=\"selected\""; ?> >1967</option>
	<option value="1966" <?php if ($BirthYear == "1966") echo "selected=\"selected\""; ?> >1966</option>
	<option value="1965" <?php if ($BirthYear == "1965") echo "selected=\"selected\""; ?> >1965</option>
	<option value="1964" <?php if ($BirthYear == "1964") echo "selected=\"selected\""; ?> >1964</option>
	<option value="1963" <?php if ($BirthYear == "1963") echo "selected=\"selected\""; ?> >1963</option>
	<option value="1962" <?php if ($BirthYear == "1962") echo "selected=\"selected\""; ?> >1962</option>
	<option value="1961" <?php if ($BirthYear == "1961") echo "selected=\"selected\""; ?> >1961</option>
	<option value="1960" <?php if ($BirthYear == "1960") echo "selected=\"selected\""; ?> >1960</option>
	<option value="1959" <?php if ($BirthYear == "1959") echo "selected=\"selected\""; ?> >1959</option>
	<option value="1958" <?php if ($BirthYear == "1958") echo "selected=\"selected\""; ?> >1958</option>
	<option value="1957" <?php if ($BirthYear == "1957") echo "selected=\"selected\""; ?> >1957</option>
	<option value="1956" <?php if ($BirthYear == "1956") echo "selected=\"selected\""; ?> >1956</option>
	<option value="1955" <?php if ($BirthYear == "1955") echo "selected=\"selected\""; ?> >1955</option>
	<option value="1954" <?php if ($BirthYear == "1954") echo "selected=\"selected\""; ?> >1954</option>
	<option value="1953" <?php if ($BirthYear == "1953") echo "selected=\"selected\""; ?> >1953</option>
	<option value="1952" <?php if ($BirthYear == "1952") echo "selected=\"selected\""; ?> >1952</option>
	<option value="1951" <?php if ($BirthYear == "1951") echo "selected=\"selected\""; ?> >1951</option>
	<option value="1950" <?php if ($BirthYear == "1950") echo "selected=\"selected\""; ?> >1950</option>
	<option value="1949" <?php if ($BirthYear == "1949") echo "selected=\"selected\""; ?> >1949</option>
	<option value="1948" <?php if ($BirthYear == "1948") echo "selected=\"selected\""; ?> >1948</option>
	<option value="1947" <?php if ($BirthYear == "1947") echo "selected=\"selected\""; ?> >1947</option>
	<option value="1946" <?php if ($BirthYear == "1946") echo "selected=\"selected\""; ?> >1946</option>
	<option value="1945" <?php if ($BirthYear == "1945") echo "selected=\"selected\""; ?> >1945</option>
	<option value="1944" <?php if ($BirthYear == "1944") echo "selected=\"selected\""; ?> >1944</option>
	<option value="1943" <?php if ($BirthYear == "1943") echo "selected=\"selected\""; ?> >1943</option>
	<option value="1942" <?php if ($BirthYear == "1942") echo "selected=\"selected\""; ?> >1942</option>
	<option value="1941" <?php if ($BirthYear == "1941") echo "selected=\"selected\""; ?> >1941</option>
	<option value="1940" <?php if ($BirthYear == "1940") echo "selected=\"selected\""; ?> >1940</option>
	<option value="1939" <?php if ($BirthYear == "1939") echo "selected=\"selected\""; ?> >1939</option>
	<option value="1938" <?php if ($BirthYear == "1938") echo "selected=\"selected\""; ?> >1938</option>
	<option value="1937" <?php if ($BirthYear == "1937") echo "selected=\"selected\""; ?> >1937</option>
	<option value="1936" <?php if ($BirthYear == "1936") echo "selected=\"selected\""; ?> >1936</option>
	<option value="1935" <?php if ($BirthYear == "1935") echo "selected=\"selected\""; ?> >1935</option>
	<option value="1934" <?php if ($BirthYear == "1934") echo "selected=\"selected\""; ?> >1934</option>
	<option value="1933" <?php if ($BirthYear == "1933") echo "selected=\"selected\""; ?> >1933</option>
	<option value="1932" <?php if ($BirthYear == "1932") echo "selected=\"selected\""; ?> >1932</option>
	<option value="1931" <?php if ($BirthYear == "1931") echo "selected=\"selected\""; ?> >1931</option>
	<option value="1930" <?php if ($BirthYear == "1930") echo "selected=\"selected\""; ?> >1930</option>
	<option value="1929" <?php if ($BirthYear == "1929") echo "selected=\"selected\""; ?> >1929</option>
	<option value="1928" <?php if ($BirthYear == "1928") echo "selected=\"selected\""; ?> >1928</option>
	<option value="1927" <?php if ($BirthYear == "1927") echo "selected=\"selected\""; ?> >1927</option>
	<option value="1926" <?php if ($BirthYear == "1926") echo "selected=\"selected\""; ?> >1926</option>
	<option value="1925" <?php if ($BirthYear == "1925") echo "selected=\"selected\""; ?> >1925</option>
	<option value="1924" <?php if ($BirthYear == "1924") echo "selected=\"selected\""; ?> >1924</option>
	<option value="1923" <?php if ($BirthYear == "1923") echo "selected=\"selected\""; ?> >1923</option>
	<option value="1922" <?php if ($BirthYear == "1922") echo "selected=\"selected\""; ?> >1922</option>
	<option value="1921" <?php if ($BirthYear == "1921") echo "selected=\"selected\""; ?> >1921</option>
	<option value="1920" <?php if ($BirthYear == "1920") echo "selected=\"selected\""; ?> >1920</option>
	<option value="1919" <?php if ($BirthYear == "1919") echo "selected=\"selected\""; ?> >1919</option>
	<option value="1918" <?php if ($BirthYear == "1918") echo "selected=\"selected\""; ?> >1918</option>
	<option value="1917" <?php if ($BirthYear == "1917") echo "selected=\"selected\""; ?> >1917</option>
	<option value="1916" <?php if ($BirthYear == "1916") echo "selected=\"selected\""; ?> >1916</option>
	<option value="1915" <?php if ($BirthYear == "1915") echo "selected=\"selected\""; ?> >1915</option>
	<option value="1914" <?php if ($BirthYear == "1914") echo "selected=\"selected\""; ?> >1914</option>
	<option value="1913" <?php if ($BirthYear == "1913") echo "selected=\"selected\""; ?> >1913</option>
	<option value="1912" <?php if ($BirthYear == "1912") echo "selected=\"selected\""; ?> >1912</option>
</select>
</fieldset>
<fieldset id="emergencyinfo">
<legend>Emergency Contact Info</legend>
<label>Full Name: </label>
<input name="ECFullName" type="text" class="input_20_200" id="Emergency Contact Full Name" value="<?php echo $results['kumo_reg_data_ecfullname'] ?>"  />
<br />
<label>Phone Number: </label>
<input name="ECPhoneNumber" type="text" class="input_20_200" id="Emergency Contact Phone Number" value="<?php echo $results['kumo_reg_data_ecphone'] ?>"  />
<br />
</fieldset>
<?php if ($year_diff < 18) { ?>
<fieldset id="parentinfo">
<legend>Parent Contact Info</legend>
<input name="Same" type="checkbox" class="checkbox" onclick="sameInfo();" <?php if ($results['kumo_reg_data_same'] == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> /><span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
<br /><br />
<label>Full Name: </label>
<input name="PCFullName" type="text" class="input_20_200" id="Parent Contact Full Name" value="<?php echo $results['kumo_reg_data_parent'] ?>"  />
<br />
<label>Phone Number: </label>
<input name="PCPhoneNumber" type="text" class="input_20_200" id="Parent Contact Phone Number" value="<?php echo $results['kumo_reg_data_parentphone'] ?>" />
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
<label>Notes : </label>
<textarea name="Notes" rows="5"><?php echo $results['kumo_reg_data_notes']; ?></textarea>
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
