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

CREATE TABLE postComment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    parent_comment_id INT DEFAULT NULL,
    content TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES userAccount(id)
        ON DELETE CASCADE,

    FOREIGN KEY (post_id)
        REFERENCES post(id)
        ON DELETE CASCADE,

    FOREIGN KEY (parent_comment_id)
        REFERENCES postComment(id)
        ON DELETE CASCADE
);

CREATE OR REPLACE VIEW postEngagementView AS
SELECT
    post.id AS post_id,

    (SELECT COUNT(*)
     FROM postLike
     WHERE postLike.post_id = post.id) AS like_count,

    (SELECT COUNT(*)
     FROM postComment
     WHERE postComment.post_id = post.id) AS comment_count

FROM post;

CREATE OR REPLACE VIEW userPostStatsView AS
SELECT
    userAccount.id AS user_id,
    COUNT(DISTINCT post.id) AS total_posts,
    COUNT(postLike.id) AS total_likes_received
FROM userAccount
LEFT JOIN post
    ON post.user_id = userAccount.id
LEFT JOIN postLike
    ON postLike.post_id = post.id
GROUP BY userAccount.id;



CREATE TRIGGER validateCommentBeforeInsert
BEFORE INSERT ON postComment
FOR EACH ROW
BEGIN
    IF NEW.content IS NULL
       OR CHAR_LENGTH(TRIM(NEW.content)) = 0
       OR CHAR_LENGTH(NEW.content) > 1000 THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Comment must contain between 1 and 1000 characters.';

    END IF;
END;

CREATE TRIGGER validatePostBeforeInsert
BEFORE INSERT ON post
FOR EACH ROW
BEGIN
    IF
        (NEW.content IS NULL OR CHAR_LENGTH(TRIM(NEW.content)) = 0)
        AND
        (NEW.image IS NULL OR CHAR_LENGTH(TRIM(NEW.image)) = 0)
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'A post must contain text or an image.';
    END IF;
END;


