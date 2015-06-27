<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('registration_add');

if ((isset($_POST["action"])) && ($_POST["action"] == "Finish")) {
    $_SESSION["currentOrder"] = Array();
    unset ($_SESSION["current"]);
    redirect("/index.php");
}

if ((isset($_POST["action"])) && ($_POST["action"] == "Paid")) {
    $orderId = regAddOrder($_SESSION['currentOrder']);
    logMessage($_SESSION['username'], 120, "At-Con Registration order ID ". $orderId);

    orderCheckIn($orderId);
    orderPaid($orderId, $_POST["PayType"], $_POST["total"], $_POST['notes']);
    foreach ($_SESSION["currentOrder"] as $attendee) {
        logMessage($_SESSION['username'], 30, "At-Con Check in " . $attendee->first_name . ' '. $attendee->last_name);
    }
} elseif (isset($_GET["action"]) && ($_GET["action"] == "clear")) {
    $_SESSION["currentOrder"] = Array();
    unset ($_SESSION["current"]);
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
                var number=prompt("Please enter the 6 digit authorization number","ex 123456");
            } while ((number=="") || (number=="ex 123456"));

            if (document.getElementById('Notes').value == "") {
                document.getElementById('Notes').value = "The Credit Card Authorization Number is: " + number;
            } else {
                document.getElementById('Notes').value = document.getElementById('Notes').value + "---" + "The Credit Card Authorization Number is: " + number;
            }

            document.getElementById("AuthDisplay").value = number;
        }
        
        function requireCreditField() {
            if (document.getElementById("Credit").checked) {
                document.getElementById("AuthDisplay").required = true;
            } else {
                document.getElementById("AuthDisplay").required = false;
                
            }
        }
        function clearVerify() {
            var answer=confirm("Are you sure you want to cancel this order? All attendee records in this order will be lost.");
            if (answer==true) {
                window.location='/reg_pages/reg_order.php?action=clear';
            }
        }

    </script>

</head>

<body>

<?php require '../includes/template/navigationBar.php'; ?>

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
                <a href="/reg_pages/reg_add.php">Add Another</a><br>
                <table id="attendee_table" class="table report">
                    <tr>
                        <th>Name</th>
                        <th>Pass Type</th>
                        <th>Cost</th>
                    </tr>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['currentOrder'] as $attendee) {
                        $total += $attendee->paid_amount; ?>
                        <tr>
                            <td><?php echo $attendee->first_name . ' ' . $attendee->last_name ?></td>
                            <td><?php echo $attendee->pass_type ?></td>
                            <td>$<?php echo $attendee->paid_amount ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2" style="text-align: right;">Total:</td>
                        <td>$<?php echo $total?></td></tr>
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
                    <br />
                    <input type="radio" name="PayType" value="Money Order" id="Money Order" onchange="requireCreditField();" />
                    <label for="Money Order" class="control-label">Money Order</label>
                    <br />
                    <input type="radio" name="PayType" value="Credit/Debit" id="Credit" onchange="requireCreditField();" onclick="creditAuth()" />
                    <label for="Credit" class="control-label">Credit Card</label>
                    <input name="AuthDisplay" type="text" id="AuthDisplay" pattern="\d{6}" /> (6 digit authorization number)
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
                <input name="Paid" type="submit" class="btn btn-primary col-xs-offset-5" value="Take Money" />
            </form>
        <?php } ?>
        
    </div>

    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->

<?php require '../includes/template/scripts.php' ?>

</body>
</html>
