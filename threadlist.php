<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is an admin
$is_admin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role'] == 'admin';

include 'partials/_dbconnect.php';
include 'partials/_header.php';

$id = $_GET['catid'];
$sql_cat = "SELECT * FROM `categories` WHERE category_id = $id";
$result_cat = mysqli_query($conn, $sql_cat);

if (!$result_cat) {
    die("Error fetching category: " . mysqli_error($conn));
}

while ($row_cat = mysqli_fetch_assoc($result_cat)) {
    $catname = $row_cat["category_name"];
    $catdesc = $row_cat["category_description"];
}

$showAlert = false;
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $th_title = $_POST['title'];
    $th_desc = $_POST['desc'];
    $sql_insert = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) 
                   VALUES ('$th_title', '$th_desc', '$id', '".$_SESSION['sno']."', current_timestamp())";
    $result_insert = mysqli_query($conn, $sql_insert);
    if (!$result_insert) {
        die("Error inserting thread: " . mysqli_error($conn));
    }
    $showAlert = true;
    if ($showAlert) {
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your thread has been added in queue for approval.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

if (isset($_GET['delete'])) {
    if ($_GET['delete'] == 'success') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Thread has been deleted.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    } else if ($_GET['delete'] == 'error') {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Could not delete the thread.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9G9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        #ques {
            min-height: 555px;
        }
    </style>
    <title>Welcome to iDiscuss</title>
</head>

<body>

    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to <?php echo $catname ?> forum</h1>
            <p class="lead"><?php echo $catdesc ?> </p>
            <hr class="my-4">
            <p>This forum is to discuss about all types of hatchback.</p>
            <p>Don't start a topic in the wrong category.</p>
            <p>Don't divert a topic by changing it midstream.</p>
            <p class="lead">
                <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a>
            </p>
        </div>
    </div>

    <div class="container" id="ques">
        <h1 class="py-2">Browse Questions</h1>
        <?php
        $sql_threads = "SELECT * FROM threads WHERE status='approved' AND thread_cat_id = $id";
        $result_threads = mysqli_query($conn, $sql_threads);

        if (!$result_threads) {
            die("Error fetching threads: " . mysqli_error($conn));
        }

        $noResult = true;
        while ($row_threads = mysqli_fetch_assoc($result_threads)) {
            $noResult = false;
            $id_thread = $row_threads['thread_id'];
            $title_thread = $row_threads['thread_title'];
            $desc_thread = $row_threads['thread_desc'];
            $thread_time = $row_threads['timestamp'];
            $thread_user_id = $row_threads['thread_user_id'];
            $sql2="SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2); 
            echo '<div class="media my-3">
                <img class="mr-3" src="img/download.jfif" width="50px" alt="Generic placeholder image">
                <div class="media-body">
                    <p class="font-weight-bold my-0">'. $row2['user_email'].' ' . $thread_time . '</p>
                    <h5 class="mt-0"><a class="text-dark" href="thread.php?threadid=' . $id_thread . '">' . $title_thread . '</a></h5>
                    ' . $desc_thread . '
                </div>';
                if ($is_admin) {
                    echo '<form method="post" action="delete_thread.php" style="display:inline;">
                        <input type="hidden" name="thread_id" value="' . $id_thread . '">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>';
                }
            echo '</div>';
        }
        if ($noResult) {
            echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <p class="display-4">No Questions yet in this category :(</p>
                    <p class="lead">Be the first one to ask a question.</p>
                </div>
            </div>';
        }
        ?>
        <div class="container">
            <h1 class="py-2">Ask a question</h1>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
                <div class="form-group">
                    <label for="title">Problem Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                </div>
                <div class="form-group">
                    <label for="desc">Elaborate your problem</label>
                    <textarea class="form-control" id="desc" name="desc" placeholder="Explain here"
                        style="height: 100px"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>

    <?php include 'partials/_footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>

</html>
