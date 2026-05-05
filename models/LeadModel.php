<?php
// models/LeadModel.php

require_once __DIR__ . '/Model.php';

class LeadModel extends Model {
    public function create($data) {
        $sql = "INSERT INTO leads (annonce_id, client_name, client_phone, client_email, message) 
                VALUES (?, ?, ?, ?, ?)";
        return $this->query($sql, [
            $data['annonce_id'],
            $data['client_name'],
            $data['client_phone'],
            $data['client_email'],
            $data['message']
        ]);
    }

    public function getAll() {
        return $this->fetchAll("SELECT l.*, a.title as annonce_title FROM leads l 
                                LEFT JOIN annonces a ON l.annonce_id = a.id 
                                ORDER BY l.created_at DESC");
    }

    public function getById($id) {
        return $this->fetch("SELECT * FROM leads WHERE id = ?", [$id]);
    }

    public function saveReply($id, $reply) {
        $sql = "UPDATE leads SET admin_reply = ?, replied_at = NOW() WHERE id = ?";
        return $this->query($sql, [$reply, $id]);
    }
}
