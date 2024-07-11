<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !=='admin') {
    header('Location: index.php');
    exit;
}

include 'partials/_dbconnect.php';

// Handle grant, revoke admin requests and delete users
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['grant_admin'])) {
        $user_id = $_POST['sno'];
        $grant_admin_sql = "UPDATE users SET is_admin=1 WHERE sno='$user_id'";
        $grant_admin_result = mysqli_query($conn, $grant_admin_sql);
        if ($grant_admin_result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Admin privileges granted successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error granting admin privileges.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }

    if (isset($_POST['revoke_admin'])) {
        $user_id = $_POST['sno'];
        $revoke_admin_sql = "UPDATE users SET is_admin=0 WHERE sno='$user_id'";
        $revoke_admin_result = mysqli_query($conn, $revoke_admin_sql);
        if ($revoke_admin_result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Admin privileges revoked successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error revoking admin privileges.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }

    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['sno'];
        $delete_user_sql = "DELETE FROM users WHERE sno='$user_id'";
        $delete_user_result = mysqli_query($conn, $delete_user_sql);
        if ($delete_user_result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    User deleted successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error deleting user.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }
}

// Fetch all users
$sql_users = "SELECT * FROM users";
$result_users = mysqli_query($conn, $sql_users);

// Fetch counts for widgets
$sql_total_users = "SELECT COUNT(*) as count FROM users";
$result_total_users = mysqli_query($conn, $sql_total_users);
$total_users = mysqli_fetch_assoc($result_total_users)['count'];

$sql_total_admins = "SELECT COUNT(*) as count FROM users WHERE is_admin = 1";
$result_total_admins = mysqli_query($conn, $sql_total_admins);
$total_admins = mysqli_fetch_assoc($result_total_admins)['count'];
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

    <title>Manage Users</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

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
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
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
                            <a href="contact.php" class="nav-link">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Contact</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="visitors.php" class="nav-link">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Visitors</p>
                            </a>
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
                        <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true): ?>
                            <li class="nav-item">
                                <a href="admin.php" class="nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>Admin</p>
                                </a>
                            </li>
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
                            <h1 class="m-0">Manage Users</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Widgets row -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php echo $total_users; ?></h3>
                                    <p>Total Users</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?php echo $total_admins; ?></h3>
                                    <p>Total Admins</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->

                    <!-- Users table row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Users List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Email</th>
                                                <th>Admin</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($result_users)) {
                                                echo '<tr>';
                                                echo '<td>' . $row['sno'] . '</td>';
                                                echo '<td>' . $row['user_email'] . '</td>';
                                                echo '<td>' . ($row['is_admin'] ? 'Yes' : 'No') . '</td>';
                                                echo '<td>';
                                                if ($row['is_admin']) {
                                                    echo '<form method="POST" style="display:inline;">
                                                            <input type="hidden" name="sno" value="' . $row['sno'] . '">
                                                            <button type="submit" name="revoke_admin" class="btn btn-danger btn-sm">Revoke Admin</button>
                                                          </form>';
                                                } else {
                                                    echo '<form method="POST" style="display:inline;">
                                                            <input type="hidden" name="sno" value="' . $row['sno'] . '">
                                                            <button type="submit" name="grant_admin" class="btn btn-success btn-sm">Grant Admin</button>
                                                          </form>';
                                                }
                                                echo '<form method="POST" style="display:inline; margin-left: 5px;">
                                                        <input type="hidden" name="sno" value="' . $row['sno'] . '">
                                                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                                                      </form>';
                                                echo '</td>';
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
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="/FORUMS/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/FORUMS/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/FORUMS/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
