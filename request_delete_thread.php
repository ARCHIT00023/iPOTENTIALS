<?php
// request_delete_thread.php
include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_delete'])) {
    $thread_id = $_POST['thread_id'];

    // Update the thread to mark it as pending delete
    $sql = "UPDATE threads SET pending_delete = 1 WHERE thread_id = $thread_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: threadlist.php?catid={$_GET['catid']}&delete=success");
    } else {
        header("Location: threadlist.php?catid={$_GET['catid']}&delete=error");
    }
}
?>
