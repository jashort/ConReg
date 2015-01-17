<div id="menu">
    <ul>
        <li><a href="/index.php">HOME</a></li>
    </ul>
    <?php if (has_right('ops_search')) { ?>
        <ul>
            <li class="header_li">Ops</li>
            <li><a href="/opssearch/attendee_list.php">SEARCH</a></li>
        </ul>
    <?php } ?>

    <?php if (has_right('prereg_checkin')) { ?>
        <ul>
            <li class="header_li">PRE-REGISTRATION</li>
            <li><a href="/prereg_pages/prereg_checkin_list.php">CHECK IN</a></li>
        </ul>
    <?php } ?>
    <?php if (has_right('registration_add')) { ?>
        <ul>
            <li class="header_li">REGISTRATION</li>
            <li><a href="/reg_pages/reg_add.php">NEW</a></li>
            <!--<li><a href="/reg_pages/reg_tablet_complete_list.php">TABLET</a></li>-->
            <?php if (has_right('registration_modify')) { ?>
                <li><a href="/reg_pages/reg_update_list.php">UPDATE</a></li>
            <?php } ?>
            <?php if (has_right('reprint_badge')) { ?>
                <li><a href="/reg_pages/reg_badge_reprint.php">REPRINT BADGE</a></li>
            <?php } ?>
        </ul>
        <?php if (has_right('manage_staff')) { ?>
            <ul>
                <li class="header_li">USER ADMIN</li>
                <li><a href="/staff/staff_add.php">ADD REGISTRATION USER</a></li>
                <li><a href="/staff/staff_update_list.php">UPDATE REGISTRATION USER</a></li>
                <li><a href="/staff/staff_contact_list.php">STAFF PHONE LIST</a></li>
            </ul>
        <?php } ?>
        <ul>
            <?php if (has_right('attendee_search')) { ?>
                <li class="header_li">KUMORICON ADMIN</li>
                <li><a href="/admin/admin_attendee_list.php">SEARCH ATTENDEE</a></li>
            <?php } ?>
            <?php if (has_right('super_admin')) { ?>
                <li><a href="/admin/csvimport.php">IMPORT CSV</a></li>
                <li><a href="/admin/reg_history_list.php">HISTORY</a></li>
            <?php } ?>
            <?php if (has_right('report_view')) { ?>
                <li><a href="/admin/admin_report.php">REPORTS</a></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <ul>
        <li class="header_li"><a href="/logout.php">Logout</a></li>
    </ul>
</div>