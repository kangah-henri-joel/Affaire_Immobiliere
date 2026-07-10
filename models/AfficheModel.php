<?php
// models/AfficheModel.php

require_once __DIR__ . '/Model.php';

class AfficheModel extends Model {

    /** Sauvegarder une affiche générée */
    public function save($annonceId, $userId, $modelName, $imageData = null) {
        $this->query(
            "INSERT INTO affiches (annonce_id, user_id, model_name, image_data) VALUES (?, ?, ?, ?)",
            [(int)$annonceId, (int)$userId, $modelName, $imageData]
        );
        return $this->getDb()->lastInsertId();
    }

    /** Récupérer les affiches d'une annonce */
    public function getByAnnonce($annonceId) {
        return $this->fetchAll(
            "SELECT af.*, a.title as annonce_title, u.full_name as created_by
             FROM affiches af
             JOIN annonces a ON af.annonce_id = a.id
             JOIN users u ON af.user_id = u.id
             WHERE af.annonce_id = ?
             ORDER BY af.created_at DESC",
            [(int)$annonceId]
        );
    }

    /** Récupérer toutes les affiches d'un admin */
    public function getByUser($userId) {
        return $this->fetchAll(
            "SELECT af.*, a.title as annonce_title
             FROM affiches af
             JOIN annonces a ON af.annonce_id = a.id
             WHERE af.user_id = ?
             ORDER BY af.created_at DESC",
            [(int)$userId]
        );
    }
}
