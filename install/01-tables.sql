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
    total_amount    DECIMAL(9, 2) NOT NULL,
    paid            CHAR(3) NOT NULL COMMENT 'yes or no',
    paytype         VARCHAR(60) NOT NULL,
    notes           TEXT
);


CREATE TABLE pass_types
(
    id                  INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name                VARCHAR(250) NOT NULL,
    category            CHAR(10) NOT NULL COMMENT 'weekend, friday, saturday, vip, etc (lower case)',
    visible             CHAR(1) COMMENT 'Y or N',
    min_age             TINYINT UNSIGNED NOT NULL COMMENT 'Minimum age in years',
    max_age             TINYINT UNSIGNED NOT NULL COMMENT 'Max age in years',
    cost                DECIMAL(7,2) NOT NULL
);

INSERT INTO pass_types (name, category, visible, min_age, max_age, cost)
VALUES
    ('Full Weekend - Adult', 'weekend', 'Y', 13, 255, 55),
    ('Friday Only - Adult', 'friday', 'Y', 13, 255, 30),
    ('Saturday Only - Adult', 'saturday', 'Y', 13, 255, 40),
    ('Sunday Only - Adult', 'sunday', 'Y', 13, 255, 40),
    ('Monday Only - Adult', 'monday', 'Y', 13, 255, 30),
    ('Full Weekend - Child', 'weekend', 'Y', 6, 12, 45),
    ('Friday - Child', 'friday', 'Y', 6, 12, 20),
    ('Saturday - Child', 'saturday', 'Y', 6, 12, 30),
    ('Sunday - Child', 'sunday', 'Y', 6, 12, 30),
    ('Monday - Child', 'monday', 'Y', 6, 12, 20),
    ('Child Under 5', 'weekend', 'Y', 0, 5, 0),
    ('VIP', 'vip', 'Y', 0, 255, 300);

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
    paid_amount     DECIMAL(7, 2) NOT NULL,
    pass_type       VARCHAR(50),
    pass_type_id    INT UNSIGNED,
    FOREIGN KEY     (pass_type_id) REFERENCES pass_types(id),
    reg_type        VARCHAR(50) COMMENT 'Values: Reg or PreReg',
    order_id        CHAR(32) REFERENCES orders(order_id),
    checked_in      CHAR(3) NOT NULL COMMENT 'Values: yes or no',
    notes           TEXT,
    added_by        VARCHAR(80),
    created         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX last_name_index (last_name),
    INDEX order_id_index (order_id)
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
    (120, 'Added Order'),
    (130, 'Added Pass Type'),
    (140, 'Modified Pass Type'),
    (150, 'Deleted Pass Type');


CREATE TABLE history
(
    id              INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    changed_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type_id         SMALLINT UNSIGNED NOT NULL,
    FOREIGN KEY     (type_id) REFERENCES history_types(id),
    username        VARCHAR(60) NOT NULL,
    description     TEXT NOT NULL,
    INDEX type_index (type_id),
    INDEX username_index (username)
);
