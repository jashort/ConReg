<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('registration_modify');

if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
} elseif (isset($_POST["Update"])) {
  $attendee = new Attendee();
  $attendee->fromArray($_POST);
  $pass = getPassType($_POST["pass_type_id"]);
  $attendee->pass_type = $pass->category;
  if (trim($_POST["paid_amount"]) == '') {
    $attendee->paid_amount = $pass->cost;
  } else {
    if(preg_match('/^\d{1,4}\.\d{0,2}$/', trim($_POST["paid_amount"]))) {
      $attendee->paid_amount = $_POST["paid_amount"];
    } else {
      die('Manual price amount must contain numbers only. Ex: 19.99');
    }
  }

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

    function cancelVerify() {
      var answer=confirm("Are you sure you want to cancel?");
      if (answer==true) {
        window.location='/';
      }
    }

  </script>

</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

<div class="container">

  <!-- Main component for a primary marketing message or call to action -->
  <div class="jumbotron">
    <h2>Update Attendee</h2>

    <form name="reg_update" action="reg_update.php" method="post" class="form-horizontal">
      <input name="id" type="hidden" value="<?php echo $attendee->id ?>" />
      <input name="order_id" type="hidden" value="<?php echo $attendee->order_id ?>" />
      <fieldset id="personalinfo">
        <legend>Attendee Info</legend>
        <div class="form-group">
          <label for="First Name" class="control-label col-sm-2">First Name</label>
          <div class="col-sm-4">
            <input name="first_name" type="text" maxlength="60" class="form-control" id="First Name"
                   value="<?php echo $attendee->first_name ?>" />
          </div>
        </div>
        
        <div class="form-group">
          <label for="Last Name" class="control-label col-sm-2">Last Name</label>
          <div class="col-sm-4">
            <input name="last_name" type="text" maxlength="60" class="form-control" id="Last Name"
                   value="<?php echo $attendee->last_name ?>" />
          </div>          
        </div>

        <div class="form-group">
          <label for="Badge Name" class="control-label col-sm-2">Badge Name</label>
          <div class="col-sm-4">
            <input name="badge_name" type="text" maxlength="15" class="form-control" id="Badge Name"
                   value="<?php echo $attendee->badge_name ?>" />
          </div>          
        </div>
          <input name="badge_number" type="hidden" maxlength="10" id="Badge Number"
                 value="<?php echo $attendee->badge_number ?>" />
        
        <div class="form-group">
          <label for="Zip" class="control-label col-sm-2">Zip</label>
          <div class="col-sm-2">
            <input name="zip" type="text" class="form-control" maxlength="10" id="Zip"
                   value="<?php echo $attendee->zip ?>" />
          </div>
        </div>

        <div class="form-group">
          <label for="E-Mail" class="control-label col-sm-2">E-Mail</label>
          <div class="col-sm-4">
            <input name="email" type="text" class="form-control" maxlength="250" id="E-Mail"
                   value="<?php echo $attendee->email ?>" />
          </div>
        </div>

        <div class="form-group">
          <label for="PhoneNumber" class="control-label col-sm-2">Phone Number</label>
          <div class="col-sm-4">
            <input name="phone" type="text" class="form-control" id="PhoneNumber" maxlength="60"
                   value="<?php echo $attendee->phone ?>" />
          </div>
        </div>

        <div class="form-group form-inline">
          <label class="control-label col-sm-2">Birth Date</label>
          <div class="col-sm-5">
            <input type="number" class="form-control two-character" maxlength="2" name="birth_month" id="Birth Month"
                   value="<?php echo $attendee->getBirthMonth() ?>" min="1" max="12" placeholder="MM">
            <span class="bold_text">/</span>
            <input type="number" class="form-control two-character" maxlength="2" name="birth_day" id="Birth Day"
                   value="<?php echo $attendee->getBirthDay() ?>" min="1" max="31" placeholder="DD">
            <span class="bold_text">/</span>
            <input type="number" class="form-control four-character" maxlength="4" name="birth_year" id="Birth Year"
                   value="<?php echo $attendee->getBirthYear() ?>" min="1900" max="2015" placeholder="YYYY">
            (Month / Day / Year)
          </div>
        </div>
      </fieldset>
      
      <br />
      <fieldset id="emergencyinfo">
        <legend>Emergency Contact Info</legend>
        <div class="form-group">
          <label for="emergency contact full name" class="control-label col-sm-2">Full Name:</label>
          <div class="col-sm-4">
            <input name="ec_fullname" id="ECFullName" type="text" class="form-control" maxlength="250"
                   id="Emergency Contact Full Name" value="<?php echo $attendee->ec_fullname ?>" />
          </div>
        </div>

        <div class="form-group">
          <label for="ECPhone" class="control-label col-sm-2">Phone Number:</label>
          <div class="col-sm-4">
            <input name="ec_phone" id="ECPhone" type="text" class="form-control" maxlength="60"
                   value="<?php echo $attendee->ec_phone ?>" />
          </div>
        </div>
      </fieldset>
      <br />

      <?php if ($attendee->isMinor()) { ?>
        <fieldset id="parentinfo">
          <legend>Parent Contact Info</legend>
          <div class="form-group">
            <label class="control-label col-sm-2">Same as Emergency Contact</label>
            <div class="col-sm-2">
              <input name="ec_same" id="Same" type="checkbox" class="form-control" onclick="sameInfo();"
                <?php if ($attendee->ec_same == "Y") { echo "value=\"Y\" checked"; } else { echo "value=\"\""; } ?> />
            </div>
          </div>
          
          <div class="form-group">
            <label for="parent contact full name" class="control-label col-sm-2">Full Name:</label>
            <div class="col-sm-4">
              <input name="parent_fullname" id="PCFullName" type="text" class="form-control" maxlength="250"
                     id="Parent Contact Full Name" value="<?php echo $attendee->parent_fullname ?>" />
            </div>
          </div>

          <div class="form-group">
            <label for="PCPhone" class="control-label col-sm-2">Phone Number:</label>
            <div class="col-sm-4">
              <input name="parent_phone" id="PCPhone" type="text" class="form-control" maxlength="60"
                     value="<?php echo $attendee->parent_phone ?>" />
            </div>
          </div>

          <div class="form-group">
            <span class="bold_text">Parental Consent Form Received</span>
            <div class="col-sm-1">
              <input name="parent_form" id="ParentForm" type="checkbox"
                  <?php if ($attendee->parent_form == "Yes") { echo "value=\"Yes\" checked"; } else { echo "value=\"\""; } ?>
                     id="Parent Contact Form Verification" class="form-control" />
            </div>
          </div>
        </fieldset>
      <?php } else { ?>
        <input type="hidden" name="ec_same" value="<?php echo $attendee->ec_same?>" />
        <input type="hidden" name="parent_fullname" value="<?php echo $attendee->parent_fullname ?>" />
        <input type="hidden" name="parent_phone" value="<?php echo $attendee->parent_phone ?>" />
        <input type="hidden" name="parent_form" value="<?php echo $attendee->parent_form ?>" />
      <?php } ?>
      <br />
      <fieldset id="paymentinfo">
        <legend>Pass Type</legend>
        <div class="form-inline">
          <div class="form-group">
            <label for="pass_type_id" class="control-label col-sm-4">Select Pass Type:</label>
            <div class="col-sm-2">
              <select name="pass_type_id" required class="form-control"
                      onchange="alert('Change the amount paid below if necessary');">
                <?php
                $passTypeList = passTypeForAgeList($attendee->getAge());
                while ($passType = $passTypeList->fetch()) { ?>
                  <option value="<?php echo $passType->id?>" 
                    <?php if ($attendee->pass_type_id == $passType->id) echo " selected";?>>
                    <?php echo $passType->name?> (Normal Price: $<?php echo $passType->cost?>)</option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </fieldset>

      <fieldset>
          <div class="form-group">
            <label for="paid_amount" class="control-label col-sm-2">Amount Paid $</label>
            <div class="col-sm-2">
              <input name="paid_amount" type="text" maxlength="8" class="form-control" id="paid_amount"
                     <?php if (!hasRight('registration_manual_price')) { ?> readonly <?php } ?>
                     value="<?php echo $attendee->paid_amount ?>" />
            </div>
          </div>
      </fieldset>
      <fieldset id="notes">
        <div class="form-group">
          <label for="notes" class="control-label">Notes</label>
          <textarea name="notes" rows="5" class="form-control" id="notes"><?php echo $attendee->notes ?></textarea>
        </div>
      </fieldset>

      <fieldset id="checkedin">
        <div class="form-group">
          <label for="checked_in" class="control-label col-sm-2">Checked In</label>
          <div class="col-sm-1">
            <select name="checked_in">
            <option value="Yes" <?php if ($attendee->checked_in == "Yes") { echo "selected"; }?>>Yes</option>
            <option value="No" <?php if ($attendee->checked_in == "No") { echo "selected"; }?>>No</option>
            </select>
          </div>
        </div>
      </fieldset>

      <input type="hidden" name="reg_type" value="<?php echo $attendee->reg_type?>" />
      <input type="hidden" name="paid" value="<?php echo $attendee->paid ?>" />
      <input type="hidden" name="id" value="<?php echo $attendee->id ?>" />
      <input type="hidden" name="country" value="<?php echo $attendee->country ?>" />

      <input name="Cancel" type="button" class="btn btn-danger" onclick="cancelVerify()" value="Cancel" />
      <input name="Update" type="submit" value="Update" class="btn btn-primary col-xs-offset-5" />
    </form>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
