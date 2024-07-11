<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $thread_id = isset($_POST['thread_id']) ? (int)$_POST['thread_id'] : 0;

    if ($thread_id > 0) {
        // Use a prepared statement to delete the thread
        $stmt = $conn->prepare("DELETE FROM threads WHERE thread_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $thread_id);
            if ($stmt->execute()) {
                // Redirect back to the previous page with a success message
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '&delete=success');
            } else {
                // Redirect back to the previous page with an error message
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '&delete=error');
            }
            $stmt->close();
        } else {
            // Error preparing the statement
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&delete=stmt_error');
        }
    } else {
        // Redirect back to the previous page with an error message for invalid ID
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '&delete=invalid_id');
    }
} else {
    // Redirect to home if accessed directly
    header('Location: index.php');
}

$conn->close();
exit;
?>
