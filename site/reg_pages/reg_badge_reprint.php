<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('badge_reprint');

if (isset($_GET['lname'])) {
  $attendees = attendeeSearchLastName($_GET['lname']);
} else {
  $attendees = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">

  <title>Reprint Badge</title>

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
    <h2>Reprint Badge</h2>

    <?php if (!isset($_GET["lname"])) { // Show if no search term ?>
      <form name="ln" action="/reg_pages/reg_badge_reprint.php" method="get" target="_self" class="form-inline">
        <fieldset id="list_table_search">
          <label for="id" class="control-label">Last Name</label>
          <input name="lname" type="text" class="form-control" required maxlength="60" autofocus autocomplete="off"/>
          <input name="Submit" type="submit" class="btn btn-primary" value="Search" />
        </fieldset>
      </form>
    <?php } elseif (isset($_GET["lname"])) { // Show if search term ?>
      <table id="list_table" class="table">
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Birth Date</th>
          <th scope="col">Checked In</th>
          <th></th>
        </tr>
        <?php while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) { ?>
          <tr>
            <td><?php echo $attendee->first_name . " " . $attendee->last_name ?></td>
            <td><?php echo $attendee->getBirthDate(); ?></td>
            <td><?php echo $attendee->checked_in; ?></td>
            <td><?php if ($attendee->checked_in == "Yes") { ?>
                <form action="badgereprint.php" method="post" target="_blank">
                  <input type="hidden" name="print" value="<?php echo $attendee->id?>" />
                  <input type="submit" id="print<?php echo $attendee->id?>" value="Reprint Badge" class="btn btn-sm">
                </form>
              <? } ?>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } // Show if search term ?>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
