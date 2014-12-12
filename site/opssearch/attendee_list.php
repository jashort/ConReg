<?php require('../Connections/kumo_conn.php'); ?>
<?php
require('../includes/authcheck.php');

if (isset($_GET['id'])) {
  $colname_rs_checkin_list = $_GET['id'];


if ($_GET['field'] == "bid") {
$query_rs_checkin_list = sprintf("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_bnumber = '%s'", mysql_real_escape_string($colname_rs_checkin_list));
}
elseif ($_GET['field'] == "ln") {
$query_rs_checkin_list = sprintf("SELECT * FROM kumo_reg_data WHERE kumo_reg_data_lname LIKE '%s'", mysql_real_escape_string($colname_rs_checkin_list));
}

mysql_select_db($database_kumo_conn, $kumo_conn);
$rs_checkin_list = mysql_query($query_rs_checkin_list, $kumo_conn) or die(mysql_error());
$row_rs_checkin_list = mysql_fetch_assoc($rs_checkin_list);
$totalRows_rs_checkin_list = mysql_num_rows($rs_checkin_list);
}
//echo $query_rs_checkin_list;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kumoricon Registration</title>
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" />
</script>
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
<script type="text/javascript">
function validateBID(){
    if(document.bid.id.value == "")
    {
        alert("A search value is needed!");
        return false;
    }
}
function validateLN(){
    if(document.ln.id.value == "")
    {
        alert("A search value is needed!");
        return false;
    }
}
</script>
</head>
<body>
<div id="header"></div> 
<div id="menu">
<!-- Menu Removed -->
<ul>
<li><a href="/opssearch/attendee_list.php">SEARCH ATTENDEE</a></li>
</ul>
</div> 
<div id="content">
<?php if (!isset($_GET["id"])) { // Show if no search term ?>
  <form name="bid" action="/opssearch/attendee_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Badge Number : </label><input name="id" type="text" class="input_20_200" />
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateBID();" />
      <input name="field" type="hidden" value="bid" /><br />
      <span class="bold_text">(DOESN'T WORK WITH PRE-REGISTRATIONS)</span>
    </fieldset>
  </form>
  <form name="ln" action="/opssearch/attendee_list.php" method="get" target="_self">
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
    <th scope="col">Badge Name</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="/opssearch/attendee_display.php?id=<?php echo $row_rs_checkin_list['kumo_reg_data_id']; ?>"><?php echo $row_rs_checkin_list['kumo_reg_data_fname'] . " "; ?><?php echo $row_rs_checkin_list['kumo_reg_data_lname']; ?></a></td>
      <td><?php echo $row_rs_checkin_list['kumo_reg_data_bnumber']; ?></td>
      <td><?php echo $row_rs_checkin_list['kumo_reg_data_bname']; ?></td>
    </tr>
    <?php } while ($row_rs_checkin_list = mysql_fetch_assoc($rs_checkin_list)); ?>
</table>
<?php } // Show if no search term ?>
</div>
<div id="footer">&copy; Tim Zuidema</div> 
</body>
</html>
<?php
mysql_free_result($rs_checkin_list);
?>
