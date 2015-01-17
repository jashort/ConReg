<?php
require_once('../Connections/kumo_conn.php');
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
require_right('badge_reprint');

include("../includes/pdf/mpdf.php");
$mpdf=new mPDF('utf-8', array(215.9,279.4), 0, '', 0, 0, 0, 0, 0, 0, 'L');

// Buffer the following html with PHP so we can store it to a variable later
ob_start();

$attendee = getAttendee($_GET['print']);
$age = $attendee->getAge();
if ($age >= 18) {
	$stripeColor = "#323e99";
} elseif (($age > 12) && ($age <= 17)) {
	$stripeColor = "#e39426";
} else {
	$stripeColor = "#cc202a";
}

?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<style>
			#buffer {
				width: 4in;
				height: 2.68in;
				padding: 0;
				margin-top: 0;
				margin-right: auto;
				margin-left: auto;
			}

			#badge {
				width: 4in;
				height: 3.127in;
				padding: 0;
				background-color: <?php echo $stripeColor; ?>;
				margin-top: 0;
				margin-right: auto;
				margin-left: auto;
				/*border: 1px solid #000;*/
			}

			#name {
				width: 3.32in;
				height: .627in;
				margin-left: .59in;
				padding-top: 1.25in;
				padding-bottom: 1.25in;
				padding-left: .25in;
				font-family: "Copperplate Gothic Bold", serif;
				font-size: 24px;
				background-color: #FFF;
			}

		</style>
	</head>
	<body>
	<div id="buffer"></div>
	<div id="badge">
		<!--<div id="stripe">
        </div>-->
		<div id="name">
			<?php echo $attendee->first_name . " " . $attendee->last_name; ?>
		</div>
	</div>
	</body>
	</html>
<?php

$html = ob_get_contents();
ob_end_clean();

// send the captured HTML from the output buffer to the mPDF class for processing
$mpdf->WriteHTML($html);

$mpdf->Output();
exit;
?>