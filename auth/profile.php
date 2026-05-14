<?php
require '../middleware/auth.php';
require '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Mini Social</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f3;
            color: #1a1a1a;
            min-height: 100vh;
            padding: 1.5rem 1rem;
        }

        .wrap {
            max-width: 560px;
            margin: 0 auto;
            width: 100%;
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            gap: 12px;
        }

        .back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
            border: 1px solid #e8e8e6;
            padding: 6px 12px;
            border-radius: 8px;
            background: #fff;
            transition: border-color 0.15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .back:hover { border-color: #bbb; }

        .page-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: right;
        }

        .card {
            background: #fff;
            border: 1px solid #e8e8e6;
            border-radius: 14px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.875rem 1.125rem;
            border-bottom: 1px solid #f0f0ee;
        }

        .icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .icon-cam  { background: #e8f0fe; color: #1a56db; }
        .icon-edit { background: #dcfce7; color: #15803d; }
        .icon-key  { background: #fef9c3; color: #a16207; }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .card-body {
            padding: 1rem 1.125rem;
        }

        .form-group {
            margin-bottom: 0.875rem;
        }

        .form-group:last-of-type {
            margin-bottom: 0;
        }

        .label {
            display: block;
            font-size: 11px;
            color: #999;
            margin-bottom: 5px;
            letter-spacing: 0.01em;
        }

        input[type=text],
        input[type=password],
        input[type=file] {
            width: 100%;
            padding: 9px 11px;
            border: 1px solid #e8e8e6;
            border-radius: 8px;
            font-size: 14px;
            background: #fafafa;
            color: #1a1a1a;
            transition: border-color 0.15s, background 0.15s;
            min-width: 0;
        }

        input[type=file] {
            padding: 7px 11px;
            cursor: pointer;
        }

        input:focus {
            outline: none;
            border-color: #999;
            background: #fff;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            margin-top: 0.875rem;
            padding: 9px 14px;
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.15s;
        }

        .submit:hover { opacity: 0.80; }
        .submit:active { opacity: 0.70; }

        @media (max-width: 600px) {
            body { padding: 1.25rem 0.875rem; }

            .card { border-radius: 12px; }

            .card-head { padding: 0.75rem 1rem; }

            .card-body { padding: 0.875rem 1rem; }

            .row { grid-template-columns: 1fr; gap: 0; }
        }

        @media (max-width: 400px) {
            body { padding: 1rem 0.75rem; }

            .top { margin-bottom: 1.125rem; }

            .back { font-size: 12px; padding: 5px 10px; }

            .page-title { font-size: 14px; }

            .card-head { gap: 8px; padding: 0.7rem 0.875rem; }

            .icon { width: 28px; height: 28px; font-size: 14px; border-radius: 7px; }

            .card-title { font-size: 13px; }

            .card-body { padding: 0.75rem 0.875rem; }

            input[type=text],
            input[type=password],
            input[type=file] {
                font-size: 13px;
                padding: 8px 10px;
            }

            .submit { font-size: 13px; padding: 8px 12px; margin-top: 0.75rem; }
        }

        @media (max-width: 320px) {
            body { padding: 0.875rem 0.625rem; }

            .back span { display: none; }

            .back { padding: 6px 8px; }
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="top">
        <a href="../index.php" class="back">
            <i class="ti ti-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <span class="page-title">Edit profil</span>
    </div>

    <div class="card">
        <div class="card-head">
            <div class="icon icon-cam"><i class="ti ti-camera"></i></div>
            <span class="card-title">Foto profil</span>
        </div>
        <div class="card-body">
            <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <div class="form-group">
                    <label class="label">Pilih foto baru (jpg/png, maks 2 MB)</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="submit">
                    <i class="ti ti-upload"></i> Upload foto
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div class="icon icon-edit"><i class="ti ti-edit"></i></div>
            <span class="card-title">Username</span>
        </div>
        <div class="card-body">
            <form action="update_username.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <div class="form-group">
                    <label class="label">Username baru (4–30 karakter, huruf/angka/_)</label>
                    <input type="text"
                           name="username"
                           value="<?= htmlspecialchars($user['username']) ?>"
                           required minlength="4" maxlength="30"
                           autocomplete="username">
                </div>
                <button type="submit" class="submit">
                    <i class="ti ti-check"></i> Simpan username
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div class="icon icon-key"><i class="ti ti-key"></i></div>
            <span class="card-title">Password</span>
        </div>
        <div class="card-body">
            <form action="update_password.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <div class="row">
                    <div class="form-group">
                        <label class="label">Password lama</label>
                        <input type="password" name="old_password"
                               required autocomplete="current-password" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="label">Password baru (min 8 karakter)</label>
                        <input type="password" name="new_password"
                               required minlength="8" autocomplete="new-password" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="submit">
                    <i class="ti ti-lock"></i> Simpan password
                </button>
            </form>
        </div>
    </div>

</div>
</body>
</html>