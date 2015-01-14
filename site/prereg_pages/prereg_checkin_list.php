<?php
require('../Connections/kumo_conn.php');
require('../includes/authcheck.php');
require('../includes/functions.php');
require_right('prereg_checkin');


if (isset($_GET['id']) && isset($_GET['field'])) {
  $attendees = preRegSearch($_GET['id'], $_GET['field']);
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
  <?php if (!isset($_GET["id"])) { // Show if no search term ?>
    <form name="ln" action="/prereg_pages/prereg_checkin_list.php" method="get" target="_self">
      <fieldset id="list_table_search">
        <label>Last Name : <input name="id" type="text" class="input_20_200" /></label><br />
        <input name="Submit" type="submit" class="submit_button" value="Search" />
        <input name="field" type="hidden" value="ln" />
      </fieldset>
    </form>
    <form name="fn" action="/prereg_pages/prereg_checkin_list.php" method="get" target="_self">
      <fieldset id="list_table_search">
        <label>First Name : </label>
        <input name="id" type="text" class="input_20_200" /><br />
        <input name="Submit" type="submit" class="submit_button" value="Search" />
        <input name="field" type="hidden" value="fn" />
      </fieldset>
    </form>
  <?php } else {  // There is a search term, display results ?>
    <table id="list_table">
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Badge Name</th>
        <th scope="col">Order</th>
        <th scope="col">Checked In</th>
      </tr>
      <?php
        $lastOrder = -1;
        foreach ($attendees as $attendee) { 
          if ($attendee['kumo_reg_data_orderid'] == $lastOrder) {
            $rowClass = '';
          } else {
            $rowClass = 'spacer_row';
            $lastOrder = $attendee['kumo_reg_data_orderid'];
          }
      ?>
        <tr>
          <td class="<?php echo $rowClass ?>"><a href="/prereg_pages/prereg_checkin.php?id=<?php echo $attendee['kumo_reg_data_id']; ?>"><?php echo $attendee['kumo_reg_data_fname'] . " " . $attendee['kumo_reg_data_lname']; ?></a></td>
          <td class="<?php echo $rowClass ?>"><?php echo $attendee['kumo_reg_data_bname']; ?></td>
          <td class="<?php echo $rowClass ?>"><?php echo $attendee['kumo_reg_data_orderid']; ?></td>
          <td class="<?php echo $rowClass ?>"><?php echo $attendee['kumo_reg_data_checkedin']; ?></td>
        </tr>
      <?php 
        } 
      ?>
    </table>
  <?php } ?>
  <!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div>
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
