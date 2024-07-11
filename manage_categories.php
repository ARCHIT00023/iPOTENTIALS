<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'partials/_dbconnect.php';

// Handle form submission to add new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cat_name']) && isset($_POST['cat_desc'])) {
    $cat_name = $_POST['cat_name'];
    $cat_desc = $_POST['cat_desc'];

    // Debugging output
    echo "Category Name: $cat_name<br>";
    echo "Category Description: $cat_desc<br>";

    $sql = "INSERT INTO categories (category_name, category_description) VALUES ('$cat_name', '$cat_desc')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $success_message = "Category added successfully.";
    } else {
        $error_message = "Failed to add category: " . mysqli_error($conn);
    }
}

// Handle deletion of category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_cat_id'])) {
    $delete_cat_id = $_POST['delete_cat_id'];

    $sql_delete = "DELETE FROM categories WHERE category_id = $delete_cat_id";
    $result_delete = mysqli_query($conn, $sql_delete);

    if ($result_delete) {
        header('Location: manage_categories.php');
        exit;
    } else {
        $error_message = "Failed to delete category: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Manage Categories</title>
</head>
<body>
    <?php include 'partials/_header.php'; ?>

    <div class="container my-4">
        <h2 class="text-center">Manage Categories</h2>

        <!-- Form to add new category -->
        <form action="manage_categories.php" method="post">
            <div class="form-group">
                <label for="cat_name">Category Name</label>
                <input type="text" class="form-control" id="cat_name" name="cat_name" required>
            </div>
            <div class="form-group">
                <label for="cat_desc">Category Description</label>
                <textarea class="form-control" id="cat_desc" name="cat_desc" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>

        <?php
        // Display success message if set
        if (isset($success_message)) {
            echo '<div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                    ' . $success_message . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }

        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
                    ' . $error_message . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
        ?>

        <h3 class="mt-4">Existing Categories</h3>
        <ul class="list-group">
            <?php
            $sql_select = "SELECT * FROM categories";
            $result_select = mysqli_query($conn, $sql_select);

            while ($row = mysqli_fetch_assoc($result_select)) {
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                        ' . htmlspecialchars($row['category_name']) . '
                        <form action="manage_categories.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_cat_id" value="' . $row['category_id'] . '">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                      </li>';
            }
            ?>
        </ul>
    </div>

    <?php include 'partials/_footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
