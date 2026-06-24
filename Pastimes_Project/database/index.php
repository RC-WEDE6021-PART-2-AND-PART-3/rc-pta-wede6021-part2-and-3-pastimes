<?php require_once 'includes/header.php'; ?>
<div class="hero">
    <h2>Find Your Style. Save the Planet.</h2>
    <p>Buy and sell quality second-hand clothing at affordable prices.</p>
    <a href="products.php" class="btn btn-primary">Shop Now</a>
    <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn btn-secondary">Sell an Item</a>
    <?php endif; ?>
</div>

<h3>Featured Products</h3>
<div class="grid">
    <?php
    $sql = "SELECT product_id, title, price, image_path FROM products WHERE status='available' ORDER BY created_at DESC LIMIT 6";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)):
    ?>
    <div class="product-card">
        <a href="product.php?id=<?= $row['product_id'] ?>">
            <img src="<?= !empty($row['image_path']) ? h($row['image_path']) : 'images/default.jpg' ?>" alt="<?= h($row['title']) ?>">
            <div class="card-body">
                <h4><?= h($row['title']) ?></h4>
                <span class="price">R<?= number_format($row['price'], 2) ?></span>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
</div>
<?php require_once 'includes/footer.php'; ?>