-- ============================================================
-- Migration : Table des demandes clients
-- à exécuter dans phpMyAdmin sur la base immo_affaire_db
-- ============================================================

CREATE TABLE IF NOT EXISTS demandes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    client_name     VARCHAR(100) NOT NULL,
    client_phone    VARCHAR(25) NOT NULL,
    category        VARCHAR(50) DEFAULT NULL COMMENT 'terrain, maison, vehicule, moto...',
    type            ENUM('achat','location') DEFAULT 'achat',
    budget_max      DECIMAL(15,2) DEFAULT NULL,
    pays            VARCHAR(100) DEFAULT NULL,
    ville           VARCHAR(100) DEFAULT NULL,
    commune         VARCHAR(100) DEFAULT NULL,
    quartier        VARCHAR(100) DEFAULT NULL,
    description     TEXT NOT NULL,
    status          ENUM('active','closed') DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_user   (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

SELECT 'Table demandes créée avec succès !' AS statut;
