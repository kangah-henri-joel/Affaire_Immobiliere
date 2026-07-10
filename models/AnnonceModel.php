<?php
// models/AnnonceModel.php

require_once __DIR__ . '/Model.php';

class AnnonceModel extends Model {
    public function getAll($filters = []) {
        $sql = "SELECT a.*, c.name as category_name, i.file_path as image_path, i.media_type,
                       u.full_name as author_name, u.phone_whatsapp as author_whatsapp,
                       u.phone_tel as author_phone, u.avatar as author_avatar
                FROM annonces a 
                JOIN categories c ON a.category_id = c.id 
                LEFT JOIN images i ON i.annonce_id = a.id AND i.is_main = 1
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.deleted_at IS NULL";
        $params = [];

        // Exclure les brouillons par défaut, sauf demande explicite (ex: panel admin)
        if (empty($filters['include_drafts'])) {
            $sql .= " AND a.status != 'brouillon'";
        }

        // Filtrer par administrateur / utilisateur créateur
        if (!empty($filters['user_id'])) {
            $sql .= " AND a.user_id = ?";
            $params[] = (int)$filters['user_id'];
        }

        if (!empty($filters['category'])) {
            $sql .= " AND LOWER(c.slug) = LOWER(?)";
            $params[] = trim($filters['category']);
        }

        if (!empty($filters['query'])) {
            $sql .= " AND (a.title LIKE ? OR a.location_name LIKE ?)";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
        }

        $sql .= " ORDER BY a.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        return $this->fetchAll($sql, $params);
    }

    /**
     * Retourne les annonces en corbeille (soft-deleted) pour un admin donné.
     * Si user_id est null → retourne toutes les annonces supprimées (super_admin).
     */
    public function getTrashed($userId = null) {
        $sql = "SELECT a.*, c.name as category_name, i.file_path as image_path, i.media_type
                FROM annonces a
                JOIN categories c ON a.category_id = c.id
                LEFT JOIN images i ON i.annonce_id = a.id AND i.is_main = 1
                WHERE a.deleted_at IS NOT NULL";
        $params = [];
        if ($userId !== null) {
            $sql .= " AND a.user_id = ?";
            $params[] = (int)$userId;
        }
        $sql .= " ORDER BY a.deleted_at DESC";
        return $this->fetchAll($sql, $params);
    }

    /** Déplace une annonce dans la corbeille (soft delete). */
    public function softDelete($id) {
        $this->query("UPDATE annonces SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
    }

    /** Restaure une annonce depuis la corbeille. */
    public function restore($id) {
        $this->query("UPDATE annonces SET deleted_at = NULL WHERE id = ?", [(int)$id]);
    }

    /** Supprime définitivement une annonce (hard delete). */
    public function hardDelete($id) {
        $this->query("DELETE FROM annonces WHERE id = ?", [(int)$id]);
    }

    /** Compte les annonces en corbeille pour un admin (badge sidebar). */
    public function countTrashed($userId = null) {
        if ($userId !== null) {
            $r = $this->fetch("SELECT COUNT(*) as n FROM annonces WHERE deleted_at IS NOT NULL AND user_id = ?", [(int)$userId]);
        } else {
            $r = $this->fetch("SELECT COUNT(*) as n FROM annonces WHERE deleted_at IS NOT NULL");
        }
        return (int)($r['n'] ?? 0);
    }

    public function getById($id) {
        $sql = "SELECT a.*, c.name as category_name, i.file_path as image_path, i.media_type,
                       u.full_name as author_name, u.phone_whatsapp as author_whatsapp,
                       u.phone_tel as author_phone, u.avatar as author_avatar, u.username as author_username
                FROM annonces a 
                JOIN categories c ON a.category_id = c.id 
                LEFT JOIN images i ON i.annonce_id = a.id AND i.is_main = 1
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.id = ?";
        return $this->fetch($sql, [$id]);
    }

    public function getMedia($annonceId) {
        return $this->fetchAll("SELECT * FROM images WHERE annonce_id = ? ORDER BY is_main DESC", [$annonceId]);
    }

    public function create($data) {
        $sql = "INSERT INTO annonces (user_id, published_by, category_id, title, description, price, type, location_name, latitude, longitude, whatsapp_contact, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->query($sql, [
            $data['user_id'] ?? null,
            $data['published_by'] ?? null,
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['type'],
            $data['location_name'],
            $data['latitude'],
            $data['longitude'],
            $data['whatsapp_contact'],
            $data['status'] ?? 'disponible'
        ]);
        return $this->db->lastInsertId();
    }

    public function getCategories() {
        return $this->fetchAll("SELECT * FROM categories");
    }

    public function delete($id) {
        $this->query("DELETE FROM annonces WHERE id = ?", [(int)$id]);
    }
}
