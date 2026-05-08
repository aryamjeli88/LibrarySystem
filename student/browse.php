<?php
session_start();
require_once '../includes/config.php';
if (!isset($_SESSION['user_id'])) header("Location: ../auth/login.php");

if (isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d');

    // كود الاستعارة (PDO)
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, book_id, borrow_date) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $date]);

    // تحديث حالة الكتاب
    $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ?")->execute([$book_id]);
    header("Location: history.php");
}

$books = $pdo->query("SELECT * FROM books WHERE status = 'available'")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header style="background:#6F4E37; color:white; padding:10px; text-align:center;">
        <h1>Available Books</h1>
        <a href="history.php" style="color:white;">My History</a> | <a href="../auth/logout.php" style="color:white;">Logout</a>
    </header>
    <div class="container" style="display:flex; flex-wrap:wrap; gap:20px; padding:20px;">
        <?php foreach($books as $b): ?>
        <div class="card" style="width:250px;">
            <h3><?= $b['title'] ?></h3>
            <p>Author: <?= $b['author'] ?></p>
            <form method="POST">
                <input type="hidden" name="book_id" value="<?= $b['id'] ?>">
                <button type="submit" name="borrow">Borrow</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>