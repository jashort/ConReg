<?php
require_once('includes/classes.php');
require_once('includes/authcheck.php');
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
        <br />
        <p>Registration options for Kumoricon 2015:</p>
        <br />
        <ul>
            <li>Pre-register online, by mail, or at one of our promotional events. All pre-registrations cover the entire weekend:
                <ul>
                    <li>$45 – through Jan. 5, 2015</li>
                    <li>$50 – through Apr. 3, 2015</li>
                    <li>$55 – through July 10, 2015</li>
                    <li>$57 – through Aug. 14, 2015</li>
                </ul>
            </li>
            <li>Register at the door:
                <ul>
                    <li>Prices for age 13 and up:
                        <ul>
                            <li>$60 – Full weekend</li>
                            <li>$30 – Friday only</li>
                            <li>$40 – Saturday only</li>
                            <li>$40 – Sunday only</li>
                            <li>$30 – Monday only</li>
                        </ul>
                    </li>
                    <li>Prices for age 6-12:
                        <ul>
                            <li>$45 - Full weekend (Same cost pre-registered or at the door)</li>
                            <li>$20 – Friday only</li>
                            <li>$30 – Saturday only</li>
                            <li>$30 – Sunday only</li>
                            <li>$20 – Monday only</li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>Free – Children age 5 and under</li>
        </ul>
    </div>

    <?php require 'includes/template/footer.php' ?>

</div> <!-- /container -->


<?php require 'includes/template/scripts.php' ?>

</body>
</html>
