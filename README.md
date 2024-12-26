# PHP_Registration_Login

# Contributors
    1. CLARENCE LARIA - SIT/B/01-02287/2021
    1. OBED MBOYA - SIT/B/01-05039/2022
    1. ELON OGONJI - SIT/B/01-02917/2022
    1. VICTOR HIKAH - SIT/B/01-02884/2022
# PHP User Management System Documentation

## Overview

This PHP-based project implements a user management system. The system allows users to:

1. Register for an account.
2. Log in to their accounts.
3. Reset their password through email in case they forget it.

The system uses a MySQL database to store user details and PHPMailer for sending emails.

## Features

1. **User Registration:** New users can create accounts by providing personal details.
2. **User Login:** Registered users can log in using their email and password.
3. **Password Reset:** Users can reset their password by receiving an email with a reset link.

---

## Installation and Setup

### Prerequisites

- WAMP server (or any PHP server environment).
- PHP version 7.4 or later.
- MySQL database.
- Composer (for managing dependencies).

### Steps

1. **Download the Project:**

   - Clone or download the project files into the `www` directory of your WAMP server.

2. **Set Up the Database:**

   - Open phpMyAdmin and create a database called `user_database`.
   - Run the following SQL query to create the `users` table:
     ```sql
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         first_name VARCHAR(50) NOT NULL,
         last_name VARCHAR(50) NOT NULL,
         email VARCHAR(100) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         reset_token VARCHAR(255),
         reset_token_expiry DATETIME,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```

3. **Install PHPMailer:**

   - Run the following command in the project directory to install PHPMailer:
     ```bash
     composer require phpmailer/phpmailer
     ```

4. **Update Configuration Files:**

   - Configure the database connection in the PHP scripts (registration, login, reset password, etc.).
   - Update email settings in the `send_reset_email.php` script to match your SMTP configuration.

---

## Project Structure

```
root
|-- assets/
    |--logo.jpg           #Logo for the website
|-- composer.json         # Responsible for handling third party packages
|-- composer.lock         # Responsible for handling third party packages
|-- login.html            # User login page
|-- login.php             # User login script
|-- logout.php            # Logout a user script
|-- dashboard.php         # Dashboard for logged-in users
|-- forgot_password.html  # Form to request password reset
|-- registration.php      # User registration script
|-- send_reset_email.php  # Script to send reset password email
|-- reset_password.php    # Form to reset password
|-- signup.html           # Form to register users
|-- update_password.php   # Script to reset password in the database
|-- vendor/               # PHPMailer and Composer dependencies
```

---

## Features in Detail

### 1. User Registration

**File:** `register.php && signup.html`

- Users provide their first name, last name, email, and password.
- The password is hashed using PHP's `password_hash()` function before storing it in the database.
- Basic validation ensures no duplicate email registrations.

---

### 2. User Login

**File:** `login.php && login.html`

- Users log in using their email and password.
- Passwords are verified using PHP's `password_verify()` function.

---

### 3. Password Reset

**Step 1: Forgot Password Form**

**File:** `forgot_password.html`

- A form where the user enters their email to request a password reset.

---

**Step 2: Send Reset Email**

**File:** `send_reset_email.php`

Uses PHPMailer to send an email containing the reset link.

**Step 3: Reset Password Form**

**File:** `reset_password.php`

- Validates the token and expiry before allowing the user to set a new password.

---

## SMTP Configuration

- Update the SMTP settings in `send_reset_email.php` as shown:

```php
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-email-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

---

## Security Considerations

1. **Password Hashing:** All passwords are hashed using `password_hash()`.
2. **Prepared Statements:** SQL queries use prepared statements to prevent SQL injection.
3. **Token Expiry:** Reset tokens expire after 4 hour.
4. **Session Management:** Secure session handling prevents unauthorized access.

---

## Troubleshooting

1. **Email Not Sending:**

   - Check SMTP settings and ensure the email credentials are correct.
   - Verify that your Google account allows less secure apps (if applicable).

2. **Database Connection Issues:**

   - Verify the files have the correct credentials.
        $host = 'localhost';
        $db = 'user_database';
        $user = 'root';
        $pass = '';
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

3. **Reset Link Not Working:**

   - Ensure the token and expiry logic are correctly implemented.

---


