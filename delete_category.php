<?php
// Check if session is active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit;
}

// Include database connection
include 'partials/_dbconnect.php';

// Check if POST request with cat_id
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cat_id'])) {
    $cat_id = $_POST['cat_id'];

    // Prepare SQL statement to delete category
    $sql = "DELETE FROM categories WHERE category_id = $cat_id";

    // Execute query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect back to manage_categories.php after successful deletion
        header('Location: manage_categories.php');
        exit;
    } else {
        // Handle error (optional)
        echo "Error deleting category: " . mysqli_error($conn);
    }
} else {
    // Handle case where cat_id is not set (optional)
    echo "Category ID not provided.";
}
?>
