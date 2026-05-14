<?php
session_start();
require '../config/db.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die("Input tidak boleh kosong");
}

if (strlen($username) < 4) {
    die("Username minimal 4 karakter");
}

if (strlen($username) > 30) {
    die("Username maksimal 30 karakter");
}

if (strlen($password) < 8) {
    die("Password minimal 8 karakter");
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    die("Username hanya boleh huruf, angka, underscore");
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);

if ($stmt->fetch()) {
    die("Username sudah dipakai");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hash]);

$user_id = $pdo->lastInsertId();

$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;

header("Location: ../index.php");
exit;
?>