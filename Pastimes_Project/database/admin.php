<?php require_once 'includes/header.php';
if (!isLoggedIn() || $_SESSION['role'] != 'admin') redirect('dashboard.php');

if (isset($_GET['verify']) && is_numeric($_GET['verify'])) {
    $sid = $_GET['verify'];
    mysqli_query($conn, "UPDATE users SET is_verified_seller=1 WHERE user_id=$sid");
    redirect('admin.php');
}
$sellers = mysqli_query($conn, "SELECT user_id, username, is_verified_seller FROM users WHERE role='seller'");
?>
<h2>Admin - Verify Sellers</h2>
<table style="width:100%">
    <tr><th>Username</th><th>Status</th><th>Action</th></tr>
    <?php while ($s = mysqli_fetch_assoc($sellers)): ?>
    <tr>
        <td><?= h($s['username']) ?></td>
        <td><?= $s['is_verified_seller'] ? '✔ Verified' : 'Pending' ?></td>
        <td>
            <?php if (!$s['is_verified_seller']): ?>
                <a href="?verify=<?= $s['user_id'] ?>" class="btn btn-primary">Verify</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php require_once 'includes/footer.php'; ?>