<?php
// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$host = 'localhost';
$db = 'user_database';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a unique reset token
        $reset_tok = bin2hex(random_bytes(32));
        $reset_token = hash('sha256', $reset_tok);
        $reset_expiry = date('Y-m-d H:i:s', strtotime('+4 hour'));

        // Save the reset token and expiry time in the database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        $stmt->execute([$reset_token, $reset_expiry, $email]);

        // Create the reset link
        $reset_link = "http://localhost/reset_password.php?token=$reset_token";

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; // Replace With Your email address
            $mail->Password = 'your-app-password'; // Replace with Your email password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ),
            );
            $mail->SMTPDebug = 2; // Debug level (1 for errors, 2 for messages, etc.)

            

            // Email content
            $mail->setFrom('your-email@gmail.com', 'PHP Assignment');
            $mail->addAddress($email); // Recipient email
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "<p>We received a request to reset your password.</p>
                          <p>Click the link below to reset your password:</p>
                          <p><a href='$reset_link'>$reset_link</a></p>
                          <p>This link will expire in 1 hour.</p>";

            // Send email
            $mail->send();
            echo 'Password reset link sent to your email.';
        } catch (Exception $e) {
            echo "Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'No account found with that email address.';
    }
}
?>
