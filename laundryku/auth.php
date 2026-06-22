<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/firebase.php';

$firebase = new FirebaseHelper();
$action = $_GET['action'] ?? '';

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function flashError($message) {
    $_SESSION['auth_error'] = $message;
    redirect('login.php');
}

function flashSuccess($message) {
    $_SESSION['auth_success'] = $message;
    redirect('login.php');
}

switch ($action) {
    case 'login':
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            flashError('Email dan kata sandi wajib diisi.');
        }

        $users = $firebase->read('users');
        if (!is_array($users)) {
            flashError('Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        }

        foreach ($users as $userId => $userData) {
            if (!empty($userData['email']) && strtolower($userData['email']) === strtolower($email)) {
                if (!empty($userData['password']) && password_verify($password, $userData['password'])) {
                    $_SESSION['user'] = [
                        'id' => $userId,
                        'name' => $userData['name'] ?? $email,
                        'email' => $userData['email']
                    ];
                    redirect('index.php');
                }
                break;
            }
        }

        flashError('Email atau kata sandi salah.');
        break;

    case 'register':
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (!$name || !$email || !$password || !$passwordConfirm) {
            flashError('Semua field pendaftaran wajib diisi.');
        }

        if ($password !== $passwordConfirm) {
            flashError('Kata sandi dan konfirmasi harus sama.');
        }

        if (strlen($password) < 6) {
            flashError('Kata sandi minimal 6 karakter.');
        }

        $users = $firebase->read('users');
        if (is_array($users)) {
            foreach ($users as $userData) {
                if (!empty($userData['email']) && strtolower($userData['email']) === strtolower($email)) {
                    flashError('Email ini sudah terdaftar. Silakan login.');
                }
            }
        }

        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = $firebase->create('users', $newUser);
        if ($result && isset($result['name'])) {
            $_SESSION['user'] = [
                'id' => $result['name'],
                'name' => $name,
                'email' => $email
            ];
            redirect('index.php');
        }

        flashError('Gagal membuat akun. Coba lagi.');
        break;

    default:
        redirect('login.php');
        break;
}
