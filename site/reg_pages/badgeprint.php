<?php
header('Content-Type: application/pdf');

require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('badge_print');

include("../includes/pdf/mpdf.php");
$mpdf=new mPDF('utf-8', array(215.9,139.7), 0, '', 0, 0, 0, 0, 0, 0, 'P');


$attendees = array();
if (isset($_POST['print'])) {
	$attendees = getAttendeePDO($_POST['print']);
} elseif (isset($_POST['order'])) {
	$attendees = orderListAttendees($_POST['order']);
} else {
	die("No parameters");
}

// Buffer the following html with PHP so we can store it to a variable later
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<style type="text/css">
			#buffer {
				width: 4in;
				height: 1in;
				padding: 0;
				margin-top: 0;
				margin-right: auto;
				margin-left: auto;
			}

			#badge {
				width: 4in;
				height: 3.127in;
				padding: 0;
				margin-top: 0.09in;
				margin-right: auto;
				margin-left: auto;
				white-space:nowrap;
				/*border: 1px solid #000;*/
			}

			.adult {
				background-color: #323e99;
			}

			.minor {
				background-color: #e39426;
			}

			.child {
				background-color: #cc202a;
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
	<?php
	while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) {
		logMessage($_SESSION['username'], 40, "Printed badge for " .
			$attendee->first_name . ' ' . $attendee->last_name . " (ID " . $attendee->id . ")");

		$age = $attendee->getAge();
		if ($age >= 18) {
			$ageClass = "adult";
		} elseif (($age > 12) && ($age <= 17)) {
			$ageClass = "minor";
		} else {
			$ageClass = "child";
		}?>

		<div id="buffer"></div>
		<div id="badge" class="<?php echo $ageClass;?>">
			<!--<div id="stripe">
            </div>-->
			<div id="name">
				<?php echo $attendee->getNameForBadge(); ?>
			</div>
		</div>
		<div id="buffer"></div>
	<?php } ?>
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