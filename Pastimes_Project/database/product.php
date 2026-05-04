<?php require_once 'includes/header.php';
$id = $_GET['id'] ?? 0;
$stmt = mysqli_prepare($conn, "SELECT p.*, u.username, u.is_verified_seller FROM products p JOIN users u ON p.seller_id=u.user_id WHERE p.product_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$product) { echo "<p>Product not found.</p>"; require 'includes/footer.php'; exit; }
?>
<div class="grid" style="grid-template-columns: 1fr 1fr; gap:2rem;">
    <div>
        <img src="<?= h($product['image_path'] ?: 'images/default.jpg') ?>" style="width:100%; max-height:400px; object-fit:contain;">
    </div>
    <div class="card">
        <h2><?= h($product['title']) ?></h2>
        <p class="price" style="font-size:1.5rem;">R<?= number_format($product['price'], 2) ?></p>
        <p><strong>Condition:</strong> <?= h($product['condition']) ?></p>
        <p><strong>Size:</strong> <?= h($product['size']) ?></p>
        <p><strong>Category:</strong> <?= h($product['category']) ?></p>
        <p><?= nl2br(h($product['description'])) ?></p>
        <p>Seller: <?= h($product['username']) ?> <?= $product['is_verified_seller'] ? '⭐ Verified' : '' ?></p>
        <?php if (isLoggedIn() && $product['seller_id'] != $_SESSION['user_id']): ?>
        <a href="cart.php?action=add&id=<?= $product['product_id'] ?>" class="btn btn-primary">Add to Cart</a>
        <a href="message_seller.php?product_id=<?= $product['product_id'] ?>&receiver_id=<?= $product['seller_id'] ?>" class="btn btn-secondary">Message Seller</a>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>