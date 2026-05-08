<?php 
require_once '../includes/config.php';
// Check if user is logged in
if(!isset($_SESSION['user_id'])) header("Location: ../auth/login.php");

// Handle Return Logic (Update transaction and book status)
if(isset($_POST['return_book'])) {
    $t_id = $_POST['t_id'];
    $b_id = $_POST['b_id'];
    
    // Update return date in transactions
    $stmt = $pdo->prepare("UPDATE transactions SET return_date = CURDATE() WHERE id = ?");
    $stmt->execute([$t_id]);
    
    // Make book available again
    $update = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
    $update->execute([$b_id]);
    header("Location: history.php");
}

// Fetch user's borrowing history
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT b.title, b.id as b_id, t.id as t_id, t.borrow_date, t.return_date 
                       FROM transactions t JOIN books b ON t.book_id = b.id 
                       WHERE t.user_id = ? ORDER BY t.id DESC");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My History - YIC Library</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>My Borrowing History</header>
    <nav>
        <a href="browse.php">Browse Catalog</a>
        <a href="../auth/logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Your Loans</h2>
        <table>
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Borrowed Date</th>
                    <th>Status / Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($history as $h): ?>
                <tr>
                    <td><?= clean($h['title']) ?></td>
                    <td><?= $h['borrow_date'] ?></td>
                    <td>
                        <?php if(!$h['return_date']): ?>
                            <form method="POST">
                                <input type="hidden" name="t_id" value="<?= $h['t_id'] ?>">
                                <input type="hidden" name="b_id" value="<?= $h['b_id'] ?>">
                                <button name="return_book" style="background:#D2B48C; color:#6F4E37; width:auto; padding:5px 15px;">Return Book</button>
                            </form>
                        <?php else: ?>
                            <span style="color: green;">Returned on <?= $h['return_date'] ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>