<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $user_id = $_SESSION['user_id'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    $stmt = $pdo->prepare('INSERT INTO posts (title, content, image, user_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$title, $content, $image, $user_id]);

    header('Location: index.php');
}

if (!isset($_SESSION['user_id'])) {
    echo "Por favor, inicie sesión para crear contenido.";
} else {
?>

<h1 class="mb-4">Create New Content</h1>

<form action="create.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Tittle </label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
    </div>
    <div class="form-group">
        <label for="image">Imagen</label>
        <input type="file" class="form-control-file" id="image" name="image" required>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>

<?php
}
include 'footer.php';
?>