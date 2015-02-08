<?php
require_once('../includes/functions.php');
require_once('../includes/passtypes.php');

require_once('../includes/authcheck.php');
requireRight('registration_add');

if ((!array_key_exists('part', $_POST) && !array_key_exists('part', $_GET)) || 
    (array_key_exists('action', $_GET) && $_GET['action'] == "clear")) {
  // Brand new attendee
  $_SESSION['current'] = new Attendee();
  if (!array_key_exists('currentOrder', $_SESSION)) { // Create order array if it doesn't exist
    $_SESSION['currentOrder'] = Array();
  }
  // Get it the next available badge number and set some default values
  $badge = $_SESSION['initials'] . str_pad(getBadgeNumber($_SESSION['staffid']), 4, '0', STR_PAD_LEFT);
  $_SESSION['current']->badge_number = $badge;
  $_SESSION['current']->added_by = $_SESSION['username'];
  $_SESSION['current']->reg_type = "Reg";
  $_SESSION['current']->checked_in = "N";
  $_SESSION['current']->paid = "N";
  $_SESSION['current']->parent_form = "N";
  $_SESSION['current']->ec_same = "N";
  redirect('/reg_pages/reg_add.php?part=1');
} elseif (array_key_exists('part', $_POST)) {
  // Handle posting form data and redirecting to the next section
  if ($_POST['part'] == 1) {
    $_SESSION['current']->first_name = $_POST["first_name"];
    $_SESSION['current']->last_name = $_POST["last_name"];
    $_SESSION['current']->phone = $_POST["phone"];
    $_SESSION['current']->email = $_POST["email"];
    $_SESSION['current']->zip = $_POST["zip"];
    $_SESSION['current']->birthdate = $_POST["birth_year"] . '-' . $_POST["birth_month"] . '-' . $_POST["birth_day"];
    redirect('/reg_pages/reg_add.php?part=2');
  } elseif ($_POST['part'] == 2) {
    $_SESSION['current']->ec_fullname = $_POST["ec_fullname"];
    $_SESSION['current']->ec_phone = $_POST["ec_phone"];
    if (array_key_exists("same", $_POST)) {
      $_SESSION['current']->ec_same = $_POST["same"];
    } else {
      $_SESSION['current']->ec_same = "N";
    }
    $_SESSION['current']->parent_fullname = $_POST["parent_fullname"];
    $_SESSION['current']->parent_phone = $_POST["parent_phone"];
    $_SESSION['current']->parent_form = $_POST["parent_form"];
    redirect('/reg_pages/reg_add.php?part=3');
  } elseif ($_POST['part'] == 3) {
    $_SESSION['current']->pass_type = $_POST["pass_type"];
    if ($_POST['pass_type'] == "Manual Price") {
      if(preg_match('^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$', $_POST["paid_amount"])) {
        $_SESSION['current']->paid_amount = $_POST["paid_amount"];
      } else {
        die('Manual price amount must contain numbers only. Ex: 19.99');
      }
    } else {
      $_SESSION['current']->paid_amount = calculatePassCost($_SESSION['current']->getAge(), $_POST['pass_type']);
    }
    $_SESSION['current']->notes = $_POST["notes"];
    redirect('/reg_pages/reg_add.php?part=4');
  } elseif ($_POST['part'] == 4) {
    // Add the current attendee to the open order
    array_push($_SESSION['currentOrder'], $_SESSION['current']);
    unset($_SESSION['current']);
    redirect("/reg_pages/reg_order.php");
  }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">

  <title>Registration</title>

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
        document.getElementById('PCPhoneNumber').value = document.getElementById('ECPhoneNumber').value;
      } else {
        document.getElementById('Same').value = "";
        document.getElementById('PCFullName').value = "";
        document.getElementById('PCPhoneNumber').value = "";
      }
    }

    function clearVerify() {
      var answer=confirm("Are you sure you want to clear?");
      if (answer==true) {
        window.location='/reg_pages/reg_add.php?action=clear';
      }
    }
    function manualPrice() {
      do {
        var amount = prompt("Please enter the amount","ex 40.00");
        var currencyCheck = new RegExp("^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$");
        var currencyFormat = currencyCheck.test(amount);
      } while ((amount=="") || (currencyFormat==false));
      do {
        var reason = prompt("Please enter the reason for the manual pricing","");
      } while (reason=="");
      document.getElementById('MPAmount').value = amount;
      document.getElementById('Notes').value = reason;
    }
  </script>


</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

<div class="container">

  <!-- Main component for a primary marketing message or call to action -->
  <div class="jumbotron">
    <h2>Registration</h2>
    <?php if (array_key_exists('part', $_GET) && $_GET["part"]=="1"){ ?>
      <form name="reg_add1" action="reg_add.php" method="post" class="form-inline">
        <input name="part" type="hidden" value="1" />
        <fieldset id="personalinfo">
          <legend>Attendee Info</legend>
            <label for="First Name" class="control-label">First Name</label>
              <input name="first_name" type="text" maxlength="60" class="form-control" id="First Name"
                     value="<?php echo $_SESSION['current']->first_name; ?>" autocomplete="off" autofocus required />
            <label for="Last Name" class="control-label">Last Name</label>
              <input name="last_name" type="text" maxlength="60" class="form-control" id="Last Name"
                     value="<?php echo $_SESSION['current']->last_name; ?>" autocomplete="off" required />
            <br />
            <label for="PhoneNumber" class="control-label">Phone Number</label>
              <input name="phone" type="text" maxlength="60" class="form-control" id="PhoneNumber"
                     value="<?php echo $_SESSION['current']->phone; ?>" />
            <br />
            <label for="EMail" class="control-label">EMail</label>
              <input name="email" type="text" class="form-control" maxlength="250" id="EMail"
                     value="<?php echo $_SESSION['current']->email; ?>" autocomplete="off" />
            <br />
            <label for="Zip" class="control-label">Zip</label>
              <input name="zip" type="text" class="form-control" maxlength="10" id="Zip"
                     value="<?php echo $_SESSION['current']->zip; ?>" autocomplete="off" />
            <br />

            <label>Badge Number</label> <?php echo $_SESSION["current"]->badge_number ?>
            <br /><br />
            <label for="Birth Month" class="control-label">Birth Date:</label>
            <? // If a birthdate has been set, display it. Otherwise, display blank fields
              if ($_SESSION['current']->getAge() == -1) { ?>
                <input type="number" class="form-control two-character" maxlength="2" name="birth_month" id="Birth Month"
                       value="<?php echo $_SESSION['current']->getBirthMonth() ?>" min="1" max="12" placeholder="MM"
                       required autocomplete="off">
                /
                <input type="number" class="form-control two-character" maxlength="2" name="birth_day" id="Birth Day"
                       value="<?php echo $_SESSION['current']->getBirthDay() ?>" min="1" max="31" placeholder="DD" 
                       required autocomplete="off">
                /
                <input type="number" class="form-control four-character" maxlength="4" name="birth_year" id="Birth Year"
                       value="<?php echo $_SESSION['current']->getBirthYear()?>" min="1900" max="2015" placeholder="YYYY"
                       required autocomplete="off">
                (Month / Day / Year)
            <? } else { ?>
                <input type="number" class="form-control two-character" maxlength="2" name="birth_month" id="Birth Month"
                       min="1" max="12" placeholder="MM" required autocomplete="off">
                /
                <input type="number" class="form-control two-character" maxlength="2" name="birth_day" id="Birth Day"
                       min="1" max="31" placeholder="DD" required autocomplete="off">
                /
                <input type="number" class="form-control four-character" maxlength="4" name="birth_year" id="Birth Year"
                       min="1900" max="2015" placeholder="YYYY" required autocomplete="off">
                (Month / Day / Year)
      <? } ?>

        </fieldset><br>
        <input name="Next" type="submit" class="btn btn-primary" value="Next" />
        <input name="Clear" type="button" class="btn btn-danger" onclick="clearVerify()" value="Clear" />
      </form>
    <?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="2") { ?>

      <fieldset id="currentage">
        <label class="control-label">Current Age:</label> <?php echo $_SESSION['current']->getAge() ?>
      </fieldset>
      <form name="reg_add2" action="reg_add.php" method="post" class="form-inline">
        <input name="part" type="hidden" value="2" />
        <fieldset id="emergencyinfo">
          <legend>Emergency Contact Info</legend>
          <label for="ECFullName" class="control-label">Full Name</label>
            <input name="ec_fullname" id="ECFullName" type="text" class="form-control" maxlength="250"
                   value="<?php echo $_SESSION["current"]->ec_fullname; ?>" required autocomplete="off" autofocus />
          <br />
          <label for="ECPhoneNumber" class="control-label">Phone Number</label>
            <input name="ec_phone" id="ECPhoneNumber" type="text" class="form-control" maxlength="60"
                   value="<?php echo $_SESSION['current']->ec_phone ?>" required autocomplete="off"/>
          <br />
        </fieldset>
        <?php if ($_SESSION['current']->isMinor()) { ?>
          <fieldset id="parentinfo">
            <legend>Parent Contact Info</legend>
            <input name="same" id="Same" type="checkbox" class="checkbox" value="Y" onClick="sameInfo();"
                <?php if ($_SESSION['current']->ec_same == "Y") { echo "checked"; } ?> />
            <label for="Same" class="control-label">Same as Emergency Contact Info</label>
            <br /><br />
            <label for="PCFullName" class="control-label">Full Name</label>
            <input name="parent_fullname" id="PCFullName" type="text" class="form-control" maxlength="250"
                   value="<?php echo $_SESSION['current']->ec_fullname; ?>" required />
            <br />
            <label for="PCPhoneNumber" class="control-label">Phone Number:</label>
              <input name="parent_phone" id="PCPhoneNumber" type="tel" class="form-control" maxlength="60"
                     value="<?php echo $_SESSION['current']->ec_phone; ?>" autocomplete="off" required />
            <br /><br />
            <input name="parent_form" type="checkbox" value="Y"
                <?php if ($_SESSION['current']->parent_form == "Y") { echo "checked"; } ?> 
                   id="PCFormVer" class="checkbox" />
            <label for="PCFormVer" class="form-control">Parental Consent Form Received</label>

          </fieldset>
        <?php } else { ?>
          <input name="same" value="N" type="hidden" />
          <input name="parent_fullname" value="" type="hidden" />
          <input name="parent_phone" value="" type="hidden" />
          <input name="parent_form" value="N" type="hidden" />
        <? } ?>
        <br>
        <input name="Next" type="submit" class="btn btn-primary" value="Next" />
        <input name="Clear" type="button" class="btn btn-danger" onclick="clearVerify()" value="Clear" />
      </form>
    <?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="3") { ?>
      <?
      // Get pass costs based on age
      $weekendCost = calculatePassCost($_SESSION['current']->getAge(), "Weekend");
      $vipCost = calculatePassCost($_SESSION['current']->getAge(), "VIP");
      $fridayCost = calculatePassCost($_SESSION['current']->getAge(), "Friday");
      $saturdayCost = calculatePassCost($_SESSION['current']->getAge(), "Saturday");
      $sundayCost = calculatePassCost($_SESSION['current']->getAge(), "Sunday");
      $mondayCost = calculatePassCost($_SESSION['current']->getAge(), "Monday");
      ?>

      <form name="reg_add3" action="reg_add.php" method="post" class="form-inline">
        <input name="part" type="hidden" value="3" />
        <fieldset id="paymentinfo">
          <legend>Pass Type</legend>
            <input type="radio" name="pass_type" value="Weekend" id="Weekend" class="form-control" required
                <?php if ($_SESSION['current']->pass_type == "Weekend") echo "checked=\"checked\""; ?> />
            <label for="PassType_0" class="control-label">
              All Weekend - $<?php echo $weekendCost ?></label>
            <br>
            <input type="radio" name="pass_type" value="VIP" id="VIP" class="form-control" required
                <?php if ($_SESSION['current']->pass_type == "VIP") echo "checked=\"checked\""; ?> />
            <label for="PassType_1" class="control-label">
              VIP - $<?php echo $vipCost ?></label>
            <br>
            <input name="pass_type" type="radio" id="Friday" value="Friday" class="form-control" required
                <?php if ($_SESSION['current']->pass_type == "Friday") echo "checked=\"checked\""; ?> />
            <label for="PassType_2" class="control-label">
              Friday Only - $<?php echo $fridayCost ?></label>
            <br />
            <input name="pass_type" type="radio" id="Saturday" value="Saturday" class="form-control" required
                <?php if ($_SESSION['current']->pass_type == "Saturday") echo "checked=\"checked\""; ?> />
            <label for="PassType_3" class="control-label">
              Saturday Only - $<?php echo $saturdayCost ?></label>
            <br />
            <input type="radio" name="pass_type" value="Sunday" id="Sunday" class="form-control" required
                <?php if ($_SESSION['current']->pass_type == "Sunday") echo "checked=\"checked\""; ?> />
            <label for="PassType_4" class="control-label">
              Sunday Only - $<?php echo $sundayCost ?></label>
            <br />
            <input name="pass_type" type="radio" id="Monday" value="Monday" class="form-control" required
                <?php if ($_SESSION['current']->pass_type == "Monday") echo "checked=\"checked\""; ?> />
            <label for="PassType_5" class="control-label">
              Monday Only - $<?php echo $mondayCost ?></label>
            <br />
            <?php if (hasRight('registration_manual_price')) { ?>
              <input name="pass_type" type="radio" id="Manual" onclick="manualPrice()" value="Manual Price" required
                <?php if ($_SESSION['current']->pass_type == "Manual Price") echo "checked=\"checked\""; ?> />
              <label for="PassType_6" class="control-label">
                Manual Price - $</label>
              <input name="paid_amount" type="text" class="form-control" id="paid_amount"
                     value="<?php echo $_SESSION['current']->paid_amount ?>" />
            <?php } ?>
          <br />
        </fieldset>
        <fieldset id="notes">
          <label for="Notes" class="control-label">Notes</label><br>
          <textarea name="notes" id="Notes" rows="5" cols="80"><?php echo $_SESSION['current']->notes; ?></textarea>
        </fieldset>
        <br>
        <input name="Next" type="submit" class="btn btn-primary" value="Next" />
        <input name="Clear" type="button" class="btn btn-danger" onclick="clearVerify()" value="Clear" />

      </form>
    <?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="4") { ?>
      <fieldset id="personalinfo">
        <legend>Attendee Info</legend>
        <label>First Name: </label>
        <?php echo $_SESSION['current']->first_name; ?>
        <label>Last Name: </label>
        <?php echo $_SESSION['current']->last_name; ?>
        <br />
        <label>Phone Number: </label>
        <?php echo $_SESSION['current']->phone; ?>
        <br />
        <label>Email: </label>
        <?php echo $_SESSION['current']->email; ?>
        <br />
        <label>Zip: </label>
        <?php echo $_SESSION['current']->zip; ?>
        <br />
        <label>Badge Number: </label>
        <?php echo $_SESSION['current']->badge_number; ?>
        <br />
        <label>Birth Date: </label>
        <?php echo $_SESSION['current']->getBirthDate() ?>
      </fieldset>
      <fieldset id="emergencyinfo">
        <legend>Emergency Contact Info</legend>
        <label>Full Name: </label>
        <?php echo $_SESSION['current']->ec_fullname ?>
        <br />
        <label>Phone Number: </label>
        <?php echo $_SESSION['current']->ec_phone ?>
        <br />
      </fieldset>
      <?php if ($_SESSION['current']->isMinor()) { ?>
        <fieldset id="parentinfo">
          <legend>Parent Contact Info</legend>
          <label>Full Name: </label>
          <?php echo $_SESSION['current']->parent_fullname ?>
          <br />
          <label>Phone Number: </label>
          <?php echo $_SESSION['current']->parent_phone ?>
          <br />
          <label>Parental Permission Form Submitted: </label>
          <?php echo $_SESSION['current']->parent_form; ?>
        </fieldset>
      <?php } ?>
      <fieldset id="paymentinfo">
        <legend>PASS TYPE</legend>
        <?php echo $_SESSION['current']->pass_type ?> - $<?php echo $_SESSION['current']->paid_amount ?>
      </fieldset>

      <fieldset id="paymentinfo">
        <legend>NOTES</legend>
        <?php echo $_SESSION['current']->notes; ?>
      </fieldset>

      <br>
      <form name="reg_add" action="reg_add.php" method="post">
        <input type="hidden" name="SubmitNow" value="Yes" />
        <input type="hidden" name="part" value="4" />
        <!--<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=3');return document.MM_returnValue" value="Previous" />-->
        <input name="Done" type="submit" class="btn btn-primary" value="Done" />
        <input name="Clear" type="button" class="btn btn-danger" onclick="clearVerify()" value="Clear" />
      </form>
    <?php } ?>

  </div>
  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
