<?php require('../Connections/kumo_conn.php'); ?>
<?php
require('../includes/authcheck.php');

if (isset($_GET['letter'])) {
  $colname_rs_verification_list = $_GET['letter'];

mysql_select_db($db_name, $kumo_conn);
$query_rs_verification_list = "SELECT * FROM kumo_reg_data WHERE kumo_reg_data_bnumber like '" .$colname_rs_verification_list . "%' order by kumo_reg_data_bnumber ASC";
$rs_verification_list = mysql_query($query_rs_verification_list, $kumo_conn) or die(mysql_error());
$row_rs_verification_list = mysql_fetch_assoc($rs_verification_list);
$totalRows_rs_verification_list = mysql_num_rows($rs_verification_list);
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
  <form name="bid" action="/reg_pages/reg_verification_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Badge  Letter : </label><input name="letter" type="text" class="input_20_200" />
      <input name="Submit" type="submit" class="submit_button" value="Search" />
      <span class="bold_text">(DOESN'T WORK WITH PRE-REGISTRATIONS)</span>
    </fieldset>
  </form>
<?php if (isset($_GET["letter"])) { // Show if no search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Badge Number</th>
    <th scope="col">Badge Name</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rs_verification_list['kumo_reg_data_fname'] . " "; ?><?php echo $row_rs_verification_list['kumo_reg_data_lname']; ?></a></td>
      <td><?php echo $row_rs_verification_list['kumo_reg_data_bnumber']; ?></td>
      <td><?php echo $row_rs_verification_list['kumo_reg_data_bname']; ?></td>
    </tr>
    <?php } while ($row_rs_verification_list = mysql_fetch_assoc($rs_verification_list)); ?>
</table>
<?php } // Show if no search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_verification_list);
?>
