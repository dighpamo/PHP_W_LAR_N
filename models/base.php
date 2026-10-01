<?php

use PDO;
use App\Database;

class BaseModel {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    private function checkValue(string $value): bool
    {
        $value = preg_match('/[a-zA-Zа-яА-Я]/u', $value);
        return empty($value);
    }

    public function getById(int $id, string $basename): array {
        $stmt = $this->db->prepare("SELECT * FROM " . $basename . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}