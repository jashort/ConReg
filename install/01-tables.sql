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
    kumo_reg_staff_bnumber INT DEFAULT 0 NOT NULL COMMENT 'Last Badge Number Created',
    kumo_reg_staff_accesslevel INT DEFAULT 1 NOT NULL
);
ALTER TABLE registration.kumo_reg_staff ADD CONSTRAINT unique_kumo_reg_staff_id UNIQUE (kumo_reg_staff_id);
ALTER TABLE registration.kumo_reg_staff ADD CONSTRAINT unique_kumo_reg_staff_initials UNIQUE (kumo_reg_staff_initials);


CREATE TABLE registration.kumo_reg_data
(
    kumo_reg_data_id         INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    kumo_reg_data_fname      VARCHAR(60)     NOT NULL,
    kumo_reg_data_lname      VARCHAR(60)     NOT NULL,
    kumo_reg_data_bname      VARCHAR(60) COMMENT 'Badge Name',
    kumo_reg_data_bnumber    VARCHAR(10),
    kumo_reg_data_address    VARCHAR(250),
    kumo_reg_data_city       VARCHAR(80),
    kumo_reg_data_state      VARCHAR(20),
    kumo_reg_data_zip        VARCHAR(10),
    kumo_reg_data_country    VARCHAR(250),
    kumo_reg_data_phone      VARCHAR(60),
    kumo_reg_data_email      VARCHAR(250),
    kumo_reg_data_bdate      DATE,
    kumo_reg_data_ecfullname VARCHAR(250),
    kumo_reg_data_ecphone    VARCHAR(250),
    kumo_reg_data_same       CHAR(1) COMMENT 'Values: Y or empty string',
    kumo_reg_data_parent     VARCHAR(250),
    kumo_reg_data_parentphone VARCHAR(60),
    kumo_reg_data_parentform CHAR(3) COMMENT 'Values: yes or no',
    kumo_reg_data_paid       CHAR(3) COMMENT 'Values: yes or no',
    kumo_reg_data_paidamount DECIMAL(5, 2),
    kumo_reg_data_passtype   VARCHAR(50),
    kumo_reg_data_regtype    VARCHAR(50),
    kumo_reg_data_paytype    VARCHAR(50),
    kumo_reg_data_checkedin  CHAR(3) COMMENT 'Values: yes or no',
    kumo_reg_data_notes      TEXT,
    kumo_reg_data_staff_add  VARCHAR(60),
    kumo_reg_data_timestamp  TIMESTAMP
);


CREATE TABLE kumo_reg_quick_data (
    kumo_reg_quick_data_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    kumo_reg_quick_data_fname VARCHAR(60) NOT NULL,
    kumo_reg_quick_data_lname VARCHAR(60) NOT NULL,
    kumo_reg_quick_data_bnumber VARCHAR(10) NOT NULL,
    kumo_reg_quick_data_staff_add VARCHAR(60) NOT NULL,
    kumo_reg_quick_data_completed BOOLEAN,
    kumo_reg_quick_data_timestamp TIMESTAMP
);
ALTER TABLE registration.kumo_reg_quick_data ADD CONSTRAINT unique_kumo_reg_quick_data_id UNIQUE (kumo_reg_quick_data_id);


CREATE TABLE registration.kumo_reg_admin
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    kumo_reg_admin_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    kumo_reg_admin_agent VARCHAR(80) NOT NULL,
    kumo_reg_admin_text TEXT NOT NULL
);


CREATE TABLE registration.orders
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    amount DECIMAL(10,0) NOT NULL,
    paid TINYINT NOT NULL
);
CREATE UNIQUE INDEX unique_id ON orders (id);
