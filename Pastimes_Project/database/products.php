<?php require_once 'includes/header.php';
$sql = "SELECT p.product_id, p.title, p.price, p.image_path, u.username AS seller
        FROM products p JOIN users u ON p.seller_id = u.user_id
        WHERE p.status='available' ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<h2>All Products</h2>
<div class="grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="product-card">
        <a href="product.php?id=<?= $row['product_id'] ?>">
            <img src="<?= !empty($row['image_path']) ? h($row['image_path']) : 'images/default.jpg' ?>" alt="<?= h($row['title']) ?>">
            <div class="card-body">
                <h4><?= h($row['title']) ?></h4>
                <p class="price">R<?= number_format($row['price'], 2) ?></p>
                <p>Seller: <?= h($row['seller']) ?></p>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
</div>
<?php require_once 'includes/footer.php'; ?>