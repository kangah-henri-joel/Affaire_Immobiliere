<?php
// models/DemandeModel.php

require_once __DIR__ . '/Model.php';

class DemandeModel extends Model {

    /**
     * Récupérer toutes les demandes actives (pour la page publique / agents).
     */
    public function getActive($filters = []) {
        $sql = "SELECT d.*, u.phone_whatsapp as user_whatsapp, u.phone_tel as user_phone, u.avatar as user_avatar
                FROM demandes d
                LEFT JOIN users u ON d.user_id = u.id
                WHERE d.status = 'active'";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND d.category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND d.type = ?";
            $params[] = $filters['type'];
        }
        if (!empty($filters['pays'])) {
            $sql .= " AND d.pays LIKE ?";
            $params[] = "%{$filters['pays']}%";
        }
        if (!empty($filters['ville'])) {
            $sql .= " AND d.ville LIKE ?";
            $params[] = "%{$filters['ville']}%";
        }

        $sql .= " ORDER BY d.created_at DESC";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Récupérer les demandes d'un utilisateur connecté.
     */
    public function getByUserId($userId) {
        return $this->fetchAll(
            "SELECT * FROM demandes WHERE user_id = ? ORDER BY created_at DESC",
            [(int)$userId]
        );
    }

    /**
     * Récupérer une demande par ID.
     */
    public function getById($id) {
        return $this->fetch("SELECT * FROM demandes WHERE id = ?", [(int)$id]);
    }

    /**
     * Créer une nouvelle demande.
     */
    public function create($data) {
        $sql = "INSERT INTO demandes (user_id, client_name, client_phone, category, type, budget_max, pays, ville, commune, quartier, description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->query($sql, [
            (int)$data['user_id'],
            $data['client_name'],
            $data['client_phone'],
            $data['category'] ?? null,
            $data['type'] ?? 'achat',
            !empty($data['budget_max']) ? (float)$data['budget_max'] : null,
            $data['pays'] ?? null,
            $data['ville'] ?? null,
            $data['commune'] ?? null,
            $data['quartier'] ?? null,
            $data['description'],
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Clôturer une demande (le client la retire).
     * Sécurité : vérifier que l'user_id correspond.
     */
    public function close($id, $userId) {
        $this->query(
            "UPDATE demandes SET status = 'closed' WHERE id = ? AND user_id = ?",
            [(int)$id, (int)$userId]
        );
    }

    /**
     * Supprimer définitivement une demande (admin / super_admin).
     */
    public function delete($id) {
        $this->query("DELETE FROM demandes WHERE id = ?", [(int)$id]);
    }

    /**
     * Compter les demandes actives (pour badge sidebar).
     */
    public function countActive() {
        $r = $this->fetch("SELECT COUNT(*) as n FROM demandes WHERE status = 'active'");
        return (int)($r['n'] ?? 0);
    }
}
