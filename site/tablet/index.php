<?php
require_once('../includes/classes.php');
require_once('../includes/functions.php');
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
        <h2>Welcome to Kumoricon!</h2>

        <form action="search.php" method="get">
            <p>Search for your registration by:</p>
            <div class="row">
                <label for="PhoneNumber" class="control-label col-sm-2 input-lg">Phone Number:</label>
                <div class="col-sm-3">
                    <input name="phone" type="text" maxlength="60" class="form-control input-lg"
                           id="PhoneNumber" required autocomplete="off" autofocus />
                </div>
                <div class="col-sm-2 btn-group-lg">
                    <input type="submit" value="Search" class="btn btn-primary"/>
                    <input type="reset" class="btn btn-danger"/>
                </div>
            </div>

        </form>
    </div>

</div> <!-- /container -->


<?php require '../includes/template/scripts.php' ?>

</body>
</html>
