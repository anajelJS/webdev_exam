DROP DATABASE IF EXISTS socialsDB;
CREATE DATABASE socialsDB;
USE socialsDB;

CREATE TABLE userAccount (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fName VARCHAR(255) NOT NULL,
    lName VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    bio VARCHAR(500) DEFAULT NULL,
    profilePicture VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* when the row is first created, automatically put the current date and time here*/
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES userAccount(id)
        ON DELETE CASCADE
);


CREATE TABLE registrationAttempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ipAddress VARCHAR(45) NOT NULL,
    attemptedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_registration_ip_time (ipAddress, attemptedAt)
);

CREATE TABLE postLike (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)
    REFERENCES userAccount(id)
    ON DELETE CASCADE, /* if the user's account is deleted, delete their like */
    FOREIGN KEY (post_id)
    REFERENCES post(id)
    ON DELETE CASCADE,
    UNIQUE KEY unique_user_post_like (user_id, post_id)
);

