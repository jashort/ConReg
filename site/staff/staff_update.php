<?php
require_once('../includes/functions.php');
require_once('../includes/roles.php');

require_once('../includes/authcheck.php');
requireRight('manage_staff');

if (isset($_GET['staff_id'])) {
    $staff = getStaff($_GET['staff_id']);
} elseif (isset($_POST["create"])) {
    $staff = new Staff();
    $staff->fromArray($_POST);
    staffUpdate($staff);
    logMessage($_SESSION['username'], "Updated user ". $_POST['username']);

    redirect("/index.php");
} elseif (isset($_POST["passwordReset"])) {
    passwordReset($_POST["username"],$_POST["password"]);
    logMessage($_SESSION['username'], "Reset password for ". $_POST['username']);
    redirect("/index.php");
    die();
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
<!-- InstanceBeginEditable name="head" -->
<script src="/assets/javascript/jquery-1.8.0.js" type="text/javascript"></script>
<script src="/assets/javascript/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<script>
jQuery(function($){
   $("#cellnumber").mask("(999) 999-9999");
});
</script>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
<fieldset id="list_table_search">
<form name="staffupdate" action="/staff/staff_update.php" method="post">
<input name="staff_id" type="hidden" value="<?php echo $staff->staff_id; ?>" />
<input name="username" type="hidden" value="<?php echo $staff->username; ?>" />
<label>
Username : </label>
<span class="display_text"><?php echo $staff->username; ?></span><br /><br />
<label>First Name : <input name="first_name" type="text" class="input_20_150" value="<?php echo $staff->first_name; ?>" /></label><br />
<label>Last Name : <input name="last_name" type="text" class="input_20_150" value="<?php echo $staff->last_name; ?>" /></label><br />
<label>Initials : <input name="initials" type="text" class="input_20_150" value="<?php echo $staff->initials; ?>" /></label><br />
<label>Cell Phone Number : <input id="cellnumber" name="phone_number" type="text" class="input_20_150"  value="<?php echo $staff->phone_number; ?>" /></label><br />
<label>Access Level :
<select name="access_level" class="select_25_125" id="accesslevel">
<?php
    foreach (array_keys($ROLES) as $i) {
        if ($i == $staff->access_level){
            $selected = 'selected';
        } else {
            $selected = '';
        }
        echo "<option value='" . $i . "' " . $selected . " >" . $ROLES[$i]['name'] . "</option>\n";
    }
?>
</select><br />
<label>Enabled : <select name="enabled" class="select_25_75">
<option value="1" <?php if ($staff->enabled=="1"){echo 'selected';}?>>Yes</option>
<option value="0" <?php if ($staff->enabled=="0"){echo 'selected';}?>>No</option>
</select></label><br />
<input name="create" type="submit" class="submit_button" value="Update" />
</form>
<form name="password" action="/staff/staff_update.php" method="post">
<input name="username" type="hidden" value="<?php echo $staff->username; ?>" />
<input name="password" type="hidden" value="password" />
<input name="passwordReset" type="submit" class="submit_button" value="Password Reset" /><br />
</form>
</fieldset>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>