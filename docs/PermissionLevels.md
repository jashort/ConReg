Permission Levels
-----------------

Roles are configured in includes/roles.php. A role does NOT inherit the permissions of 
roles numerically lower on the list. (Permission names are in parenthesis)

- 1: User
    + Can add attendees (registration_add)
    + Can check in preregistered attendees (prereg_add)
    + Can only print badges when adding an attendee (badge_print)
  
- 2: Super User
    + Can add attendees (registration_add)
    + Can modify attendees (registration_modify)
    + Can set price manually for a attendee (registration_manual_price)
    + Can check in preregistered attendees (prereg_add)
    + Can only print badges when adding an attendee. (badge_print)
  
- 3: Manager
    + Can add attendees (registration_add)
    + Can modify attendees (registration_modify)
    + Can set price manually for a attendee (registration_manual_price)
    + Can check in preregistered attendees (prereg_add)
    + Can print badges (badge_print)
    + Can reprint badges (badge_reprint)
    + Can add or modify staff users (manage_staff)
    + Can view staff phone list (manage_staff)
    + Can search attendee list (attendee_search)
  
- 4: Operations
    + Can search for attendees and view limited information (ops_search)
  
- 99: Super User
    + All rights granted by override in the has_right() function.
    
    
Permissions are set on each page, with either the has_right() function (to test if the logged in 
user has the given right) or the require_right() functions (abort page load if the given right
isn't present).

    