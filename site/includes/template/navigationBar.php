<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Kumoricon</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/index.php">Home</a></li>
                <?php if (hasRight('prereg_checkin')) { ?>
                    <li><a href="/prereg_pages/prereg_checkin_list.php">Pre-Reg Checkin</a></li>
                <?php } ?>

                <?php if (hasRight('registration_add')) { ?>
                    <li><a href="/reg_pages/reg_add.php">At-Con Registration</a></li>
                <?php } ?>

                <?php if (hasRight('attendee_search') || hasRight('registration_modify') || hasRight('reprint_badge')) { ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Attendees <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if (hasRight('attendee_search')) { ?>
                            <li><a href="/admin/admin_attendee_list.php">Attendee Search</a></li>
                        <?php } ?>

                        <?php if (hasRight('registration_modify')) { ?>
                           <li><a href="/reg_pages/reg_update_list.php">Modify</a></li>
                        <?php } ?>
                        <?php if (hasRight('reprint_badge')) { ?>
                           <li><a href="/reg_pages/reg_badge_reprint.php">Reprint Badge</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <? } ?>
                
                <?php if (hasRight('report_view') || hasRight("manage_pass_types")) { ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administration <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if (hasRight('report_view')) { ?>
                            <li><a href="/admin/admin_report.php">Reports</a></li>
                        <?php } ?>
                        <?php if (hasRight('manage_pass_types')) { ?>
                            <li><a href="/admin/pass_type_list.php">Pass Types</a></li>
                        <?php } ?>
                        <?php if (hasRight('super_admin')) { ?>
                            <li class="divider"></li>
                            <li class="dropdown-header">Super-Admin</li>
                            <li><a href="/admin/csvimport.php">Import CSV</a></li>
                            <li><a href="/admin/reg_history_list.php">History</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>

                <?php if (hasRight('manage_staff')) { ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Manage Staff <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/staff/staff_add.php">Add User</a></li>
                        <li><a href="/staff/staff_update_list.php">Update User</a></li>
                        <li><a href="/staff/staff_contact_list.php">Staff Phone List</a></li>
                    </ul>
                </li>
                <?php } ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="/logout.php">Logout</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>