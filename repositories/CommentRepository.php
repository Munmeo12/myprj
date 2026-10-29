<?php
// File: repositories/CommentRepository.php
require_once __DIR__ . '/../database/db.php';

function findAllComments(): array {
    $sql = "SELECT c.*, p.title AS post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            ORDER BY c.id DESC";
    return db()->query($sql)->fetchAll();
}

function updateCommentStatus(int $id, string $status): bool {
    $stmt = db()->prepare("UPDATE comments SET status = :status WHERE id = :id");
    return $stmt->execute(['status' => $status, 'id' => $id]);
}

function deleteComment(int $id): bool {
    $stmt = db()->prepare("DELETE FROM comments WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>