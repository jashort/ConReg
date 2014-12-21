<?php
require('../Connections/kumo_conn.php');
require('../includes/authcheck.php');

require_right('registration_update');

if (isset($_GET['id'])) {
  $colname_rs_checkin_list = $_GET['id'];

if ($_GET['field'] == "ln") {
$query_rs_update_list = sprintf("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_lname LIKE '%s'", mysql_real_escape_string($colname_rs_checkin_list));
}

mysql_select_db($db_name, $kumo_conn);
//$query_rs_update_list = sprintf("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_bnumber = %s", mysql_real_escape_string($colname_rs_update_list));
$rs_update_list = mysql_query($query_rs_update_list, $kumo_conn) or die(mysql_error());
$row_rs_update_list = mysql_fetch_assoc($rs_update_list);
$totalRows_rs_update_list = mysql_num_rows($rs_update_list);
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
<?php if (!isset($_GET["id"])) { // Show if no search term ?>
  <form name="ln" action="/reg_pages/reg_update_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Last Name : </label><input name="id" type="text" class="input_20_200" /><br />
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateLN();" />
      <input name="field" type="hidden" value="ln" />
      </fieldset>
  </form>
<?php } // Show if no search term ?>
<?php if (isset($_GET["id"])) { // Show if no search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Badge Number</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="/reg_pages/reg_update.php?id=<?php echo $row_rs_update_list['kumo_reg_data_id']; ?>"><?php echo $row_rs_update_list['kumo_reg_data_fname'] . " "; ?><?php echo $row_rs_update_list['kumo_reg_data_lname']; ?></a></td>
      <td><?php echo $row_rs_update_list['kumo_reg_data_bnumber']; ?></td>
    </tr>
    <?php } while ($row_rs_update_list = mysql_fetch_assoc($rs_update_list)); ?>
</table>
<?php } // Show if no search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_update_list);
?>
