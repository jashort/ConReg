/* Create database tables */
USE registration;

CREATE TABLE registration.reg_staff
(
    staff_id        INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username        VARCHAR(60) NOT NULL,
    password        CHAR(98) NOT NULL,
    enabled         BOOLEAN DEFAULT TRUE NOT NULL,
    first_name      VARCHAR(60) NOT NULL,
    last_name       VARCHAR(60) NOT NULL,
    initials        CHAR(3) NOT NULL,
    phone_number    VARCHAR(60),
    last_badge_number MEDIUMINT(5) UNSIGNED ZEROFILL DEFAULT 0 NOT NULL COMMENT 'Last badge number created by this user',
    access_level    INT DEFAULT 1 NOT NULL
);
ALTER TABLE registration.reg_staff ADD CONSTRAINT unique_id UNIQUE (staff_id);
ALTER TABLE registration.reg_staff ADD CONSTRAINT unique_initials UNIQUE (initials);


CREATE TABLE registration.orders
(
    order_id        INT PRIMARY KEY NOT NULL UNIQUE AUTO_INCREMENT,
    total_amount    DECIMAL(10,0) NOT NULL,
    paid            CHAR(3) NOT NULL COMMENT 'yes or no',
    paytype         VARCHAR(60) NOT NULL
);


CREATE TABLE registration.attendees
(
    id              INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    first_name      VARCHAR(60) NOT NULL,
    last_name       VARCHAR(60) NOT NULL,
    badge_name      VARCHAR(120) COMMENT 'Badge Name',
    badge_number    VARCHAR(10) UNIQUE NOT NULL,
    zip             VARCHAR(10),
    country         VARCHAR(250),
    phone           VARCHAR(20) NOT NULL,
    email           VARCHAR(250),
    birthdate       DATE NOT NULL,
    ec_fullname     VARCHAR(250) NOT NULL,
    ec_phone        VARCHAR(250) NOT NULL,
    ec_same         CHAR(1) COMMENT 'Values: Y or empty string',
    parent_fullname VARCHAR(250),
    parent_phone    VARCHAR(60),
    parent_form     CHAR(3) COMMENT 'Values: yes or no',
    paid            CHAR(3) COMMENT 'Values: yes or no',
    paid_amount     DECIMAL(5, 2) NOT NULL,
    pass_type       VARCHAR(50),
    reg_type        VARCHAR(50) COMMENT 'Values: Reg or PreReg',
    order_id        INT,
    checked_in      CHAR(3) NOT NULL COMMENT 'Values: yes or no',
    notes           TEXT,
    added_by        VARCHAR(80),
    created         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE registration.attendees ADD FOREIGN KEY (order_id) REFERENCES orders (order_id);


CREATE TABLE registration.history
(
    id              INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    timestamp       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    agent           VARCHAR(80) NOT NULL,
    description     TEXT NOT NULL
);

