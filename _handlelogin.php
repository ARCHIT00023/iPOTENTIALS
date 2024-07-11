<?php
session_start();
$showError = "false";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $login_email = $_POST['loginEmail'];
    $login_pass = $_POST['loginPass'];

    // Check if this email exists
    $existSql = "SELECT * FROM `users` WHERE `user_email` = '$login_email'";
    $result = mysqli_query($conn, $existSql);

    if ($result) {
        $numRows = mysqli_num_rows($result);
        if ($numRows == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($login_pass, $row['user_pass'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_email'] = $login_email;
                $_SESSION['sno'] = $row['sno']; // Store the user's sno
                $_SESSION['role'] = $row['is_admin'] == 1 ? 'admin' : 'user'; // Store the user's role

                header("Location: /FORUMS/index.php?loginsuccess=true");
                exit();
            } else {
                $_SESSION['login_error'] = "Invalid Password";
            }
        } else {
            $_SESSION['login_error'] = "No Account Found with This Email";
        }
    } else {
        $_SESSION['login_error'] = "Database query failed";
    }
    header("Location: /FORUMS/index.php?loginsuccess=false");
    exit();
}
?>
