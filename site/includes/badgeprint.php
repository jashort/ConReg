<?php //include "BarcodeQR.php"; 
session_start();
if (!isset($_SESSION["BadgeName"])) {
	$BadgeName = $_GET["bn"];
} else {
	$BadgeName = $_SESSION["BadgeName"];
}
if (!isset($_SESSION["BadgeNumber"])) {
	$BadgeNumber = $_GET["bid"];
} else {
	$BadgeNumber = $_SESSION["BadgeNumber"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Badge</title>
<link rel="stylesheet" type="text/css" media="all" href="../assets/css/kumobadge.css" />
</head>
<body>
<div id="badge">
<div id="badge_name"><?php echo $BadgeName; ?></div>
<div id="badge_QR">
<?php 
$url = urlencode("regdev.definitivellc.net/qr.php?bid=" . $BadgeNumber);
?>
<img src="http://chart.apis.google.com/chart?chs=150x150&cht=qr&chl=<?php echo $url;?>">
</div>
</div>
</div>
</body>
</html>