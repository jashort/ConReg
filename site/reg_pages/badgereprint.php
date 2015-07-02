<?php
require_once('../includes/functions.php');
require_once('../includes/authcheck.php');
requireRight('badge_reprint');

$attendees = array();
if (isset($_POST['print'])) {
    $attendees = getAttendeePDO($_POST['print']);
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
        if ($attendee->checked_in != "Yes") {
            die("Error: attendee hasn't checked in yet.");
        }
        logMessage($_SESSION['username'], 40, "Reprinted badge for " .
            $attendee->first_name . ' ' . $attendee->last_name . " (ID " . $attendee->id . ")");

        $age = $attendee->getAge();
        if ($age >= 18) {
            $ageClass = "adult";
        } elseif (($age > 12) && ($age <= 17)) {
            $ageClass = "minor";
        } else {
            $ageClass = "child";
        }?>

	<div class="badge">
		<div class="colorbar <?php echo $ageClass;?>"></div>
		<div class="name"><?php echo $attendee->getNameForBadge(); ?>
		</div>
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