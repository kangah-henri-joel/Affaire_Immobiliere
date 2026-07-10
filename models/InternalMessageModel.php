<?php
// models/InternalMessageModel.php

require_once __DIR__ . '/Model.php';

class InternalMessageModel extends Model {

    /** Envoyer un message */
    public function send($senderId, $receiverId, $content) {
        return $this->query(
            "INSERT INTO internal_messages (sender_id, receiver_id, content) VALUES (?, ?, ?)",
            [(int)$senderId, (int)$receiverId, $content]
        );
    }

    /** Récupérer la conversation entre deux utilisateurs */
    public function getConversation($userId1, $userId2) {
        $sql = "SELECT m.*, 
                       us.full_name as sender_name, us.avatar as sender_avatar, us.role as sender_role,
                       ur.full_name as receiver_name
                FROM internal_messages m
                JOIN users us ON m.sender_id   = us.id
                JOIN users ur ON m.receiver_id = ur.id
                WHERE (m.sender_id = ? AND m.receiver_id = ?)
                   OR (m.sender_id = ? AND m.receiver_id = ?)
                ORDER BY m.created_at ASC";
        return $this->fetchAll($sql, [(int)$userId1, (int)$userId2, (int)$userId2, (int)$userId1]);
    }

    /** Marquer les messages comme lus */
    public function markAsRead($receiverId, $senderId) {
        return $this->query(
            "UPDATE internal_messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ? AND is_read = 0",
            [(int)$receiverId, (int)$senderId]
        );
    }

    /** Compter les messages non lus pour un utilisateur */
    public function countUnread($userId) {
        $r = $this->fetch("SELECT COUNT(*) as n FROM internal_messages WHERE receiver_id = ? AND is_read = 0", [(int)$userId]);
        return (int)($r['n'] ?? 0);
    }

    /** Lister tous les admins ayant écrit au super-admin (pour vue super-admin) */
    public function getAdminThreads($superAdminId) {
        $sql = "SELECT u.id as admin_id, u.full_name as admin_name, u.avatar, u.username,
                       MAX(m.created_at) as last_message_at,
                       COUNT(CASE WHEN m.receiver_id = ? AND m.is_read = 0 THEN 1 END) as unread_count,
                       (SELECT content FROM internal_messages WHERE 
                           (sender_id = u.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = u.id)
                           ORDER BY created_at DESC LIMIT 1) as last_content
                FROM internal_messages m
                JOIN users u ON (m.sender_id = u.id OR m.receiver_id = u.id)
                WHERE (m.sender_id = ? OR m.receiver_id = ?)
                  AND u.id != ?
                  AND u.role IN ('admin', 'agent')
                GROUP BY u.id
                ORDER BY last_message_at DESC";
        return $this->fetchAll($sql, [
            (int)$superAdminId,
            (int)$superAdminId,
            (int)$superAdminId,
            (int)$superAdminId,
            (int)$superAdminId,
            (int)$superAdminId,
        ]);
    }
}
