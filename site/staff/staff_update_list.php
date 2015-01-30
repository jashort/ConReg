<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('manage_staff');

$staffList = staffList();

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
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
  <form action="/staff/staff_update.php" method="get" target="_self">
    <fieldset id="list_table_search">
      <label>Staff Username :
      <select name="staff_id" id="staff_id"  class="select_25_150" >
        <?php while ($staff = $staffList->fetch()) { ?>
          <option value="<?php echo $staff->staff_id ?>">
            <?php echo $staff->first_name ?> <?php echo $staff->last_name ?>
            (<?php echo $staff->username ?>)</option>
        <?php } ?>
      </select></label>
      <input name="Submit" type="submit" class="submit_button" value="Go" />
    </fieldset>
  </form>
<!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div> 
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
