<?php require_once 'includes/header.php';
if (!isLoggedIn()) redirect('login.php');
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) redirect('cart.php');

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $postal = trim($_POST['postal']);
    if (empty($fullname) || empty($phone) || empty($address) || empty($city) || empty($postal)) {
        $error = "All delivery fields are required.";
    } else {
        $total = 0;
        foreach ($cart as $item) {
            $res = mysqli_query($conn, "SELECT price FROM products WHERE product_id={$item['id']}");
            $prod = mysqli_fetch_assoc($res);
            $total += $prod['price'] * $item['qty'];
        }
        $stmt = mysqli_prepare($conn, "INSERT INTO orders (buyer_id, total_amount) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "id", $_SESSION['user_id'], $total);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($conn);
        foreach ($cart as $item) {
            $res = mysqli_query($conn, "SELECT price FROM products WHERE product_id={$item['id']}");
            $price = mysqli_fetch_assoc($res)['price'];
            $stmt2 = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "iiid", $order_id, $item['id'], $item['qty'], $price);
            mysqli_stmt_execute($stmt2);
        }
        $_SESSION['cart'] = [];
        $success = "Order placed successfully! Your order number is #$order_id.";
    }
}
?>
<?php if ($success): ?>
    <div class="card"><h2><?= $success ?></h2><a href="products.php">Continue Shopping</a></div>
<?php else: ?>
<h2>Delivery Details</h2>
<?php if ($error): ?><p style="color:red;"><?= h($error) ?></p><?php endif; ?>
<form method="post" class="card" style="max-width:600px;">
    <div class="form-group"><label>Full Name</label><input type="text" name="fullname" class="form-control" required></div>
    <div class="form-group"><label>Phone Number</label><input type="text" name="phone" class="form-control" required></div>
    <div class="form-group"><label>Address</label><input type="text" name="address" class="form-control" required></div>
    <div class="form-group"><label>City</label><input type="text" name="city" class="form-control" required></div>
    <div class="form-group"><label>Postal Code</label><input type="text" name="postal" class="form-control" required></div>
    <h3>Order Summary</h3>
    <?php
    $total = 0;
    foreach ($cart as $item):
        $res = mysqli_query($conn, "SELECT title, price FROM products WHERE product_id={$item['id']}");
        $prod = mysqli_fetch_assoc($res);
        if (!$prod) continue;
        $sub = $prod['price'] * $item['qty'];
        $total += $sub;
    ?>
    <p><?= h($prod['title']) ?> x<?= $item['qty'] ?> = R<?= number_format($sub,2) ?></p>
    <?php endforeach; ?>
    <p><strong>Total: R<?= number_format($total,2) ?></strong></p>
    <button type="submit" class="btn btn-primary">Place Order</button>
</form>
<?php endif; ?>
<?php require_once 'includes/footer.php'; ?>