<?php
require('../Connections/kumo_conn.php');
require('../includes/authcheck.php');
require_right('manage_staff');

$stmt = "SELECT staff_id, username FROM reg_staff ORDER BY username ASC";
$results = $conn->query($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Kumoricon Registration</title>
<!-- InstanceEndEditable -->
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
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
      <select name="username" id="username"  class="select_25_150" >
        <?php foreach ($results as $result) { ?>
          <option value="<?php echo $result['username']?>"><?php echo $result['username']?></option>
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
