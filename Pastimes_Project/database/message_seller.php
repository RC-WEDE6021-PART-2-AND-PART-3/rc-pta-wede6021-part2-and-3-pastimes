<?php require_once 'includes/header.php';
if (!isLoggedIn()) redirect('login.php');
$product_id = $_GET['product_id'] ?? 0;
$receiver_id = $_GET['receiver_id'] ?? 0;
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $body = trim($_POST['body']);
    $stmt = mysqli_prepare($conn, "INSERT INTO messages (product_id, sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiiss", $product_id, $_SESSION['user_id'], $receiver_id, $subject, $body);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Message sent!";
    }
}
?>
<div class="card" style="max-width:600px; margin:2rem auto;">
    <h2>Contact Seller</h2>
    <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>Subject</label><input type="text" name="subject" class="form-control" required></div>
        <div class="form-group"><label>Message</label><textarea name="body" class="form-control" rows="5" required></textarea></div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>
<?php require_once 'includes/footer.php'; ?>