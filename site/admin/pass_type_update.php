<?php
require_once('../includes/functions.php');
require_once('../includes/roles.php');

require_once('../includes/authcheck.php');
requireRight('manage_pass_types');

if (isset($_GET['id'])) {
    $passType = getPassType($_GET['id']);
} elseif (isset($_POST["create"])) {
    $passType = new PassType();
    $passType->fromArray($_POST);
    passTypeUpdate($passType);
    logMessage($_SESSION['username'], 140, "Updated pass type " . $_POST['name']);
    redirect("/admin/pass_type_list.php");
} elseif (isset($_POST["action"]) && $_POST["action"] == "delete") {
    passTypeDelete($_GET['id']);
    logMessage($_SESSION['username'], 150, "Deleted pass type " . $_POST['name']);
    redirect("/admin/pass_type_list.php");
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
        <h2>Update Pass Type</h2>
        
        <div class="col-sm-6 text-right">

        </div>
        <form action="/admin/pass_type_update.php" method="post" class="form-horizontal">
            <input name="id" type="hidden" value="<?php echo $passType->id; ?>" />
            <input name="name" type="hidden" value="<?php echo $passType->name; ?>" />
            <input name="action" type="hidden" value="delete" />
            <input type="submit" class="btn btn-danger" value="Delete This Pass Type" />
        </form><br>

        <div class="col-sm-10"> </div>
        <form action="/admin/pass_type_update.php" method="post" class="form-horizontal">
            <input name="id" type="hidden" value="<?php echo $passType->id; ?>" />
            <div class="form-group">
                <label for="Name" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" id="name"
                           placeholder="Name" maxlength="250" required="required"
                           value="<?php echo $passType->name; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="visible" class="col-sm-3 control-label">Visible</label>
                <div class="col-sm-2">
                    <select name="visible" class="form-control">
                        <option value="Y" <?php if ($passType->visible == "Y") { echo " SELECTED"; } ?>>Yes</option>
                        <option value="N" <?php if ($passType->visible == "N") { echo " SELECTED"; } ?>>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="min_age" class="col-sm-3 control-label">Minimum Age (Years)</label>
                <div class="col-sm-1">
                    <input type="text" class="form-control" name="min_age" id="min_age"
                           placeholder="minimum age" maxlength="3" required="required"
                           value="<?php echo $passType->min_age; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="max_age" class="col-sm-3 control-label">Maximum Age (Years)</label>
                <div class="col-sm-1">
                    <input type="text" class="form-control" name="max_age" id="max_age"
                           placeholder="maximum age" maxlength="3" required="required"
                           value="<?php echo $passType->max_age; ?>">
                </div>
                (255 for "minimum age+")
            </div>
            <div class="form-group">
                <label for="cost" class="col-sm-3 control-label">Cost</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="cost" id="cost"
                           placeholder="0.00" maxlength="8" required="required"
                           min="0" max="99999"
                           value="<?php echo $passType->cost; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button name="create" type="submit" class="btn btn-primary" value="Update">Update</button>
                </div>
            </div>
        </form>

    </div>

    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
