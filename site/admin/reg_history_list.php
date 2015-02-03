<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('super-admin');

$history = historyList(50);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Kumoricon Registration Change History</title>
<!-- InstanceEndEditable -->
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" /> 
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>
<div id="content"><!-- InstanceBeginEditable name="Content" -->
<table id="list_table">
  <tr>
    <th>Time</th>
    <th>Username</th>
    <th>Action</th>
    <th>Description</th>
  </tr>
  <?php while ($item = $history->fetch()) { ?>
    <tr>
      <td><?php echo $item["changed_at"] ?></td>
      <td><?php echo $item["username"] ?></td>
      <td><?php echo $item["type"] ?></td>
      <td><?php echo $item["description"] ?></td>
    </tr>
  <?php } ?>

</table>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
