<?php
require '../middleware/auth.php';
require '../config/db.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, profile_image FROM users WHERE id = ?");
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
    <title>Buat Post - Mini Social</title>
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
            margin-bottom: 1.25rem;
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
            overflow: hidden;
        }

        .card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.875rem 1.125rem;
            border-bottom: 1px solid #f0f0ee;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e8f0fe;
            color: #1a56db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .card-username {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .card-sub {
            font-size: 11px;
            color: #aaa;
            margin-top: 1px;
        }

        .card-body {
            padding: 1rem 1.125rem;
        }

        textarea {
            width: 100%;
            min-height: 140px;
            border: none;
            outline: none;
            resize: none;
            font-size: 16px;
            font-family: inherit;
            color: #1a1a1a;
            background: transparent;
            line-height: 1.6;
        }

        textarea::placeholder {
            color: #bbb;
        }

        .divider {
            height: 1px;
            background: #f0f0ee;
            margin: 0 1.125rem;
        }

        .card-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.125rem;
            gap: 10px;
        }

        .foot-left {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .file-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #888;
            border: 1px solid #e8e8e6;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: border-color 0.15s, color 0.15s;
            white-space: nowrap;
        }

        .file-label:hover { border-color: #bbb; color: #555; }

        input[type=file] { display: none; }

        .file-name {
            font-size: 12px;
            color: #aaa;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .submit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .submit:hover { opacity: 0.80; }
        .submit:active { opacity: 0.70; }

        .preview-wrap {
            padding: 0 1.125rem 1rem;
            display: none;
        }

        .preview-wrap.show { display: block; }

        .preview-img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e8e8e6;
            display: block;
        }

        .preview-remove {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #e53e3e;
            background: none;
            border: none;
            cursor: pointer;
            margin-top: 8px;
            padding: 0;
        }

        .preview-remove:hover { opacity: 0.75; }

        @media (max-width: 600px) {
            body { padding: 1.25rem 0.875rem; }
            .card { border-radius: 12px; }
            textarea { min-height: 120px; font-size: 15px; }
        }

        @media (max-width: 400px) {
            body { padding: 1rem 0.75rem; }
            .back { font-size: 12px; padding: 5px 10px; }
            .page-title { font-size: 14px; }
            .card-head { padding: 0.75rem 1rem; }
            .card-body { padding: 0.875rem 1rem; }
            .card-foot { padding: 0.75rem 1rem; flex-wrap: wrap; }
            .divider { margin: 0 1rem; }
            .preview-wrap { padding: 0 1rem 0.875rem; }
            .file-name { max-width: 100px; }
        }

        @media (max-width: 320px) {
            body { padding: 0.875rem 0.625rem; }
            .back span { display: none; }
            .back { padding: 6px 8px; }
            textarea { min-height: 100px; }
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
        <span class="page-title">Buat post</span>
    </div>

    <div class="card">
        <div class="card-head">
            <?php if (!empty($user['profile_image'])): ?>
                <img class="avatar"
                    src="/mini-social/upload/profiles/<?= htmlspecialchars($user['profile_image']) ?>"
                    style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <div class="avatar">
                <?= strtoupper(mb_substr($user['username'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div>
                <div class="card-username"><?= htmlspecialchars($_SESSION['username']) ?></div>
                <div class="card-sub">Posting sekarang</div>
            </div>
        </div>

        <form action="store.php" method="POST" enctype="multipart/form-data" id="postForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

            <div class="card-body">
                <textarea name="content"
                          placeholder="Apa yang kamu pikirkan?"
                          required
                          id="content"
                          maxlength="2000"></textarea>
            </div>

            <div class="preview-wrap" id="previewWrap">
                <img src="" alt="Preview" class="preview-img" id="previewImg">
                <button type="button" class="preview-remove" id="removeBtn">
                    <i class="ti ti-x"></i> Hapus foto
                </button>
            </div>

            <div class="divider"></div>

            <div class="card-foot">
                <div class="foot-left">
                    <label class="file-label" for="image">
                        <i class="ti ti-photo" style="font-size:16px"></i> Foto
                    </label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <span class="file-name" id="fileName"></span>
                </div>
                <button type="submit" class="submit">
                    <i class="ti ti-send" style="font-size:15px"></i> Post
                </button>
            </div>
        </form>
    </div>

</div>

<script>
const imageInput = document.getElementById('image');
const previewWrap = document.getElementById('previewWrap');
const previewImg  = document.getElementById('previewImg');
const fileName    = document.getElementById('fileName');
const removeBtn   = document.getElementById('removeBtn');

imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    fileName.textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewWrap.classList.add('show');
    };
    reader.readAsDataURL(file);
});

removeBtn.addEventListener('click', function() {
    imageInput.value = '';
    previewImg.src = '';
    previewWrap.classList.remove('show');
    fileName.textContent = '';
});
</script>
</body>
</html>