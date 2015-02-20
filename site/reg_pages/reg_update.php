<?php
require_once('../includes/functions.php');
require_once('../includes/passtypes.php');

require_once('../includes/authcheck.php');
requireRight('registration_modify');

if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
} elseif (isset($_POST["Update"])) {
  $attendee = new Attendee();
  $attendee->fromArray($_POST);
  regUpdate($attendee);
  logMessage($_SESSION['username'], 60, "Modified attendee " .
      $attendee->first_name . ' ' . $attendee->last_name . "(ID " . $attendee->id . ")");

  redirect("/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">

  <title>Edit Attendee</title>

  <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/navbar-fixed-top.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="../assets/dist/js/html5shiv-3.7.2.min.js"></script>
  <script src="../assets/dist/js/respond-1.4.2.min.js"></script>
  <![endif]-->
  <script type="text/javascript">

    function sameInfo() {

      if (document.getElementById('Same').checked) {
        document.getElementById('Same').value = "Y";
        document.getElementById('PCFullName').value = document.getElementById('ECFullName').value;
        document.getElementById('PCPhone').value = document.getElementById('ECPhone').value;
      } else {
        document.getElementById('Same').value = "";
        document.getElementById('PCFullName').value = "";
        document.getElementById('PCPhone').value = "";
      }
    }

    function setAmount(amt) {
      document.getElementById('paid_amount').value = amt;
    }

  </script>

</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

<div class="container">

  <!-- Main component for a primary marketing message or call to action -->
  <div class="jumbotron">
    <h2>Complete Order</h2>

    <form name="reg_update" action="reg_update.php" method="post" class="form-inline">
      <input name="id" type="hidden" value="<?php echo $attendee->id ?>" />
      <input name="order_id" type="hidden" value="<?php echo $attendee->order_id ?>" />
      <fieldset id="personalinfo">
        <legend>Attendee Info</legend>
        <label for="First Name" class="control-label">First Name</label>
          <input name="first_name" type="text" maxlength="60" class="form-control" id="First Name" 
                 value="<?php echo $attendee->first_name ?>" />
        <label for="Last Name" class="control-label">Last Name</label>
          <input name="last_name" type="text" maxlength="60" class="form-control" id="Last Name" 
                 value="<?php echo $attendee->last_name ?>" />
        <br />
        <label for="Badge Name" class="control-label">Badge Name</label>
          <input name="badge_name" type="text" maxlength="15" class="form-control" id="Badge Name" 
                 value="<?php echo $attendee->badge_name ?>" />

          <input name="badge_number" type="hidden" maxlength="10" id="Badge Number"
                 value="<?php echo $attendee->badge_number ?>" />
        <br />

        <label for="Zip" class="control-label">Zip</label>
          <input name="zip" type="text" class="form-control" maxlength="10" id="Zip" 
                 value="<?php echo $attendee->zip ?>" />
        <br />
        <label for="E-Mail" class="control-label">E-Mail</label>
          <input name="email" type="text" class="form-control" maxlength="250" id="E-Mail" 
                 value="<?php echo $attendee->email ?>" />
        <br />
        <label for="PhoneNumber" class="control-label">Phone Number</label>
          <input name="phone" type="text" class="form-control" id="PhoneNumber" maxlength="60" 
                 value="<?php echo $attendee->phone ?>" />
        <br />
        <label class="control-label">Birth Date</label>
          <input type="number" class="form-control" maxlength="2" name="birth_month" id="Birth Month"
                 value="<?php echo $attendee->getBirthMonth() ?>" min="1" max="12" placeholder="MM">
          <span class="bold_text">/</span>
          <input type="number" class="form-control" maxlength="2" name="birth_day" id="Birth Day"
                 value="<?php echo $attendee->getBirthDay() ?>" min="1" max="31" placeholder="DD">
          <span class="bold_text">/</span>
          <input type="number" class="form-control" maxlength="4" name="birth_year" id="Birth Year" 
                 value="<?php echo $attendee->getBirthYear() ?>" min="1900" max="2015" placeholder="YYYY">
        (Month / Day / Year)
      </fieldset>
      
      <fieldset id="emergencyinfo">
        <legend>Emergency Contact Info</legend>
        <label for="emergency contact full name" class="control-label">Full Name:</label>
          <input name="ec_fullname" id="ECFullName" type="text" class="form-control" maxlength="250" 
                 id="Emergency Contact Full Name" value="<?php echo $attendee->ec_fullname ?>" />
        <br />
        <label for="ECPhone" class="control-label">Phone Number:</label>
          <input name="ec_phone" id="ECPhone" type="text" class="form-control" maxlength="60" 
                 value="<?php echo $attendee->ec_phone ?>" />
        <br />
      </fieldset>
      <?php if ($attendee->isMinor()) { ?>
        <fieldset id="parentinfo">
          <legend>Parent Contact Info</legend>
          <input name="ec_same" id="Same" type="checkbox" class="form-control" onclick="sameInfo();"
              <?php if ($attendee->ec_same == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> />
          <span class="bold_text"> SAME AS EMERGENCY CONTACT INFO</span>
          <br /><br />
          
          <label for="parent contact full name" class="control-label">Full Name:</label>
            <input name="parent_fullname" id="PCFullName" type="text" class="form-control" maxlength="250" 
                   id="Parent Contact Full Name" value="<?php echo $attendee->parent_fullname ?>" />
          <br />
          <label for="PCPhone" class="control-label">Phone Number:</label>
            <input name="parent_phone" id="PCPhone" type="text" class="form-control" maxlength="60" 
                   value="<?php echo $attendee->parent_phone ?>" />
          <br /><br />
          <input name="parent_form" id="ParentForm" type="checkbox" 
              <?php if ($attendee->parent_form == "Yes") { echo "value=\"Yes\" checked"; } else { echo "value=\"\""; } ?> 
                 id="Parent Contact Form Verification" class="form-control" />
          <span class="bold_text"> PARENTAL CONSENT FORM RECEIVED</span>
        </fieldset>
      <?php } else { ?>
        <input type="hidden" name="ec_same" value="<?php echo $attendee->ec_same?>" />
        <input type="hidden" name="parent_fullname" value="<?php echo $attendee->parent_fullname ?>" />
        <input type="hidden" name="parent_phone" value="<?php echo $attendee->parent_phone ?>" />
        <input type="hidden" name="parent_form" value="<?php echo $attendee->parent_form ?>" />
      <? } ?>
      <fieldset id="paymentinfo">
        <legend>PASS TYPE</legend>
        <? $amount = calculatePassCost($attendee->getAge(), 'Weekend') ?>
        <input type="radio" name="pass_type" value="Weekend" id="PassType_0" class="form-control"
               onchange="setAmount(<?php echo $amount?>);" 
               <?php if ($attendee->pass_type == "Weekend") echo "checked=\"checked\""; ?> />
        <label for="PassType_0" class="control-label">
          All Weekend - $<?php echo $amount ?></label>
        <br />

        <input type="radio" name="pass_type" value="VIP" id="PassType_1" class="form-control"
               onchange="setAmount(<?php echo $amount?>);" 
               <?php if ($attendee->pass_type == "VIP") echo "checked=\"checked\""; ?> />
        <label for="PassType_1" class="control-label">
          <? $amount = calculatePassCost($attendee->getAge(), 'VIP') ?>
          VIP - $<?php echo $amount ?></label>
        <br />
        <input type="radio" name="pass_type" value="Friday" id="PassType_2" class="form-control"
               onchange="setAmount(<?php echo $amount ?>);" 
               <?php if ($attendee->pass_type == "Friday") echo "checked=\"checked\""; ?> />
        <label for="PassType_2" class="control-label">
          <? $amount = calculatePassCost($attendee->getAge(), 'Friday') ?>
          Saturday Only - $<?php echo $amount ?></label>
        <br />
        <input type="radio" name="pass_type" value="Saturday" id="PassType_3" class="form-control" 
               onchange="setAmount(<?php echo $amount ?>);" 
               <?php if ($attendee->pass_type == "Saturday") echo "checked=\"checked\""; ?> />
        <label for="PassType_3" class="control-label">
          <? $amount = calculatePassCost($attendee->getAge(), 'Saturday') ?>
          Saturday Only - $<?php echo $amount ?></label>
        <br />
        <input type="radio" name="pass_type" value="Sunday" id="PassType_4" class="form-control"
               onchange="setAmount(<?php echo $amount ?>);" 
               <?php if ($attendee->pass_type == "Sunday") echo "checked=\"checked\""; ?> />
        <label for="PassType_4" class="control-label">
          <? $amount = calculatePassCost($attendee->getAge(), 'Sunday') ?>
          Sunday Only - $<?php echo $amount ?></label>
        <br />
        <input type="radio" name="pass_type" value="Monday" id="PassType_5" class="form-control"
               onclick="setAmount(<?php echo $amount ?>)"
               <?php if ($attendee->pass_type == "Monday") echo "checked=\"checked\""; ?> />
        <label for="PassType_5">
          <? $amount = calculatePassCost($attendee->getAge(), 'Monday') ?>
          Monday Only - $<?php echo $amount ?></label>
        <br />
        <?php if (hasRight('registration_manual_price')) { ?>
          <input type="radio" name="pass_type" value="Manual Price" id="PassType_6" class="form-control" 
          onclick="setAmount(<?php echo $attendee->paid_amount ?>)" 
          <?php if ($attendee->pass_type == "Manual Price") echo "checked=\"checked\""; ?> />
          <label for="PassType_6" class="control-label">
            Manual Price - $<?php echo $attendee->paid_amount ?>
          </label>
          <br /><br />
          <label for="paid_amount" class="control-label">Amount Paid $</label>
          <input name="paid_amount" type="text" maxlength="8" class="form-control" id="paid_amount" 
          value="<?php echo $attendee->paid_amount ?>" />
          <br />
        <?php } ?>
      </fieldset>
      <fieldset id="notes">
        <label for="notes" class="control-label">Notes</label>
          <textarea name="notes" rows="5" class="form-control" id="notes"><?php echo $attendee->notes ?></textarea>
      </fieldset>

      <br>
      <fieldset id="checkedin">
        <label for="checked_in" class="control-label">Checked In</label>
        <select name="checked_in" class="form-control">
          <option value="Yes" <?php if ($attendee->checked_in == "Yes") { echo "selected"; }?>>Yes</option>
          <option value="No" <?php if ($attendee->checked_in == "No") { echo "selected"; }?>>No</option>
        </select>
      </fieldset>

      <input type="hidden" name="reg_type" value="<?php echo $attendee->reg_type?>" />
      <input type="hidden" name="paid" value="<?php echo $attendee->paid ?>" />
      <input type="hidden" name="id" value="<?php echo $attendee->id ?>" />
      <input type="hidden" name="country" value="<?php echo $attendee->country ?>" />

      <input name="Update" type="submit" value="Update" class="btn btn-primary" />
    </form>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
