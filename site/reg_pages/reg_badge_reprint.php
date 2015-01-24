<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
require_right('badge_reprint');

if (isset($_GET['lname'])) {
  $attendees = attendeeSearchLastName($_GET['lname']);
} else {
  $attendees = array();
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
      <input name="Submit" type="submit" class="submit_button" value="Search" />
      </fieldset>
  </form>
<?php } elseif (isset($_GET["lname"])) { // Show if search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Birth Date</th>
    <th scope="col">Checked In</th>
    <th></th>
  </tr>
  <?php while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) { ?>
    <tr>
      <td><?php echo $attendee->first_name . " " . $attendee->last_name ?></td>
      <td><?php echo $attendee->getBirthDate(); ?></td>
      <td><?php echo $attendee->checked_in; ?></td>
      <td><?php if ($attendee->checked_in == "Yes") { ?>
        <form action="badgereprint.php" method="post" target="_blank">
          <input type="hidden" name="print" value="<?php echo $attendee->id?>" />
          <input type="submit" id="print<?php echo $attendee->id?>" value="Reprint Badge">
        </form>
        <? } ?>
      </td>
    </tr>
  <?php } ?>
</table>
<?php } // Show if search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>

