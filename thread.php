<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css">
    <style>
        #ques {
            min-height: 433px;
        }
    </style>
    <title>Welcome to iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>
    <?php
    if (!isset($_GET['threadid'])) {
        echo '<div class="container my-4">
                <div class="alert alert-danger" role="alert">
                    Error: Thread ID parameter is missing. Please go back to <a href="/FORUMS">iDiscuss Home</a>.
                </div>
            </div>';
        include 'partials/_footer.php';
        exit;
    }
    
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` WHERE thread_id=$id"; 
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_id = $row['thread_id'];

        $sql2 = "SELECT user_email FROM `users` WHERE sno='{$row['thread_user_id']}'";
        $result2 = mysqli_query($conn, $sql2);
        if ($result2 && mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            $posted_by = $row2['user_email'];
        } else {
            $posted_by = "Unknown";
        }
    } else {
        echo '<div class="container my-4">
                <div class="alert alert-danger" role="alert">
                    Error: Thread not found. Please go back to <a href="/FORUMS">iDiscuss Home</a>.
                </div>
            </div>';
        include 'partials/_footer.php';
        exit;
    }
    ?>

    <?php
    $showAlert = false;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['comment'])) {
            $comment = $_POST['comment'];
            $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
            $user_email = $_SESSION['user_email'];
            $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$user_email', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your comment has been added!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
        } elseif (isset($_POST['delete_comment_id'])) {
            $delete_comment_id = $_POST['delete_comment_id'];
            $sql = "DELETE FROM `comments` WHERE `comment_id` = $delete_comment_id";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> The comment has been deleted.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
        }
    }
    ?>

    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo isset($title) ? htmlspecialchars($title) : "Thread not found"; ?></h1>
            <p class="lead"><?php echo isset($desc) ? htmlspecialchars($desc) : ""; ?></p>
            <hr class="my-4">
            <p>This is a peer to peer forum. No Spam / Advertising / Self-promote in the forums is not allowed. Do not
                post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post
                questions. Remain respectful of other members at all times.</p>
            <p>Posted by: <em><?php echo htmlspecialchars($posted_by); ?></em></p>
        </div>
    </div>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<div class="container">
            <h1 class="py-2">Post a Comment</h1>
            <form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="post">
                <div class="form-group">
                    <label for="comment">Type your comment</label>
                    <textarea class="form-control" id="summernote" name="comment" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Post Comment</button>
            </form>
        </div>';
    } else {
        echo '<div class="container">
            <h1 class="py-2">Post a Comment</h1>
            <p class="lead">You are not logged in. Please login to be able to post comments.</p>
        </div>';
    }
    ?>
    <div class="container mb-5" id="ques">
        <h1 class="py-2">Discussions</h1>
        <?php
        $sql = "SELECT * FROM `comments` WHERE thread_id=$id";
        $result = mysqli_query($conn, $sql);
        $noResult = true;
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $noResult = false;
                $comment_id = $row['comment_id'];
                $content = htmlspecialchars_decode($row['comment_content'], ENT_QUOTES);
                $comment_time = $row['comment_time'];
                $comment_by = $row['comment_by'];

                $sql2 = "SELECT user_email, role FROM `users` WHERE email='$comment_by'";
                $result2 = mysqli_query($conn, $sql2);
                if ($result2 && mysqli_num_rows($result2) > 0) {
                    $row2 = mysqli_fetch_assoc($result2);
                    $user_email = $row2['user_email'];
                    $user_role = $row2['role'] ?? ''; // Make sure role key exists
                } else {
                    $user_email = "Unknown";
                    $user_role = "";
                }

                echo '<div class="media my-3">
                    <img src="img/user.png" width="54px" class="mr-3" alt="...">
                    <div class="media-body">
                        <p class="font-weight-bold my-0">' . htmlspecialchars($user_email) . ' at ' . htmlspecialchars($comment_time) . '</p>' . $content . '
                        <form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="post">
                            <input type="hidden" name="delete_comment_id" value="' . $comment_id . '">';
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                    echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                }
                echo '</form>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <p class="display-4">No Comments Found</p>
                    <p class="lead">Be the first person to comment</p>
                </div>
            </div>';
        }
        ?>
    </div>

    <?php include 'partials/_footer.php';?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

    <!-- AdminLTE JS -->
    <script src="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
</body>

</html>
