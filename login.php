<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Assume $email and $password are retrieved from a form
$email = $_POST['email'];
$password = $_POST['password'];

// Query to check user credentials
$sql = "SELECT * FROM users WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['loggedin'] = true;
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['is_admin'] = $user['is_admin'];
    $_SESSION['user_id'] = $user['sno'];
    // Redirect to the home page or dashboard
    header("Location: index.php");
} else {
    // Handle login failure
    echo "Invalid email or password";
}

$stmt->close();
$conn->close();
?>