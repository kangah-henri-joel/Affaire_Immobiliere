<?php
// models/PublicationModel.php

require_once __DIR__ . '/Model.php';

class PublicationModel extends Model {
    public function schedule($data) {
        $sql = "INSERT INTO publications (annonce_id, platform, scheduled_at, generated_text, repeat_days) 
                VALUES (?, ?, ?, ?, ?)";
        return $this->query($sql, [
            $data['annonce_id'],
            $data['platform'],
            $data['scheduled_at'],
            $data['generated_text'],
            $data['repeat_days'] ?? 0
        ]);
    }

    public function getPending() {
        $sql = "SELECT p.*, a.title, a.whatsapp_contact 
                FROM publications p 
                JOIN annonces a ON p.annonce_id = a.id 
                WHERE p.status = 'pending' AND p.scheduled_at <= NOW()";
        return $this->fetchAll($sql);
    }

    public function getAll() {
        $sql = "SELECT p.*, a.title as annonce_title 
                FROM publications p 
                JOIN annonces a ON p.annonce_id = a.id 
                ORDER BY p.scheduled_at DESC";
        return $this->fetchAll($sql);
    }

    public function create($data) {
        return $this->schedule($data);
    }

    public function updateStatus($id, $status, $error = null) {
        $sql = "UPDATE publications SET status = ?, published_at = NOW(), error_message = ? WHERE id = ?";
        $this->query($sql, [$status, $error, $id]);

        // Logic for Recurring Publications
        if ($status === 'published') {
            $current = $this->getById($id);
            if ($current && $current['repeat_days'] > 0) {
                $nextDate = date('Y-m-d H:i:s', strtotime("+{$current['repeat_days']} days", strtotime($current['scheduled_at'])));
                $this->schedule([
                    'annonce_id' => $current['annonce_id'],
                    'platform' => $current['platform'],
                    'scheduled_at' => $nextDate,
                    'generated_text' => $current['generated_text'],
                    'repeat_days' => $current['repeat_days']
                ]);
            }
        }
        return true;
    }

    public function getById($id) {
        return $this->fetch("SELECT * FROM publications WHERE id = ?", [$id]);
    }

    public function update($id, $data) {
        $sql = "UPDATE publications SET platform = ?, scheduled_at = ?, generated_text = ?, repeat_days = ? WHERE id = ?";
        return $this->query($sql, [
            $data['platform'],
            $data['scheduled_at'],
            $data['generated_text'],
            $data['repeat_days'] ?? 0,
            $id
        ]);
    }

    public function delete($id) {
        return $this->query("DELETE FROM publications WHERE id = ?", [$id]);
    }
}
