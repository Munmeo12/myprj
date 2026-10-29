<?php
// File: repositories/CategoryRepository.php
require_once __DIR__ . '/../database/db.php';

function findAllCategories(): array {
    return db()->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
}

function findCategoryById(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $res = $stmt->fetch();
    return $res ?: null;
}

function saveCategory(string $name, string $slug, string $desc, ?int $id = null): bool {
    if ($id) {
        $sql = "UPDATE categories SET name = :name, slug = :slug, description = :desc WHERE id = :id";
        $params = ['name' => $name, 'slug' => $slug, 'desc' => $desc, 'id' => $id];
    } else {
        $sql = "INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :desc)";
        $params = ['name' => $name, 'slug' => $slug, 'desc' => $desc];
    }
    return db()->prepare($sql)->execute($params);
}

function deleteCategory(int $id): bool {
    $stmt = db()->prepare("DELETE FROM categories WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>