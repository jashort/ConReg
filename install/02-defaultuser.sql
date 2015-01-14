/* Add a default admin user */
USE registration;

INSERT INTO reg_staff (username, password, enabled,
first_name, last_name, initials, phone_number, access_level)
    VALUES (
      'admin',
      '$6$KOPxdTQi$yFThfbOilEPcmipj8T3D.PvFE9c1bALHbIeUvlGs4L3jonu0VhRNqwq0RZxEUPeDgloC/oqQ0KlwMRc17Zk5h/',
      1,
      'Default',
      'Admin',
      'DA',
      '(123) 456-7890',
      99
    )
