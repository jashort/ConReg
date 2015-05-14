<?php
require_once('includes/classes.php');
require_once('includes/authcheck.php');
require_once('includes/functions.php');

$passTypeList = passTypeVisibleList();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">

    <title>Kumoricon Registration</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/navbar-fixed-top.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/dist/js/html5shiv-3.7.2.min.js"></script>
    <script src="assets/dist/js/respond-1.4.2.min.js"></script>
    <![endif]-->
</head>

<body>

<?php require 'includes/template/navigationBar.php'; ?>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h2>Registration Prices and Options</h2>

        <table class="table-bordered table-striped col-sm-7">
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Age Range (Years)</th>
                <th class="text-center">Cost</th>
            </tr>
            <?php while ($passType = $passTypeList->fetch()) { ?>
                <tr>
                    <td>
                        <? if ($passType->visible != "Y") { ?>
                            <i><?php echo $passType->name ?> (hidden)</i>
                        <? } else { ?>
                            <?php echo $passType->name ?>
                        <? } ?>
                    </td>
                    <td class="text-center">
                        <?php echo $passType->getAgeRange(); ?>
                    </td>
                    <td class="text-right">$<?php echo $passType->cost ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>

    <?php require 'includes/template/footer.php' ?>

</div> <!-- /container -->


<?php require 'includes/template/scripts.php' ?>

</body>
</html>
