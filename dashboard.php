<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.html');
    exit;
}

// Get user email from the session
$userEmail = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-full">
    <div class="min-h-full flex flex-col items-center justify-center p-6 bg-gray-100">
        <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-800">Welcome to your Dashboard</h1>
            <p class="mt-2 text-gray-600">Hello, <strong><?php echo htmlspecialchars($userEmail); ?></strong>!</p>

            <div class="mt-6">
                <p class="text-gray-700">You were successfully logged in.</p>
            </div>

            <div class="mt-6">
                <a href="logout.php" class="inline-block bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
