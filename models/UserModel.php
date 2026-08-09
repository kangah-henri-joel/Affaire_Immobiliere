<?php
// models/UserModel.php

require_once __DIR__ . '/Model.php';

class UserModel extends Model {
    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        return $this->fetch($sql, [$username, $username]);
    }

    public function verifyLogin($username, $password) {
        $user = $this->getByUsername($username);
        if ($user && password_verify($password, $user['password']) && $user['status'] !== 'pending') {
            return $user;
        }
        return false;
    }

    public function getAllActiveClientsEmails() {
        return $this->fetchAll("SELECT email FROM users WHERE role = 'client' AND status = 'active' AND email IS NOT NULL");
    }

    public function getAllAgents() {
        return $this->fetchAll("SELECT * FROM users WHERE role = 'agent' ORDER BY created_at DESC");
    }

    public function getAllAdmins() {
        return $this->fetchAll("SELECT * FROM users WHERE role = 'admin' ORDER BY created_at DESC");
    }

    public function getAllUsers() {
        return $this->fetchAll("SELECT * FROM users ORDER BY role ASC, created_at DESC");
    }

    public function updateRole($id, $role) {
        $allowed = ['super_admin', 'admin', 'agent', 'client'];
        if (!in_array($role, $allowed)) return false;
        return $this->query("UPDATE users SET role = ? WHERE id = ?", [$role, $id]);
    }

    public function updateStatus($id, $status) {
        return $this->query("UPDATE users SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function countByRole($role) {
        $result = $this->fetch("SELECT COUNT(*) as total FROM users WHERE role = ?", [$role]);
        return $result['total'] ?? 0;
    }

    public function getById($id) {
        return $this->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO users (username, password, full_name, email, country, role, phone_tel) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->query($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['full_name'],
            $data['email'] ?? null,
            $data['country'] ?? null,
            $data['role'] ?? 'agent',
            $data['phone_tel'] ?? null
        ]);
    }

    public function createClient($data) {
        $data['role'] = 'client';
        $this->create($data);
        return $this->db->lastInsertId();
    }

    public function createAgent($data) {
        $sql = "INSERT INTO users (username, password, full_name, email, country, role, status, phone_tel) VALUES (?, ?, ?, ?, ?, 'agent', 'pending', ?)";
        $this->query($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['full_name'],
            $data['email'] ?? null,
            $data['country'] ?? null,
            $data['phone_tel'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function createAdmin($data) {
        $sql = "INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'admin')";
        return $this->query($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['full_name']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET username = ?, full_name = ?, email = ?, country = ?, phone_whatsapp = ?, phone_tel = ?, phone_fixe = ? WHERE id = ?";
        $params = [
            $data['username'],
            $data['full_name'],
            $data['email'] ?? null,
            $data['country'] ?? null,
            $data['phone_whatsapp'] ?? null,
            $data['phone_tel'] ?? null,
            $data['phone_fixe'] ?? null,
            $id
        ];

        if (!empty($data['password'])) {
            $sql = "UPDATE users SET username = ?, full_name = ?, email = ?, country = ?, phone_whatsapp = ?, phone_tel = ?, phone_fixe = ?, password = ? WHERE id = ?";
            $params = [
                $data['username'],
                $data['full_name'],
                $data['email'] ?? null,
                $data['country'] ?? null,
                $data['phone_whatsapp'] ?? null,
                $data['phone_tel'] ?? null,
                $data['phone_fixe'] ?? null,
                password_hash($data['password'], PASSWORD_BCRYPT),
                $id
            ];
        }

        if (!empty($data['avatar'])) {
            // If avatar provided, update it too
            $sqlAvatar = "UPDATE users SET avatar = ? WHERE id = ?";
            $this->query($sqlAvatar, [$data['avatar'], $id]);
        }

        return $this->query($sql, $params);
    }

    public function delete($id) {
        return $this->query("DELETE FROM users WHERE id = ?", [$id]);
    }
}
