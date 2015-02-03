<?php 
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');

if (isset($_POST["Reset"])) {
    passwordReset($_POST["username"],$_POST["password"]);
    logMessage($_POST['username'], 90, "Changed password");

    redirect("/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">

    <title>Reset Password</title>

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
        function verifyPassword(){
            document.status = true;
            if ((document.passchange.password.value != document.passchange.password2.value) || (document.passchange.password.value == "")) {
                alert("The password fields do not match or are blank.  Please retype them to make sure they are the same.");
                document.status = false;
            }}
    </script>
</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h2>Reset Password</h2>

        <form action="staff_password_reset.php" id="passchange" name="passchange" method="post" class="form">
            <fieldset id="list_table_search">
                <input name="username" type="hidden" value="<?php if(array_key_exists('username', $_GET)) { echo $_GET["username"]; } ?>" />
                <label for="password" class="control-label">New Password</label>
                <input id="password" name="password" type="password" class="form-control" required autocomplete="off" autofocus />
                <br />
                <label for="password2" class="control-label">Verify Password</label>
                <input id="password2" name="password2" type="password" class="form-control" required autocomplete="off" autofocus />
                <br />
                <input name="Reset" type="submit" class="btn btn-primary" value="Change"  onclick="verifyPassword();return document.status" />
            </fieldset>
        </form>


    </div>

    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
