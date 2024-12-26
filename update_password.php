<?php
// Database connection
$host = 'localhost';
$db = 'user_database';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($password) < 8) {
        echo "<p style='color: red;'>Password must be at least 8 characters long.</p>";
    } elseif ($password !== $confirmPassword) {
        echo "<p style='color: red;'>Passwords do not match.</p>";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the user's password and clear the reset token
        $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expiry = NULL WHERE reset_token = :token");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':token', $token);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Password successfully updated. You can now <a href='login.html'>log in</a>.</p>";
        } else {
            echo "<p style='color: red;'>Failed to reset password. Please try again later.</p>";
        }
    }
}
?>
