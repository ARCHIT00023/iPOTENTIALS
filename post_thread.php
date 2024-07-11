<?php
// post_thread.php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $thread_title = $_POST['title'];
    $thread_desc = $_POST['description'];
    $user_id = $_SESSION['user_id'];
    $forum_id = $_POST['forum_id'];
    $status = 'new'; // Set status to new

    $sql = "INSERT INTO threads (thread_title, thread_desc, thread_user_id, forum_id, status) 
            VALUES ('$thread_title', '$thread_desc', '$user_id', '$forum_id', '$status')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: threadlist.php?forumid=$forum_id");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<?php
$showAlert=false;
$method = $_SERVER['REQUEST_METHOD'];
if($method=='POST'){
    //INSERT INTO DATABASE AS PENDING
    $th_title =$_POST['title'];
    $th_desc =$_POST['desc'];
    $sql = "INSERT INTO `threads` ( `thread_title`, `thread_desc`, `thread_cat_id`, `thread_user`, `timestamp`, `status`) VALUES ( ?, ?, ?, '0', current_timestamp(), 'pending')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $th_title, $th_desc, $id);
    mysqli_stmt_execute($stmt);
    $showAlert=true;
    if($showAlert){
        echo' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success !</strong> Your thread has been added and awaiting admin approval.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}
?>

