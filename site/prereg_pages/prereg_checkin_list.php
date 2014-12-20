<?php
require('../Connections/kumo_conn.php');
require('../includes/authcheck.php');

if (isset($_GET['id'])) {
  $colname_rs_checkin_list = $_GET['id'];


  if ($_GET['field'] == "bid") {
    $query_rs_checkin_list = sprintf("SELECT kumo_reg_data_id, kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bname, kumo_reg_data_checkedin FROM kumo_reg_data WHERE kumo_reg_data_bnumber = '%s'", mysql_real_escape_string($colname_rs_checkin_list));
  } elseif ($_GET['field'] == "ln") {
    $query_rs_checkin_list = sprintf("SELECT kumo_reg_data_id, kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bname, kumo_reg_data_checkedin FROM kumo_reg_data WHERE kumo_reg_data_lname LIKE '%s'", mysql_real_escape_string($colname_rs_checkin_list));
  } elseif ($_GET['field'] == "fn") {
    $query_rs_checkin_list = sprintf("SELECT kumo_reg_data_id, kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bname, kumo_reg_data_checkedin FROM kumo_reg_data WHERE kumo_reg_data_fname LIKE '%s'", mysql_real_escape_string($colname_rs_checkin_list));
  }

  mysql_select_db($db_name, $kumo_conn);
  $rs_checkin_list = mysql_query($query_rs_checkin_list, $kumo_conn) or die(mysql_error());
  $row_rs_checkin_list = mysql_fetch_assoc($rs_checkin_list);
  $totalRows_rs_checkin_list = mysql_num_rows($rs_checkin_list);

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
<div id="menu">
  <ul>
    <li><a href="/index.php">HOME</a></li>
  </ul>
  <?php if ($_SESSION['access']==0) { ?>
    <ul>
      <li class="header_li">Ops</li>
      <li><a href="/opssearch/attendee_list.php">SEARCH</a></li>
    </ul>
  <?php } ?>
  <?php if ($_SESSION['access']!=0) { ?>
    <ul>
      <li class="header_li">PRE-REGISTRATION</li>
      <li><a href="/prereg_pages/prereg_checkin_list.php">CHECK IN</a></li>
    </ul>
    <ul>
      <li class="header_li">REGISTRATION</li>
      <li><a href="/reg_pages/reg_add.php">NEW</a></li>
      <!--<li><a href="/reg_pages/reg_tablet_complete_list.php">TABLET</a></li>-->
      <?php if ($_SESSION['access']>=2) { ?>
        <li><a href="/reg_pages/reg_update_list.php">UPDATE</a></li>
      <?php } ?>
      <?php if ($_SESSION['access']>=3) { ?>
        <li><a href="/reg_pages/reg_badge_reprint.php">REPRINT BADGE</a></li>
      <?php } ?>
      <!--<li><a href="/reg_pages/reg_quick_add.php">QUICK REG</a></li>
      <li><a href="/reg_pages/reg_quick_complete_list.php">QUICK REG COMPLETE</a></li>-->
    </ul>
    <?php if ($_SESSION['access']>=3) { ?>
      <ul>
        <li class="header_li">USER ADMIN</li>
        <li><a href="/staff/staff_add.php">ADD REGISTRATION USER</a></li>
        <li><a href="/staff/staff_update_list.php">UPDATE REGISTRATION USER</a></li>
        <li><a href="/staff/staff_contact_list.php">STAFF PHONE LIST</a></li>
      </ul>
    <?php } ?>
    <ul>
      <?php if ($_SESSION['access']>=3) { ?>
        <li class="header_li">KUMORICON ADMIN</li>
        <li><a href="/admin/admin_attendee_list.php">SEARCH ATTENDEE</a></li>
      <?php } ?>
      <?php if ($_SESSION['access']>=4) { ?>
        <li><a href="/admin/csvimport.php">IMPORT CSV</a></li>
        <li><a href="/admin/admin_report.php">REPORTS</a></li>
      <?php } ?>
    </ul>
  <?php } ?>
  <ul>
    <li class="header_li"><a href="/logout.php">Logout</a></li>
  </ul>
</div>
<div id="content"><!-- InstanceBeginEditable name="Content" -->
  <?php if (!isset($_GET["id"])) { // Show if no search term ?>
    <form name="ln" action="/prereg_pages/prereg_checkin_list.php" method="get" target="_self">
      <fieldset id="list_table_search">
        <label>Last Name : </label><input name="id" type="text" class="input_20_200" /><br />
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
        <th scope="col">Checked In</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="/prereg_pages/prereg_checkin.php?id=<?php echo $row_rs_checkin_list['kumo_reg_data_id']; ?>"><?php echo $row_rs_checkin_list['kumo_reg_data_fname'] . " " . $row_rs_checkin_list['kumo_reg_data_lname']; ?></a></td>
          <td><?php echo $row_rs_checkin_list['kumo_reg_data_bname']; ?></td>
          <td><?php echo $row_rs_checkin_list['kumo_reg_data_checkedin']; ?></td>
        </tr>
      <?php
        } while ($row_rs_checkin_list = mysql_fetch_assoc($rs_checkin_list));
      ?>
    </table>
  <?php
    mysql_free_result($rs_checkin_list);
  }
  ?>
  <!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div>
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
