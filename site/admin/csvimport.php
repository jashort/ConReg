<?php
require_once('../Connections/kumo_conn.php');
require_once('../includes/functions.php');
require_once('../includes/authcheck.php');

require_right('super-admin');

if ($_FILES && $_FILES['csv']['size'] > 0) {

    //get the csv file
    $file = $_FILES['csv']['tmp_name'];
    $handle = fopen($file,"r");
	
	$BNumber = badgeNumberSelect();
    
    //loop through the csv file and insert into database
	$count = 0;
	$first = true;
	while (($data = fgetcsv($handle,1000,"\t","'")) !== FALSE) {
		// Skip the first line
		if ($first == true) {
			$first = false;
			continue;
		}
		// Skip empty lines and lines where the first field starts with "#"
        if (count($data) > 1 && substr($data[0], 0, 1) != '#') {
			if ($data[3] == "" || $data[3] == "NULL") {
				$data[3] = sprintf("ONL%1$04d", $BNumber);
			}

			$PhoneNumber = $data[6];
			$Phone_Stripped = preg_replace("/[^a-zA-Z0-9s]/","",$PhoneNumber);

			$conn->beginTransaction();
			// Create order if it doesn't exist. If it does, increment the total amount
			$stmt = $conn->prepare("INSERT INTO kumo_reg_orders (order_id, total_amount, paid, paytype)
									VALUES (:orderid, :amount, :paid, :paytype)
									ON DUPLICATE KEY UPDATE total_amount = total_amount + :amount");
			$stmt->execute(array('orderid' => $data[17],
 								 'amount' => $data[15],
								 'paid' => $data[14],
								 'paytype' => 'ONLINE'));

			$stmt = $conn->prepare("
							INSERT INTO kumo_reg_data (kumo_reg_data_fname, kumo_reg_data_lname, kumo_reg_data_bnumber, kumo_reg_data_bname, kumo_reg_data_zip, kumo_reg_data_country,
                           kumo_reg_data_phone, kumo_reg_data_email, kumo_reg_data_bdate, kumo_reg_data_ecfullname, kumo_reg_data_ecphone,
                           kumo_reg_data_same, kumo_reg_data_parent, kumo_reg_data_parentphone, kumo_reg_data_parentform,
                           kumo_reg_data_paid, kumo_reg_data_paidamount, kumo_reg_data_passtype, kumo_reg_data_regtype,
                           kumo_reg_data_checkedin, kumo_reg_data_staff_add, kumo_reg_data_orderid) VALUES
                           (:firstname, :lastname, :bnumber, :bname, :zip, :country,
                           :phone, :email, :bdate, :ecname, :ecphone,
                           :same, :pcname, :pcphone, :parentform, :paid, :amount, :passtype, :regtype, :checkedin, :staffadd, :orderid)");
			$stmt->execute(array('firstname' => $data[0],
				'lastname' => $data[1],
				'bname' => $data[2],
				'bnumber' => $data[3],
				'zip' => $data[4],
				'country' => $data[5],
				'phone' => $Phone_Stripped,
				'email' => $data[7],
				'bdate' => $data[8],
				'ecname' => $data[9],
				'ecphone' => $data[10],
				'same' => $data[11],
				'pcname' => $data[12],
				'pcphone' => $data[13],
				'parentform' => 'No',
				'paid' => $data[14],
				'amount' => $data[15],
				'passtype' => $data[16],
				'regtype' => 'PreReg',
				'checkedin' => 'No',
				'staffadd' => 'ONLINE',
				'orderid' => $data[17]));
			$conn->commit();

			$BNumber++;
			$count += 1;
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
