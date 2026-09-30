DROP DATABASE IF EXISTS socialsDB;
CREATE DATABASE socialsDB;
USE socialsDB;

CREATE TABLE userAccount (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fName VARCHAR(255) NOT NULL,
    lName VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* when the row is first created, automatically put the current date and time here*/
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

