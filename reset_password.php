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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token
    $stmt = $pdo->prepare("SELECT email FROM users WHERE reset_token = :token AND reset_expiry > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Display the password reset form
        echo '<form action="update_password.php" method="POST">
                <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                <label for="password">New Password:</label>
                <input type="password" name="password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
                <button type="submit">Reset Password</button>
              </form>';
    } else {
        echo "<p style='color: red;'>Invalid or expired token.</p>";
    }
}
?>
