-- ============================================================
-- Migration v2 – Nouvelles fonctionnalités
-- Exécuter dans phpMyAdmin ou via MySQL CLI
-- ============================================================

USE immo_affaire_db;

-- ── 1. Ajouter parent_id aux commentaires (réponses imbriquées) ──────────────
ALTER TABLE comments 
    ADD COLUMN IF NOT EXISTS parent_id INT NULL DEFAULT NULL AFTER annonce_id,
    ADD CONSTRAINT fk_comment_parent FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE;

-- ── 2. Bureaux (un par admin) ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS bureaux (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL UNIQUE,
    nom             VARCHAR(150),
    adresse         TEXT,
    phone_whatsapp  VARCHAR(25),
    phone_tel       VARCHAR(25),
    phone_fixe      VARCHAR(25),
    email           VARCHAR(150),
    description     TEXT,
    logo            VARCHAR(255),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 3. Messagerie interne admin ↔ super-admin ─────────────────────────────────
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

-- ── 4. Messagerie client ↔ admin (conversations persistantes) ────────────────
CREATE TABLE IF NOT EXISTS client_conversations (
    id               VARCHAR(64) PRIMARY KEY,   -- hash unique
    admin_id         INT NOT NULL,
    client_token     VARCHAR(100) NOT NULL,
    client_name      VARCHAR(100),
    client_email     VARCHAR(150),              -- pour retrouver la conversation
    annonce_id       INT NULL,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_message_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS client_messages (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id  VARCHAR(64) NOT NULL,
    sender_type      ENUM('client','admin') NOT NULL,
    sender_name      VARCHAR(100),
    content          TEXT NOT NULL,
    is_read          TINYINT(1) DEFAULT 0,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES client_conversations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 5. Visiteurs du site ──────────────────────────────────────────────────────
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

-- ── 6. Affiches publicitaires ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS affiches (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id  INT NOT NULL,
    user_id     INT NOT NULL,
    model_name  VARCHAR(100),
    image_data  LONGTEXT,      -- base64 de l'image générée (optionnel)
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 7. S'assurer que la table comments existe avec la bonne structure ─────────
CREATE TABLE IF NOT EXISTS comments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id  INT NOT NULL,
    parent_id   INT NULL DEFAULT NULL,
    author_name VARCHAR(100),
    client_token VARCHAR(100),
    content     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id)  REFERENCES comments(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── 8. Mise à jour table users : s'assurer des colonnes nécessaires ───────────
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS status ENUM('active','inactive') DEFAULT 'active' AFTER role,
    ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) AFTER status,
    ADD COLUMN IF NOT EXISTS phone_whatsapp VARCHAR(25) AFTER avatar,
    ADD COLUMN IF NOT EXISTS phone_tel VARCHAR(25) AFTER phone_whatsapp,
    ADD COLUMN IF NOT EXISTS phone_fixe VARCHAR(25) AFTER phone_tel;

-- ── 9. Mise à jour table annonces ─────────────────────────────────────────────
ALTER TABLE annonces
    ADD COLUMN IF NOT EXISTS user_id      INT NULL AFTER id,
    ADD COLUMN IF NOT EXISTS published_by VARCHAR(100) AFTER user_id;

-- ── 10. Mise à jour table images : ajouter media_type ────────────────────────
ALTER TABLE images
    ADD COLUMN IF NOT EXISTS media_type ENUM('image','video') DEFAULT 'image' AFTER is_main;

SELECT 'Migration v2 terminée avec succès !' AS statut;
