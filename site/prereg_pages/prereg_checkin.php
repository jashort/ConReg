<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('prereg_checkin');

if (isset($_POST["Update"])) {
  // If a minor, check for parental consent form
  if (isset($_POST["Minor"]) && $_POST["Minor"] == true) {
    if (!isset($_POST["PCFormVer"]) || $_POST["PCFormVer"] != "on") {
      echo('Error: Parental consent form not verified. Click back and check "Parental Consent Form Received" after verifying attendee information');
      die();
    }
  }

  // Make sure information was verified
  if (isset($_POST["checkin"]) && $_POST["checkin"] == true) {
    regCheckIn($_POST["id"]);
    $attendee = getAttendee($_POST["id"]);
    logMessage($_SESSION['username'], 20, "PreReg check in " . $attendee->first_name . ' ' . $attendee->last_name);

    if (isset($_POST["Minor"]) && $_POST["Minor"] == true && $_POST["PCFormVer"] == "on") {
      regCheckInParentFormReceived($_POST["id"]);
    }
    // Display the print badge link below.

  } else {
    echo('Error: Attendee Information not verified. Click back and check "Verified Info" after verifying attendee information');
    die();
  }
}

if (isset($_POST["Done"])) {
  redirect("/prereg_pages/prereg_checkin_list.php?field=ord&id=".$_POST["oid"]);
  die();
}

if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
} else {
  $attendee = Array();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">

  <title>Pre-Reg Checkin</title>

  <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/navbar-fixed-top.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="../assets/dist/js/html5shiv-3.7.2.min.js"></script>
  <script src="../assets/dist/js/respond-1.4.2.min.js"></script>
  <![endif]-->
</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

<div class="container">

  <!-- Main component for a primary marketing message or call to action -->
  <div class="jumbotron">
    <h2>Pre-Reg Checkin</h2>

    <? if (isset($_POST["checkin"]) && $_POST["checkin"] == true) { ?>
      <br><br>
      <div class="container col-lg-2">
        <form action="/reg_pages/badgeprint.php" method="post" target="_blank" class="form-inline">
          <input type="hidden" name="print" value="<?php echo $_POST['id'] ?>" />
          <input name='printbutton' type='submit' value='Print Badge' class='btn btn-primary' />
        </form>
      </div>

      <div class="container col-lg-2">
        <form action="/prereg_pages/prereg_checkin.php" method="post" class="form-inline">
          <input name="oid" type="hidden" value="<?php echo $_POST["oid"]?>" />
          <input name='Done' type='submit' value='Done' class='btn btn-primary' />
        </form>

      </div>

    <? } else { ?>


    <form action="/prereg_pages/prereg_checkin.php" method="post" class="form-inline">
      <fieldset id="personalinfo">
        <legend>Attendee Info</legend>
        <label>Name: </label>
        <span class="input-xlarge uneditable-input">
          <?php echo $attendee->first_name; ?> <?php echo $attendee->last_name; ?>
        </span>
        <br />
        <label>Badge Name: </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->badge_name; ?></span>
        <br />
        <label>Zip : </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->zip; ?></span>
        <label>Country : </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->country; ?></span>
        <br />
        <label>E-Mail : </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->email; ?></span>
        <br />
        <label>Phone Number: </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->phone; ?></span>
        <br />
        <label>Birth Date: </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->getBirthDate(); ?></span>
      </fieldset>
      <br />
      <fieldset id="emergencyinfo">
        <legend>Emergency Contact Info</legend>
        <label>Full Name: </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->ec_fullname; ?></span>
        <br />
        <label>Phone Number: </label>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->ec_phone; ?></span>
        <br />
      </fieldset>

      <?php if ($attendee->isMinor()) { ?>
        <br />
        <fieldset id="parentinfo">
          <legend>Parent Contact Info</legend>
          <label>Full Name: </label>
          <span class="input-xlarge uneditable-input"><?php echo $attendee->parent_fullname; ?></span>
          <br />
          <label>Phone Number: </label>
          <span class="input-xlarge uneditable-input"><?php echo $attendee->parent_phone; ?></span>
          <br /><br />
          <input name="PCFormVer" type="checkbox" <?php if ($attendee->parent_form == "Yes") { echo "checked disabled"; } ?> 
                 id="Parent Contact Form Verification" class="form-control" required />
          <label for="Parent Contact Form Verification" class="control-label">Parental Consent Form Received</label>
        </fieldset>
      <?php } ?>

      <br />
      <fieldset id="paymentinfo">
        <legend>Pass Type</legend>
          <label>Pass Type:</label>
           <?echo $attendee->pass_type; ?> - $<?echo $attendee->paid_amount; ?><br>
      </fieldset>
      <fieldset id="notes">
        <legend>Notes</legend>
        <span class="input-xlarge uneditable-input"><?php echo $attendee->notes; ?></span>
      </fieldset>
      <fieldset id="checkin">
        <legend>Check In</legend>
        <?php if ($attendee->checked_in == "Yes") { ?>
          <span class='input-xlarge uneditable-input'>CHECKED IN</span>
        <? } else { ?>
          <label for="Information Verification" class="control-label">Information Verified</label>
          <input name='checkin' type='checkbox' id='Information Verification' class='form-control' required />
          <br />
          <input name="id" type="hidden" value="<?php echo $attendee->id ?>" />
          <input name="oid" type="hidden" value="<?php echo $attendee->order_id ?>" />
          <input name="Minor" type="hidden" value="<?php echo $attendee->isMinor(); ?>" />

          <input name='Update' type='submit' value='Check In' class='btn btn-primary' />

        <? } ?>

    </form>
    </fieldset>

    <? } ?>
  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
