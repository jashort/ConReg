Data Import Format for Attendees Who Preregister on Website
===========================================================

 Tab delimited file with Unix line endings (LF). The first row is a header and is
 currently ignored. (IE, it makes the file easier to read, but changing the field order
 would break the import)
 An example file is [here](PreRegDataImportExample.csv).
 
 
 Contains the following fields:

| Field | Name              | Notes                                                    |
| ----: | ----------------- | -------------------------------------------------------- |
|   1   | First Name        | 60 characters max                                        |
|   2   | Last Name         | 60 characters max                                        |
|   3   | Badge Name        | 60 characters max [[1]](#1)                              |
|   4   | Badge Number      | 10 characters max, empty/NULL is okay [[2]](#2)          |
|   5   | Zip Code          | 10 characters max                                        |
|   6   | Country           | 250 characters max                                       |
|   7   | Phone             | 60 characters max, numbers only                          |
|   8   | Email Address     | 250 characters max                                       |
|   9   | Birthdate         | YYYY-MM-DD format (ex: 1990-12-30)                       |
|  10   | Emergency Contact | Emergency Contact Name, 250 characters max               |
|  11   | Emergency Phone   | Emergency Contact Phone, 250 characters max              |
|  12   | EC Same as Parent | "Y" if emergency contact is parent, "N" otherwise        |
|  13   | Parent Name       | 250 characters max                                       |
|  14   | Parent Phone      | 250 characters max                                       |
|  15   | Paid              | "Y" if paid, "N" otherwise                               |
|  16   | Amount            | Amount Paid, numbers/decimal only. (ex: 50.00 )          |
|  17   | Pass Type         | 50 characters max, valid values: "Weekend", "VIP"        |
|  18   | Order ID          | ??? Currently an integer [[3]](#3)                       |
 
 
 
Notes:
------
<a name="1">1:</a> Currently we're storing 60 characters for the badge name. I believe the actual limit
will be less. 

<a name="2">2:</a> If the website generates/stores badge numbers, put it here. Otherwise just leave it
blank. Previously, this was generated when data was imported in to the registration system.

<a name="3">3:</a> Order ID is currently an integer in the registration system, but if the web site
uses something else let me know. The goal is to associate attendees who register at the same
time/pay together.