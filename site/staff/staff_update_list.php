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
    <h2>Update User</h2>

    <form action="/staff/staff_update.php" method="get" target="_self" class="form-inline">
      <fieldset id="list_table_search">
        <label for="staff_id">Staff Username</label>
          <select name="staff_id" id="staff_id"  class="form-control" >
            <?php while ($staff = $staffList->fetch()) { ?>
              <option value="<?php echo $staff->staff_id ?>">
                <?php echo $staff->first_name ?> <?php echo $staff->last_name ?>
                (<?php echo $staff->username ?>)</option>
            <?php } ?>
          </select>
        <input name="Submit" type="submit" class="btn btn-primary" value="Go" />
      </fieldset>
    </form>


  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
