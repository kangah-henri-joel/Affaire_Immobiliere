<?php
// models/ActionLogModel.php

require_once __DIR__ . '/Model.php';

class ActionLogModel extends Model {
    
    public function log($userId, $action, $details = null) {
        $sql = "INSERT INTO action_logs (user_id, action, details) VALUES (?, ?, ?)";
        return $this->query($sql, [$userId, $action, $details]);
    }

    public function getByUser($userId, $limit = 50) {
        $sql = "SELECT * FROM action_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql, [$userId]);
    }

    public function getAll($limit = 100) {
        $sql = "SELECT al.*, u.username, u.full_name 
                FROM action_logs al 
                JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql);
    }
}
