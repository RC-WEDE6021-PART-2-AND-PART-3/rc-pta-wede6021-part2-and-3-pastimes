<?php require_once 'includes/header.php';
if (!isLoggedin()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if ($role == 'seller' && !isVerifiedSeller()) {
    echo '<div class="card"><p>Your seller account is pending verification. Please wait for admin approval.</p></div>';
}
?>
<h2>Dashboard</h2>
<?php if ($role == 'seller' && isVerifiedSeller()): ?>
    <div class="card">
        <a href="upload_product.php" class="btn btn-primary">Upload New Product</a>
        <a href="products.php" class="btn btn-secondary">View All Products</a>
    </div>
    <?php
    $stmt = mysqli_prepare($conn, "SELECT count(*) as total, sum(case when status='available' then 1 else 0 end) as active, sum(case when status='sold' then 1 else 0 end) as sold FROM products WHERE seller_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    ?>
    <div class="grid">
        <div class="card">Total Listings: <?= $stats['total'] ?></div>
        <div class="card">Active: <?= $stats['active'] ?></div>
        <div class="card">Sold: <?= $stats['sold'] ?></div>
    </div>
    <h3>Recent Listings</h3>
    <?php
    $listings = mysqli_query($conn, "SELECT product_id, title, price, status, created_at FROM products WHERE seller_id=$user_id ORDER BY created_at DESC LIMIT 5");
    while ($p = mysqli_fetch_assoc($listings)): ?>
        <div class="card">
            <strong><?= h($p['title']) ?></strong> - R<?= number_format($p['price'],2) ?> - <?= $p['status'] ?> (<?= $p['created_at'] ?>)
        </div>
    <?php endwhile; ?>
<?php elseif ($role == 'buyer'): ?>
    <p>Welcome, <?= h($_SESSION['username']) ?>. Start shopping <a href="products.php">here</a>.</p>
<?php endif; ?>
<?php require_once 'includes/footer.php'; ?>