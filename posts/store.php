<?php
session_start();
require '../middleware/auth.php';
require '../config/db.php';

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("CSRF token tidak valid");
}

unset($_SESSION['csrf_token']);

$content = trim($_POST['content'] ?? '');
$cleanContent = trim(strip_tags($content));
$user_id = $_SESSION['user_id'];

if (empty($cleanContent)) {
    die("Status tidak boleh kosong");
}

if (strlen($cleanContent) > 1000) {
    die("Status terlalu panjang");
}

$imageName = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("Terjadi error saat upload file");
    }

    $allowedExt = ['jpg', 'jpeg', 'png'];
    $allowedMime = ['image/jpeg', 'image/png'];

    $originalName = $_FILES['image']['name'];
    $tmpFile = $_FILES['image']['tmp_name'];

    if (substr_count($originalName, '.') > 1) {
        die("Nama file mencurigakan");
    }

    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        die("Format file tidak diizinkan");
    }

    $mime = mime_content_type($tmpFile);

    if (!in_array($mime, $allowedMime)) {
        die("File bukan gambar valid");
    }

    if (getimagesize($tmpFile) === false) {
        die("File bukan gambar valid");
    }

    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        die("Ukuran file terlalu besar (max 2MB)");
    }

    $uploadDir = __DIR__ . '/../upload/files/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!is_writable($uploadDir)) {
        die("Folder upload tidak bisa ditulis");
    }

    $newName = uniqid() . "." . $ext;
    $uploadPath = $uploadDir . $newName;

    if (!move_uploaded_file($tmpFile, $uploadPath)) {
        die("Upload gagal");
    }

    $imageName = $newName;
}

$stmt = $pdo->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $cleanContent, $imageName]);

header("Location: /mini-social/index.php");
exit;
?>