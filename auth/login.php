<?php 
require_once '../includes/config.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 2. Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        // Create Sessions
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // 3. Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../student/browse.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid Login! Please check your email and password.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - YIC Library</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>Library System - Login</header>
    <div class="container">
        <form method="POST" action="">
            <h2>Welcome Back</h2>
            <input type="email" name="email" placeholder="Email (e.g. admin@yic.edu.sa)" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <p style="text-align:center;">New student? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>