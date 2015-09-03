<?php
require_once('../includes/functions.php');
require_once('../includes/roles.php');

require_once('../includes/authcheck.php');
requireRight('manage_staff');

if (isset($_POST["create"])) {
  $staff = new Staff();
  $staff->fromArray($_POST);
  $staff->setPassword('password');  // New user password is just "password"
  staffAdd($staff);
  logMessage($_SESSION['username'], 70, "Added user ". $_POST['username']);
  redirect("/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../favicon.ico">

  <title>Add User</title>

  <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/navbar-fixed-top.css" rel="stylesheet">

  <script type="text/javascript">
    function autoFill() {
      // Auto fill the initials and username based on the first and last name
      var first = document.getElementById("First Name");
      var last = document.getElementById("last_name");
      var initials = document.getElementById("initials");
      var username = document.getElementById("username");
      if (first.value != "" && last.value != "") {
        if (initials.value == "") {
          initials.value = first.value.charAt(0).toUpperCase() + last.value.charAt(0).toUpperCase();
        }
        if (username.value == "") {
          username.value = first.value.charAt(0).toLowerCase() + last.value.toLowerCase();
        }
      }
    }
  </script>

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
    <h2>Add User</h2>

    <form action="/staff/staff_add.php" method="post" class="form-horizontal">
      <div class="form-group">
        <label for="First Name" class="col-sm-2 control-label">First Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="first_name" id="First Name"  autofocus="autofocus"
                 placeholder="First Name" maxlength="60" required="required" onchange="autoFill()" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label for="last_name" class="col-sm-2 control-label">Last Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name"
                 maxlength="60" required="required" onchange="autoFill()" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label for="initials" class="col-sm-2 control-label">Initials</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="initials" id="initials" placeholder="Initials"
                 maxlength="3" required="required" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label for="username" class="col-sm-2 control-label">Username</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                 maxlength="60" required="required" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label for="cellnumber" class="col-sm-2 control-label">Cell Phone Number</label>
        <div class="col-sm-4">
          <input type="tel" class="form-control" name="phone_number" id="cellnumber" placeholder="xxx-xxx-xxxx"
                 pattern="^\d{3}-?\d{3}-?\d{4}$" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label for="accesslevel" class="col-sm-2 control-label">Access Level</label>
        <div class="col-sm-4">
          <select name="access_level" id="accesslevel" class="form-control">
            <?php
            foreach (array_keys($ROLES) as $i) {
              echo "<option value='" . $i . "'>" . $ROLES[$i]['name'] . "</option>\n";
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="enabled" class="col-sm-2 control-label">Enabled</label>
        <div class="col-sm-4">
          <select id="enabled" name="enabled" class="form-control">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <button name="create" type="submit" class="btn btn-primary col-sm-offset-10" value="create">Create</button>
        </div>
      </div>
    </form>

  </div>

  <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>

