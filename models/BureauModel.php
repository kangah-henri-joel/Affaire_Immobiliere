<?php
// models/BureauModel.php

require_once __DIR__ . '/Model.php';

class BureauModel extends Model {

    /** Récupérer le bureau d'un admin */
    public function getByUserId($userId) {
        return $this->fetch("SELECT b.*, u.full_name as admin_name, u.username, u.avatar as admin_avatar
                             FROM bureaux b
                             JOIN users u ON b.user_id = u.id
                             WHERE b.user_id = ?", [(int)$userId]);
    }

    /** Récupérer tous les bureaux (page publique) */
    public function getAll() {
        return $this->fetchAll("SELECT b.*, u.full_name as admin_name, u.username, u.avatar as admin_avatar
                                FROM bureaux b
                                JOIN users u ON b.user_id = u.id
                                WHERE u.role IN ('admin', 'super_admin', 'agent')
                                ORDER BY b.created_at DESC");
    }

    /** Récupérer un bureau par son id */
    public function getById($id) {
        return $this->fetch("SELECT b.*, u.full_name as admin_name, u.username, u.avatar as admin_avatar
                             FROM bureaux b
                             JOIN users u ON b.user_id = u.id
                             WHERE b.id = ?", [(int)$id]);
    }

    /** Créer ou mettre à jour le bureau d'un admin (UPSERT) */
    public function upsert($userId, $data) {
        $existing = $this->getByUserId($userId);
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->create($userId, $data);
        }
    }

    public function create($userId, $data) {
        $sql = "INSERT INTO bureaux (user_id, nom, adresse, ville, phone_whatsapp, phone_tel, phone_fixe, email, description, logo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->query($sql, [
            (int)$userId,
            $data['nom'] ?? null,
            $data['adresse'] ?? null,
            $data['ville'] ?? null,
            $data['phone_whatsapp'] ?? null,
            $data['phone_tel'] ?? null,
            $data['phone_fixe'] ?? null,
            $data['email'] ?? null,
            $data['description'] ?? null,
            $data['logo'] ?? null,
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE bureaux SET nom=?, adresse=?, ville=?, phone_whatsapp=?, phone_tel=?, phone_fixe=?, email=?, description=?, logo=?
                WHERE id=?";
        return $this->query($sql, [
            $data['nom'] ?? null,
            $data['adresse'] ?? null,
            $data['ville'] ?? null,
            $data['phone_whatsapp'] ?? null,
            $data['phone_tel'] ?? null,
            $data['phone_fixe'] ?? null,
            $data['email'] ?? null,
            $data['description'] ?? null,
            $data['logo'] ?? null,
            (int)$id,
        ]);
    }
}
