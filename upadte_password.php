<?php
include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM password_resets WHERE token='$token' AND expires_at > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        $sql = "UPDATE users SET user_password='$password' WHERE user_email='$email'";
        if (mysqli_query($conn, $sql)) {
            $sql = "DELETE FROM password_resets WHERE token='$token'";
            mysqli_query($conn, $sql);
            echo "<script>alert('Your password has been updated.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired token.'); window.location.href = 'forgot_password.php';</script>";
    }
}
?>
