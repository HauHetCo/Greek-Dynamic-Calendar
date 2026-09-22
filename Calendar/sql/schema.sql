CREATE DATABASE IF NOT EXISTS db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_name;

CREATE TABLE IF NOT EXISTS namedays (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    person_name VARCHAR(120) NOT NULL,
    month_of_year TINYINT UNSIGNED NOT NULL,
    day_of_month TINYINT UNSIGNED NOT NULL,
    notes VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (person_name),
    INDEX idx_month_day (month_of_year, day_of_month)
);

CREATE TABLE IF NOT EXISTS holidays (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    month_of_year TINYINT UNSIGNED NOT NULL,
    day_of_month TINYINT UNSIGNED NOT NULL,
    is_public_holiday TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_holiday_month_day (month_of_year, day_of_month)
);
