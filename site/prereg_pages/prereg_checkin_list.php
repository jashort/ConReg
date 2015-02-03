<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('prereg_checkin');


if (isset($_GET['id']) && isset($_GET['field'])) {
  $attendees = preRegSearch($_GET['id'], $_GET['field']);
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
    <?php if (!isset($_GET["id"])) { // Show if no search term ?>
      <form name="ln" action="/prereg_pages/prereg_checkin_list.php" method="get" target="_self" class="form-inline">
        <fieldset id="list_table_search">
          <label for="id" class="control-label">Last Name</label>
          <input name="id" type="text" class="form-control" autocomplete="off" maxlength="60" placeholder="Last Name" autofocus />
          <input name="Submit" type="submit" class="btn btn-primary" value="Search" />
          <input name="field" type="hidden" value="ln" />
        </fieldset>
      </form>
    <?php } else {  // There is a search term, display results ?>
      <table id="list_table" class="table">
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Badge Name</th>
          <th scope="col">Order</th>
          <th scope="col">Checked In</th>
        </tr>
        <?php
        $lastOrder = -1;
        foreach ($attendees as $attendee) {
          if ($attendee->order_id == $lastOrder) {
            $rowClass = '';
          } else {
            $rowClass = 'spacer_row';
            $lastOrder = $attendee->order_id;
          }
          ?>
          <tr>
            <td class="<?php echo $rowClass ?>">
              <a href="/prereg_pages/prereg_checkin.php?id=<?php echo $attendee->id; ?>">
                <?php echo $attendee->first_name . " " . $attendee->last_name; ?></a>
            </td>
            <td class="<?php echo $rowClass ?>"><?php echo $attendee->badge_name; ?></td>
            <td class="<?php echo $rowClass ?>"><?php echo $attendee->order_id; ?></td>
            <td class="<?php echo $rowClass ?>"><?php echo $attendee->checked_in; ?></td>
          </tr>
        <?php
        }
        ?>
      </table>
    <?php } ?>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
