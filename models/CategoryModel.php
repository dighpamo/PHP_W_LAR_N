<?php 

namespace Models;

use PDO;
use App\Database;

class CategoryModel {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    private const BLOCKED = [
        'id',
        'slug',
    ];

    private const REQUIRED = [
        'category_name',
    ];

    private function PrepareSlug(string $str): string
    {
        $res = strtolower($str);
        $res = preg_replace('/[^a-z0-9-]+/', '-', $res);
        $res = preg_replace('/-+/', '-', $res);
        $res = trim($res, '-');
        $res = substr($res, 0, 200);
        $res = trim($res, '-');
        return $res;
    }

    private function checkValue(string $value): bool
    {
        $value = preg_match('/[a-zA-Zа-яА-Я]/u', $value);
        return empty($value);
    }

    public function getById(int $id): array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBySlug(string $slug): array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
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
        $stmt = $this->db->prepare("UPDATE categories SET " . implode(", ", $cont) . " WHERE id = :id");
        $values['id'] = $id;
        return $stmt->execute($values);
    }

    public function getAll(int $page = 1, int $perPage = 10): array
    {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT * 
            FROM categories 
            ORDER BY id
            LIMIT :perPage 
            OFFSET :page");
        $stmt->bindValue('page', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $values): bool
    {
        if (empty($values)) {
            return false;
        }
        if (array_intersect(self::BLOCKED, array_keys($values)) || !array_intersect(self::REQUIRED, array_keys($values))) {
            return false;
        }
        if ($this->checkValue($values['category_name'])) {
            return false;
        }

        $title = $values['category_name'];

        $slug = transliterator_transliterate("Russian-Latin/BGN", $title);
        $slug = $this->PrepareSlug($slug);

        if ($slug === '') {
            $slug = 'category_name';
        }

        $baseSlug = $slug;
        $i = 2;
        while (true) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = :slug");
            $stmt->execute(['slug' => $baseSlug]);
            $count = $stmt->fetchColumn();
            if ((int) $count === 0) {
                break;
            }
            $baseSlug = $slug . "-" . $i;
            $i++;
        }

        $values['slug'] = $baseSlug;
        $cont = array();
        $val = array();
        foreach (array_keys($values) as $k) {
            $cont[] = ':' . $k;
            $val[] = $k;
        }
        $stmt = $this->db->prepare("INSERT INTO categories (" . implode(", ", $val)  . ") VALUES (" . implode(", ", $cont) . ")");
        return $stmt->execute($values);
    }

    public function getTotalCount(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getAdminIndexes(int $page = 1, int $perPage = 10): array
    {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT id, category_name  
            FROM categories
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