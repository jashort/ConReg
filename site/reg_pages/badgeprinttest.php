<?php
require_once('../includes/functions.php');
//require_once('../includes/authcheck.php');


$attendees = array();

$attendee = new Attendee();
$attendee->first_name = "Test";
$attendee->last_name = "Badge";
$attendee->badge_number = 'TST00000';
$attendee->badge_name = "Some Test Guy";
$attendee->reg_type = 'PreReg';
$attendee->pass_type = 'Weekend';
$attendee->pass_type_id = 1;
$attendee->zip = '12345';
$attendee->birthdate = '1990-01-01';
$attendee->checked_in = 'Yes';
$attendees[] = $attendee;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../favicon.ico">
	<title></title>
	<link href="../assets/css/badge.css" rel="stylesheet">
</head>
<body>
<?php
    //while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) {
        if ($attendee->checked_in != "Yes") {
            die("Error: attendee " . $attendee->first_name . " " . $attendee->last_name . " hasn't checked in yet.");
        }
        $pass = getPassType($attendee->pass_type_id);
        ?>

    <div class="spacer"> </div>
	<div class="badge">
		<div class="colorbar" style="background-color: #<?php echo $pass->stripe_color; ?>">
            <div class="colorbarText"><?php echo implode("<br />", str_split($pass->stripe_text)); ?></div>
        </div>
		<div class="name"><?php echo $attendee->getNameForBadge(); ?></div>
        <div class="smallName"><?php echo $attendee->getSmallNameForBadge(); ?></div>
        <div class="badgeNumber"><?php echo $attendee->badge_number ?></div>
        <div class="dayText"><?php echo $pass->day_text ?></div>
	</div>
<?php // } ?>

<script src="/assets/dist/js/jquery-1.11.2.min.js"></script>
<script>
    // Automatically print and then close the window.
    $(document).ready(function(){
        setTimeout(function(){ window.close();},500);
        $(window).bind("beforeunload",function(){
            window.print();
        });
    });
</script>
</body>
</html>