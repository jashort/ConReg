<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

if (isset($_POST["Update"])) {
  if (isset($_POST["checkin"]) && $_POST["checkin"] == true) {
    regcheckin($_POST["Id"]);
    redirect("/index.php");
    die();
  } else {
    /*
     * TODO: Move error handing in to the form so the site is still displayed instead of
     * just showing an error message
     *
     */
    echo('Error: Attendee Information not verified. Click back and check "Verified Info" after verifying attendee information');
    die();
  }
}


$colname_rs_update = "-1";
if (isset($_GET['id'])) {
  $colname_rs_update = $_GET['id'];
}
mysql_select_db($db_name, $kumo_conn);
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
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

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
<?php if ($row_rs_update['kumo_reg_data_checkedin'] == "Yes") { ?>
  <span class='display_text'>CHECKED IN</span>
<? } else { ?>
  <input name='checkin' type='checkbox' id='Information Verification' class='checkbox' />
  <span class='display_text'>VERIFIED INFO</span><br />
  <div class='centerbutton'>
    <input name=Id type=hidden value='<? echo $Id ?>' />
    <input name='Update' type='submit' value='update' class='submit_button' />
  </div>
<? } ?>

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
