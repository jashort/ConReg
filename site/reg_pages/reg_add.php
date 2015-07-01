<?php
require_once('../includes/functions.php');

require_once('../includes/authcheck.php');
requireRight('registration_add');

if (array_key_exists('action', $_GET) && $_GET['action'] == "clear") {
    // Brand new attendee
    $_SESSION['current'] = new Attendee();
    if (!array_key_exists('currentOrder', $_SESSION)) { // Create order array if it doesn't exist
        $_SESSION['currentOrder'] = Array();
    }
    // Get it the next available badge number and set some default values
    $badge = $_SESSION['initials'] . str_pad(getBadgeNumber($_SESSION['staffid']), 4, '0', STR_PAD_LEFT);
    $_SESSION['current']->badge_number = $badge;
    $_SESSION['current']->added_by = $_SESSION['username'];
    $_SESSION['current']->reg_type = "Reg";
    $_SESSION['current']->checked_in = "N";
    $_SESSION['current']->paid = "N";
    $_SESSION['current']->parent_form = "N";
    $_SESSION['current']->ec_same = "N";
    redirect('/reg_pages/reg_add.php?part=1');
} elseif (array_key_exists('action', $_GET) && $_GET['action'] == "cancel") {
    // When canceling, return to the order screen
    unset($_SESSION['current']);
    redirect('reg_order.php');
} elseif (array_key_exists('part', $_POST)) {
    // Handle posting form data and redirecting to the next section
    if ($_POST['part'] == 1) {
        $_SESSION['current']->first_name = $_POST["first_name"];
        $_SESSION['current']->last_name = $_POST["last_name"];
        $_SESSION['current']->phone = $_POST["phone"];
        $_SESSION['current']->email = $_POST["email"];
        $_SESSION['current']->zip = $_POST["zip"];
        $_SESSION['current']->birthdate = $_POST["birth_year"] . '-' . $_POST["birth_month"] . '-' . $_POST["birth_day"];
        $_SESSION['current']->ec_fullname = $_POST["ec_fullname"];
        $_SESSION['current']->ec_phone = $_POST["ec_phone"];
        if (array_key_exists("same", $_POST)) {
            $_SESSION['current']->ec_same = $_POST["same"];
        } else {
            $_SESSION['current']->ec_same = "N";
        }
        if (array_key_exists("parent_fullname", $_POST)) {
            $_SESSION['current']->parent_fullname = $_POST["parent_fullname"];
        }
        if (array_key_exists("parent_phone", $_POST)) {
            $_SESSION['current']->parent_phone = $_POST["parent_phone"];
        }
        if (array_key_exists("parent_form", $_POST)) {
            $_SESSION['current']->parent_form = $_POST["parent_form"];
        }
        redirect('/reg_pages/reg_add.php?part=2');

    } elseif ($_POST['part'] == 2) {
        $pass = getPassType($_POST["pass_type_id"]);
        $_SESSION['current']->pass_type = $pass->category;
        $_SESSION['current']->pass_type_id = $_POST["pass_type_id"];
        if (trim($_POST["paid_amount"]) == '') {
            $_SESSION['current']->paid_amount = $pass->cost;
        } else {
            if(preg_match('/^\d{1,4}\.?\d{0,2}$/', trim($_POST["paid_amount"]))) {
                $_SESSION['current']->paid_amount = $_POST["paid_amount"];
            } else {
                die('Manual price amount must contain numbers only. Ex: 19.99');
            }
        }
        $_SESSION['current']->notes = $_POST["notes"];
        redirect('/reg_pages/reg_add.php?part=3');
    } elseif ($_POST['part'] == 3) {
        // Add the current attendee to the open order
        array_push($_SESSION['currentOrder'], $_SESSION['current']);
        unset($_SESSION['current']);
        redirect("/reg_pages/reg_order.php");
    }
} elseif (!array_key_exists("part", $_GET)) {
    // If a part of the form hasn't been specified, clear the form and then redirect to part 1
    redirect("/reg_pages/reg_add.php?action=clear");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../favicon.ico">

    <title>Registration</title>

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

        function sameInfo() {
            if (document.getElementById('Same').checked) {
                document.getElementById('Same').value = "Y";
                document.getElementById('PCFullName').value = document.getElementById('ECFullName').value;
                document.getElementById('PCPhoneNumber').value = document.getElementById('ECPhoneNumber').value;
            } else {
                document.getElementById('Same').value = "";
                document.getElementById('PCFullName').value = "";
                document.getElementById('PCPhoneNumber').value = "";
            }
        }

        // When birthdate changes, check if it's a date and if the attendee is over 18, disable the parental
        // consent form fields
        function adultCheck() {
            var month = parseInt($('#birth_month').val());
            var day = parseInt($('#birth_day').val());
            var year = parseInt($('#birth_year').val());


            if (1 <= month && month <= 12 && 1 <= day && day <= 31 && 1900 <= year && year <= 2100) {
                var today = new Date();
                var birthDate = new Date(year, month-1, day);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 18) {
                    // is Minor
                    $('#Same, #PCFullName, #PCPhoneNumber, #PCFormVer').prop("disabled", false);
                    $('#PCFullName, #PCPhoneNumber, #PCFormVer').prop("required", true);
                } else {
                    $('#Same, #PCFullName, #PCPhoneNumber, #PCFormVer').prop("disabled", true);
                    $('#PCFullName, #PCPhoneNumber, #PCFormVer').prop("required", false);
                }
                console.log(age);
            } else {
                // No date
                $('#Same, #PCFullName, #PCPhoneNumber, #PCFormVer').prop("disabled", false);
                $('#PCFullName, #PCPhoneNumber, #PCFormVer').prop("required", true);
            }
        }

        function cancelVerify() {
            var answer=confirm("Are you sure you want to cancel?");
            if (answer==true) {
                window.location='/reg_pages/reg_add.php?action=cancel';
            }
        }
        function manualPrice() {
            do {
                var amount = prompt("Please enter the amount","ex 40.00");
                var currencyCheck = new RegExp("^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$");
                var currencyFormat = currencyCheck.test(amount);
            } while ((amount=="") || (currencyFormat==false));
            do {
                var reason = prompt("Please enter the reason for the manual pricing","");
            } while (reason=="");
            document.getElementById('MPAmount').value = amount;
            document.getElementById('Notes').value = reason;
        }
    </script>
</head>

<body>

<?php require '../includes/template/navigationBarNoLinks.php'; ?>

<div class="container">
    <div class="jumbotron">
        <legend>Attendee Information</legend>
        <?php if (array_key_exists('part', $_GET) && $_GET["part"]=="1"){ ?>
            <form name="reg_add1" action="reg_add.php" method="post" class="form-horizontal">
                <input type="hidden" name="part" value="1">
                <div class="row">
                    <div class="form-group">
                        <label for="First Name" class="control-label col-sm-1">First Name</label>
                        <div class="col-sm-4">
                            <input name="first_name" type="text" maxlength="60" class="form-control" id="First Name"
                                   value="<?php echo $_SESSION['current']->first_name; ?>" autocomplete="off" autofocus required />
                        </div>
                        <div class="form-group">
                            <label for="Last Name" class="control-label col-sm-1">Last Name</label>
                            <div class="col-sm-4">
                                <input name="last_name" type="text" maxlength="60" class="form-control" id="Last Name"
                                       value="<?php echo $_SESSION['current']->last_name; ?>" autocomplete="off" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                            <label for="PhoneNumber" class="control-label col-sm-1">Phone</label>
                            <div class="col-sm-4">
                                <input name="phone" type="text" maxlength="60" class="form-control" id="PhoneNumber"
                                       value="<?php echo $_SESSION['current']->phone; ?>" />
                            </div>

                            <div class="form-group">
                                <label for="Birth Month" class="control-label col-sm-1">Birth Date:</label>
                                <div class="col-sm-4 form-inline">
                                    <?php // If a birthdate has been set, display it. Otherwise, display blank fields
                                    if ($_SESSION['current']->getAge() == -1) { ?>
                                        <input type="number" class="form-control two-character" maxlength="2" name="birth_month" id="Birth Month"
                                               value="<?php echo $_SESSION['current']->getBirthMonth() ?>" min="1" max="12" placeholder="MM"
                                               required autocomplete="off">
                                        /
                                        <input type="number" class="form-control two-character" maxlength="2" name="birth_day" id="Birth Day"
                                               value="<?php echo $_SESSION['current']->getBirthDay() ?>" min="1" max="31" placeholder="DD"
                                               required autocomplete="off">
                                        /
                                        <input type="number" class="form-control four-character" maxlength="4" name="birth_year" id="Birth Year"
                                               value="<?php echo $_SESSION['current']->getBirthYear()?>" min="1900" max="2015" placeholder="YYYY"
                                               required autocomplete="off">
                                        (Month / Day / Year)
                                    <?php } else { ?>
                                        <input type="number" class="form-control two-character" maxlength="2" name="birth_month" id="birth_month"
                                               min="1" max="12" placeholder="MM" required autocomplete="off">
                                        /
                                        <input type="number" class="form-control two-character" maxlength="2" name="birth_day" id="birth_day"
                                               min="1" max="31" placeholder="DD" required autocomplete="off">
                                        /
                                        <input type="number" class="form-control four-character" maxlength="4" name="birth_year" id="birth_year"
                                               min="1900" max="2015" placeholder="YYYY" required autocomplete="off">
                                        (Month / Day / Year)
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <label for="EMail" class="control-label col-sm-1">EMail</label>
                            <div class="col-sm-4">
                                <input name="email" type="text" class="form-control" maxlength="250" id="EMail"
                                       value="<?php echo $_SESSION['current']->email; ?>" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label for="Zip" class="control-label col-sm-1">Zip</label>
                                <div class="col-sm-2">
                                    <input name="zip" type="text" class="form-control" maxlength="10" id="Zip"
                                           value="<?php echo $_SESSION['current']->zip; ?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <legend>Emergency Contact Information</legend>
                            <div class="form-group"><label>&nbsp;</label></div>
                            <div class="form-group">
                                <label for="ECFullName" class="control-label col-sm-2">Full Name</label>
                                <div class="col-sm-8">
                                    <input name="ec_fullname" id="ECFullName" type="text" class="form-control" maxlength="250"
                                           value="<?php echo $_SESSION["current"]->ec_fullname; ?>" required autocomplete="off" autofocus />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ECPhoneNumber" class="control-label col-sm-2">Phone</label>
                                <div class="col-sm-8">
                                    <input name="ec_phone" id="ECPhoneNumber" type="text" class="form-control" maxlength="20"
                                           value="<?php echo $_SESSION['current']->ec_phone ?>" required autocomplete="off"
                                           pattern="\+?\d{10,}" title="Requires at least 10 digits" />

                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <legend>Parent Contact Info</legend>
                            <div class="form-group">
                                <label for="Same" class="control-label col-sm-5">Same as Emergency Contact Info</label>
                                <input name="same" id="Same" type="checkbox" class="checkbox-inline" value="Y" onClick="sameInfo();"
                                    <?php if ($_SESSION['current']->ec_same == "Y") { echo "checked"; } ?> />
                            </div>

                            <div class="form-group">
                                <label for="PCFullName" class="control-label col-sm-2">Full Name</label>
                                <div class="col-sm-8">
                                    <input name="parent_fullname" id="PCFullName" type="text" class="form-control" maxlength="250"
                                           value="<?php echo $_SESSION['current']->ec_fullname; ?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="PCPhoneNumber" class="control-label col-sm-2">Phone</label>
                                <div class="col-sm-8">
                                    <input name="parent_phone" id="PCPhoneNumber" type="tel" class="form-control" maxlength="10"
                                           value="<?php echo $_SESSION['current']->ec_phone; ?>" autocomplete="off" required
                                           pattern="\+?\d{10,}" title="Requires at least 10 digits" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="PCFormVer" class="control-label col-sm-5">Parental Consent Form Received</label>
                                <div class="col-sm-2">
                                    <input name="parent_form" type="checkbox" value="Y"
                                        <?php if ($_SESSION['current']->parent_form == "Y") { echo "checked"; } ?>
                                           id="PCFormVer" class="checkbox" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input name="Cancel" type="button" class="btn btn-danger" onclick="cancelVerify()" value="Cancel" />
                    <input name="Next" type="submit" class="btn btn-primary col-xs-offset-5" value="Next" />
            </form>
        <?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="2") { ?>
            <form name="reg_add2" action="reg_add.php" method="post" class="form-inline">
                <input name="part" type="hidden" value="2" />
                <fieldset id="paymentinfo">
                    <legend>Pass Type</legend>

                    <div class="form-group">
                        <label for="pass_type_id" class="control-label col-sm-5">Select Pass Type:</label>
                        <div class="col-sm-2">
                            <select name="pass_type_id" required class="form-control">
                                <?php
                                $passTypeList = passTypeForAgeList($_SESSION['current']->getAge());
                                while ($passType = $passTypeList->fetch()) { ?>
                                    <option value="<?php echo $passType->id?>"
                                        <?php if ($_SESSION['current']->pass_type_id == $passType->id) echo " selected";?>>
                                        <?php echo $passType->name?> - $<?php echo $passType->cost?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <?php if (hasRight('registration_manual_price')) { ?>
                        <div class="form-group">
                            <label for="pass_type" class="control-label col-sm-5">Set Manual Price:<br/> <small>(blank for default price)</small></label>
                            <div class="col-sm-2">
                                <input name="paid_amount" type="text" class="form-control" id="paid_amount"
                                       value="<?php echo $_SESSION['current']->paid_amount ?>" />
                            </div>
                        </div>
                    <?php } ?>
                    <br />
                </fieldset>
                <fieldset id="notes">
                    <label for="Notes" class="control-label col-sm-2">Notes</label><br>
                    <textarea name="notes" id="Notes" rows="5" cols="80"><?php echo $_SESSION['current']->notes; ?></textarea>
                </fieldset>
                <br>
                <input name="Cancel" type="button" class="btn btn-danger" onclick="cancelVerify()" value="Cancel" />
                <input name="Next" type="submit" class="btn btn-primary col-xs-offset-5" value="Next" />

            </form>
        <?php } elseif (array_key_exists('part', $_GET) && $_GET["part"]=="3") { ?>
            <fieldset id="personalinfo">
                <legend>Attendee Info</legend>
                <label>Name: </label>
                <?php echo $_SESSION['current']->first_name; ?>
                <?php echo $_SESSION['current']->last_name; ?>
                <br />
                <label>Phone Number: </label>
                <?php echo $_SESSION['current']->phone; ?>
                <br />
                <label>Email: </label>
                <?php echo $_SESSION['current']->email; ?>
                <br />
                <label>Zip: </label>
                <?php echo $_SESSION['current']->zip; ?>
                <br />
                <label>Birth Date</label>
                <?php echo $_SESSION['current']->getBirthDate() ?>
            </fieldset>
            <br />
            <fieldset id="emergencyinfo">
                <legend>Emergency Contact Info</legend>
                <label>Full Name: </label>
                <?php echo $_SESSION['current']->ec_fullname ?>
                <br />
                <label>Phone Number: </label>
                <?php echo $_SESSION['current']->ec_phone ?>
                <br />
            </fieldset>
            <?php if ($_SESSION['current']->isMinor()) { ?>
                <br />
                <fieldset id="parentinfo">
                    <legend>Parent Contact Info</legend>
                    <label>Full Name: </label>
                    <?php echo $_SESSION['current']->parent_fullname ?>
                    <br />
                    <label>Phone Number: </label>
                    <?php echo $_SESSION['current']->parent_phone ?>
                    <br />
                    <label>Parental Permission Form Submitted: </label>
                    <?php echo $_SESSION['current']->parent_form; ?>
                </fieldset>
            <?php } ?>
            <br />
            <fieldset id="paymentinfo">
                <legend>PASS TYPE</legend>
                <?php echo $_SESSION['current']->pass_type ?> - $<?php echo $_SESSION['current']->paid_amount ?>
            </fieldset>

            <br />
            <fieldset id="notes">
                <legend>NOTES</legend>
                <?php echo $_SESSION['current']->notes; ?>
            </fieldset>

            <br>
            <form name="reg_add" action="reg_add.php" method="post">
                <input type="hidden" name="SubmitNow" value="Yes" />
                <input type="hidden" name="part" value="3" />
                <!--<input name="Previous" type="button" class="next_button" onclick="MM_goToURL('parent','/reg_pages/reg_add.php?part=3');return document.MM_returnValue" value="Previous" />-->
                <input name="Cancel" type="button" class="btn btn-danger" onclick="cancelVerify()" value="Cancel" />
                <input name="Done" type="submit" class="btn btn-primary col-xs-offset-5" value="Done" />
            </form>
        <?php } ?>

    </div>
    <?php require '../includes/template/footer.php' ?>

</div> <!-- /container -->


<?php require '../includes/template/scripts.php' ?>
<script type="text/javascript">
    $('#birth_month').on('input', adultCheck);
    $('#birth_day').on('input', adultCheck);
    $('#birth_year').on('input', adultCheck);
</script>

</body>
</html>
