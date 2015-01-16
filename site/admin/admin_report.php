<?php
require('../includes/functions.php');
require('../includes/authcheck.php');

require_right('report_view');

mysql_select_db($db_name, $kumo_conn);
$query_rs_reports = "select distinct (select count(*) from kumo_reg_data where checked_in = 'yes' AND reg_type='prereg') AS preregcheckedincount,
                                     (select count(*) from kumo_reg_data where checked_in = 'no' AND reg_type='prereg') AS preregnotcheckedincount,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created like '2014-08-29%') AS countregon829,
                                     (select sum(paid_amount) from kumo_reg_data where reg_type = 'reg' AND created like '2014-08-29%') AS sumregon829,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created like '2014-08-30%') AS countregon830,
                                     (select sum(paid_amount) from kumo_reg_data where reg_type = 'reg' AND created like '2014-08-30%') AS sumregon830,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created like '2014-08-31%') AS countregon831,
                                     (select sum(paid_amount) from kumo_reg_data where reg_type = 'reg' AND created like '2014-08-31%') AS sumregon831,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created like '2014-09-01%') AS countregon91,
                                     (select sum(paid_amount) from kumo_reg_data where reg_type = 'reg' AND created like '2014-09-01%') AS sumregon91,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created like '2014-09-02%') AS countregon92,
                                     (select sum(paid_amount) from kumo_reg_data where reg_type = 'reg' AND created like '2014-09-02%') AS sumregon92,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created like '2014-09-03%') AS countregon93,
                                     (select count(*) from kumo_reg_data where reg_type = 'reg' AND created > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)) AS reginlasthour,
                                     (select count(badge_number) from kumo_reg_data where pass_type = 'Weekend') AS passtypeweekend,
                                     (select count(badge_number) from kumo_reg_data where pass_type = 'Friday') AS passtypefriday,
                                     (select count(badge_number) from kumo_reg_data where pass_type = 'Saturday') AS passtypesaturday,
                                     (select count(badge_number) from kumo_reg_data where pass_type = 'Sunday') AS passtypesunday,
                                     (select count(badge_number) from kumo_reg_data where pass_type = 'Monday') AS passtypemonday,
                                     (select count(*) from kumo_reg_data where reg_type like 'reg') AS regtotal,
                                     (select count(*) from kumo_reg_data where checked_in = 'Yes') AS checkedintotal,
                                     (select sum(paid_amount) from kumo_reg_data where reg_type = 'reg') AS sumregtotal from kumo_reg_data;";
$rs_reports = mysql_query($query_rs_reports, $kumo_conn) or die(mysql_error());
$row_rs_reports = mysql_fetch_assoc($rs_reports);
$totalRows_rs_reports = mysql_num_rows($rs_reports);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kumoricon Registration</title>
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" /> 
<meta http-equiv="refresh" content="60" />
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>
<div id="content">
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
<?php if (has_right('report_view_revenue')) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 8/29/2014: <?php echo $row_rs_reports['sumregon829']; ?></td>
  </tr>
<?php } ?>
      <tr>
    <td colspan="2">Number of Registrations on 8/30/2014: <?php echo $row_rs_reports['countregon830']; ?></td>
  </tr>
<?php if (has_right('report_view_revenue')) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 8/30/2014: <?php echo $row_rs_reports['sumregon830']; ?></td>
  </tr>
<?php } ?>
    <tr>
    <td colspan="2">Number of Registrations on 8/31/2014: <?php echo $row_rs_reports['countregon831']; ?></td>
  </tr>
<?php if (has_right('report_view_revenue')) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 8/31/2014: <?php echo $row_rs_reports['sumregon831']; ?></td>
  </tr>
<?php } ?>
    <tr>
  <tr>
    <td colspan="2">Number of Registrations on 9/1/2014: <?php echo $row_rs_reports['countregon91']; ?></td>
  </tr>
<?php if (has_right('report_view_revenue')) { ?>
  <tr>
    <td colspan="2">Revenue of Registrations on 9/1/2014: <?php echo $row_rs_reports['sumregon91']; ?></td>
  </tr>
<?php } ?>
  <tr>
    <th colspan="2">Registrations In The Last Hour</th>
  </tr>
  <tr>
    <td colspan="2"><?php echo $row_rs_reports['reginlasthour']; ?></td>
  </tr>
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
      <td width="50%">
        <?php if (has_right('report_view_revenue')) { ?>
          Total From All At-Con Registrations:<br /><br />$<?php echo $row_rs_reports['sumregtotal']; ?>
        <?php } ?>
      </td>
    </tr>
</table>
</div>

<div id="footer">&copy; Tim Zuidema</div> 

</body>
</html>
