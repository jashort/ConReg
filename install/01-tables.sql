/* Drop tables if they exist */
DROP TABLE IF EXISTS reg_staff, orders, attendees, history, history_types;

/* Create database tables */

CREATE TABLE reg_staff
(
    staff_id        INT(10) PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT,
    username        VARCHAR(60) NOT NULL UNIQUE,
    password        CHAR(98) NOT NULL,
    enabled         BOOLEAN DEFAULT TRUE NOT NULL,
    first_name      VARCHAR(60) NOT NULL,
    last_name       VARCHAR(60) NOT NULL,
    initials        CHAR(3) UNIQUE NOT NULL,
    phone_number    VARCHAR(60),
    last_badge_number MEDIUMINT(5) UNSIGNED ZEROFILL DEFAULT 0 NOT NULL COMMENT 'Last badge number created by this user',
    access_level    INT DEFAULT 1 NOT NULL
);


CREATE TABLE orders
(
    order_id        CHAR(32) PRIMARY KEY NOT NULL UNIQUE,
    total_amount    DECIMAL(10, 0) NOT NULL,
    paid            CHAR(3) NOT NULL COMMENT 'yes or no',
    paytype         VARCHAR(60) NOT NULL,
    notes           TEXT
);


CREATE TABLE attendees
(
    id              INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    first_name      VARCHAR(60) NOT NULL,
    last_name       VARCHAR(60) NOT NULL, 
    badge_name      VARCHAR(120) COMMENT 'Badge Name',
    badge_number    VARCHAR(10) UNIQUE NOT NULL,
    zip             VARCHAR(10),
    country         VARCHAR(250),
    phone           VARCHAR(60) NOT NULL,
    email           VARCHAR(250),
    birthdate       DATE NOT NULL,
    ec_fullname     VARCHAR(250) NOT NULL,
    ec_phone        VARCHAR(60) NOT NULL,
    ec_same         CHAR(1) COMMENT 'Values: Y or N',
    parent_fullname VARCHAR(250),
    parent_phone    VARCHAR(60),
    parent_form     CHAR(3) COMMENT 'Values: yes or no',
    paid            CHAR(3) COMMENT 'Values: yes or no',
    paid_amount     DECIMAL(5, 2) NOT NULL,
    pass_type       VARCHAR(50),
    reg_type        VARCHAR(50) COMMENT 'Values: Reg or PreReg',
    order_id        CHAR(32) REFERENCES orders(order_id),
    checked_in      CHAR(3) NOT NULL COMMENT 'Values: yes or no',
    notes           TEXT,
    added_by        VARCHAR(80),
    created         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX last_name_index (last_name),
    INDEX order_id_index (order_id)
);


CREATE TABLE history
(
    id              INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    changed_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type_id         SMALLINT UNSIGNED NOT NULL REFERENCES history_types(id),
    username        VARCHAR(60) NOT NULL,
    description     TEXT NOT NULL,
    INDEX type_index (type_id),
    INDEX username_index (username)
);

CREATE TABLE history_types
(
    id              SMALLINT UNSIGNED PRIMARY KEY NOT NULL,
    type            VARCHAR(60)
);

INSERT INTO history_types (id, type)
    VALUES
        (0, 'Login'),
        (10, 'Logout'),
        (20, 'PreReg CheckIn'),
        (30, 'AtCon CheckIn'),
        (40, 'Badge Print'),
        (50, 'Badge RePrint'),
        (60, 'Attendee Update'),
        (70, 'Added User'),
        (80, 'Changed User'),
        (90, 'Set Own Password'),
        (100, 'Reset Password'),
        (110, 'Imported PreReg Data'),
        (120, 'Added Order');
