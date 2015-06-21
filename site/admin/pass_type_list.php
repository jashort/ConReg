<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('manage_pass_types');

$passTypeList = passTypeList();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">

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
    <h2>Pass Types</h2>

    <p>Note: editing pass types here will not change attendees who have already registered. For example,
    changing the cost of an adult weekend pass will not change records of people who have already
    pre-registered.</p>
    
    <table class="table-bordered table-striped col-md-6">
      <thead>
      <tr>
        <th class="text-center">Name</th>
        <th class="text-center">Age Range (Years)</th>
        <th class="text-center">Cost</th>
        <th class="text-center"><a href="pass_type_update.php">Add New</a></th>
      </tr>
      </thead>
      <?php while ($passType = $passTypeList->fetch()) { ?>
          <tr>
            <td>
            <?php if ($passType->visible != "Y") { ?>
              <i><?php echo $passType->name ?> (hidden)</i>
            <?php } else { ?>
              <?php echo $passType->name ?>
            <?php } ?>
            </td>
            <td class="text-center">
              <?php echo $passType->getAgeRange() ?>
            </td>
            <td class="text-right">$<?php echo $passType->cost ?></td>
            <td class="text-center"><a href="/admin/pass_type_update.php?id=<?php echo $passType->id ?>">Update</td>
          </tr>
      <?php } ?>

    </table>
  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
