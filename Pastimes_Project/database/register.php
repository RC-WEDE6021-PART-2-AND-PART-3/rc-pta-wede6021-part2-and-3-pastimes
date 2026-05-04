<?php require_once 'includes/header.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, username, password_hash, role) VALUES (?, ?, ?, ?, 'buyer')");
        mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email, $username, $hash);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'buyer';
            $_SESSION['is_verified_seller'] = 0;
            redirect('dashboard.php');
        } else {
            $error = "Username or email already exists.";
        }
    }
}
?>
<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h2>Create an Account</h2>
    <?php if ($error): ?><p class="error" style="color:red;"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p style="margin-top: 1rem;">Already have an account? <a href="login.php">Login here</a></p>
</div>
<?php require_once 'includes/footer.php'; ?>