/* Add a default admin user */
USE registration;

INSERT INTO kumo_reg_staff (kumo_reg_staff_username, kumo_reg_staff_password, kumo_reg_staff_enabled,
kumo_reg_staff_fname, kumo_reg_staff_lname, kumo_reg_staff_initials, kumo_reg_staff_phone_number, kumo_reg_staff_accesslevel)
    VALUES (
      'admin',
      '$6$KOPxdTQi$yFThfbOilEPcmipj8T3D.PvFE9c1bALHbIeUvlGs4L3jonu0VhRNqwq0RZxEUPeDgloC/oqQ0KlwMRc17Zk5h/',
      1,
      'Default',
      'Admin',
      'DA',
      '(123) 456-7890',
      4
    )
