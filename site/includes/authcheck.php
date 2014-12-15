<?php 
if (!isset($_SESSION)) {
  session_start();
}

$restrictGoTo = "/login.php";

switch ($_SESSION['access']) {
    case 1:
        $accessWord = "User";
        break;
    case 2:
        $accessWord = "Super User";
        break;
    case 3:
        $accessWord = "Manager";
        break;
    case 4:
        $accessWord = "Administrator";
        break;
}

if (!(isset($_SESSION['username']))) {   
  $queryStringChar = "?";
  $currentPage = $_SERVER['PHP_SELF'];
  if (strpos($restrictGoTo, "?")) $queryStringChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $currentPage .= "?" . $_SERVER['QUERY_STRING'];
  $restrictGoTo .= $queryStringChar . "accesscheck=" . urlencode($currentPage);
  header("Location: " . $restrictGoTo); 
  exit;
}
