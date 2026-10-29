<?php
// File: database/db.php
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host     = '127.0.0.1';
        $port     = '3307'; // Tách riêng port chuẩn DSN
        $dbname   = 'news_blog_db_new';
        $username = 'root';
        $password = '';
        $charset  = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException("Lỗi kết nối CSDL: " . $e->getMessage(), (int)$e->getCode());
        }
    }
    return $pdo;
}
?>