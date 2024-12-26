<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $host = 'localhost';
    $db = 'user_database';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get form inputs
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validation
        if (!$email) {
            echo "<p style='color: red;'>Invalid email address.</p>";
        } elseif (strlen($password) < 8) {
            echo "<p style='color: red;'>Password must be at least 8 characters long.</p>";
        } elseif ($password !== $confirmPassword) {
            echo "<p style='color: red;'>Passwords do not match.</p>";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Registration successful!</p>";
            } else {
                echo "<p style='color: red;'>An error occurred. Please try again.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
    }
}
?>
