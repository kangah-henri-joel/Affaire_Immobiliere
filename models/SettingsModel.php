<?php
// models/SettingsModel.php

require_once __DIR__ . '/Model.php';

class SettingsModel extends Model {
    public function getAll() {
        $data = $this->fetchAll("SELECT * FROM settings");
        $settings = [];
        foreach ($data as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function update($key, $value) {
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?";
        return $this->query($sql, [$key, $value, $value]);
    }

    public function updateMultiple($data) {
        foreach ($data as $key => $value) {
            $this->update($key, $value);
        }
        return true;
    }
}
