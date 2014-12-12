<?php require('../Connections/kumo_conn.php'); ?>
<?php require('../includes/authcheck.php'); ?>
<?php

	$stmt = $conn->prepare("SELECT * FROM kumo_reg_staff ORDER BY kumo_reg_staff_fname ASC");
    $stmt->execute();

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
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Phone Number</th>
    </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $results['kumo_reg_staff_fname'] . " " . $results['kumo_reg_staff_lname']; ?></td>
      <td><?php echo $results['kumo_reg_staff_phone_number']; ?></td>
      </tr>
    <?php } while ($results = $stmt->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>