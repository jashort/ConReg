<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('badge_print');

$attendees = array();
if (isset($_POST['print'])) {
	$attendees = getAttendeePDO($_POST['print']);
} elseif (isset($_POST['order'])) {
	$attendees = orderListAttendees($_POST['order']);
} else {
	die("No parameters");
}

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
	while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) {
        $pass = getPassType($attendee->pass_type_id);

        logMessage($_SESSION['username'], 40, "Printed badge for " .
			$attendee->first_name . ' ' . $attendee->last_name . " (ID " . $attendee->id . ")"); ?>

        <div class="badge">
            <div class="colorbar" style="background-color: #<?php echo $pass->stripe_color; ?>">
                <div class="colorbarText"><?php echo $pass->stripe_text; ?></div>
            </div>
            <div class="name"><?php echo $attendee->getNameForBadge(); ?></div>
            <div class="smallName"><?php echo $attendee->getSmallNameForBadge(); ?></div>
            <div class="badgeNumber"><?php echo $attendee->badge_number ?></div>
            <div class="dayText"><?php echo $pass->day_text ?></div>
        </div>
    <?php } ?>

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