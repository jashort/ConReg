<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('attendee_search');

if (isset($_GET['id'])) {
    if ($_GET['field'] == "bid") {
        $attendees = attendeeSearchBadgeNumber($_GET['id']);
    } else {
        $attendees = attendeeSearchLastName($_GET['id']);
    }
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

    <?php while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) { ?>
        <tr>
            <td><a href="/admin/admin_attendee_display.php?id=<?php echo $attendee->id ?>" ><?php echo $attendee->first_name . " " . $attendee->last_name ?></a></td>
            <td><?php echo $attendee->badge_number; ?></td>
            <td><?php echo $attendee->badge_name; ?></td>
        </tr>
    <?php } ?>
</table>
<?php } ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
