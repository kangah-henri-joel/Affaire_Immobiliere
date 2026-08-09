<?php
// models/LikeModel.php

require_once __DIR__ . '/Model.php';

class LikeModel extends Model {

    /**
     * Retourne l'identifiant unique du visiteur (basé sur la session PHP).
     */
    public static function getVisitorId(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['visitor_like_id'])) {
            $_SESSION['visitor_like_id'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['visitor_like_id'];
    }

    /**
     * Vérifie si le visiteur a déjà liké cette annonce.
     */
    public function hasLiked(int $annonceId, string $visitorId): bool {
        $row = $this->fetch(
            "SELECT id FROM likes WHERE annonce_id = ? AND visitor_id = ?",
            [$annonceId, $visitorId]
        );
        return !empty($row);
    }

    /**
     * Ajoute un like.
     */
    public function addLike(int $annonceId, string $visitorId): void {
        $this->query(
            "INSERT IGNORE INTO likes (annonce_id, visitor_id) VALUES (?, ?)",
            [$annonceId, $visitorId]
        );
    }

    /**
     * Retire un like (unlike).
     */
    public function removeLike(int $annonceId, string $visitorId): void {
        $this->query(
            "DELETE FROM likes WHERE annonce_id = ? AND visitor_id = ?",
            [$annonceId, $visitorId]
        );
    }

    /**
     * Compte le nombre total de likes pour une annonce.
     */
    public function countLikes(int $annonceId): int {
        $row = $this->fetch(
            "SELECT COUNT(*) as total FROM likes WHERE annonce_id = ?",
            [$annonceId]
        );
        return (int)($row['total'] ?? 0);
    }
}
