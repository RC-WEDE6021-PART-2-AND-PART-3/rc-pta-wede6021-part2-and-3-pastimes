<?php require_once 'includes/header.php';
if (!isLoggedIn() || $_SESSION['role'] != 'seller' || !isVerifiedSeller()) {
    redirect('dashboard.php');
}
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $category = $_POST['category'] ?? '';
    $size = $_POST['size'] ?? '';
    $condition = $_POST['condition'] ?? 'Good';
    $image = $_FILES['image'];

    if (empty($title) || empty($price) || !is_numeric($price)) {
        $error = "Title and valid price are required.";
    } elseif ($image['error'] !== UPLOAD_ERR_OK) {
        $error = "Image upload failed.";
    } else {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . '_' . basename($image['name']);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO products (seller_id, title, description, price, category, size, `condition`, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "issdssss", $_SESSION['user_id'], $title, $description, $price, $category, $size, $condition, $target_file);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Product uploaded successfully!";
            } else {
                $error = "Database error.";
            }
        } else {
            $error = "Could not save the uploaded file.";
        }
    }
}
?>
<div class="card" style="max-width:600px; margin:2rem auto;">
    <h2>Upload New Product</h2>
    <?php if ($error): ?><p style="color:red;"><?= h($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green;"><?= h($success) ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group"><label>Product Title</label><input type="text" name="title" class="form-control" required></div>
        <div class="form-group"><label>Price (R)</label><input type="number" step="0.01" name="price" class="form-control" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="4"></textarea></div>
        <div class="form-group"><label>Category</label><input type="text" name="category" class="form-control" placeholder="e.g. Jackets, Dresses"></div>
        <div class="form-group"><label>Size</label><input type="text" name="size" class="form-control" placeholder="e.g. M, L"></div>
        <div class="form-group"><label>Condition</label><select name="condition" class="form-control">
            <option value="New with tags">New with tags</option>
            <option value="Like new">Like new</option>
            <option value="Good" selected>Good</option>
            <option value="Fair">Fair</option>
        </select></div>
        <div class="form-group"><label>Product Image</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
<?php require_once 'includes/footer.php'; ?><?php include 'config.php';
if(isset($_POST['upload'])){
    $name=$_POST['name'];
    $price=$_POST['price'];

    $conn->query("INSERT INTO products(name,price) VALUES('$name','$price')");
    echo "Product uploaded!";
}
?>
<form method="POST">
<input name="name" placeholder="Item Name" required>
<input name="price" placeholder="Price" required>
<button name="upload">Upload</button>
</form>