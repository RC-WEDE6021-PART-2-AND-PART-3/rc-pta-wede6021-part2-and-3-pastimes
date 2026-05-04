<?php require_once 'includes/header.php';
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart = &$_SESSION['cart'];

// Add to cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    $found = false;
    foreach ($cart as &$item) {
        if ($item['id'] == $pid) {
            $item['qty']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $cart[] = ['id' => $pid, 'qty' => 1];
    }
    redirect('cart.php');
}

// Remove from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    $cart = array_filter($cart, function($item) use ($pid) { return $item['id'] != $pid; });
    $_SESSION['cart'] = array_values($cart); // reindex
    redirect('cart.php');
}
?>
<h2>Your Cart</h2>
<?php if (empty($cart)): ?>
    <p>Your cart is empty. <a href="products.php">Continue shopping</a></p>
<?php else: ?>
    <table style="width:100%; border-collapse:collapse;">
        <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php
        $total = 0;
        foreach ($cart as $item):
            $pid = $item['id'];
            $qty = $item['qty'];
            $res = mysqli_query($conn, "SELECT title, price, image_path FROM products WHERE product_id=$pid");
            $prod = mysqli_fetch_assoc($res);
            if (!$prod) continue; 
            $subtotal = $prod['price'] * $qty;
            $total += $subtotal;
        ?>
        <tr>
            <td><img src="<?= h($prod['image_path'] ?: 'images/default.jpg') ?>" width="50"> <?= h($prod['title']) ?></td>
            <td>R<?= number_format($prod['price'],2) ?></td>
            <td><?= $qty ?></td>
            <td>R<?= number_format($subtotal,2) ?></td>
            <td><a href="cart.php?action=remove&id=<?= $pid ?>" class="btn btn-danger">Remove</a></td>
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="4" style="text-align:right;"><strong>Total: R<?= number_format($total,2) ?></strong></td><td></td></tr>
        </tbody>
    </table>
    <a href="checkout.php" class="btn btn-primary" style="margin-top:1rem;">Checkout</a>
<?php endif; ?>
<?php require_once 'includes/footer.php'; ?>