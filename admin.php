<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'partials/_dbconnect.php';


// Handle approval and delete requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $thread_id_to_approve = $_POST['thread_id'];
        $approve_sql = "UPDATE threads SET status='approved' WHERE thread_id='$thread_id_to_approve'";
        $approve_result = mysqli_query($conn, $approve_sql);
        if ($approve_result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Thread approved successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error approving thread.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }

    if (isset($_POST['delete'])) {
        $thread_id_to_delete = $_POST['thread_id'];
        $delete_sql = "DELETE FROM threads WHERE thread_id='$thread_id_to_delete'";
        $delete_result = mysqli_query($conn, $delete_sql);
        if ($delete_result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Thread deleted successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error deleting thread.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }

    if (isset($_POST['approve_delete'])) {
        $thread_id_to_approve_delete = $_POST['thread_id'];
        $approve_delete_sql = "DELETE FROM threads WHERE thread_id='$thread_id_to_approve_delete'";
        $approve_delete_result = mysqli_query($conn, $approve_delete_sql);
        if ($approve_delete_result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Thread delete approved successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error approving thread delete.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }
}

// Fetch new threads
$sql_new_threads = "SELECT * FROM threads WHERE status='new'";
$result_new_threads = mysqli_query($conn, $sql_new_threads);

// Fetch threads pending deletion approval
$sql_pending_delete_threads = "SELECT * FROM threads WHERE pending_delete=1";
$result_pending_delete_threads = mysqli_query($conn, $sql_pending_delete_threads);

// Fetch total threads count
$sql_total_threads = "SELECT COUNT(*) AS total_threads FROM threads";
$result_total_threads = mysqli_query($conn, $sql_total_threads);
$total_threads = mysqli_fetch_assoc($result_total_threads)['total_threads'];

// Fetch pending threads count
$sql_pending_threads = "SELECT COUNT(*) AS pending_threads FROM threads WHERE status='new'";
$result_pending_threads = mysqli_query($conn, $sql_pending_threads);
$pending_threads = mysqli_fetch_assoc($result_pending_threads)['pending_threads'];

?>

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
    <link rel="stylesheet" href="/FORUMS/AdminLTE-3.2.0/dist/css/adminlte.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/FORUMS/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">

    <!-- Custom CSS -->
    <style>
    .navbar-nav .nav-item .btn {
        color: #fff;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        text-align: center;
    }

    .navbar-nav .nav-item .btn:hover {
        color: #fff;
    }

    .small-box {
        text-align: center;
    }

    .navbar-text {
        margin-right: 15px;
    }

    .navbar-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .navbar-brand-custom {
        margin-right: 30px;
    }

    .nav-item-custom {
        margin-left: 10px;
    }

    .form-inline-custom {
        display: flex;
        align-items: center;
    }

    .form-inline-custom .form-control {
        width: 150px;
    }

    .form-inline-custom .btn {
        padding: 0.25rem 0.5rem;
    }

    .navbar-nav .nav-item-custom .btn {
        margin-left: 10px;
    }

    .content-wrapper {
        margin-left: 250px;
        /* Adjust based on the sidebar width */
        transition: margin-left 0.3s;
    }

    .content-wrapper.collapsed {
        margin-left: 0;
    }

    .main-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        /* Adjust as needed */
        background: #343a40;
        color: #fff;
        transition: left 0.3s;
    }

    .main-sidebar.collapsed {
        left: -250px;
    }

    .main-sidebar .nav-link {
        color: #fff;
    }

    .main-sidebar .nav-link:hover {
        background: #495057;
    }

    .sidebar-toggle-btn {
        position: fixed;
        top: 15px;
        left: 250px;
        /* Adjust based on the sidebar width */
        transition: left 0.3s;
    }

    .sidebar-toggle-btn.collapsed {
        left: 10px;
    }
    </style>

    <title>Admin Dashboard</title>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php
        include 'partials/_loginmodal.php';
        include 'partials/_signupmodal.php';
        ?>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/FORUMS" class="brand-link">
                
                <span class="brand-text font-weight-light">iFORUMS</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="about.php" class="nav-link">
                                <i class="nav-icon fas fa-info"></i>
                                <p>About</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Categories
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="manage_categories.php" class="nav-link">
                                        <p>Manage Category</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="manage_users.php" class="nav-link">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Manage Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.php" class="nav-link">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Contact</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <!-- <a href="visitors.php" class="nav-link">
                                <i class="nav-icon fas fa-paper-plane"></i>
                                <p>Messages</p>
                            </a> -->

                            <?php if( $_SESSION['role']== 'admin'): ?>
                        <li class="nav-item">
                            <a href="admin.php" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                        </li>
                        <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                        <li class="nav-item">
                            <a href="partials/_logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#loginmodal">
                                <i class="nav-icon fas fa-sign-in-alt"></i>
                                <p>Login</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#signupmodal">
                                <i class="nav-icon fas fa-user-plus"></i>
                                <p>Signup</p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Admin Dashboard</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Widget for total threads -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php echo $total_threads; ?></h3>
                                    <p>Total Threads</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Widget for pending threads -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo $pending_threads; ?></h3>
                                    <p>Pending Threads</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">New Threads</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($result_new_threads)) {
                                                echo '<tr>';
                                                echo '<td>' . $row['thread_id'] . '</td>';
                                                echo '<td>' . $row['thread_title'] . '</td>';
                                                echo '<td>' . $row['thread_desc'] . '</td>';
                                                echo '<td>
                                                        <form method="POST" style="display:inline;">
                                                            <input type="hidden" name="thread_id" value="' . $row['thread_id'] . '">
                                                            <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                                                        </form>
                                                        <form method="POST" style="display:inline;">
                                                            <input type="hidden" name="thread_id" value="' . $row['thread_id'] . '">
                                                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                      </td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>

                    <!-- Threads Pending Delete Approval -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Threads Pending Delete Approval</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($result_pending_delete_threads)) {
                                                echo '<tr>';
                                                echo '<td>' . $row['thread_id'] . '</td>';
                                                echo '<td>' . $row['thread_title'] . '</td>';
                                                echo '<td>' . $row['thread_desc'] . '</td>';
                                                echo '<td>
                                                        <form method="POST" style="display:inline;">
                                                            <input type="hidden" name="thread_id" value="' . $row['thread_id'] . '">
                                                            <button type="submit" name="approve_delete" class="btn btn-success btn-sm">Approve Delete</button>
                                                        </form>
                                                      </td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">

            </div>
            <!-- Default to the left -->
            <strong>iFORUMS</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="/FORUMS/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/FORUMS/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/FORUMS/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>

    <!-- Custom JS -->
    <script>
    // Add your custom JavaScript code here
    </script>
</body>

</html>