<?php
include 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SESSION['user_id'] != $post['user_id']) {
    echo "This action does not have permission to edit this content.";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $post['image'];

    if ($_FILES['image']['name']) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image;

        // This code verify that the file upload was successful
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Image has been successfully enhanced
        } else {
            echo "Error uploading image.";
        }
    }

    $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?');
    $stmt->execute([$title, $content, $image, $id]);

    header('Location: index.php');
}
?>

<h1 class="mb-4">Update Content</h1>

<form action="update.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="image">Imagen</label>
        <?php if ($post['image']): ?>
            <div>
                <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Imagen actual" style="max-width: 200px; display: block; margin-bottom: 10px;">
            </div>
        <?php endif; ?>
        <input type="file" class="form-control-file" id="image" name="image">
        <small>Leave blank to keep the current image.</small>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<?php include 'footer.php'; ?>