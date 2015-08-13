<?php
require_once('../includes/classes.php');
require_once('../includes/functions.php');

$attendees = array();
if (isset($_GET["phone"])) {

    $attendees = preRegPhoneSearch($_GET['phone']);
} else {
    redirect("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../favicon.ico">

    <title>Kumoricon Registration</title>

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

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron mascot tablet">
        <a href="index.php" class="btn btn-lg btn-danger" style="float:right;">Exit</a>
        <p>Registrations found:</p>
        <table id="list_table" class="table">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Badge Name</th>
                <th scope="col"></th>
            </tr>
            <?php
            foreach ($attendees as $attendee) {
            ?>
            <tr>
                <td><?php echo $attendee->first_name?> <?php echo $attendee->last_name?></td>
                <td><?php echo $attendee->badge_name?></td>
                <td>
                    <?php if ($attendee->checked_in == 'Yes') { ?>
                        Checked In
                    <?php } else { ?>
                        <form action="verify.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $attendee->id ?>"/>
                            <input type="submit" value="Check In" class="btn btn-primary btn-lg"/>
                        </form>
                    <?php } ?>
                </td>
            </tr>
            <? } ?>
        </table>



    </div>

</div> <!-- /container -->


<?php require '../includes/template/scripts.php' ?>

</body>
</html>
