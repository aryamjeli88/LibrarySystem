<?php
session_start();

// Database configuration
// config.php inside includes folder
$host = 'localhost';
$db   = 'library_db'; // Check if this matches your DB name exactly
$user = 'root';
$pass = ''; // Leave empty if you are using XAMPP default

try {
    // PDO connection (Phase 3 Requirement)
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// XSS Prevention (Phase 4 Requirement)
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>