<?php
require '../middleware/auth.php';
require '../config/db.php';

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    die("Permintaan tidak valid");
}

$user_id = $_SESSION['user_id'];

if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    die("Pilih file dulu");
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die("Terjadi error saat upload");
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
    die("Format tidak valid");
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpFile);
finfo_close($finfo);

if (!in_array($mime, $allowedMime)) {
    die("File bukan gambar valid");
}

if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
    die("Max 2MB");
}

$uploadDir = __DIR__ . '/../upload/profiles/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!is_writable($uploadDir)) {
    die("Folder tidak bisa ditulis");
}

$fileName = uniqid() . "." . $ext;
$uploadPath = $uploadDir . $fileName;

if (!move_uploaded_file($tmpFile, $uploadPath)) {
    die("Upload gagal");
}

$stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$old = $stmt->fetch();

if ($old && !empty($old['profile_image'])) {
    $oldPath = $uploadDir . $old['profile_image'];
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}

$stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
$stmt->execute([$fileName, $user_id]);

header("Location: ../index.php");
exit;
?>