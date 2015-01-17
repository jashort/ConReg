<?php
require_once('../Connections/kumo_conn.php');

require_once('../includes/authcheck.php');
require_right('manage_staff');

$stmt = $conn->prepare("SELECT * FROM reg_staff ORDER BY first_name ASC");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <?php foreach ($results as $result) { ?>
    <tr>
      <td><?php echo $result['first_name'] . " " . $result['last_name']; ?></td>
      <td><?php echo $result['phone_number']; ?></td>
      </tr>
    <?php } ?>
</table>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>