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
$username = trim($_POST['username'] ?? '');

if (empty($username)) {
    die("Username tidak boleh kosong");
}

if (strlen($username) < 4) {
    die("Username minimal 4 karakter");
}

if (strlen($username) > 30) {
    die("Username maksimal 30 karakter");
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    die("Username hanya boleh huruf, angka, dan underscore");
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
$stmt->execute([$username, $user_id]);

if ($stmt->fetch()) {
    die("Username sudah dipakai");
}

$stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
$stmt->execute([$username, $user_id]);

$_SESSION['username'] = $username;

header("Location: profile.php");
exit;
?>