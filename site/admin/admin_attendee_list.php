<?php
require_once('../Connections/kumo_conn.php');

require_once('../includes/authcheck.php');
require_right('attendee_search');

if (isset($_GET['id'])) {
  $colname_rs_checkin_list = $_GET['id'];


if ($_GET['field'] == "bid") {
$query_rs_checkin_list = sprintf("SELECT * FROM attendees WHERE badge_number = '%s'", mysql_real_escape_string($colname_rs_checkin_list));
}
elseif ($_GET['field'] == "ln") {
$query_rs_checkin_list = sprintf("SELECT * FROM attendees WHERE last_name LIKE '%s'", mysql_real_escape_string($colname_rs_checkin_list));
}

mysql_select_db($db_name, $kumo_conn);
$rs_checkin_list = mysql_query($query_rs_checkin_list, $kumo_conn) or die(mysql_error());
$row_rs_checkin_list = mysql_fetch_assoc($rs_checkin_list);
$totalRows_rs_checkin_list = mysql_num_rows($rs_checkin_list);
}
//echo $query_rs_checkin_list;
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
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if (!isset($_GET["id"])) { // Show if no search term ?>
  <form name="bid" action="/admin/admin_attendee_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Badge Number: <input name="id" type="text" class="input_20_200" /></label>
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateBID();" />
      <input name="field" type="hidden" value="bid" /><br />
      <span class="bold_text">(DOESN'T WORK WITH PRE-REGISTRATIONS)</span>
    </fieldset>
  </form>
  <form name="ln" action="/admin/admin_attendee_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Last Name : <input name="id" type="text" class="input_20_200" /></label><br />
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateLN();" />
      <input name="field" type="hidden" value="ln" />
      </fieldset>
  </form>
<?php } else { // Show if search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Badge Number</th>
    <th scope="col">Badge Name</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="/admin/admin_attendee_display.php?id=<?php echo $row_rs_checkin_list['id']; ?>"><?php echo $row_rs_checkin_list['first_name'] . " "; ?><?php echo $row_rs_checkin_list['last_name']; ?></a></td>
      <td><?php echo $row_rs_checkin_list['badge_number']; ?></td>
      <td><?php echo $row_rs_checkin_list['badge_name']; ?></td>
    </tr>
    <?php } while ($row_rs_checkin_list = mysql_fetch_assoc($rs_checkin_list)); ?>
</table>
<?php
    mysql_free_result($rs_checkin_list);
} ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
