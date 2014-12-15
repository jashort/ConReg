/* Create database tables */
USE registration;

CREATE TABLE orders
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    amount DECIMAL(10,0) NOT NULL,
    paid TINYINT NOT NULL
);
CREATE UNIQUE INDEX unique_id ON orders (id);
