<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/forums">iFORUMS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Categories
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
            </li>
        </ul>
        <div class="row mx-2">
            <form class="form-inline my-2 my-lg-0" action="search.php" method="get">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                <span class="navbar-text">
                    Welcome <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                </span>

              
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="admin.php" class="btn btn-outline-warning ml-2">Admin</a>
                <?php endif; ?>
                <a href="partials/_logout.php" class="btn btn-outline-success ml-2">Logout</a>
            <?php else: ?>
                <button class="btn btn-outline-success ml-2" data-toggle="modal" data-target="#loginmodal">Login</button>
                <button class="btn btn-outline-success mx-2" data-toggle="modal" data-target="#signupmodal">Signup</button>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php
include 'partials/_loginmodal.php';
include 'partials/_signupmodal.php';
?>
