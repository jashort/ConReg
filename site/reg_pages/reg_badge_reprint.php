<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('badge_reprint');

if (isset($_GET['search'])) {
  $attendees = attendeeSearch($_GET['search']);
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
  <link rel="icon" href="../favicon.ico">

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

    <?php if (!isset($_GET["search"])) { // Show if no search term ?>
      <form name="searchForm" action="/reg_pages/reg_badge_reprint.php" method="get" target="_self" class="form-inline">
        <fieldset id="list_table_search">
          <label for="search" class="control-label">Search</label>
          <input name="search" type="text" maxlength="60" autocomplete="off" autofocus required class="form-control" />
          <input name="Submit" type="submit" class="btn btn-primary" value="Search" /><br>
          <small>Searches for first name, last name, full name, or badge number.</small>
        </fieldset>
      </form>
    <?php } else { ?>
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
            <td><?php echo $attendee->getBirthDate(); ?> (<?php echo $attendee->getAge();?> years old)</td>
            <td><?php echo $attendee->checked_in; ?></td>
            <td><?php if ($attendee->checked_in == "Yes") { ?>
                <a class="btn btn-sm btn-primary"
                   href="/reg_pages/reg_update.php?id=<?php echo $attendee->id?>">Reprint Badge</a>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } ?>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
