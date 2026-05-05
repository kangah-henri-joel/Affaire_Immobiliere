<?php
// models/AnnonceModel.php

require_once __DIR__ . '/Model.php';

class AnnonceModel extends Model {
    public function getAll($filters = []) {
        $sql = "SELECT a.*, c.name as category_name, i.file_path as image_path, i.media_type FROM annonces a 
                JOIN categories c ON a.category_id = c.id 
                LEFT JOIN images i ON i.annonce_id = a.id AND i.is_main = 1
                WHERE 1=1";
        $params = [];

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

    public function getById($id) {
        $sql = "SELECT a.*, c.name as category_name, i.file_path as image_path, i.media_type FROM annonces a 
                JOIN categories c ON a.category_id = c.id 
                LEFT JOIN images i ON i.annonce_id = a.id AND i.is_main = 1
                WHERE a.id = ?";
        return $this->fetch($sql, [$id]);
    }

    public function getMedia($annonceId) {
        return $this->fetchAll("SELECT * FROM images WHERE annonce_id = ? ORDER BY is_main DESC", [$annonceId]);
    }

    public function create($data) {
        $sql = "INSERT INTO annonces (category_id, title, description, price, type, location_name, latitude, longitude, whatsapp_contact) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->query($sql, [
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['type'],
            $data['location_name'],
            $data['latitude'],
            $data['longitude'],
            $data['whatsapp_contact']
        ]);
        return $this->db->lastInsertId();
    }

    public function getCategories() {
        return $this->fetchAll("SELECT * FROM categories");
    }
}
