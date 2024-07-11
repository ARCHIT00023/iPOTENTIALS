<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate password (replace with your actual admin password validation logic)
    $admin_password = 'your_admin_password_here'; // Replace with your admin password hash or plain text

    if ($password === $admin_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['is_admin'] = true;
        $_SESSION['timestamp'] = time(); // Reset timestamp to prevent immediate re-lock
        header('Location: admin.php');
        exit;
    } else {
        // Password incorrect, redirect back to lock screen with error message
        header('Location: lockscreen.php?error=password');
        exit;
    }
} else {
    // Redirect to lock screen if accessed directly
    header('Location: lockscreen.php');
    exit;
}
?>
