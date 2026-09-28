<?php

namespace Models;

use PDO;
use App\Database;

class UserModel {
    private PDO $db;

    private const BLOCKED = [
        'id',
        'created_at'
    ];

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

    public function update(int $id, array $values): bool
    {
        if (empty($values)) {
            return false;
        }
        if (array_intersect(self::BLOCKED, array_keys($values))) {
            return false;
        }
        
        $cont = array();
        foreach (array_keys($values) as $k) {
            $cont[] = $k . '= :' . $k;
        }
        $stmt = $this->db->prepare("UPDATE articles SET " . implode(", ", $cont) . " WHERE id = :id");
        $values['id'] = $id;
        return $stmt->execute($values);
    }

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT id, username, created_at FROM users ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminIndexes(int $page = 1, int $perPage = 10): array
    {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT id, username, created_at  
            FROM users
            ORDER BY id
            LIMIT :perPage 
            OFFSET :page"
        );
        $stmt->bindValue('page', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
