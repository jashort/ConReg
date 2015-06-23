<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('super-admin');

if (array_key_exists('type', $_GET)) {
  $history = historyList(50, $_GET['type']);
} else {
  $history = historyList(50);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../favicon.ico">

  <title>History</title>

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
    <h2>History</h2>
    <a href="reg_history_list.php?type=login">Logins</a> -
    <a href="reg_history_list.php?type=prereg%20checkin">PreReg Checkin</a> -
    <a href="reg_history_list.php?type=atcon%20checkin">At Con Checkin</a> -
    <a href="reg_history_list.php">All</a>
    <table id="list_table" class="table report">
      <tr>
        <th>Time</th>
        <th>Username</th>
        <th>Action</th>
        <th>Description</th>
      </tr>
      <?php while ($item = $history->fetch()) { ?>
        <tr>
          <td><?php echo $item["changed_at"] ?></td>
          <td><?php echo $item["username"] ?></td>
          <td><?php echo $item["type"] ?></td>
          <td><?php echo $item["description"] ?></td>
        </tr>
      <?php } ?>

    </table>


  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
