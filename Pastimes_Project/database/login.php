<?php require_once 'includes/header.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id, username, password_hash, role, is_verified_seller FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['is_verified_seller'] = $row['is_verified_seller'];
                redirect('dashboard.php');
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Username not found.";
        }
    }
}
?>
<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h2>Welcome Back!</h2>
    <?php if ($error): ?><p style="color:red;"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p style="margin-top: 1rem;">Don't have an account? <a href="register.php">Register here</a></p>
</div>
<?php require_once 'includes/footer.php'; ?>