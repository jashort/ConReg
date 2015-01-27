<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('super-admin');

if ($_FILES && $_FILES['csv']['size'] > 0) {
	// load the csv file
	$file = $_FILES['csv']['tmp_name'];
	$handle = fopen($file, "r");
	$count = importPreRegCsvFile($handle, $_SESSION['staffid']);

	logMessage($_SESSION['username'], "Imported  ". $count . " prereg attendees from CSV");

	//redirect
	header('Location: csvimport.php?complete=1&count=' . $count);
	die();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Kumoricon Registration</title>
<!-- InstanceEndEditable -->
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->

<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<div>
<?php if (!empty($_GET['complete'])) { ?>

	<? echo $_GET['count'] ?> lines imported. <a href="/">Continue</a><br>

<? } else { ?>
	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
		<p>Import Pre-registered Attendees. Note: Importing the same file multiple times may create duplicates.</p>
		Choose CSV file: <br />
		<input name="csv" type="file" id="csv" />
		<input type="submit" name="Submit" value="Submit" />
	</form>

<? }  ?>
</div>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
