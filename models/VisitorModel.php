<?php
// models/VisitorModel.php

require_once __DIR__ . '/Model.php';

class VisitorModel extends Model {

    /** Enregistrer une visite */
    public function log($ip, $userAgent, $page, $sessionToken) {
        // Éviter de logguer trop souvent la même session sur la même page (dans les 5 dernières minutes)
        $existing = $this->fetch(
            "SELECT id FROM site_visitors WHERE session_token = ? AND page_visited = ? AND visited_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)",
            [$sessionToken, $page]
        );
        if ($existing) return; // déjà enregistré récemment

        $this->query(
            "INSERT INTO site_visitors (ip_address, user_agent, page_visited, session_token) VALUES (?, ?, ?, ?)",
            [$ip, $userAgent, $page, $sessionToken]
        );
    }

    /** Statistiques globales */
    public function getStats() {
        $db = $this->getDb();
        return [
            'total_visits'    => $db->query("SELECT COUNT(*) FROM site_visitors")->fetchColumn(),
            'unique_visitors' => $db->query("SELECT COUNT(DISTINCT session_token) FROM site_visitors")->fetchColumn(),
            'unique_ips'      => $db->query("SELECT COUNT(DISTINCT ip_address) FROM site_visitors")->fetchColumn(),
            'today_visits'    => $db->query("SELECT COUNT(*) FROM site_visitors WHERE DATE(visited_at) = CURDATE()")->fetchColumn(),
            'week_visits'     => $db->query("SELECT COUNT(*) FROM site_visitors WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn(),
        ];
    }

    /** Visites par jour (30 derniers jours) */
    public function getByDay($days = 30) {
        return $this->fetchAll(
            "SELECT DATE(visited_at) as date, COUNT(*) as visits, COUNT(DISTINCT session_token) as unique_v
             FROM site_visitors
             WHERE visited_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(visited_at)
             ORDER BY date DESC",
            [$days]
        );
    }

    /** Pages les plus visitées */
    public function getTopPages($limit = 10) {
        return $this->fetchAll(
            "SELECT page_visited, COUNT(*) as visits, COUNT(DISTINCT session_token) as unique_v
             FROM site_visitors
             GROUP BY page_visited
             ORDER BY visits DESC
             LIMIT ?",
            [$limit]
        );
    }

    /** Derniers visiteurs */
    public function getRecent($limit = 50) {
        return $this->fetchAll(
            "SELECT * FROM site_visitors ORDER BY visited_at DESC LIMIT ?",
            [$limit]
        );
    }

    /** Visiteurs uniques récents avec info */
    public function getUniqueVisitors($limit = 50) {
        return $this->fetchAll(
            "SELECT session_token, ip_address, user_agent,
                    MIN(visited_at) as first_visit, MAX(visited_at) as last_visit,
                    COUNT(*) as page_count
             FROM site_visitors
             GROUP BY session_token
             ORDER BY last_visit DESC
             LIMIT ?",
            [$limit]
        );
    }
}
