<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('manage_staff');

$staffList = staffList();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Kumoricon Registration</title>
  <link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Phone Number</th>
    </tr>
  <?php while ($staff = $staffList->fetch()) { ?>
    <tr>
      <td><?php echo $staff["first_name"] ?> <?php echo $staff["last_name"] ?></td>
      <td><?php echo $staff["phone_number"] ?></td>
    </tr>
  <?php } ?>

</table>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>