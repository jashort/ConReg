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
    logMessage($_SESSION['username'], 80, "Updated user ". $_POST['username']);

    redirect("/index.php");
} elseif (isset($_POST["passwordReset"])) {
    passwordReset($_POST["username"],$_POST["password"]);
    logMessage($_SESSION['username'], 100, "Reset password for ". $_POST['username']);
    redirect("/index.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">

    <title>Update User</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/navbar-fixed-top.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="../assets/dist/js/html5shiv-3.7.2.min.js"></script>
    <script src="../assets/dist/js/respond-1.4.2.min.js"></script>
    <![endif]-->
</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h2>Update User</h2>

        <form action="/staff/staff_update.php" method="post" class="form-horizontal">
            <input name="staff_id" type="hidden" value="<?php echo $staff->staff_id; ?>" />
            <input name="username" type="hidden" value="<?php echo $staff->username; ?>" />
            <div class="form-group">
                <label for="First Name" class="col-sm-2 control-label">First Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="first_name" id="First Name" 
                           placeholder="First Name" maxlength="60" required="required"
                           value="<?php echo $staff->first_name; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class="col-sm-2 control-label">Last Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="last_name" id="last_name" 
                           placeholder="Last Name" maxlength="60" required="required"
                           value="<?php echo $staff->last_name; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="initials" class="col-sm-2 control-label">Initials</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="initials" id="initials" 
                           placeholder="Initials" maxlength="3" required="required"
                           value="<?php echo $staff->initials; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="username" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="username" id="username" 
                           placeholder="Username" maxlength="60" required="required" readonly="readonly"
                           value="<?php echo $staff->username; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="cellnumber" class="col-sm-2 control-label">Cell Phone Number</label>
                <div class="col-sm-4">
                    <input type="tel" class="form-control" name="phone_number" id="cellnumber" 
                           placeholder="xxx-xxx-xxxx" pattern="^\d{3}-?\d{3}-?\d{4}$"
                           value="<?php echo $staff->phone_number; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="accesslevel" class="col-sm-2 control-label">Access Level</label>
                <div class="col-sm-4">
                    <select name="access_level" id="accesslevel" class="form-control">
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
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="enabled" class="col-sm-2 control-label">Enabled</label>
                <div class="col-sm-4">
                    <select id="enabled" name="enabled" class="form-control">
                        <option value="1" <?php if ($staff->enabled=="1"){echo 'selected';}?>>Yes</option>
                        <option value="0" <?php if ($staff->enabled=="0"){echo 'selected';}?>>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button name="create" type="submit" class="btn btn-primary" value="Update">Update</button>
                </div>
            </div>
        </form>
        </form>
        <form name="password" action="/staff/staff_update.php" method="post">
            <input name="username" type="hidden" value="<?php echo $staff->username; ?>" />
            <input name="password" type="hidden" value="password" />
            <input name="passwordReset" type="submit" class="btn btn-danger" value="Password Reset" /><br />
        </form>

    </div>

    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
