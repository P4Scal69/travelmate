-- ============================================================
-- TravelMate - Smart Trip Planner & Travel Companion
-- Database schema for STIWK2114 Mobile Programming
-- ============================================================

CREATE DATABASE IF NOT EXISTS travelmate CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE travelmate;

-- Users table (supports login / session requirement)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Trips table (main data table)
-- Field types demonstrate: Integer (id, user_id, budget, rating),
-- String/Text (destination, country, notes, photo),
-- Date (start_date, created_at),
-- Decimal/numeric (latitude, longitude)
CREATE TABLE IF NOT EXISTS trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    destination VARCHAR(120) NOT NULL,
    country VARCHAR(80) NOT NULL,
    start_date DATE NOT NULL,
    budget INT NOT NULL,
    rating TINYINT NOT NULL DEFAULT 3,
    notes TEXT,
    latitude DECIMAL(10,8) DEFAULT NULL,
    longitude DECIMAL(11,8) DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample user (password: student123)
INSERT INTO users (username, password, full_name) VALUES
('student', '$2y$10$Ji73lAsNcH1.1AUOH3Zmm.G2oH8tkfAQvn.rjOR.cOkf0ATStnP/C', 'Demo Student');

-- Sample trips
INSERT INTO trips (user_id, destination, country, start_date, budget, rating, notes, latitude, longitude, photo) VALUES
(1, 'Mount Kinabalu', 'Malaysia', '2026-08-15', 850, 5, 'Early morning hike, book permit in advance.', 6.0755, 116.5610, NULL),
(1, 'Tokyo', 'Japan', '2026-09-20', 4500, 4, 'Visit Asakusa temple and teamLab museum.', 35.6762, 139.6503, NULL),
(1, 'Bali', 'Indonesia', '2026-10-05', 2200, 4, 'Beach resort and Ubud rice terraces.', -8.3405, 115.0920, NULL),
(1, 'Langkawi', 'Malaysia', '2026-11-12', 1200, 5, 'Cable car and island hopping tour.', 6.3500, 99.8000, NULL);
