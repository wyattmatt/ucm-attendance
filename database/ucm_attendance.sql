-- UCM Attendance System Database
-- Run this SQL in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS ucm_attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ucm_attendance;

-- ============================================
-- ADMINS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS admins (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- EVENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS events (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    input_label VARCHAR(255) NOT NULL DEFAULT 'Kode Kehadiran',
    input_description VARCHAR(500) DEFAULT NULL,
    has_participants TINYINT(1) NOT NULL DEFAULT 0,
    background_image VARCHAR(255) DEFAULT NULL,
    status ENUM('upcoming', 'ongoing', 'completed') NOT NULL DEFAULT 'upcoming',
    created_by VARCHAR(36) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- EVENT SESSIONS TABLE (optional per event)
-- ============================================
CREATE TABLE IF NOT EXISTS event_sessions (
    id VARCHAR(36) PRIMARY KEY,
    event_id VARCHAR(36) NOT NULL,
    session_name VARCHAR(255) NOT NULL,
    session_order INT NOT NULL DEFAULT 1,
    start_time TIME DEFAULT NULL,
    end_time TIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- PARTICIPANTS TABLE (from CSV upload)
-- ============================================
CREATE TABLE IF NOT EXISTS participants (
    id VARCHAR(36) PRIMARY KEY,
    event_id VARCHAR(36) NOT NULL,
    name VARCHAR(255) DEFAULT NULL,
    unique_code VARCHAR(255) NOT NULL,
    additional_info TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- ATTENDANCES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS attendances (
    id VARCHAR(36) PRIMARY KEY,
    event_id VARCHAR(36) NOT NULL,
    session_id VARCHAR(36) DEFAULT NULL,
    participant_id VARCHAR(36) DEFAULT NULL,
    input_value VARCHAR(255) NOT NULL,
    attended_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES event_sessions(id) ON DELETE SET NULL,
    FOREIGN KEY (participant_id) REFERENCES participants(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- SEED: Use the Migrate controller instead
-- Visit: http://localhost/ucm_attendance/migrate
-- It will create tables and seed admin accounts
-- with proper bcrypt password hashes.
-- ============================================
