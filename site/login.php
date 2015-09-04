<?php
require_once('includes/functions.php');
require_once('includes/roles.php');

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}
if (isset($_POST['username'])) {
	validateUser($_POST['username'], $_POST['password']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico">

	<title>Login</title>

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

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Kumoricon</a>
		</div>
		</div><!--/.nav-collapse -->
	</div>
</nav>

<div class="container">

	<!-- Main component for a primary marketing message or call to action -->
	<div class="jumbotron mascot">

		<form action="login.php" name="login" method="POST" class="form-horizontal">
			<div class="form-group">
				<label for="username" class="control-label col-xs-2">Username</label>
				<div class="col-xs-4">
					<input type="text" class="form-control" id="username" name="username" 
						   placeholder="Username" autofocus="autofocus" autocomplete="off" required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="control-label col-xs-2">Password</label>
				<div class="col-xs-4">
					<input type="password" class="form-control" id="password" name="password" 
						   placeholder="Password" required="required">
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-offset-2 col-xs-10">
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</div>
		</form>
	</div>


	<?php require 'includes/template/footer.php' ?>

</div> <!-- /container -->


<?php require 'includes/template/scripts.php' ?>

</body>
</html>

