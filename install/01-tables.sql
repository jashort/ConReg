/* Create database tables */
USE registration;

CREATE TABLE registration.reg_staff
(
    staff_id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(60) NOT NULL,
    password CHAR(98) NOT NULL,
    enabled BOOLEAN DEFAULT TRUE NOT NULL,
    first_name VARCHAR(60) NOT NULL,
    last_name VARCHAR(60) NOT NULL,
    initials CHAR(3) NOT NULL,
    phone_number VARCHAR(60),
    last_badge_number MEDIUMINT(5) UNSIGNED ZEROFILL DEFAULT 0 NOT NULL COMMENT 'Last Badge Number Created',
    access_level INT DEFAULT 1 NOT NULL
);
ALTER TABLE registration.reg_staff ADD CONSTRAINT unique_id UNIQUE (staff_id);
ALTER TABLE registration.reg_staff ADD CONSTRAINT unique_initials UNIQUE (initials);


CREATE TABLE registration.kumo_reg_orders
(
    order_id INT PRIMARY KEY NOT NULL UNIQUE AUTO_INCREMENT,
    total_amount DECIMAL(10,0) NOT NULL,
    paid CHAR(3) NOT NULL COMMENT 'yes or no',
    paytype VARCHAR(60) NOT NULL
);


CREATE TABLE registration.kumo_reg_data
(
    kumo_reg_data_id         INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    kumo_reg_data_fname      VARCHAR(60) NOT NULL,
    kumo_reg_data_lname      VARCHAR(60) NOT NULL,
    kumo_reg_data_bname      VARCHAR(120) COMMENT 'Badge Name',
    kumo_reg_data_bnumber    VARCHAR(10) UNIQUE NOT NULL,
    kumo_reg_data_zip        VARCHAR(10),
    kumo_reg_data_country    VARCHAR(250),
    kumo_reg_data_phone      VARCHAR(20) NOT NULL,
    kumo_reg_data_email      VARCHAR(250),
    kumo_reg_data_bdate      DATE NOT NULL,
    kumo_reg_data_ecfullname VARCHAR(250) NOT NULL,
    kumo_reg_data_ecphone    VARCHAR(250) NOT NULL,
    kumo_reg_data_same       CHAR(1) COMMENT 'Values: Y or empty string',
    kumo_reg_data_parent     VARCHAR(250),
    kumo_reg_data_parentphone VARCHAR(60),
    kumo_reg_data_parentform CHAR(3) COMMENT 'Values: yes or no',
    kumo_reg_data_paid       CHAR(3) COMMENT 'Values: yes or no',
    kumo_reg_data_paidamount DECIMAL(5, 2) NOT NULL,
    kumo_reg_data_passtype   VARCHAR(50),
    kumo_reg_data_regtype    VARCHAR(50) COMMENT 'Values: Reg or PreReg',
    kumo_reg_data_orderid    INT,
    kumo_reg_data_checkedin  CHAR(3) NOT NULL COMMENT 'Values: yes or no',
    kumo_reg_data_notes      TEXT,
    kumo_reg_data_staff_add  VARCHAR(80),
    kumo_reg_data_timestamp  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE registration.kumo_reg_data ADD FOREIGN KEY (kumo_reg_data_orderid) REFERENCES kumo_reg_orders (order_id);


CREATE TABLE registration.kumo_reg_admin
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    kumo_reg_admin_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    kumo_reg_admin_agent VARCHAR(80) NOT NULL,
    kumo_reg_admin_text TEXT NOT NULL
);

