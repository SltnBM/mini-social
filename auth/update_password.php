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

$old = $_POST['old_password'] ?? '';
$new = $_POST['new_password'] ?? '';

if (empty($old) || empty($new)) {
    die("Field tidak boleh kosong");
}

if (strlen($new) < 8) {
    die("Password minimal 8 karakter");
}

$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !password_verify($old, $user['password'])) {
    die("Password lama salah");
}

$newHash = password_hash($new, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$newHash, $user_id]);

header("Location: profile.php");
exit;
?>