<?php
session_start();
require_once '../config/kuis_db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];
        header('Location: ../index.php');
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Etalase Digital Stella</title>
    <link rel="stylesheet" href="../assets/css/style.php.css?v=soft-pink">
    <link rel="stylesheet" href="../assets/css/auth.css?v=soft-pink">
    <style>
        .card { background: white; padding: 25px; border-radius: 8px; width: 320px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #d97798; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body class="auth-page">
<div class="card">
    <img class="auth-logo" src="../assets/images/telkom-school-logo.png" alt="Logo Telkom Schools">
    <h2>Login Etalase</h2>
    <?php if($error): ?><p style="color:red; font-size:14px;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p style="font-size:12px;">Siswa baru? <a href="register.php">Daftar Akun</a></p>
</div>
</body>
</html>
