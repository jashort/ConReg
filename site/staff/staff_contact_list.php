<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('manage_staff');

$staffList = staffList();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../favicon.ico">

  <title>Update User</title>

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
    <h2>Staff Phone List</h2>

    <table id="list_table" class="table report">
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Phone Number</th>
      </tr>
      <?php while ($staff = $staffList->fetch()) { ?>
        <tr>
          <td><?php echo $staff->first_name ?> <?php echo $staff->last_name ?></td>
          <td><?php echo $staff->phone_number ?></td>
        </tr>
      <?php } ?>

    </table>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
