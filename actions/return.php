<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (isset($_GET['book_id']) && isset($_SESSION['user_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?")->execute([$book_id]);

        $pdo->prepare("INSERT INTO transactions (user_id, book_id, action_type) VALUES (?, ?, 'return')")->execute([$user_id, $book_id]);

        $pdo->commit();
        header("Location: ../student/browse.php?msg=Returned Successfully");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>