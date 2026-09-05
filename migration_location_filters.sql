-- ============================================================
-- Migration : Ajout des colonnes de localisation détaillée
-- à exécuter dans phpMyAdmin sur la base immo_affaire_db
-- ============================================================

ALTER TABLE `annonces`
    ADD COLUMN IF NOT EXISTS `pays`     VARCHAR(100) NULL AFTER `location_name`,
    ADD COLUMN IF NOT EXISTS `ville`    VARCHAR(100) NULL AFTER `pays`,
    ADD COLUMN IF NOT EXISTS `commune`  VARCHAR(100) NULL AFTER `ville`,
    ADD COLUMN IF NOT EXISTS `quartier` VARCHAR(100) NULL AFTER `commune`;

-- Ajout des index pour accélérer les recherches par localisation
ALTER TABLE `annonces`
    ADD INDEX IF NOT EXISTS `idx_pays`     (`pays`),
    ADD INDEX IF NOT EXISTS `idx_ville`    (`ville`),
    ADD INDEX IF NOT EXISTS `idx_commune`  (`commune`),
    ADD INDEX IF NOT EXISTS `idx_quartier` (`quartier`);

SELECT 'Migration réussie : colonnes pays, ville, commune, quartier ajoutées.' AS statut;
