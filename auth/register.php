<?php 
require_once '../includes/config.php';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'student'; // Default role for new signups

    try {
        // 1. Check if email already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        
        if ($check->rowCount() > 0) {
            echo "<script>alert('Error: This email is already registered!');</script>";
        } else {
            // 2. Insert new student
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $role]);
            
            echo "<script>
                    alert('Registration Successful! You can login now.');
                    window.location.href = 'login.php';
                  </script>";
        }
    } catch (PDOException $e) {
        // This will help you see the exact error if it fails
        die("Database Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - YIC Library</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>Library System - Student Registration</header>
    <div class="container">
        <form method="POST" action="">
            <h2>Create New Account</h2>
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="register">Sign Up</button>
            <p style="text-align:center;">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>