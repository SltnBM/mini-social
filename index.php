<?php
require 'middleware/auth.php';
require 'config/db.php';

$stmt = $pdo->query("
    SELECT p.*, u.username, u.profile_image
    FROM posts p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
");
$posts = $stmt->fetchAll();

function getInitial($name) {
    return strtoupper(mb_substr($name, 0, 1));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Social</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f3;
            color: #1a1a1a;
            min-height: 100vh;
        }

        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e8e8e6;
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .nav-brand {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.3px;
            color: #1a1a1a;
            white-space: nowrap;
        }

        .nav-divider {
            width: 1px;
            height: 16px;
            background: #e0e0dc;
        }

        .greeting {
            font-size: 13px;
            color: #888;
            white-space: nowrap;
        }

        .greeting strong {
            color: #1a1a1a;
            font-weight: 500;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid #e0e0dc;
            background: transparent;
            color: #444;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn:hover { background: #f0f0ee; }

        .btn-primary {
            background: #1a1a1a;
            color: #fff;
            border-color: transparent;
        }

        .btn-primary:hover { background: #333; }

        .feed {
            max-width: 640px;
            margin: 1.25rem auto;
            padding: 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .card {
            background: #fff;
            border: 1px solid #e8e8e6;
            border-radius: 12px;
            padding: 1.125rem 1.25rem;
            transition: border-color 0.15s;
        }

        .card:hover { border-color: #ccc; }

        .card-header {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 0.875rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #e8e8e6;
            flex-shrink: 0;
        }

        .avatar-fallback {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .af-blue  { background: #e8f0fe; color: #1a56db; }
        .af-green { background: #dcfce7; color: #15803d; }
        .af-amber { background: #fef9c3; color: #a16207; }

        .meta-name {
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
        }

        .meta-time {
            font-size: 12px;
            color: #aaa;
            margin-top: 2px;
        }

        .content {
            font-size: 15px;
            color: #222;
            line-height: 1.65;
        }

        .post-img {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e8e8e6;
            margin-top: 0.875rem;
            display: block;
            max-height: 400px;
            object-fit: cover;
        }

        .card-footer {
            display: flex;
            gap: 20px;
            margin-top: 0.875rem;
            padding-top: 0.875rem;
            border-top: 1px solid #f0f0ee;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #aaa;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: color 0.15s;
        }

        .action-btn:hover { color: #1a1a1a; }

        .empty {
            text-align: center;
            padding: 3rem 0;
            color: #aaa;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .nav {
                padding: 0.75rem 1rem;
            }

            .nav-left { gap: 10px; }

            .nav-brand { font-size: 14px; }

            .nav-divider { display: none; }

            .greeting { display: none; } 

            .btn {
                padding: 7px;
                gap: 0;
                border-radius: 50%;
                width: 34px;
                height: 34px;
                justify-content: center;
            }

            .btn-label { display: none; }

            .feed { padding: 0 0.75rem; margin: 0.875rem auto; }

            .card { padding: 1rem; }

            .content { font-size: 14px; }
        }

        @media (max-width: 360px) {
            .nav-brand { font-size: 13px; }
        }
    </style>
</head>
<body>

<nav class="nav">
    <div class="nav-left">
        <span class="nav-brand">mini social</span>
        <div class="nav-divider"></div>
        <span class="greeting">Halo, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
    </div>
    <div class="nav-right">
        <a href="posts/create.php" class="btn btn-primary">
            <i class="ti ti-plus"></i>
            <span class="btn-label">Post</span>
        </a>
        <a href="auth/profile.php" class="btn">
            <i class="ti ti-user"></i>
            <span class="btn-label">Profil</span>
        </a>
        <a href="auth/logout.php" class="btn">
            <i class="ti ti-logout"></i>
            <span class="btn-label">Keluar</span>
        </a>
    </div>
</nav>

<div class="feed">
    <?php if (empty($posts)): ?>
        <p class="empty">Belum ada postingan.</p>
    <?php else: ?>
        <?php foreach ($posts as $p): ?>
        <div class="card">
            <div class="card-header">
                <?php if (!empty($p['profile_image'])): ?>
                    <img class="avatar"
                         src="/mini-social/upload/profiles/<?= htmlspecialchars($p['profile_image']) ?>"
                         alt="Foto <?= htmlspecialchars($p['username']) ?>">
                <?php else: ?>
                    <div class="avatar-fallback af-blue">
                        <?= getInitial($p['username']) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="meta-name"><?= htmlspecialchars($p['username']) ?></div>
                    <div class="meta-time">
                        <?= date('d M Y, H:i', strtotime($p['created_at'])) ?>
                    </div>
                </div>
            </div>

            <p class="content"><?= nl2br(htmlspecialchars($p['content'])) ?></p>

            <?php if (!empty($p['image'])): ?>
                <img class="post-img"
                     src="upload/files/<?= htmlspecialchars($p['image']) ?>"
                     alt="Foto postingan">
            <?php endif; ?>

            <div class="card-footer">
                <button class="action-btn">
                    <i class="ti ti-heart"></i> Suka
                </button>
                <button class="action-btn">
                    <i class="ti ti-message"></i> Komentar
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>