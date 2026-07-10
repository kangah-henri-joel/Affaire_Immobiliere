<?php
// models/CommentModel.php

require_once __DIR__ . '/Model.php';

class CommentModel extends Model {

    /** Récupérer tous les commentaires d'une annonce avec leurs réponses (structure arborescente) */
    public function getByAnnonceId($annonceId) {
        // Récupérer tous les commentaires (racines + réponses)
        $sql = "SELECT * FROM comments WHERE annonce_id = ? ORDER BY created_at ASC";
        $all = $this->fetchAll($sql, [$annonceId]);

        // Construire l'arbre : regrouper les réponses sous leurs parents
        $roots = [];
        $byId  = [];
        foreach ($all as &$c) {
            $c['replies'] = [];
            $byId[$c['id']] = &$c;
        }
        unset($c);
        foreach ($byId as &$c) {
            if ($c['parent_id'] === null) {
                $roots[] = &$c;
            } else {
                if (isset($byId[$c['parent_id']])) {
                    $byId[$c['parent_id']]['replies'][] = &$c;
                } else {
                    $roots[] = &$c; // parent supprimé → remonter
                }
            }
        }
        return $roots;
    }

    /** Récupérer un commentaire par son ID */
    public function getById($id) {
        return $this->fetch("SELECT * FROM comments WHERE id = ?", [$id]);
    }

    /** Vérifier si l'auteur (client_token) a déjà commenté → retourner son nom */
    public function getAuthorName($clientToken) {
        if (empty($clientToken)) return null;
        $r = $this->fetch(
            "SELECT author_name FROM comments WHERE client_token = ? ORDER BY created_at DESC LIMIT 1",
            [$clientToken]
        );
        return $r ? $r['author_name'] : null;
    }

    /** Créer un commentaire ou une réponse */
    public function create($annonceId, $authorName, $content, $clientToken, $parentId = null) {
        $sql = "INSERT INTO comments (annonce_id, parent_id, author_name, content, client_token) VALUES (?, ?, ?, ?, ?)";
        return $this->query($sql, [
            (int)$annonceId,
            $parentId ? (int)$parentId : null,
            $authorName,
            $content,
            $clientToken
        ]);
    }

    /** Supprimer un commentaire (et ses réponses via CASCADE) */
    public function delete($id) {
        return $this->query("DELETE FROM comments WHERE id = ?", [(int)$id]);
    }

    /** Compter les commentaires d'une annonce */
    public function countByAnnonce($annonceId) {
        $r = $this->fetch("SELECT COUNT(*) as n FROM comments WHERE annonce_id = ?", [(int)$annonceId]);
        return (int)($r['n'] ?? 0);
    }
}
