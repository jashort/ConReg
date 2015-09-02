<?php
require_once('../includes/functions.php');
require_once('../includes/classes.php');

require_once('../includes/authcheck.php');
requireRight('attendee_search');

$attendee = new Attendee();
if (isset($_GET['id'])) {
  $attendee = getAttendee($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../../favicon.ico">

  <title>Attendee Search</title>

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
    <h2>View Attendee</h2>

    <fieldset id="personalinfo">
      <label>Name: </label>
      <span class="display_text">
        <?php echo $attendee->first_name; ?> <?php echo $attendee->last_name; ?></span>
      <br />
      <label>Badge Name: </label>
      <span class="display_text"><?php echo $attendee->badge_name; ?></span>
      <br />
      <label>Badge Number: </label>
      <span class="display_text"><?php echo $attendee->badge_number; ?></span>
      <br />
      <label>E-Mail : </label>
      <span class="display_text"><?php echo $attendee->email; ?></span>
      <br />
      <label>Phone Number: </label>
      <span class="display_text"><?php echo $attendee->phone; ?></span>
      <br />
      <label>Birth Date: </label>
      <span class="display_text"><?php echo $attendee->getBirthDate(); ?> (<?php echo $attendee->getAge();?> years old)</span>
    </fieldset><br />
    <fieldset id="emergencyinfo">
      <legend>Emergency Contact Info</legend>
      <label>Full Name: </label>
      <span class="display_text"><?php echo $attendee->ec_fullname; ?></span>
      <br />
      <label>Phone Number: </label>
      <span class="display_text"><?php echo $attendee->ec_phone; ?></span>
      <br />
    </fieldset>
    <?php if ($attendee->isMinor()) { ?>
      <fieldset id="parentinfo">
        <legend>Parent Contact Info</legend>
        <label>Full Name: </label>
        <span class="display_text"><?php echo $attendee->parent_fullname; ?></span>
        <br />
        <label>Phone Number: </label>
        <span class="display_text"><?php echo $attendee->parent_phone; ?></span>
        <br />
        <label>Parental Permission Form Submitted: </label>
        <span class="display_text"><?php echo $attendee->parent_form; ?> </span>
      </fieldset>
    <?php } ?>
    <fieldset id="passtype">
      <label>Pass Type</label>
      <span class="display_text"><?php echo $attendee->pass_type; ?> - $<?php echo $attendee->paid_amount; ?></span>
    </fieldset>
    <fieldset id="paymentinfo">
      <label>Paid</label>
    <span class="display_text"><?php echo $attendee->paid; ?>
    </fieldset>
    <fieldset id="checkedin">
      <label>Checked In</label>
    <span class="display_text"><?php echo $attendee->checked_in; ?>
    </fieldset>

    
  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
