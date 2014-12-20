<div id="menu">
    <ul>
        <li><a href="/index.php">HOME</a></li>
    </ul>
    <?php if ($_SESSION['access']==0) { ?>
        <ul>
            <li class="header_li">Ops</li>
            <li><a href="/opssearch/attendee_list.php">SEARCH</a></li>
        </ul>
    <?php } ?>

    <?php if ($_SESSION['access']!=0) { ?>
        <ul>
            <li class="header_li">PRE-REGISTRATION</li>
            <li><a href="/prereg_pages/prereg_checkin_list.php">CHECK IN</a></li>
        </ul>
        <ul>
            <li class="header_li">REGISTRATION</li>
            <li><a href="/reg_pages/reg_add.php">NEW</a></li>
            <!--<li><a href="/reg_pages/reg_tablet_complete_list.php">TABLET</a></li>-->
            <?php if ($_SESSION['access']>=2) { ?>
                <li><a href="/reg_pages/reg_update_list.php">UPDATE</a></li>
            <?php } ?>
            <?php if ($_SESSION['access']>=3) { ?>
                <li><a href="/reg_pages/reg_badge_reprint.php">REPRINT BADGE</a></li>
            <?php } ?>
            <!--<li><a href="/reg_pages/reg_quick_add.php">QUICK REG</a></li>
            <li><a href="/reg_pages/reg_quick_complete_list.php">QUICK REG COMPLETE</a></li>-->
        </ul>
        <?php if ($_SESSION['access']>=3) { ?>
            <ul>
                <li class="header_li">USER ADMIN</li>
                <li><a href="/staff/staff_add.php">ADD REGISTRATION USER</a></li>
                <li><a href="/staff/staff_update_list.php">UPDATE REGISTRATION USER</a></li>
                <li><a href="/staff/staff_contact_list.php">STAFF PHONE LIST</a></li>
            </ul>
        <?php } ?>
        <ul>
            <?php if ($_SESSION['access']>=3) { ?>
                <li class="header_li">KUMORICON ADMIN</li>
                <li><a href="/admin/admin_attendee_list.php">SEARCH ATTENDEE</a></li>
            <?php } ?>
            <?php if ($_SESSION['access']>=4) { ?>
                <li><a href="/admin/csvimport.php">IMPORT CSV</a></li>
                <li><a href="/admin/admin_report.php">REPORTS</a></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <ul>
        <li class="header_li"><a href="/logout.php">Logout</a></li>
    </ul>
</div>