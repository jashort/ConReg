<?php 
require_once('../Connections/kumo_conn.php');
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
require_right('badge_reprint');

if (isset($_GET['id'])) {
  $colname_rs_checkin_list = $_GET['id'];

  if ($_GET['field'] == "bid") {
    $attendees = attendeeSearchBadgeNumber($_GET['id']);
  } elseif ($_GET['field'] == "ln") {
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
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<?php if (!isset($_GET["id"])) { // Show if no search term ?>
  <form name="bid" action="/reg_pages/admin_badgereprint_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Badge Number : <input name="id" type="text" class="input_20_200" /></label>
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateBID();" />
      <input name="field" type="hidden" value="bid" />
      </fieldset>
  </form>
  <form name="ln" action="/reg_pages/admin_badgereprint_list.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Last Name : <input name="id" type="text" class="input_20_200" /></label><br />
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
  <?php while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) { ?>
    <tr>
      <td><a href="#" onclick="MM_openBrWindow('../reg_pages/badgereprint.php?print=<?php echo $attendee->id; ?>','','width=700,height=500')" ><?php echo $attendee->first_name . " " . $attendee->last_name; ?></a></td>
      <td><?php echo $attendee->badge_number; ?></td>
      <td><?php echo $attendee->badge_name; ?></td>
    </tr>
    <?php } ?>
</table>
<?php } // Show if no search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
