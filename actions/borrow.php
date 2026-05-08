<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (isset($_GET['book_id']) && isset($_SESSION['user_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        $stmt1 = $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ? AND status = 'available'");
        $stmt1->execute([$book_id]);

        if ($stmt1->rowCount() > 0) {
            $stmt2 = $pdo->prepare("INSERT INTO transactions (user_id, book_id, action_type) VALUES (?, ?, 'borrow')");
            $stmt2->execute([$user_id, $book_id]);

            $pdo->commit();
            header("Location: ../student/history.php?msg=Borrowed Successfully");
        } else {
            $pdo->rollBack();
            die("Book is already borrowed or not found.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>