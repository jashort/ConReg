/* 
FOR TESTING ONLY

Clear staff database and add a test user for each role

Password for each user is the same as the username.
*/
DELETE FROM reg_staff;
INSERT INTO registration.reg_staff 
  (staff_id, username, password, enabled, first_name, last_name, initials, phone_number, last_badge_number, access_level) 
VALUES 
  (1, 'admin', '$6$SScMU5ix$/3EqX9Z/PFJs7T5sOz0iUpMff7Vw6VXLUdbcPDicyZRhn3phI8CXqL0eQuVtJFKp05M8uzt.jZy9SGIo6T968.', 1, 'Default', 'Admin', 'DA', '(123) 456-7890', 3, 99),
  (2, 'user', '$6$t.uQ4O4f$2lsfXRdU5LJMRoNKjf7U0QrQEqHQBPSCJlhS4JE/es26Hh5GXbEYrgZN3SKhI.7crUrZQcYEQciGJYCuXV9J91', 1, 'User', 'User', 'uu', '(123) 412-3412', 0, 1),
  (3, 'super', '$6$l3tXgvLf$8fGth5SL5EmXka4cpL2JhptNXEIy2iEHa1cbVuwVFTplCx9NWJ8mOKP/lX/YSCYwSZ61WhUQIAbDowDZmzo2B/', 1, 'Super', 'User', 'su', '(124) 321-4321', 0, 2),
  (4, 'manager', '$6$kvJ9Onst$iUoGfo1drT4S7xlKtgi8nGiVNYbFf5mfJiKKY.PD8P3lsmJUQStt2TCXYyXL9ZdRXtjpqV7kaFBizIlv1GWEX0', 1, 'Manager', 'User', 'MU', '(142) 314-2134', 0, 3),
  (5, 'operations', '$6$23Oa0uA9$ISJyna32aG4W82zX43frTH.kI/CqXML1du5PFHN6QsjoQB1zzozpKi6bMK/MXYNxB69tPR8r5sG347QHzqSQf/', 1, 'Operations', 'User', 'OU', '(124) 312-3421', 0, 4);
