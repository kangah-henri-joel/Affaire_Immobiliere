<?php
// models/UserModel.php

require_once __DIR__ . '/Model.php';

class UserModel extends Model {
    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->fetch($sql, [$username]);
    }

    public function verifyLogin($username, $password) {
        $user = $this->getByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getAllAgents() {
        return $this->fetchAll("SELECT * FROM users WHERE role = 'agent' ORDER BY created_at DESC");
    }

    public function getById($id) {
        return $this->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['full_name'],
            $data['role'] ?? 'agent'
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET username = ?, full_name = ? WHERE id = ?";
        $params = [$data['username'], $data['full_name'], $id];
        
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET username = ?, full_name = ?, password = ? WHERE id = ?";
            $params = [$data['username'], $data['full_name'], password_hash($data['password'], PASSWORD_BCRYPT), $id];
        }
        
        return $this->query($sql, $params);
    }

    public function delete($id) {
        return $this->query("DELETE FROM users WHERE id = ?", [$id]);
    }
}
