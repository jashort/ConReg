<?php require_once('../Connections/kumo_conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php 

if ($_FILES[csv][size] > 0) {

    //get the csv file
    $file = $_FILES[csv][tmp_name];
    $handle = fopen($file,"r");
	
	$stmt = $conn->prepare("SELECT kumo_reg_staff_bnumber FROM kumo_reg_staff WHERE kumo_reg_staff_username = :uname");
    $stmt->execute(array('uname' => $_SESSION['username']));
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	$BNumber = $results['kumo_reg_staff_bnumber']+1;
    
    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
		
		$name = explode(" ",$data[0]);
		$FirstName = $name[0];
		$LastName = $name[1];
		
		$BadgeNumber = "ONL" . $BNumber;
		
		$PhoneNumber = $data[17];
		$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);
		
		$date = explode("/",$data[18]);
		$Month = str_pad($date[0], 2, "0", STR_PAD_LEFT);
		$Day = str_pad($date[1], 2, "0", STR_PAD_LEFT);
		$Year = $date[2];
		$BDate = $Year . "-" . $Month . "-" . $Day;
		
		switch ($data[7]) {
    		case "Young Child Membership":
        		$Amount = 0;
        		break;
    		case "Child Membership":
        		$Amount = 35;
        		break;
   			case "Standard Membership":
				$Amount = 55;
				break;
    		case "VIP Membership":
				$Amount = 300;
				break;
		}
			
		$stmt = $conn->prepare("INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_bname, kumo_reg_data_address, kumo_reg_data_city, kumo_reg_data_state, kumo_reg_data_zip, kumo_reg_data_phone, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone, kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform, kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype, kumo_reg_data_paytype, kumo_reg_data_checkedin, kumo_reg_data_staff_add) VALUES (:firstname, :lastname, :bnumber, :bname, :address, :city, :state, :zip, :phone, :bdate, :ecname, :ecphone, 'No', :pcname, :pcphone, 'No', 'Yes', :amount, 'Weekend', 'PreReg', 'Credit/Debit', 'No', 'ONLINE')");			   
		$stmt->execute(array('firstname' => $FirstName, 'lastname' => $LastName, 'bnumber' => $BadgeNumber, 'bname' => $data[11], 'address' => $data[12], 'city' => $data[13], 'state' => $data[14],'zip' => $data[15], 'phone' => $Phone_Stripped, 'bdate' => $BDate, 'ecname' => $data[20], 'ecphone' => $data[21], 'pcname' => $data[22], 'pcphone' => $data[23], 'amount' => $Amount));
			
        }
		$BNumber++;
    } while ($data = fgetcsv($handle,1000,",","'"));
    //

    //redirect
    header('Location: csvimport.php?success=1'); die;

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
<div>
<?php if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose your file: <br />
  <input name="csv" type="file" id="csv" />
  <input type="submit" name="Submit" value="Submit" />
</form>
</div>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
