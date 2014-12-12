<?php require_once('../Connections/kumo_conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php 

try {
if ($_FILES[csv][size] > 0) {

    //get the csv file
    $file = $_FILES[csv][tmp_name];
    $handle = fopen($file,"r");
	
	$stmt = $conn->prepare("SELECT kumo_reg_staff_bnumber FROM kumo_reg_staff WHERE kumo_reg_staff_username = :uname");
    $stmt->execute(array('uname' => "Online"));
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	$BNumber = $results['kumo_reg_staff_bnumber']+1;
    
    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
		
		$BadgeNumber = "ONL" . $BNumber;
		
		$PhoneNumber = $data[9];
		$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);
		
		$date = explode("/",$data[10]);
		$Month = str_pad($date[0], 2, "0", STR_PAD_LEFT);
		$Day = str_pad($date[1], 2, "0", STR_PAD_LEFT);
		$Year = $date[2];
		$BDate = $Year . "-" . $Month . "-" . $Day;
		
		if ($data[18]=="Child") {
        		$Amount = 35;
		}
		
		if (($data[18]=="Adult") || ($data[18]=="Youth")) {
		switch ($data[16]) {
    		case "Standard Pre-registration until Dec. 31":
        		$Amount = 40;
        		break;
    		case "Standard Pre-registration until Apr. 13":
        		$Amount = 45;
        		break;
   			case "Standard Pre-registration until Aug. 15":
				$Amount = 50;
				break;
		}
		}
		
		if ($data[16]=="VIP Registration") {
			$Amount = 300;
		}

			
		$stmt = $conn->prepare("INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_bname, kumo_reg_data_address, kumo_reg_data_city, kumo_reg_data_state, kumo_reg_data_zip, kumo_reg_data_phone, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone, kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform, kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype, kumo_reg_data_paytype, kumo_reg_data_checkedin, kumo_reg_data_staff_add) VALUES (:firstname, :lastname, :bnumber, :bname, :address, :city, :state, :zip, :phone, :bdate, :ecname, :ecphone, 'No', :pcname, :pcphone, 'No', 'Yes', :amount, 'Weekend', 'PreReg', 'Credit/Debit', 'No', 'ONLINE')");			   
		$stmt->execute(array('firstname' => $data[0], 'lastname' => $data[1], 'bnumber' => $BadgeNumber, 'bname' => $data[2], 'address' => $data[3], 'city' => $data[4], 'state' => $data[5],'zip' => $data[6], 'phone' => $Phone_Stripped, 'bdate' => $BDate, 'ecname' => $data[11], 'ecphone' => $data[12], 'pcname' => $data[13], 'pcphone' => $data[14], 'amount' => $Amount));
			
        }
		$BNumber++;
    } while ($data = fgetcsv($handle,1000,",","'"));
    //

    //redirect
    header('Location: csvimport_brian.php?success=1'); die;

}

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
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
<form action="csvimport_brian.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
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
