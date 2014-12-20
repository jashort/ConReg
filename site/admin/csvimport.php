<?php require_once('../Connections/kumo_conn.php'); ?>
<?php require_once('../includes/functions.php'); ?>
<?php 

if ($_FILES && $_FILES['csv']['size'] > 0) {

    //get the csv file
    $file = $_FILES['csv']['tmp_name'];
    $handle = fopen($file,"r");
	
	$BNumber = badgeNumberSelect();
    
    //loop through the csv file and insert into database
	$count = 0;

	while (($data = fgetcsv($handle,1000,",","'")) !== FALSE) {
		// Skip empty lines and lines where the first field starts with "#"
        if (count($data) > 1 && substr($data[0], 0, 1) != '#') {
			$name = explode(" ",$data[0]);
			$FirstName = $name[0];
			$LastName = $name[1];

			$BadgeNumber = "ONL" . $BNumber;

			$PhoneNumber = $data[16];
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
			$stmt = $conn->prepare("INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_bname, kumo_reg_data_address, kumo_reg_data_city, kumo_reg_data_state, kumo_reg_data_zip, kumo_reg_data_phone, kumo_reg_data_email, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone, kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform, kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype, kumo_reg_data_paytype, kumo_reg_data_checkedin, kumo_reg_data_staff_add) VALUES (:firstname, :lastname, :bnumber, :bname, :address, :city, :state, :zip, :phone, :email, :bdate, :ecname, :ecphone, 'No', :pcname, :pcphone, 'No', 'Yes', :amount, 'Weekend', 'PreReg', 'Credit/Debit', 'No', 'ONLINE')");
			$stmt->execute(array('firstname' => $FirstName, 'lastname' => $LastName, 'bnumber' => $BadgeNumber, 'bname' => $data[11], 'address' => $data[12], 'city' => $data[13], 'state' => $data[14],'zip' => $data[15], 'phone' => $Phone_Stripped, 'email' => $data[17], 'bdate' => $BDate, 'ecname' => $data[20], 'ecphone' => $data[21], 'pcname' => $data[22], 'pcphone' => $data[23], 'amount' => $Amount));
			$count += 1;
			$BNumber++;
		}
    }

	// Update the logged in user's last-created badge number.
	badgeNumberSet($BNumber-1);

    //redirect
    header('Location: csvimport.php?success=1&count=' . $count); die;

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
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<div>
<?php if (!empty($_GET['success'])) { ?>

	<? echo $_GET['count'] ?> lines imported. <a href="/">Continue</a><br>

<? } else { ?>
	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
		<p>Import Pre-registered Attendees. Note: Importing the same file multiple times will create duplicates.</p>
		Choose CSV file: <br />
		<input name="csv" type="file" id="csv" />
		<input type="submit" name="Submit" value="Submit" />
	</form>

<? }  ?>
</div>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
