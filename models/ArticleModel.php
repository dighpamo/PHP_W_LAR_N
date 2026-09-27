<?php

namespace Models;

use PDO;
use App\Database;

class ArticleModel {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    private const BLOCKED = [
            'id',
            'slug',
            'views_count',
            'published_at',
            'created_at'
    ];

    private const REQUIRED = [
        'title', 'content'
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

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT a.*, c.slug AS category_slug, c.category_name
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBySlug(string $slug): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, c.category_name, c.slug AS category_slug 
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.slug = :slug"
        );
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCategory(int $category_id, int $page = 1, int $perPage = 10): array {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT a.title, a.slug, LEFT(a.content, 100) as preview, a.image_path, a.category_id, a.views_count, 
            c.category_name, c.slug as category_slug 
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.category_id = :category_id 
            LIMIT :perPage 
            OFFSET :page"
        );
        $stmt->bindValue('category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue('page', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCreator(int $creator_id, int $page = 1, int $perPage = 10): array
    {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT title, slug, LEFT(content, 100) as preview, image_path, category_id, views_count  
            FROM articles 
            WHERE creator_id = :creator_id 
            LIMIT :perPage 
            OFFSET :page"
        );
        $stmt->bindValue('creator_id', $creator_id, PDO::PARAM_INT);
        $stmt->bindValue('page', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(int $page = 1, int $perPage = 10): array {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT a.title, a.slug, LEFT(a.content, 100) as preview, a.image_path, a.category_id, a.views_count,
            c.category_name, c.slug AS category_slug 
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LIMIT :perPage 
            OFFSET :page"
        );
        $stmt->bindValue('page', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
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
        if (($values['category_id'] ?? '') === '') {
            $values['category_id'] = null;
        }
        $cont = array();
        foreach (array_keys($values) as $k) {
            $cont[] = $k . '= :' . $k;
        }
        $stmt = $this->db->prepare("UPDATE articles SET " . implode(", ", $cont) . " WHERE id = :id");
        $values['id'] = $id;
        return $stmt->execute($values);
    }

    public function create(array $values): bool {
        if (empty($values)) {
            return false;
        }
        if (array_intersect(self::BLOCKED, array_keys($values)) || !array_intersect(self::REQUIRED, array_keys($values))) {
            return false;
        }
        if ($this->checkValue($values['title']) || $this->checkValue($values['content'])) {
            return false;
        }

        if (($values['category_id'] ?? '') === '') {
            $values['category_id'] = null;
        }

        $title = $values['title'];

        $slug = transliterator_transliterate("Russian-Latin/BGN", $title);
        $slug = $this->PrepareSlug($slug);

        if ($slug === '') {
            $slug = 'article';
        }

        $baseSlug = $slug;
        $i = 2;
        while (true) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
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
        $stmt = $this->db->prepare("INSERT INTO articles (" . implode(", ", $val)  . ") VALUES (" . implode(", ", $cont) . ")");
        return $stmt->execute($values);
        
    }

    public function inceaseArticleCount(string $slug): bool {
        $stmt = $this->db->prepare("UPDATE articles SET views_count + 1 WHERE slug = :slug");
        return $stmt->execute(['slug' => $slug]);
    }

    public function getAdminIndexes(int $page = 1, int $perPage = 10): array
    {
        if ($page < 1) {
            $page = 1;
        }
        $stmt = $this->db->prepare(
            "SELECT id, title, views_count  
            FROM articles
            ORDER BY id
            LIMIT :perPage 
            OFFSET :page"
        );
        $stmt->bindValue('page', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount(): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM articles");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}