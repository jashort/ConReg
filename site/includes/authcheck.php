<?php 
if (!isset($_SESSION)) {
  session_start();
}

$restrictGoTo = "/login.php";

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

/**
 * Check if the logged in user has the given right
 *
 * Returns true if the logged in user has the given right stored in $_SESSION['rights'],
 * false otherwise. Rights are generally set in session variable at login.
 *
 * @param $right
 * @return bool
 */
function has_right($right) {
    if (array_key_exists('rights', $_SESSION) && array_key_exists($right, $_SESSION['rights'])) {
        return $_SESSION['rights'][$right];
    } elseif ($_SESSION['access'] == 99) {
        // grant super-admin role all rights
        return true;
    } else {
        return false;
    }
}


/**
 * Abort page load if the user doesn't have the given right
 *
 * @param $right
 */
function require_right($right) {
    if (!has_right($right)) {
        echo "Error: Permission denied.";
        die();
    }
}