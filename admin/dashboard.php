<?php 
require_once '../includes/config.php';

// Phase 4: Role-based access control
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied: Only Admins can access this page.");
}

// Phase 3: CRUD - Create Operation (Add New Book)
if(isset($_POST['add_book'])) {
    $title = clean($_POST['title']);
    $author = clean($_POST['author']);
    $category = clean($_POST['category']);
    
    // Using Prepared Statements for Security
    $stmt = $pdo->prepare("INSERT INTO books (title, author, category) VALUES (?, ?, ?)");
    $stmt->execute([$title, $author, $category]);
    echo "<script>alert('Book added successfully!');</script>";
}

// Fetch all books for "Manage Books" section
$all_books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();

// Fetch all active loans (Borrowed Books)
// This query joins users and books tables to show who borrowed what
$loans = $pdo->query("SELECT u.username, b.title, t.borrow_date 
                      FROM transactions t 
                      JOIN users u ON t.user_id = u.id 
                      JOIN books b ON t.book_id = b.id 
                      WHERE t.return_date IS NULL")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Library System</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .section-title { color: var(--primary-brown); margin-top: 40px; border-bottom: 2px solid var(--accent-tan); }
        .admin-nav { text-align: center; margin-bottom: 20px; }
        .admin-nav a { margin: 0 10px; color: var(--primary-brown); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <header>Admin Control Panel</header>
    
    <div class="container">
        <div class="admin-nav">
            <a href="#add">Add Book</a> | 
            <a href="#manage">Manage Catalog</a> | 
            <a href="#loans">Track Borrowers</a> | 
            <a href="../auth/logout.php" style="color:red;">Logout</a>
        </div>

        <h2 id="add">Add New Book</h2>
        <form method="POST" action="">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author Name" required>
            <input type="text" name="category" placeholder="Category (e.g. Science, Tech)" required>
            <button type="submit" name="add_book">Save Book to System</button>
        </form>

        <h2 id="loans" class="section-title">Students Borrowing List</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($loans)): ?>
                    <tr><td colspan="3">No books are currently borrowed.</td></tr>
                <?php else: ?>
                    <?php foreach($loans as $loan): ?>
                    <tr>
                        <td><?= clean($loan['username']) ?></td>
                        <td><?= clean($loan['title']) ?></td>
                        <td><?= $loan['borrow_date'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h2 id="manage" class="section-title">Library Catalog Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($all_books as $book): ?>
                <tr>
                    <td><?= $book['id'] ?></td>
                    <td><?= clean($book['title']) ?></td>
                    <td><?= clean($book['author']) ?></td>
                    <td>
                        <span style="color: <?= ($book['status'] == 'available') ? 'green' : 'red' ?>;">
                            <?= strtoupper($book['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer style="position: relative; margin-top: 50px;">© 2026 Yanbu Industrial College</footer>
</body>
</html>