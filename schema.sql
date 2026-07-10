-- Database schema for Real Estate & Marketing Platform

CREATE DATABASE IF NOT EXISTS immo_affaire_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE immo_affaire_db;

-- Users table (Admin only as per requirements)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'agent') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- e.g., Terrain, Maison, Studio, Magasin, Véhicule
    slug VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Annonces (Ads) table
CREATE TABLE IF NOT EXISTS annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(15, 2) NOT NULL,
    type ENUM('vente', 'location') NOT NULL,
    status ENUM('disponible', 'vendu', 'loué', 'brouillon') DEFAULT 'disponible',
    location_name VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    whatsapp_contact VARCHAR(20),
    views_count INT DEFAULT 0,
    clicks_count INT DEFAULT 0,
    deleted_at TIMESTAMP NULL DEFAULT NULL COMMENT 'NULL = actif, non-NULL = en corbeille',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Images table
CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_main BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Publications table (Marketing Automation)
CREATE TABLE IF NOT EXISTS publications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    platform ENUM('facebook', 'whatsapp', 'tiktok') NOT NULL,
    scheduled_at DATETIME NOT NULL,
    status ENUM('pending', 'published', 'failed') DEFAULT 'pending',
    generated_text TEXT,
    published_at DATETIME,
    error_message TEXT,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Statistics table (Daily aggregation)
CREATE TABLE IF NOT EXISTS statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    date DATE NOT NULL,
    views INT DEFAULT 0,
    clicks INT DEFAULT 0,
    source VARCHAR(50), -- e.g., Direct, Facebook, WhatsApp
    UNIQUE KEY (annonce_id, date, source),
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Leads table
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT,
    client_name VARCHAR(100),
    client_phone VARCHAR(20),
    client_email VARCHAR(100),
    message TEXT,
    status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Insert default categories
INSERT INTO categories (name, slug) VALUES 
('Terrain', 'terrain'),
('Maison', 'maison'),
('Studio', 'studio'),
('Magasin', 'magasin'),
('Véhicule', 'vehicule');
