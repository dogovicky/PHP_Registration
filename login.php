<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $host = 'localhost';
    $db = 'user_database';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    try {
        // Connect to the database
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get form inputs
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        // Validation
        if (!$email || empty($password)) {
            echo "<p style='color: red;'>Invalid email or password.</p>";
            exit;
        }

        // Check if the user exists
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                echo "<p style='color: green;'>Login successful! Welcome, " . htmlspecialchars($user['email']) . ".</p>";
                // You can start a session here if needed
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                // Redirect to dashboard or home page
                header('Location: dashboard.php');
                exit;
            } else {
                echo "<p style='color: red;'>Incorrect password.</p>";
            }
        } else {
            echo "<p style='color: red;'>No user found with this email.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
    }
}
?>
