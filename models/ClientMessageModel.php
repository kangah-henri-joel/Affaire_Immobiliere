<?php
// models/ClientMessageModel.php

require_once __DIR__ . '/Model.php';

class ClientMessageModel extends Model {

    /** Créer ou récupérer une conversation */
    public function getOrCreateConversation($adminId, $clientToken, $clientName = '', $clientEmail = '', $annonceId = null) {
        // ID déterministe basé sur admin + client token
        $convId = hash('sha256', $adminId . '_' . $clientToken);
        
        $existing = $this->fetch("SELECT * FROM client_conversations WHERE id = ?", [$convId]);
        if (!$existing) {
            $this->query(
                "INSERT INTO client_conversations (id, admin_id, client_token, client_name, client_email, annonce_id) VALUES (?, ?, ?, ?, ?, ?)",
                [$convId, (int)$adminId, $clientToken, $clientName, $clientEmail ?: null, $annonceId ? (int)$annonceId : null]
            );
        } else {
            // Mettre à jour le nom/email si fournis
            if ($clientName) {
                $this->query("UPDATE client_conversations SET client_name = ? WHERE id = ?", [$clientName, $convId]);
            }
            if ($clientEmail) {
                $this->query("UPDATE client_conversations SET client_email = ? WHERE id = ?", [$clientEmail, $convId]);
            }
        }
        return $convId;
    }

    /** Récupérer une conversation par email client (pour retrouver après fermeture navigateur) */
    public function findByEmail($clientEmail) {
        return $this->fetchAll(
            "SELECT cc.*, u.full_name as admin_name FROM client_conversations cc JOIN users u ON cc.admin_id = u.id WHERE cc.client_email = ? ORDER BY cc.last_message_at DESC",
            [$clientEmail]
        );
    }

    /** Récupérer une conversation par son ID */
    public function getConversation($convId) {
        return $this->fetch(
            "SELECT cc.*, u.full_name as admin_name, u.avatar as admin_avatar, a.title as annonce_title
             FROM client_conversations cc
             JOIN users u ON cc.admin_id = u.id
             LEFT JOIN annonces a ON cc.annonce_id = a.id
             WHERE cc.id = ?",
            [$convId]
        );
    }

    /** Récupérer tous les messages d'une conversation */
    public function getMessages($convId) {
        return $this->fetchAll(
            "SELECT * FROM client_messages WHERE conversation_id = ? ORDER BY created_at ASC",
            [$convId]
        );
    }

    /** Envoyer un message */
    public function send($convId, $senderType, $senderName, $content) {
        $this->query(
            "INSERT INTO client_messages (conversation_id, sender_type, sender_name, content) VALUES (?, ?, ?, ?)",
            [$convId, $senderType, $senderName, $content]
        );
        $this->query(
            "UPDATE client_conversations SET last_message_at = NOW() WHERE id = ?",
            [$convId]
        );
    }

    /** Marquer les messages admin comme lus (côté client) */
    public function markAdminMessagesRead($convId) {
        $this->query(
            "UPDATE client_messages SET is_read = 1 WHERE conversation_id = ? AND sender_type = 'admin' AND is_read = 0",
            [$convId]
        );
    }

    /** Marquer les messages client comme lus (côté admin) */
    public function markClientMessagesRead($convId) {
        $this->query(
            "UPDATE client_messages SET is_read = 1 WHERE conversation_id = ? AND sender_type = 'client' AND is_read = 0",
            [$convId]
        );
    }

    /** Récupérer toutes les conversations d'un admin */
    public function getAdminConversations($adminId) {
        return $this->fetchAll(
            "SELECT cc.*, 
                    (SELECT content FROM client_messages WHERE conversation_id = cc.id ORDER BY created_at DESC LIMIT 1) as last_content,
                    (SELECT sender_type FROM client_messages WHERE conversation_id = cc.id ORDER BY created_at DESC LIMIT 1) as last_sender,
                    COUNT(CASE WHEN cm.sender_type = 'client' AND cm.is_read = 0 THEN 1 END) as unread_count,
                    a.title as annonce_title
             FROM client_conversations cc
             LEFT JOIN client_messages cm ON cm.conversation_id = cc.id
             LEFT JOIN annonces a ON cc.annonce_id = a.id
             WHERE cc.admin_id = ?
             GROUP BY cc.id
             ORDER BY cc.last_message_at DESC",
            [(int)$adminId]
        );
    }

    /** Récupérer toutes les conversations d'un client (via token) */
    public function getClientConversations($clientToken) {
        return $this->fetchAll(
            "SELECT cc.*, u.full_name as admin_name, a.title as annonce_title
             FROM client_conversations cc
             JOIN users u ON cc.admin_id = u.id
             LEFT JOIN annonces a ON cc.annonce_id = a.id
             WHERE cc.client_token = ?
             ORDER BY cc.last_message_at DESC",
            [$clientToken]
        );
    }

    /** Compter les messages non lus pour un admin */
    public function countAdminUnread($adminId) {
        $r = $this->fetch(
            "SELECT COUNT(*) as n FROM client_messages cm
             JOIN client_conversations cc ON cm.conversation_id = cc.id
             WHERE cc.admin_id = ? AND cm.sender_type = 'client' AND cm.is_read = 0",
            [(int)$adminId]
        );
        return (int)($r['n'] ?? 0);
    }
}
