<?php
require_once('../includes/classes.php');
require_once('../includes/functions.php');

$attendee = null;
if (isset($_POST["id"])) {
    $attendee = getAttendee($_POST['id']);
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

    <script type="text/javascript">
        // Hide the checkin button and show the finished button
        function printed() {
            $('#checkInButton').attr('class', 'hidden');
            $("#finishButton").attr('class', 'btn btn-lg btn-primary');
            return true;
        }
    </script>
</head>

<body>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron mascot tablet">
        <a href="index.php" class="btn btn-lg btn-danger" style="float:right;">Exit</a>
        <h1>Verify Information:</h1>
        <div class="row">
            <div class="col-sm-3">Name:</div>
            <div class="col-sm-6"><?php echo $attendee->first_name; ?> <?php echo $attendee->last_name; ?></div>
        </div>
        <div class="row">
            <div class="col-sm-3">Emergency Contact:</div>
            <div class="col-sm-6"><?php echo $attendee->ec_fullname ?> <?php echo $attendee->ec_phone ?></div>
        </div>

        <div class="row">
            <div class="col-sm-1">
                <a href="search.php?phone=<?php echo $attendee->phone?>" class="btn btn-lg btn-default">Back</a>
            </div>
            <div class="col-sm-1">
                <form action="badgeprint.php" method="post" class="form-horizontal" target="_blank">
                    <input type="hidden" name="print" value="<?php echo $attendee->id?>" />
                    <input type="submit" name="submit" class="btn btn-lg btn-primary" id="checkInButton"
                           value="Check In and Print Badge" onclick="return printed();" />
                </form>
                <a href="search.php?phone=<?php echo $attendee->phone?>" class="btn btn-lg btn-primary hidden"
                   id="finishButton">Finish</a>
            </div>
        </div>



    </div>

</div> <!-- /container -->


<?php require '../includes/template/scripts.php' ?>

</body>
</html>
