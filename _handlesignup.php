_handlesignup.php
<?php
$showError = "false";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $user_email = $_POST['signupEmail'];
    $pass = $_POST['signupPassword'];
    $cpass = $_POST['signupcPassword'];
    $is_admin = isset($_POST['signupAdmin']) ? 1 : 0; // Check if the admin checkbox is checked

    // Check if this email exists
    $existSql = "SELECT * FROM `users` WHERE `user_email` = '$user_email'";
    $result = mysqli_query($conn, $existSql);

    if ($result) {
        $numRows = mysqli_num_rows($result);
        if ($numRows > 0) {
            $showError = "Email Already Exists";
        } else {
            if ($pass == $cpass) {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`user_email`, `user_pass`, `is_admin`, `timestamp`) VALUES ('$user_email', '$hash', $is_admin, current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $showAlert = true;
                    header("Location: /FORUMS/index.php?signupsuccess=true");
                    exit();
                } else {
                    $showError = "Could not insert data";
                }
            } else {
                $showError = "Passwords do not match";
            }
        }
    } else {
        $showError = "Database query failed";
    }
    header("Location: /FORUMS/index.php?signupsuccess=false&error=" . urlencode($showError));
    exit();
}
?>