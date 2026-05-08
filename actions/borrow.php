<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (isset($_GET['book_id']) && isset($_SESSION['user_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // 1. Update book status to borrowed
        $stmt1 = $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ? AND status = 'available'");
        $stmt1->execute([$book_id]);

        if ($stmt1->rowCount() > 0) {
            // 2. Insert into transactions (Matching your SQL columns: user_id, book_id, borrow_date)
            // CURDATE() captures today's date automatically
            $stmt2 = $pdo->prepare("INSERT INTO transactions (user_id, book_id, borrow_date) VALUES (?, ?, CURDATE())");
            $stmt2->execute([$user_id, $book_id]);

            $pdo->commit();
            header("Location: ../student/history.php?msg=Borrowed Successfully");
            exit();
        } else {
            $pdo->rollBack();
            die("Sorry, this book is already borrowed.");
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Error: " . $e->getMessage());
    }
}
?>