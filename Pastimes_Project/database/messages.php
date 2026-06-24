<?php require_once 'includes/header.php';
if (!isLoggedIn()) redirect('login.php');
$user_id = $_SESSION['user_id'];
$messages = mysqli_query($conn, "SELECT m.*, u.username AS sender_name FROM messages m JOIN users u ON m.sender_id=u.user_id WHERE m.receiver_id=$user_id ORDER BY m.sent_at DESC");
?>
<h2>Conversations</h2>
<div class="card">
    <?php while ($msg = mysqli_fetch_assoc($messages)): ?>
        <div style="border-bottom:1px solid #ddd; padding:0.5rem 0;">
            <strong><?= h($msg['sender_name']) ?></strong> - <?= h($msg['subject']) ?><br>
            <small><?= nl2br(h($msg['body'])) ?></small><br>
            <i><?= $msg['sent_at'] ?></i>
        </div>
    <?php endwhile; ?>
</div>
<?php require_once 'includes/footer.php'; ?>