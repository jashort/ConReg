<?php
require_once('../Connections/kumo_conn.php');

require_once('../includes/authcheck.php');
require_right('super-admin');

mysql_select_db($db_name, $kumo_conn);
$query_rsAdminList = "SELECT * FROM kumo_reg_admin ORDER BY kumo_reg_admin_timestamp DESC";
$rsAdminList = mysql_query($query_rsAdminList, $kumo_conn) or die(mysql_error());
$row_rsAdminList = mysql_fetch_assoc($rsAdminList);
$totalRows_rsAdminList = mysql_num_rows($rsAdminList);

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
<table id="list_table">
  <tr>
    <th scope="col">Alerts</th>
    </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsAdminList['kumo_reg_admin_agent'] . " - " . $row_rsAdminList['kumo_reg_admin_timestamp'] . " - " . $row_rsAdminList['kumo_reg_admin_text']; ?></td>
      </tr>
    <?php } while ($row_rsAdminList = mysql_fetch_assoc($rsAdminList)); ?>
</table>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_update_list);
?>
