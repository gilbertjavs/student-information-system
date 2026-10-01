-- Student Information Management System
-- Import this file in phpMyAdmin (localhost: create DB first; InfinityFree: select your DB, then Import)

CREATE DATABASE IF NOT EXISTS student_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_db;

CREATE TABLE IF NOT EXISTS students (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_no   VARCHAR(20)  NOT NULL UNIQUE,
    first_name   VARCHAR(60)  NOT NULL,
    last_name    VARCHAR(60)  NOT NULL,
    gender       ENUM('Male','Female','Other') NOT NULL,
    birthdate    DATE         NOT NULL,
    course       VARCHAR(80)  NOT NULL,
    year_level   TINYINT UNSIGNED NOT NULL,
    email        VARCHAR(120) NOT NULL,
    contact_no   VARCHAR(20)  NOT NULL,
    address      VARCHAR(255) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sample data so the Read function has something to show
INSERT INTO students (student_no, first_name, last_name, gender, birthdate, course, year_level, email, contact_no, address) VALUES
('2024-0001','Maria','Santos','Female','2005-03-14','BS Information Technology',2,'maria.santos@example.com','09171234567','Brgy. Mandalagan, Bacolod City'),
('2024-0002','Juan','Dela Cruz','Male','2004-11-02','BS Computer Science',3,'juan.delacruz@example.com','09181234567','Brgy. Taculing, Bacolod City'),
('2024-0003','Angela','Reyes','Female','2006-07-21','BS Education',1,'angela.reyes@example.com','09191234567','Brgy. Villamonte, Bacolod City');
