<?php
include 'partials/_dbconnect.php';
include 'partials/_header.php';

// Get the search query
$query = $_GET['query'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        footer {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <main>
            <div class="container my-4">
                <h2 class="text-center">Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
                <div class="result-container my-4">
                    <?php
                    // SQL query to search threads
                    $sql = "SELECT * FROM threads WHERE MATCH (thread_title, thread_desc) AGAINST ('$query')";
                    $result = mysqli_query($conn, $sql);
                    $noResult = true;

                    while ($row = mysqli_fetch_assoc($result)) {
                        $noResult = false;
                        $title = $row['thread_title'];
                        $desc = $row['thread_desc'];
                        $thread_id = $row['thread_id'];
                        $thread_time = $row['timestamp'];
                        $thread_user_id = $row['thread_user_id'];

                        // Fetch the user
                        $sql2 = "SELECT user_email FROM users WHERE sno='$thread_user_id'";
                        $result2 = mysqli_query($conn, $sql2);
                        $user_email = "Unknown"; // Default value in case user is not found
                        if ($result2 && mysqli_num_rows($result2) > 0) {
                            $row2 = mysqli_fetch_assoc($result2);
                            $user_email = $row2['user_email'];
                        }

                        echo '<div class="media my-3">
                                <div class="media-body">
                                    <h5 class="mt-0"><a class="text-dark" href="thread.php?threadid=' . $thread_id . '">' . htmlspecialchars($title) . '</a></h5>
                                    ' . htmlspecialchars($desc) . '
                                    <p class="font-weight-bold my-0">Asked by: ' . htmlspecialchars($user_email) . ' at ' . $thread_time . '</p>
                                </div>
                            </div>';
                    }
                    if ($noResult) {
                        echo '<div class="jumbotron jumbotron-fluid">
                                <div class="container">
                                    <h1 class="display-4">No Results Found</h1>
                                    <p class="lead">Suggestions:
                                        <ul>
                                            <li>Make sure that all words are spelled correctly.</li>
                                            <li>Try different keywords.</li>
                                            <li>Try more general keywords.</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>';
                    }
                    ?>
                </div>
            </div>
        </main>

        <?php include 'partials/_footer.php';?>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>
