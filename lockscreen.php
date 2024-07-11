<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroy session to lock the screen
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock Screen</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="/FORUMS/AdminLTE-3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition lockscreen">
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="/FORUMS"><b>iFORUMS</b></a>
        </div>
        <!-- User name -->
        <div class="lockscreen-name">Administrator</div>
        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="/FORUMS/AdminLTE-3.2.0/dist/img/user1-128x128.jpg" alt="User Image">
            </div>
            <!-- /.lockscreen-image -->

            <!-- lockscreen credentials (contains the form) -->
            <form class="lockscreen-credentials" action="unlock.php" method="post">
                <div class="input-group">
                        <input type="password" class="form-control" id="loginPass" name="loginPass" placeholder="Password" required>
                    <div class="input-group-append">
                        <button type="button" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
                    </div>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
        <!-- /.lockscreen-item -->
        <div class="help-block text-center">
            Enter your password to retrieve your session
        </div>
        <div class="text-center">
            <a href="index.php">Or sign in as a different user</a>
        </div>
    </div>
    <!-- /.center -->

    <!-- jQuery -->
    <script src="/FORUMS/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/FORUMS/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/FORUMS/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
