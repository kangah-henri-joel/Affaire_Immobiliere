-- ============================================================
-- Projet_Affaire – Schéma unifié (base unique : immo_affaire_db)
-- Importez UNIQUEMENT ce fichier dans phpMyAdmin ou MySQL CLI.
-- ============================================================

-- CREATE DATABASE IF NOT EXISTS immo_affaire_db
--     CHARACTER SET utf8mb4
--     COLLATE utf8mb4_unicode_ci;

-- USE immo_affaire_db;

-- ── Utilisateurs ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    full_name       VARCHAR(100),
    email           VARCHAR(150) UNIQUE,
    role            ENUM('super_admin', 'admin', 'agent', 'client') NOT NULL DEFAULT 'client',
    status          ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    avatar          VARCHAR(255),
    phone_whatsapp  VARCHAR(25),
    phone_tel       VARCHAR(25),
    phone_fixe      VARCHAR(25),
    country         VARCHAR(100),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Catégories ────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ── Annonces ──────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS annonces (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NULL,
    published_by     VARCHAR(100),
    category_id      INT NOT NULL,
    title            VARCHAR(255) NOT NULL,
    description      TEXT,
    price            DECIMAL(15, 2) NOT NULL,
    type             ENUM('vente', 'location') NOT NULL,
    status           ENUM('disponible', 'vendu', 'loué', 'brouillon') DEFAULT 'disponible',
    location_name    VARCHAR(255),
    pays             VARCHAR(100),
    ville            VARCHAR(100),
    commune          VARCHAR(100),
    quartier         VARCHAR(100),
    latitude         DECIMAL(10, 8),
    longitude        DECIMAL(11, 8),
    whatsapp_contact VARCHAR(20),
    views_count      INT DEFAULT 0,
    clicks_count     INT DEFAULT 0,
    deleted_at       TIMESTAMP NULL DEFAULT NULL COMMENT 'NULL = actif, non-NULL = en corbeille',
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Likes visiteurs ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS likes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    visitor_id VARCHAR(128) NOT NULL COMMENT 'Identifiant de session visiteur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (annonce_id, visitor_id),
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Images / médias ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS images (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    file_path  VARCHAR(255) NOT NULL,
    is_main    BOOLEAN DEFAULT FALSE,
    media_type ENUM('image', 'video') DEFAULT 'image',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Publications marketing ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS publications (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id     INT NOT NULL,
    platform       ENUM('facebook', 'whatsapp', 'tiktok') NOT NULL,
    scheduled_at   DATETIME NOT NULL,
    status         ENUM('pending', 'published', 'failed') DEFAULT 'pending',
    generated_text TEXT,
    published_at   DATETIME,
    error_message  TEXT,
    repeat_days    INT DEFAULT 0 COMMENT '0 = une seule publication, >0 = récurrence en jours',
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Statistiques ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS statistics (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    date       DATE NOT NULL,
    views      INT DEFAULT 0,
    clicks     INT DEFAULT 0,
    source     VARCHAR(50),
    UNIQUE KEY (annonce_id, date, source),
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Leads / contacts clients ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS leads (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id   INT,
    client_name  VARCHAR(100),
    client_phone VARCHAR(20),
    client_email VARCHAR(100),
    message      TEXT,
    status       ENUM('new', 'contacted', 'closed') DEFAULT 'new',
    admin_reply  TEXT,
    replied_at   DATETIME,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ── Commentaires publics ──────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS comments (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id   INT NOT NULL,
    parent_id    INT NULL DEFAULT NULL,
    author_name  VARCHAR(100) NOT NULL DEFAULT '',
    client_token VARCHAR(100) NOT NULL DEFAULT '',
    content      TEXT NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id)  REFERENCES comments(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Bureaux (un par admin) ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS bureaux (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL UNIQUE,
    nom            VARCHAR(150),
    adresse        TEXT,
    ville          VARCHAR(100),
    phone_whatsapp VARCHAR(25),
    phone_tel      VARCHAR(25),
    phone_fixe     VARCHAR(25),
    email          VARCHAR(150),
    description    TEXT,
    logo           VARCHAR(255),
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Messagerie interne admin ↔ super-admin ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS internal_messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    sender_id   INT NOT NULL,
    receiver_id INT NOT NULL,
    content     TEXT NOT NULL,
    is_read     TINYINT(1) DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Messagerie client ↔ admin ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS client_conversations (
    id              VARCHAR(64) PRIMARY KEY,
    admin_id        INT NOT NULL,
    client_token    VARCHAR(100) NOT NULL,
    client_name     VARCHAR(100),
    client_email    VARCHAR(150),
    annonce_id      INT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_message_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id)   REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS client_messages (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id VARCHAR(64) NOT NULL,
    sender_type     ENUM('client', 'admin') NOT NULL,
    sender_name     VARCHAR(100),
    content         TEXT NOT NULL,
    is_read         TINYINT(1) DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES client_conversations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Visiteurs du site ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS site_visitors (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    ip_address    VARCHAR(45),
    user_agent    TEXT,
    page_visited  VARCHAR(255),
    session_token VARCHAR(100),
    visited_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_token),
    INDEX idx_date    (visited_at),
    INDEX idx_page    (page_visited(100))
) ENGINE=InnoDB;

-- ── Affiches publicitaires ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS affiches (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    user_id    INT NOT NULL,
    model_name VARCHAR(100),
    image_data LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Paramètres du site ────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    setting_key   VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT
) ENGINE=InnoDB;

-- ── Journal d'activité ────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS action_logs (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    action     VARCHAR(255) NOT NULL,
    details    TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Données initiales
-- ============================================================

INSERT IGNORE INTO categories (name, slug) VALUES
('Terrain',  'terrain'),
('Maison',   'maison'),
('Studio',   'studio'),
('Magasin',  'magasin'),
('Véhicule', 'vehicule'),
('moto', 'moto');

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('company_name',     'ImmoAffaire'),
('company_email',    'contact@immoaffaire.com'),
('company_phone',    '+225 0000000000'),
('company_address',  'Abidjan, Côte d''Ivoire'),
('social_facebook',  ''),
('social_tiktok',    ''),
('company_whatsapp', ''),
('site_url',         'https://immbilierkangagh.site.je');

-- Comptes par défaut (mot de passe : admin123)
INSERT IGNORE INTO users (username, password, full_name, role) VALUES
('admin',      '$2y$10$BxaWAKokxSq6UTJa80wzCe5V66T7IaO7AawTIL8VPGLncLvKG0QsO', 'Administrateur', 'admin'),
('superadmin', '$2y$10$BxaWAKokxSq6UTJa80wzCe5V66T7IaO7AawTIL8VPGLncLvKG0QsO', 'Super Admin',    'super_admin');

SELECT 'Schéma unifié immo_affaire_db installé avec succès !' AS statut;
