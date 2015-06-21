<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('super-admin');

if ($_FILES && $_FILES['csv']['size'] > 0) {
	// load the csv file
	$file = $_FILES['csv']['tmp_name'];
	$handle = fopen($file, "r");
	$count = importPreRegCsvFile($handle, $_SESSION['staffid']);

	logMessage($_SESSION['username'], 110, "Imported  ". $count . " prereg attendees from CSV");

	//redirect
	header('Location: csvimport.php?complete=1&count=' . $count);
	die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico">

	<title>PreReg CSV Import</title>

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
		<h2>PreReg CSV Import</h2>

		<?php if (!empty($_GET['complete'])) { ?>

			<?php echo $_GET['count'] ?> lines imported. <a href="/">Continue</a><br>

		<?php } else { ?>
			<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1" class="form-inline">
				<p>Import Pre-registered Attendees. Note: Importing the same file multiple times may create duplicates.</p>

				<fieldset id="list_table_search">
					<div class="col-sm-3">
						<input name="csv" type="file" id="csv">
					</div>
					<input type="submit" name="Submit" value="Submit" class="btn btn-primary" />
				</fieldset>
			</form>

		<?php } ?>

	</div>

	<?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
