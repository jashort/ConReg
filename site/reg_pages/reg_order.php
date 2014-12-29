<?php
require('../includes/functions.php');
require('../includes/authcheck.php');
require('../includes/passtypes.php');

require_right('registration_add');

if ((isset($_POST["action"])) && ($_POST["action"] == "Finish")) {
    ordercheckin($_SESSION["OrderId"]);
    unset ($_SESSION["OrderId"]);
    redirect("/index.php");
}

if ((isset($_POST["action"])) && ($_POST["action"] == "Paid")) {
    orderpaid($_SESSION["OrderId"], $_POST["PayType"], $_POST["total"]);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>Complete Order</title>
    <!-- InstanceEndEditable -->
    <link href="../assets/css/kumoreg.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        function MM_goToURL() { //v3.0
            var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
            for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
        }
    </script>
    <script src="/assets/javascript/jquery-1.8.0.js" type="text/javascript"></script>
    <script src="/assets/javascript/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
    <!-- InstanceBeginEditable name="head" -->
    <script type="text/javascript">
        function MM_validateForm() { //v4.0
            if (document.getElementById){
                var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
                for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
                    if (val) { nm=val.id; if ((val=val.value)!="") {
                        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
                            if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
                        } else if (test.indexOf('isDate')!=-1) { var nulldate=new RegExp("(MM|DD|YYYY)"); p=nulldate.test(val);
                            if (p==true) errors+='- '+nm+' is required.\n';
                        } else if (test.indexOf('isState')!=-1) { p=val.indexOf('State');
                            if (p>1 || p==(val.length-1)) errors+='- '+nm+' is required.\n';
                        } else if (test!='R') { num = parseFloat(val);
                            if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
                            if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
                                min=test.substring(8,p); max=test.substring(p+1);
                                if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
                            } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
                } if (errors) alert('The following error(s) occurred:\n'+errors);
                document.MM_returnValue = (errors == '');
            } }
        function verifyEmail(){
            var status = false;
            if (document.reg_add1.EMail.value != document.reg_add1.EMailV.value) {
                alert("Email addresses do not match.  Please retype them to make sure they are the same.");
            }}
        function MM_goToURL() { //v3.0
            var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
            for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
        }
        function MM_openBrWindow(theURL,winName,features) { //v2.0
            window.open(theURL,winName,features);
        }
        function clearverify() {
            var answer=confirm("Are you sure you want to clear?");
            if (answer==true)
            {
                MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue;
            }
            else
            {
            }
        }
        <?php
        if (isset($_SESSION["FirstName"])) {
        if (!((stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_tablet_complete_list.php')) || (stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_add.php')) || (stristr($_SERVER['HTTP_REFERER'], '/reg_pages/reg_lastyear_list.php')))) { ?>
        (function() {
            var answer=confirm("Attendee information is set in this form and hasn't been submitted. If you are continuing please press Cancel, otherwise press Ok and the form will be cleared.");
            if (answer==true)
            {
                MM_goToURL('parent','../includes/functions.php?action=clear');return document.MM_returnValue;
            }
            else
            {
            }
        })();
        <?php } }?>
        function manualprice() {

            do {
                var amount=prompt("Please enter the amount","ex 40.00");
                var currencycheck=new RegExp("^(([0-9]\.[0-9][0-9])|([0-9][0-9]\.[0-9][0-9]))$");
                var currencyformat = currencycheck.test(amount);
            } while ((amount=="") || (currencyformat==false));

            do {
                var reason=prompt("Please enter the reason for the manual pricing","");
            } while (reason=="");

            document.reg_add3.MPAmount.value = amount;
            document.reg_add3.Amount.value = amount;
            document.reg_add3.Notes.value = reason;

        }
        function creditauth() {

            do {
                var number=prompt("Please enter the authorization number","ex 123456");
            } while ((number=="") || (number=="ex 123456"));

            if (document.reg_add3.Notes.value == "") {
                document.reg_add3.Notes.value = "The Credit Card Authorization Number is: " + number;
            } else {
                document.reg_add3.Notes.value = document.reg_add3.Notes.value + "---" + "The Credit Card Authorization Number is: " + number;
            }

            document.reg_add3.AuthDisplay.value = number;
        }
        <?php if ($year_diff > 5) { ?>
        function radiobutton() {

            //var len = document.form.name.length;
            var len = document.reg_add3.PayType.length;

            for (i = 0; i < len; i++) {
                if ( document.reg_add3.PayType[i].checked ) {
                    return true;
                }
            }
            alert('Please select a payment type!');
            return false;

        }
        <?php } ?>
    </script>
    <!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
    <? if ($_POST["action"] == "Paid") { ?>
    <fieldset id="paymentinfo">
        <legend>PAYMENT TYPE</legend>
    <p>
        <div class="centerbutton">
            <input name="Badge" type="button" class="badge_button" onclick="MM_openBrWindow('/reg_pages/badgeprint.php','','');return document.MM_returnValue" value="Print Badges" />
        </div>
        <div class="centerbutton">
            <form name="Finish" action="reg_order.php" method="post">
                <input type="hidden" name="action" value="Finish" />
                <input name="Submit" type="submit" class="next_button" value="Finish" />
            </form>
        </div><br />
    </p>
    </fieldset>

    <?php } else { ?>
    <fieldset id="attendees">
        <legend>ATTENDEES</legend>
        <a href="/reg_pages/reg_add.php">Add Another</a><br>
        <table>
            <tr>
                <th>Name</th>
                <th>Pass Type</th>
                <th>Cost</th>
            </tr>
            <?php
            $total = 0;
            foreach (orderlistattendees($_SESSION['OrderId']) as $attendee) {
                $total += $attendee['kumo_reg_data_paidamount'];
                ?>
                <tr>
                    <td><? echo $attendee['kumo_reg_data_fname'] . ' ' . $attendee['kumo_reg_data_lname']?></td>
                    <td><? echo $attendee['kumo_reg_data_passtype']?></td>
                    <td>$<? echo $attendee['kumo_reg_data_paidamount']?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" style="text-align: right;">Total:</td>
                <td>$<?php echo $total?></td></tr>
        </table>

    </fieldset>
    <form action="reg_order.php" method="post">
        <input type="hidden" name="orderid" value="<?php echo $_SESSION['OrderId']?>" />
        <input type="hidden" name="total" value="<?php echo $total?>" />
        <input type="hidden" name="action" value="Paid" />

    <fieldset id="paymentinfo">
        <legend>PAYMENT TYPE</legend>
        <p>
            <label>
                <input type="radio" name="PayType" value="Cash" id="PayType_1" <?php if ($_SESSION["PayType"] == "Cash") echo "checked=\"checked\""; ?> />
                Cash</label>
            <br />
            <label>
                <input type="radio" name="PayType" value="Check" id="PayType_2" <?php if ($_SESSION["PayType"] == "Check") echo "checked=\"checked\""; ?> />
                Check</label>
            <br />
            <label>
                <input type="radio" name="PayType" value="Money Order" id="PayType_3" <?php if ($_SESSION["PayType"] == "Money Order") echo "checked=\"checked\""; ?>/>
                Money Order</label>
            <br />
            <label>
                <input type="radio" name="PayType" value="Credit/Debit" id="PayType_3" onclick="creditauth()" <?php if ($_SESSION["PayType"] == "Credit/Debit") echo "checked=\"checked\""; ?>/>
                Credit Card</label>
            <input name="AuthDisplay" type="text" class="input_20_150" id="AuthDisplay" value="<?php echo $_SESSION["AuthNumber"] ?>" disabled="disabled"/>
            </span>
            <br />

            <?php if ($total == 0) { ?>
                <label><input name='PayType' type='radio' id='PayType_4' checked='checked' value='Free' /> Free</label>
            <?php } ?>
        </p>
    </fieldset>

        <div class="centerbutton">
            <input name="Paid" type="submit" class="badge_button" value="Take Money" />
        </div>
    </form>
    <?php } ?>
    <!-- InstanceEndEditable --></div>
<div id="footer">&copy; Tim Zuidema</div>
<!-- InstanceBeginEditable name="Javascript" --><!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
