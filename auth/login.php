<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Social</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --bg-primary: #f5f5f3;
            --bg-card: #fff;
            --border: #e8e8e6;
            --text-primary: #1a1a1a;
            --text-muted: #888;
            --border-focus: #1a56db;
            --shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .auth-container {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: var(--shadow);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            font-size: 15px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            background: #fafafa;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--border-focus);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
            pointer-events: none;
            z-index: 2;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--border-focus);
            background: rgba(26, 86, 219, 0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--text-primary);
            color: white;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: #333;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .auth-link-text {
            color: var(--text-muted);
            font-size: 15px;
            margin-bottom: 0.25rem;
            display: block;
        }

        .auth-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--border-focus);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .auth-link:hover {
            color: #0f4ac7;
            background: rgba(26, 86, 219, 0.08);
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .auth-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-container">
        <header class="auth-header">
            <h1 class="auth-title">Masuk Akun</h1>
            <p class="auth-subtitle">Selamat datang kembali di Mini Social</p>
        </header>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label class="form-label">Username</label>
                <div class="input-wrapper">
                    <i class="ti ti-user input-icon"></i>
                    <input type="text"
                           name="username"
                           required
                           class="form-input"
                           placeholder="Masukkan username"
                           autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="ti ti-lock input-icon"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="form-input"
                           placeholder="Masukkan password"
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" id="togglePassword" title="Tampilkan password">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn">Masuk Akun</button>
        </form>

        <div class="auth-links">
            <span class="auth-link-text">Belum punya akun?</span>
            <a href="register.php" class="auth-link">
                <i class="ti ti-user-plus"></i>
                Buat akun baru
            </a>
        </div>
    </main>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                toggleIcon.className = 'ti ti-eye';
                togglePassword.title = 'Tampilkan password';
            } else {
                toggleIcon.className = 'ti ti-eye-off';
                togglePassword.title = 'Sembunyikan password';
            }
        });
    </script>
</body>
</html>