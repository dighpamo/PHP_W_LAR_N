<?php
namespace Models;

use PDO;
use App\Database;

class UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::pdo();
    }

    public function getUserById(int $id): array {
        $stmt = $this->db->prepare("SELECT id, username, created_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $username, string $password): bool {
        $stmt = $this->db->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        return $stmt->execute(['username' => $username, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function login(string $username, string $password): ?int {
        $stmt = $this->db->prepare("SELECT id, password_hash FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }
        if (password_verify($password, $data['password_hash'])) {
            return $data['id'];
        }
        return null;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);

    }

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT id, username, created_at FROM users ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
