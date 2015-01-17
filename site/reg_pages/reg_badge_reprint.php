<?php
require_once('../Connections/kumo_conn.php');

require_once('../includes/authcheck.php');
require_right('badge_reprint');

if (isset($_GET['lname'])) {

	$stmt = $conn->prepare("SELECT id, first_name, last_name, birthdate FROM attendees WHERE last_name LIKE :lname");
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
      <label>Last Name : <input name="lname" type="text" class="input_20_200" /></label><br />
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
  <?php while($row = $stmt->fetch()) { ?>
    <tr>
      <td><a href="/reg_pages/badgereprint.php?print=<?php echo $row['id']; ?>" target="_new"><?php echo $row['first_name'] . " " . $row['last_name']; ?></a></td>
      <td><?php echo $row['birthdate'] ?></td>
    </tr>
    <?php } ?>
</table>
<?php } // Show if search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>

