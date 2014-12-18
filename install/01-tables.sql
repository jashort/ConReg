/* Create database tables */
USE registration;

CREATE TABLE registration.kumo_reg_staff
(
    kumo_reg_staff_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    kumo_reg_staff_username VARCHAR(60) NOT NULL,
    kumo_reg_staff_password CHAR(98) NOT NULL,
    kumo_reg_staff_enabled BOOLEAN DEFAULT TRUE NOT NULL,
    kumo_reg_staff_fname VARCHAR(60) NOT NULL,
    kumo_reg_staff_lname VARCHAR(60) NOT NULL,
    kumo_reg_staff_initials CHAR(3) NOT NULL,
    kumo_reg_staff_phone_number VARCHAR(60),
    kumo_reg_staff_accesslevel INT DEFAULT 1 NOT NULL
);
ALTER TABLE registration.kumo_reg_staff ADD CONSTRAINT unique_kumo_reg_staff_id UNIQUE (kumo_reg_staff_id);
ALTER TABLE registration.kumo_reg_staff ADD CONSTRAINT unique_kumo_reg_staff_initials UNIQUE (kumo_reg_staff_initials);


CREATE TABLE registration.orders
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    amount DECIMAL(10,0) NOT NULL,
    paid TINYINT NOT NULL
);
CREATE UNIQUE INDEX unique_id ON orders (id);
