<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
require_right('report_view');

$regByDay = registrationsByDay();
$regStats = registrationStatistics();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kumoricon Registration</title>
<link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" /> 
<meta http-equiv="refresh" content="60" />
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>
<div id="content">
<table id="list_table" width="300" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th colspan="2" class="display_text">PreRegistrations</th>
  </tr>
  <tr>
    <td colspan="2">Number of Pre-Registrations Checked In: <?php echo $regStats['preregcheckedincount']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Number of Pre-Registrations <u>NOT</u> Checked In: <?php echo $regStats['preregnotcheckedincount']; ?></td>
  </tr>
  <tr>
    <th colspan="2">Registrations</th>
  </tr>
    <?php while ($reg = $regByDay->fetch()) { ?>
        <tr>
            <td colspan="2">Number of registrations on <?php echo $reg["DAYNAME"] ?> (<?php echo $reg["DATE"] ?>):
            <?php echo $reg["DAYCOUNT"] ?></td>
        </tr>
        <?php if (has_right('report_view_revenue')) { ?>
            <tr>
                <td colspan="2">Revenue of registrations on <?php echo $reg["DAYNAME"] ?> (<?php echo $reg["DATE"] ?>):
                    $<?php echo $reg["DAYTOTAL"] ?></td>
            </tr>
        <?php } ?>
    <?php } ?>

  <tr>
    <th colspan="2">Registrations In The Last Hour</th>
  </tr>
  <tr>
    <td colspan="2"><?php echo $regStats['reginlasthour']; ?></td>
  </tr>
    <tr>
    <th colspan="2">Pass Types</th>
  </tr>
  <tr>
    <td colspan="2">Weekend/VIP: <?php echo $regStats['passtypeweekend']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Friday: <?php echo $regStats['passtypefriday']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Saturday: <?php echo $regStats['passtypesaturday']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Sunday: <?php echo $regStats['passtypesunday']; ?></td>
  </tr>
  <tr>
    <td colspan="2">Monday: <?php echo $regStats['passtypemonday']; ?></td>
  </tr>
    <tr>
    <td width="50%">Number of Pre-Registrations Checked In:<br /><?php echo $regStats['preregcheckedincount']; ?><br />
    				Number of At-Con Registrations:<br />
                    <?php echo $regStats['regtotal']; ?>
    				<br />
      				Grand Total : <?php echo $regStats['checkedintotal']; ?></td>
      <td width="50%">
        <?php if (has_right('report_view_revenue')) { ?>
          Total From All At-Con Registrations:<br /><br />$<?php echo $regStats['sumregtotal']; ?>
        <?php } ?>
      </td>
    </tr>
</table>
</div>

<div id="footer">&copy; Tim Zuidema</div> 

</body>
</html>
