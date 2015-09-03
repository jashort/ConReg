<?php
require_once('../includes/functions.php');
require_once('../includes/authcheck.php');
requireRight('registration_add');

if ((isset($_POST["action"])) && ($_POST["action"] == "Finish")) {
    $_SESSION["currentOrder"] = Array();
    unset ($_SESSION["current"]);
    redirect("/index.php");
} elseif (isset($_GET["action"]) && ($_GET["action"] == "clear")) {
    // Clear the current order and redirect to add a new attendee page.
    $_SESSION["currentOrder"] = Array();
    unset ($_SESSION["current"]);
    redirect("/reg_pages/reg_add.php");
} elseif (isset($_GET["action"]) && ($_GET["action"] == "EditAttendee")) {
    // Load the given attendee in to the $_SESSION["current"] variable and
    // send back to the reg_add.php page to for editing
    if (isset($_GET["id"])) {
        $_SESSION["current"] = $_SESSION["currentOrder"][intval($_GET["id"])];
        unset($_SESSION["currentOrder"][intval($_GET["id"])]);
        redirect("/reg_pages/reg_add.php?part=1");
    }
} elseif (isset($_POST["action"]) && ($_POST["action"] == "DeleteAttendee")) {
    // Remove the given attendee from the order
    if (isset($_POST["id"])) {
        unset($_SESSION["currentOrder"][intval($_POST["id"])]);
        $_SESSION["currentOrder"] = array_values($_SESSION["currentOrder"]); // reindex the array
    }
    redirect("/reg_pages/reg_order.php");
} elseif ((isset($_POST["action"])) && ($_POST["action"] == "Paid")) {
    $orderId = regAddOrder($_SESSION['currentOrder']);
    logMessage($_SESSION['username'], 120, "At-Con Registration order ID ". $orderId);
    orderCheckIn($orderId);
    // If the auth number in the form isn't in the notes for some reason, add it.
    $notes = $_POST["notes"];
    if (isset($_POST["AuthDisplay"]) && $_POST["AuthDisplay"] != "") {
        if (strpos($notes, (string)$_POST["AuthDisplay"]) === false) {
            $notes .= "\n---" . "The Credit Card Authorization Number is: " . $_POST["AuthDisplay"];
        }
    }
    if (isset($_POST["CheckNumber"]) && $_POST["CheckNumber"] != "") {
        if (strpos($notes, (string)$_POST["CheckNumber"]) === false) {
            $notes .= "\n---" . "The Check Number is: " . $_POST["CheckNumber"];
        }
    }
    orderPaid($orderId, $_POST["PayType"], $_POST["total"], $notes);

    foreach ($_SESSION["currentOrder"] as $attendee) {
        logMessage($_SESSION['username'], 30, "At-Con Check in " . $attendee->first_name . ' '. $attendee->last_name);
    }
} elseif (isset($_GET["action"]) && $_GET["action"] == "cancel") {
    // Cancel pending order and return to main screen
    unset($_SESSION["current"]);
    $_SESSION["currentOrder"] = Array();
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

    <title>Complete Order</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/navbar-fixed-top.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="../assets/dist/js/html5shiv-3.7.2.min.js"></script>
    <script src="../assets/dist/js/respond-1.4.2.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        function creditAuth() {
            do {
                var number=prompt("Please enter the 6 character authorization number","ex 123456");
            } while (number.match("^[0-9a-zA-Z]{6}$") === null);

            if (document.getElementById('Notes').value == "") {
                document.getElementById('Notes').value = "The credit card authorization number is: " + number;
            } else {
                document.getElementById('Notes').value = document.getElementById('Notes').value + "---" + "The credit card authorization number is: " + number;
            }

            document.getElementById("AuthDisplay").value = number;
        }
        
        function requireCreditField() {
            if (document.getElementById("Credit").checked) {
                document.getElementById("AuthDisplay").required = true;
            } else {
                document.getElementById("AuthDisplay").required = false;
            }
            if (document.getElementById("Check").checked) {
                document.getElementById("CheckNumber").required = true;
            } else {
                document.getElementById("CheckNumber").required = false;
            }
        }
        function clearVerify() {
            var answer=confirm("Are you sure you want to cancel this order? All attendee records in this order will be lost.");
            if (answer==true) {
                window.location='/reg_pages/reg_order.php?action=cancel';
            }
        }

    </script>

</head>

<body>

<?php require '../includes/template/navigationBarNoLinks.php'; ?>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h2>Complete Order</h2>

        <?php if (array_key_exists('action', $_POST) && $_POST["action"] == "Paid") { ?>
            <fieldset id="paymentinfo">
                <legend>Print Badges</legend>

                <div class="container col-lg-2">
                    <form action="/reg_pages/badgeprint.php" method="post" target="_blank">
                        <input name="order" type="hidden" value="<?php echo $orderId?>" />
                        <input name="submit" id="Print Badges" type="submit" class="btn btn-primary" value="Print Badges" />

                    </form>
                </div>

                <div class="container col-lg-2">
                    <form name="Finish" action="reg_order.php" method="post">
                        <input type="hidden" name="action" value="Finish" />
                        <input name="finish" type="submit" class="btn btn-primary" value="Finish" />
                    </form>
                </div><br />
                </p>
            </fieldset>

        <?php } else { ?>
            <fieldset id="attendees">
                <legend>Attendees</legend>
                <a href="/reg_pages/reg_add.php" class="btn btn-primary">Add Another Attendee</a><br>
                <table id="attendee_table" class="table report">
                    <tr>
                        <th>Name</th>
                        <th>Pass Type</th>
                        <th>Cost</th>
                    </tr>
                    <?php
                    $total = 0;
                    for ($i=0; $i<count($_SESSION['currentOrder']); $i++) {
                        $attendee = $_SESSION['currentOrder'][$i];
                        $total += $attendee->paid_amount; ?>
                        <tr>
                            <td class="align-middle"><?php echo $attendee->first_name . ' ' . $attendee->last_name ?></td>
                            <td class="align-middle"><?php echo $attendee->pass_type ?></td>
                            <td class="align-middle">$<?php echo money_format("%i", $attendee->paid_amount) ?></td>
                            <td>
                                <form action="reg_order.php" method="get" class="col-xs-2 form-inline">
                                    <input type="hidden" name="action" value="EditAttendee">
                                    <input type="hidden" name="id" value="<?php echo $i;?>">
                                    <input type="submit" class="btn btn-link" name="edit" value="Edit">
                                </form>
                                <form action="reg_order.php" method="post" class="col-xs-2 form-inline">
                                    <input type="hidden" name="action" value="DeleteAttendee">
                                    <input type="hidden" name="id" value="<?php echo $i;?>">
                                    <input type="submit" class="btn btn-danger btn-link" name="delete" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total:</td>
                        <td>$<?php echo money_format("%i", $total)?></td>
                        <td colspan="2"></td>
                    </tr>
                </table>

            </fieldset>
            <form action="reg_order.php" method="post">
                <input type="hidden" name="total" value="<?php echo $total?>" />
                <input type="hidden" name="action" value="Paid" />

                <fieldset id="paymentinfo">
                    <legend>Payment Type</legend>
                    <input type="radio" name="PayType" value="Cash" id="Cash" onchange="requireCreditField();" />
                    <label for="Cash" class="control-label">Cash</label>
                    <br />
                    <input type="radio" name="PayType" value="Check" id="Check" onchange="requireCreditField();" />
                    <label for="Check" class="control-label">Check</label>
                    <input name="CheckNumber" type="text" id="CheckNumber" pattern="[0-9]{0,10}" /> (check number)
                    <br />
                    <input type="radio" name="PayType" value="Money Order" id="Money Order" onchange="requireCreditField();" />
                    <label for="Money Order" class="control-label">Money Order</label>
                    <br />
                    <input type="radio" name="PayType" value="Credit/Debit" id="Credit" onchange="requireCreditField();" onclick="creditAuth()" />
                    <label for="Credit" class="control-label">Credit Card</label>
                    <input name="AuthDisplay" type="text" id="AuthDisplay" pattern="[0-9a-zA-Z]{6}" /> (6 alphanumeric character authorization number)
                    <br />
                    <?php if ($total == 0) { ?>
                        <input name='PayType' type='radio' id='Manual' checked='checked' value='Free' onchange="requireCreditField();" />
                        <label for="Manual" class="control-label">Free</label>
                    <?php } ?>
                </fieldset>
                <fieldset>
                    <label for="Notes" class="control-label">Notes</label><br>
                    <textarea name="notes" id="Notes" rows="5" cols="80"></textarea>
                </fieldset>
                <br>
                <input name="Clear" type="button" class="btn btn-danger" onclick="clearVerify()" value="Cancel Order" />
                <?php if (count($_SESSION["currentOrder"]) > 0) { ?>
                    <input name="Paid" type="submit" class="btn btn-primary col-xs-offset-5" value="Take Money" />
                <?php } ?>
                </form>
        <?php } ?>
        
    </div>

    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
