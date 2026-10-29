<?php
// File: repositories/PostRepository.php
require_once __DIR__ . '/../database/db.php';

function findAllPosts(): array {
    $sql = "SELECT p.*, c.name AS category_name 
            FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.id DESC";
    return db()->query($sql)->fetchAll();
}

function findPostById(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $res = $stmt->fetch();
    return $res ?: null;
}

function savePost(string $title, string $slug, string $summary, string $content, int $categoryId, int $authorId, ?int $id = null): bool {
    if ($id) {
        $sql = "UPDATE posts SET title = :title, slug = :slug, summary = :summary, content = :content, category_id = :cat_id WHERE id = :id";
        $params = ['title' => $title, 'slug' => $slug, 'summary' => $summary, 'content' => $content, 'cat_id' => $categoryId, 'id' => $id];
    } else {
        $sql = "INSERT INTO posts (title, slug, summary, content, category_id, author_id, status) VALUES (:title, :slug, :summary, :content, :cat_id, :author_id, 'published')";
        $params = ['title' => $title, 'slug' => $slug, 'summary' => $summary, 'content' => $content, 'cat_id' => $categoryId, 'author_id' => $authorId];
    }
    return db()->prepare($sql)->execute($params);
}

function deletePost(int $id): bool {
    $stmt = db()->prepare("DELETE FROM posts WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>