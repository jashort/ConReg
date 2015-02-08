<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('report_view');

$regByDay = registrationsByDay();
$regStats = registrationStatistics();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">

  <title>Reports</title>

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
    <h2>Reports</h2>
    <table id="list_table" class="table report">
      <tr>
        <th colspan="2" class="display_text">PreRegistrations</th>
      </tr>
      <tr>
        <td>Number of Pre-Registrations Checked In:</td>
        <td><?php echo $regStats['preregcheckedincount']; ?></td>
      </tr>
      <tr>
        <td>Number of Pre-Registrations <u>NOT</u> Checked In:</td>
        <td><?php echo $regStats['preregnotcheckedincount']; ?></td>
      </tr>
      <tr>
        <th colspan="2">Registrations</th>
      </tr>
      <?php while ($reg = $regByDay->fetch()) { ?>
        <tr>
          <td>Number of registrations on <?php echo $reg["DAYNAME"] ?> (<?php echo $reg["DATE"] ?>):</td>
          <td>
            <?php echo $reg["DAYCOUNT"] ?>
            <?php if (hasRight('report_view_revenue')) { ?>
            ($<?php echo $reg["DAYTOTAL"] ?>)
          <?php } ?>
          </td>
        </tr>
      <?php } ?>

      <tr>
        <td>Registrations In The Last Hour</td>
        <td><?php echo $regStats['reginlasthour']; ?></td>
      </tr>
      <tr>
        <th colspan="2">Pass Types</th>
      </tr>
      <tr>
        <td>Weekend/VIP:</td>
        <td><?php echo $regStats['passtypeweekend']; ?></td>
      </tr>
      <tr>
        <td>Friday:</td>
        <td><?php echo $regStats['passtypefriday']; ?></td>
      </tr>
      <tr>
        <td>Saturday:</td>
        <td><?php echo $regStats['passtypesaturday']; ?></td>
      </tr>
      <tr>
        <td>Sunday:</td>
        <td><?php echo $regStats['passtypesunday']; ?></td>
      </tr>
      <tr>
        <td>Monday:</td>
        <td><?php echo $regStats['passtypemonday']; ?></td>
      </tr>
      <tr>
        <th colspan="2">Totals</th>
      </tr>
      <tr>
        <td>Number of Pre-Registrations Checked In:</td>
        <td><?php echo $regStats['preregcheckedincount']; ?></td>
      </tr>
      <tr>
        <td>Number of At-Con Registrations:</td>
        <td><?php echo $regStats['regtotal']; ?></td>
      </tr>
      <tr>
        <td>Grand Total</td>
        <td>
          <?php echo $regStats['checkedintotal']; ?>
          <?php if (hasRight('report_view_revenue')) { ?>
            ($<?php echo $regStats['sumregtotal']; ?>)
          <?php } ?>
        </td>
      </tr>
    </table>
    </div>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
