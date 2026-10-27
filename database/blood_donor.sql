CREATE DATABASE IF NOT EXISTS blood_donor;
USE blood_donor;

-- -------------------------
-- Admin Table
-- -------------------------
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin
-- Password: admin123
INSERT INTO admin (username,email,password)
VALUES (
'Administrator',
'admin@blood.com',
'$2y$10$6M0D0B2aGDcJHrrHSgvFpsYxqb6g97uaQTd2kE31rPUeDZTeDsjVq'
);

-- -------------------------
-- Donors Table
-- -------------------------
CREATE TABLE donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    age INT NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    availability ENUM('Available','Unavailable') DEFAULT 'Available',
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -------------------------
-- Recipients Table
-- -------------------------
CREATE TABLE recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    city VARCHAR(100) NOT NULL,
    hospital VARCHAR(150) NOT NULL,
    address TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -------------------------
-- Blood Requests
-- -------------------------
CREATE TABLE blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_id INT NOT NULL,
    recipient_name VARCHAR(100) NOT NULL,
    donor_id INT DEFAULT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    city VARCHAR(100) NOT NULL,
    hospital VARCHAR(150) NOT NULL,
    message TEXT,
    status ENUM('Pending','Accepted','Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipient_id) REFERENCES recipients(id) ON DELETE CASCADE,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE SET NULL
);

-- -------------------------
-- Donation History
-- -------------------------
CREATE TABLE donation_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    recipient_id INT NOT NULL,
    donation_date DATE NOT NULL,
    hospital VARCHAR(150),
    remarks TEXT,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES recipients(id) ON DELETE CASCADE
);

-- -------------------------
-- Hospitals
-- -------------------------
CREATE TABLE hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_name VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);