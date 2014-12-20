<?php require('../Connections/kumo_conn.php'); ?>
<?php
require('../includes/authcheck.php');

if (isset($_GET['lname'])) {

	$stmt = $conn->prepare("SELECT kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bdate FROM kumo_reg_data WHERE kumo_reg_data_lname LIKE :lname");
    $stmt->execute(array('lname' => $_GET['lname']));

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
</script>
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if (!isset($_GET["lname"])) { // Show if no search term ?>
  <form name="ln" action="/reg_pages/reg_badge_reprint.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Last Name : </label><input name="lname" type="text" class="input_20_200" /><br />
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateLN();" />
      </fieldset>
  </form>
<?php } // Show if no search term ?>
<?php if (isset($_GET["lname"])) { // Show if search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Birth Date</th>
  </tr>
  <?php do {  

	$fname = $results['kumo_reg_data_fname'];
	$lname = $results['kumo_reg_data_lname'];
	$Birthdate = $results['kumo_reg_data_bdate'];

	if ($Birthdate != "") {
		
		$Birthdate_array = explode("-", $Birthdate);
		$BirthYear = $Birthdate_array[0];
		$BirthMonth = $Birthdate_array[1];
		$BirthDay = $Birthdate_array[2];

		$BDate = $BirthYear . "-" . $BirthMonth . "-" . $BirthDay;

		$date = new DateTime($BDate);
		$now = new DateTime();
		$interval = $now->diff($date);
		$year_diff = $interval->y;
	
	}
  ?>
    <tr>
      <td><a href="/reg_pages/badgereprint.php?fname=<?php echo $fname; ?>&lname=<?php echo $lname; ?>&year_diff=<?php echo $year_diff; ?>" target="_new"><?php echo $fname . " " . $lname; ?></a></td>
      <td><?php echo $Birthdate; ?></td>
    </tr>
    <?php } while ($results = $stmt->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php } // Show if search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_update_list);
?>
