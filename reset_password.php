<?php include 'partials/_dbconnect.php'; ?>
<?php include 'partials/_header.php'; ?>

<div class="container my-4">
    <h2 class="text-center">Reset Password</h2>
    <form action="update_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>

<?php include 'partials/_footer.php'; ?>
