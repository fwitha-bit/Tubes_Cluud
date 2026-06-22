<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Laundryku</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: radial-gradient(circle at top, rgba(139, 92, 246, 0.24), transparent 30%),
                        radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.18), transparent 25%),
                        #08090f;
            color: #fff;
        }
        .auth-card {
            width: min(520px, 100%);
            background: rgba(12, 13, 22, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.45);
            padding: 2rem;
            overflow: hidden;
        }
        .auth-title {
            margin-bottom: 1rem;
        }
        .auth-title h1 {
            margin: 0 0 0.5rem;
            font-size: clamp(2rem, 2.5vw, 2.5rem);
        }
        .auth-description {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }
        .auth-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .auth-tab {
            padding: 0.95rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
            text-align: center;
            cursor: pointer;
            transition: var(--transition-smooth);
        }
        .auth-tab.active {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-color: transparent;
            box-shadow: 0 12px 30px rgba(139, 92, 246, 0.18);
        }
        .auth-form {
            display: none;
        }
        .auth-form.active {
            display: block;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--text-muted);
        }
        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }
        .auth-submit {
            width: 100%;
            margin-top: 1rem;
            padding: 0.95rem 1rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-smooth);
        }
        .auth-submit:hover {
            transform: translateY(-1px);
        }
        .auth-feedback {
            margin-bottom: 1rem;
            padding: 0.95rem 1rem;
            border-radius: 14px;
            background: rgba(248, 113, 113, 0.18);
            color: #fecaca;
        }
        .auth-success {
            background: rgba(34, 197, 94, 0.18);
            color: #bbf7d0;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-title">
            <h1>Login Laundryku</h1>
            <p class="auth-description">Masuk dengan akun Anda atau daftar sekarang untuk menggunakan dashboard.</p>
        </div>

        <?php if ($error): ?>
            <div class="auth-feedback"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="auth-feedback auth-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="auth-tabs">
            <div id="tab-login" class="auth-tab active">Login</div>
            <div id="tab-register" class="auth-tab">Daftar</div>
        </div>

        <form id="login-form" class="auth-form active" method="post" action="auth.php?action=login">
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" id="login-email" name="email" class="form-control" required placeholder="contoh@mail.com">
            </div>
            <div class="form-group">
                <label for="login-password">Kata Sandi</label>
                <input type="password" id="login-password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="auth-submit">Masuk</button>
        </form>

        <form id="register-form" class="auth-form" method="post" action="auth.php?action=register">
            <div class="form-group">
                <label for="register-name">Nama</label>
                <input type="text" id="register-name" name="name" class="form-control" required placeholder="Nama lengkap">
            </div>
            <div class="form-group">
                <label for="register-email">Email</label>
                <input type="email" id="register-email" name="email" class="form-control" required placeholder="contoh@mail.com">
            </div>
            <div class="form-group">
                <label for="register-password">Kata Sandi</label>
                <input type="password" id="register-password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label for="register-password-confirm">Konfirmasi Sandi</label>
                <input type="password" id="register-password-confirm" name="password_confirm" class="form-control" required placeholder="Ketik ulang kata sandi">
            </div>
            <button type="submit" class="auth-submit">Daftar</button>
        </form>
    </div>

    <script>
        const loginTab = document.getElementById('tab-login');
        const registerTab = document.getElementById('tab-register');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
        });
    </script>
</body>
</html>
