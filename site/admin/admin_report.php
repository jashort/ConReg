<?php require('../includes/functions.php'); ?>
<?php
require('../includes/authcheck.php');

mysql_select_db($database_kumo_conn, $kumo_conn);
$query_rs_reports = "select distinct (select count(*) from kumo_reg_data where kumo_reg_data_checkedin = 'yes' AND kumo_reg_data_regtype='prereg') AS preregcheckedincount, (select count(*) from kumo_reg_data where kumo_reg_data_checkedin = 'no' AND kumo_reg_data_regtype='prereg') AS preregnotcheckedincount,(select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-08-29%') AS countregon829, (select sum(kumo_reg_data_paidamount) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-08-29%') AS sumregon829,(select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-08-30%') AS countregon830, (select sum(kumo_reg_data_paidamount) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-08-30%') AS sumregon830,(select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-08-31%') AS countregon831, (select sum(kumo_reg_data_paidamount) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-08-31%') AS sumregon831, (select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-09-01%') AS countregon91, (select sum(kumo_reg_data_paidamount) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-09-01%') AS sumregon91, (select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-09-02%') AS countregon92, (select sum(kumo_reg_data_paidamount) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-09-02%') AS sumregon92, (select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp like '2014-09-03%') AS countregon93, (select count(*) from kumo_reg_quick_data where kumo_reg_quick_data_timestamp like '2014-08-31%') AS countquickregon831, (select count(*) from kumo_reg_quick_data where kumo_reg_quick_data_timestamp like '2014-09-01%') AS countquickregon91, (select count(*) from kumo_reg_quick_data where kumo_reg_quick_data_timestamp like '2014-09-02%') AS countquickregon92, (select count(*) from kumo_reg_data where kumo_reg_data_regtype = 'reg' AND kumo_reg_data_timestamp > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)) AS reginlasthour, (select count(kumo_reg_data_bnumber) from kumo_reg_data where kumo_reg_data_passtype = 'Weekend') AS passtypeweekend, (select count(kumo_reg_data_bnumber) from kumo_reg_data where kumo_reg_data_passtype = 'Friday') AS passtypefriday,(select count(kumo_reg_data_bnumber) from kumo_reg_data where kumo_reg_data_passtype = 'Saturday') AS passtypesaturday, (select count(kumo_reg_data_bnumber) from kumo_reg_data where kumo_reg_data_passtype = 'Sunday') AS passtypesunday, (select count(kumo_reg_data_bnumber) from kumo_reg_data where kumo_reg_data_passtype = 'Monday') AS passtypemonday, (SELECT count(*) FROM kumo_reg_data WHERE kumo_reg_data_bnumber IN (SELECT kumo_reg_quick_data_bnumber FROM kumo_reg_quick_data)) AS quickentered, (select count(*) from kumo_reg_quick_data where kumo_reg_quick_data_timestamp > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)) AS quickreginlasthour, (select count(*) from kumo_reg_data where kumo_reg_data_regtype like 'reg') AS regtotal,(select count(*) from kumo_reg_data where kumo_reg_data_checkedin = 'Yes') AS checkedintotal, (select sum(kumo_reg_data_paidamount) from kumo_reg_data where kumo_reg_data_regtype = 'reg') AS sumregtotal from kumo_reg_data;";
$rs_reports = mysql_query($query_rs_reports, $kumo_conn) or die(mysql_error());
$row_rs_reports = mysql_fetch_assoc($rs_reports);
$totalRows_rs_reports = mysql_num_rows($rs_reports);

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
<meta http-equiv="refresh" content="60" />
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
<table id="list_table" width="300" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th colspan="2" class="display_text">Preregistrations</th>
  </tr>
  <tr>
    <td colspan="2">Number of Pre-Registrations Checked In: <?php echo $row_rs_reports['preregcheckedincount']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Number of Pre-Registrations <u>NOT</u> Checked In: <?php echo $row_rs_reports['preregnotcheckedincount']; ?></td>
  </tr>
  <tr>
    <th colspan="2">Registrations</th>
  </tr>
      <tr>
    <td colspan="2">Number of Registrations on 8/29/2014: <?php echo $row_rs_reports['countregon829']; ?></td>
  </tr>
<?php if ($_SESSION['access']=4) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 8/29/2014: <?php echo $row_rs_reports['sumregon829']; ?></td>
  </tr>
<?php } ?>
      <tr>
    <td colspan="2">Number of Registrations on 8/30/2014: <?php echo $row_rs_reports['countregon830']; ?></td>
  </tr>
<?php if ($_SESSION['access']=4) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 8/30/2014: <?php echo $row_rs_reports['sumregon830']; ?></td>
  </tr>
<?php } ?>
    <tr>
    <td colspan="2">Number of Registrations on 8/31/2014: <?php echo $row_rs_reports['countregon831']; ?></td>
  </tr>
<?php if ($_SESSION['access']=4) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 8/31/2014: <?php echo $row_rs_reports['sumregon831']; ?></td>
  </tr>
<?php } ?>
    <tr>
  <tr>
    <td colspan="2">Number of Registrations on 9/1/2014: <?php echo $row_rs_reports['countregon91']; ?></td>
  </tr>
<?php if ($_SESSION['access']=4) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 9/1/2014: <?php echo $row_rs_reports['sumregon91']; ?></td>
  </tr>
<?php } ?>
<!--      <th colspan="2">Quick Registrations</th>
  </tr>
  <tr>
    <td colspan="2">Number of Quick Registrations on 8/31/2013: <?php echo $row_rs_reports['countquickregon831']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Number of Quick Registrations on 9/1/2013: <?php echo $row_rs_reports['countquickregon91']; ?></td>
  </tr>
    <tr>
    <td colspan="2">Number of Quick Registrations on 9/2/2013: <?php echo $row_rs_reports['countquickregon92']; ?></td>
  </tr>-->
  <tr>
    <th colspan="2">Registrations In The Last Hour</th>
  </tr>
  <tr>
    <td colspan="2"><?php echo $row_rs_reports['reginlasthour']; ?></td>
  </tr>
<!--  <tr>
    <th colspan="2">Quick Registrations In The Last Hour</th>
  </tr>
  <tr>
    <td colspan="2"><?php echo $row_rs_reports['quickreginlasthour']; ?></td>
  </tr>-->
    <tr>
    <th colspan="2">Pass Types</th>
  </tr>
  <tr>
    <td colspan="2">Weekend: <?php echo $row_rs_reports['passtypeweekend']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Friday: <?php echo $row_rs_reports['passtypefriday']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Saturday: <?php echo $row_rs_reports['passtypesaturday']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Sunday: <?php echo $row_rs_reports['passtypesunday']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Monday: <?php echo $row_rs_reports['passtypemonday']; ?></td>
  </tr>
    <tr>
    <td width="50%">Number of Pre-Registrations Checked In:<br /><?php echo $row_rs_reports['preregcheckedincount']; ?><br />
    				Number of At-Con Registrations:<br />
                    <?php echo $row_rs_reports['regtotal']; ?>
    				<br />
      				Grand Total : <?php echo $row_rs_reports['checkedintotal']; ?></td>
    <td width="50%">Total From All At-Con Registrations:<br /><br />$<?php echo $row_rs_reports['sumregtotal']; ?>
                    </td>
    </tr>
</table>





<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
