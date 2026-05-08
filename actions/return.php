<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (isset($_GET['book_id']) && isset($_SESSION['user_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // 1. Make the book available again in the 'books' table
        $stmt1 = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
        $stmt1->execute([$book_id]);

        // 2. Update the existing transaction record with the return date
        // Instead of INSERT, we use UPDATE because the record already exists from when they borrowed it
        $stmt2 = $pdo->prepare("UPDATE transactions SET return_date = CURDATE() 
                               WHERE user_id = ? AND book_id = ? AND return_date IS NULL");
        $stmt2->execute([$user_id, $book_id]);

        $pdo->commit();
        header("Location: ../student/history.php?msg=Returned Successfully");
        exit();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Error: " . $e->getMessage());
    }
}
?>