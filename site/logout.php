<?php
// *** Logout the current user.
$logoutGoTo = "/login.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['username'] = NULL;
$_SESSION['access'] = NULL;
unset($_SESSION['username']);
unset($_SESSION['access']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
