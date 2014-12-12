<?php require('../Connections/kumo_conn.php'); ?>
<?php
require('../includes/authcheck.php');

if (isset($_GET['lname'])) {

	$stmt = $conn->prepare("SELECT kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bdate FROM kumo_reg_data WHERE kumo_reg_data_lname LIKE :lname");
    $stmt->execute(array('lname' => $_GET['lname']));

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
<?php if (!isset($_GET["lname"])) { // Show if no search term ?>
  <form name="ln" action="/reg_pages/reg_badge_reprint.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Last Name : </label><input name="lname" type="text" class="input_20_200" /><br />
      <input name="Submit" type="submit" class="submit_button" value="Search" onmousedown="validateLN();" />
      </fieldset>
  </form>
<?php } // Show if no search term ?>
<?php if (isset($_GET["lname"])) { // Show if search term ?>
<table id="list_table">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Birth Date</th>
  </tr>
  <?php do {  

	$fname = $results['kumo_reg_data_fname'];
	$lname = $results['kumo_reg_data_lname'];
	$Birthdate = $results['kumo_reg_data_bdate'];

	if ($Birthdate != "") {
		
		$Birthdate_array = explode("-", $Birthdate);
		$BirthYear = $Birthdate_array[0];
		$BirthMonth = $Birthdate_array[1];
		$BirthDay = $Birthdate_array[2];

		$BDate = $BirthYear . "-" . $BirthMonth . "-" . $BirthDay;

		$date = new DateTime($BDate);
		$now = new DateTime();
		$interval = $now->diff($date);
		$year_diff = $interval->y;
	
	}
  ?>
    <tr>
      <td><a href="/reg_pages/badgereprint.php?fname=<?php echo $fname; ?>&lname=<?php echo $lname; ?>&year_diff=<?php echo $year_diff; ?>" target="_new"><?php echo $fname . " " . $lname; ?></a></td>
      <td><?php echo $Birthdate; ?></td>
    </tr>
    <?php } while ($results = $stmt->fetch(PDO::FETCH_ASSOC)); ?>
</table>
<?php } // Show if search term ?>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rs_update_list);
?>
