<?php
include 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

//This  code check that the id provided is valid
if (!is_numeric($id)) {
    echo "ID inválido.";
    include 'footer.php';
    exit;
}

// This code find the record in the database
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$post) {
    echo "The record does not exist.";
    include 'footer.php';
    exit;
}


if ($_SESSION['user_id'] != $post['user_id']) {
    echo "This action does not have permission to delete this content.";
    include 'footer.php';
    exit;
}


// This code delete the associated image, if it exists
if ($post['image']) {
    $image_path = 'uploads/' . $post['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}


//This code delete the record from the database
$stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
$stmt->execute([$id]);


// This code redirect to main page
header('Location: index.php');
include 'footer.php';
?>
