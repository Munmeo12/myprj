-- ============================================================================
-- DỰ ÁN BÀI TẬP LỚN: WEBSITE TIN TỨC / BLOG CÓ TRANG QUẢN TRỊ
-- File: database/schema.sql
-- Mô tả: Định nghĩa cấu trúc CSDL, các bảng, khóa chính, khóa ngoại & ràng buộc
-- ============================================================================

-- 1. Tạo Database nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS news_blog_db
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE news_blog_db;

-- Disable ngoại khóa tạm thời để xóa bảng cũ an toàn không bị dính lỗi ràng buộc
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS post_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- 2. Bảng Người dùng (users)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    avatar VARCHAR(255) NULL,
    role ENUM('admin', 'editor', 'author', 'reader') NOT NULL DEFAULT 'reader',
    status ENUM('active', 'locked') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng Chuyên mục (categories)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng Bài viết (posts)
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    editor_id INT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT NULL,
    content LONGTEXT NOT NULL,
    thumbnail VARCHAR(255) NULL,
    status ENUM('draft', 'pending', 'published', 'rejected') NOT NULL DEFAULT 'draft',
    views INT NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Các ràng buộc khóa ngoại (Foreign Keys)
    CONSTRAINT fk_posts_categories FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    CONSTRAINT fk_posts_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_posts_editor FOREIGN KEY (editor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Bảng Thẻ bài viết (tags)
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Bảng Trung gian Bài viết - Thẻ (post_tags)
CREATE TABLE post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    
    CONSTRAINT fk_post_tags_posts FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_post_tags_tags FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Bảng Bình luận (comments)
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'hidden') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_comments_posts FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;