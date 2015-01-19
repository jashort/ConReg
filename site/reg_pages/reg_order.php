<?php
require_once('../includes/functions.php');
require_once('../includes/passtypes.php');

require_once('../includes/authcheck.php');
require_right('registration_add');

if ((isset($_POST["action"])) && ($_POST["action"] == "Finish")) {
    $_SESSION["currentOrder"] = Array();
    unset ($_SESSION["current"]);
    redirect("/index.php");
}

if ((isset($_POST["action"])) && ($_POST["action"] == "Paid")) {
    $orderId = regAddOrder($_SESSION['currentOrder']);
    ordercheckin($orderId);
    orderpaid($orderId, $_POST["PayType"], $_POST["total"], $_POST['Notes']);
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

        function creditAuth() {
            do {
                var number=prompt("Please enter the authorization number","ex 123456");
            } while ((number=="") || (number=="ex 123456"));

            if (document.getElementById('Notes').value == "") {
                document.getElementById('Notes').value = "The Credit Card Authorization Number is: " + number;
            } else {
                document.getElementById('Notes').value = document.getElementById('Notes').value + "---" + "The Credit Card Authorization Number is: " + number;
            }

            document.getElementById("AuthDisplay").value = number;
        }
    </script>
    <!-- InstanceEndEditable -->
</head>
<body>
<div id="header"></div>
<?php require "../includes/leftmenu.php" ?>

<div id="content"><!-- InstanceBeginEditable name="Content" -->
    <? if (array_key_exists('action', $_POST) && $_POST["action"] == "Paid") { ?>
    <fieldset id="paymentinfo">
        <legend>PRINT BADGES</legend>
    <p>
        <div class="centerbutton">
            <form action="/reg_pages/badgeprint.php" method="post" target="_blank">
                <input name="order" type="hidden" value="<?php echo $orderId?>" />
                <input name="action" type="submit" class="badge_button" value="Print Badges" />
                
            </form>
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
        <table id="attendee_table">
            <tr>
                <th>Name</th>
                <th>Pass Type</th>
                <th>Cost</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['currentOrder'] as $attendee) {
                $total += $attendee->paid_amount;
                ?>
                <tr>
                    <td><? echo $attendee->first_name . ' ' . $attendee->last_name ?></td>
                    <td><? echo $attendee->pass_type ?></td>
                    <td>$<? echo $attendee->paid_amount ?></td>
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
        <legend>PAYMENT TYPE</legend>
        <p>
            <label>
                <input type="radio" name="PayType" value="Cash" id="PayType_1"  />
                Cash</label>
            <br />
            <label>
                <input type="radio" name="PayType" value="Check" id="PayType_2" />
                Check</label>
            <br />
            <label>
                <input type="radio" name="PayType" value="Money Order" id="PayType_3" />
                Money Order</label>
            <br />
            <label>
                <input type="radio" name="PayType" value="Credit/Debit" id="PayType_3" onclick="creditAuth()" />
                Credit Card</label>
            <input name="AuthDisplay" type="text" class="input_20_150" id="AuthDisplay" disabled="disabled"/>
            </span>
            <br />

            <?php if ($total == 0) { ?>
                <label><input name='PayType' type='radio' id='PayType_4' checked='checked' value='Free' /> Free</label>
            <?php } ?>
        </p>
    </fieldset>
        <fieldset id="notes">
            <label>Notes : </label>
            <textarea name="Notes" id="Notes" rows="5"></textarea>
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
