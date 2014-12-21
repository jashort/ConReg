<?php

/*
 * Role rights and names. Roles configured here are stored in the database in
 * the kumo_reg_staff.kumo_reg_staff_accesslevel field.
 * On login, the individual rights are stored in $_SESSION['rights']
 *
 * Use the functions has_right() and require_right() to interact with them.
 *
 * For example, to create a page that will only display for users with the "badge_print" right:
 *
 * require_once('../includes/authcheck.php');
 * require_right('badge_print');
 *
 * If the currently logged in user ($_SESSION) doesn't have the "badge_print" right, an error
 * will be displayed
 */

$ROLES = array(
    1 => array('name' => 'User',
        'rights' => Array(
            'prereg_checkin' => true,
            'registration_add' => true,
            'badge_print' => true,
        )),
    2 => array('name' => 'Super User',
        'rights' => Array(
            'prereg_checkin' => true,
            'registration_add' => true,
            'registration_modify' => true,
            'badge_print' => true,
        )),
    3 => array('name' => 'Manager',
        'rights' => Array(
            'prereg_checkin' => true,
            'registration_add' => true,
            'registration_modify' => true,
            'badge_print' => true,
            'badge_reprint' => true,
            'attendee_search' => true,
            'manage_staff' => true,
        )),
    4 => array('name' => 'Operations',
        'rights' => Array(
            'ops_search' => true
        )),
    99 => array('name' => 'Super Admin',
        'rights' => Array(
            'super_admin' => true,
        ))
);

/**
 * Return the array of rights for a given role ID number, or an empty array if not found.
 * @param $roleID
 * @return mixed
 */
function get_rights($roleID) {
    global $ROLES;
    if (array_key_exists($roleID, $ROLES)) {
        return $ROLES[$roleID]['rights'];
    } else {
        return array();
    }
}
