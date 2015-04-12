<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('attendee_search');

if (isset($_GET['id'])) {
    if ($_GET['field'] == "bid") {
        $attendees = attendeeSearchBadgeNumber($_GET['id']);
    } else {
        $attendees = attendeeSearchLastName($_GET['id']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">

    <title>Attendee Search</title>

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
        <h2>Attendee Search</h2>

        <?php if (!isset($_GET["id"])) { // Show if no search term ?>
            <!--
            <form name="bid" action="/admin/admin_attendee_list.php" method="get" target="_self" class="form-inline">
                <input name="field" type="hidden" value="bid" /><br />
                <fieldset id="list_table_search">
                    <label for="id" class="col-sm-2 control-label">Badge Number</label>
                    <div class="col-sm-4">
                        <input name="id" type="text" class="form-control" />
                        <input name="Submit" type="submit" class="btn btn-primary" value="Search" onmousedown="validateBID();" />
                    </div>
                </fieldset>
            </form>
            -->

            <form name="ln" action="/admin/admin_attendee_list.php" method="get" target="_self" class="form-inline">
                <input name="field" type="hidden" value="ln" />
                    <fieldset id="list_table_search">
                        <label for="id" class="control-label">Last Name</label>
                        <input name="id" type="text" maxlength="60" autocomplete="off" autofocus required class="form-control" />
                        <input name="Submit" type="submit" class="btn btn-primary" value="Search" />
                    </fieldset>
            </form>
        <?php } else { // Show if search term ?>
            <table id="list_table" class="table">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Badge Name</th>
                    <th scope="col">Checked In?</th>
                </tr>

                <?php while ($attendee = $attendees->fetch(PDO::FETCH_CLASS)) { ?>
                    <tr>
                        <td><a href="/admin/admin_attendee_display.php?id=<?php echo $attendee->id ?>" ><?php echo $attendee->first_name . " " . $attendee->last_name ?></a></td>
                        <td><?php echo $attendee->badge_name; ?></td>
                        <td><?php echo $attendee->checked_in; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
        
    </div>

    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
