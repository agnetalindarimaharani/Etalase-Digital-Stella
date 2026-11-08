<?php
session_start();
require_once '../config/kuis_db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (:nama, :email, :password, 'siswa')");
        $stmt->execute([':nama' => $nama, ':email' => $email, ':password' => $password]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = "Email sudah terdaftar atau terjadi kesalahan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Siswa - Etalase Digital Stella</title>
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
    <h2>Registrasi Siswa</h2>
    <?php if($error): ?><p style="color:red; font-size:14px;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>
    <p style="font-size:12px;">Sudah punya akun? <a href="login.php">Login</a></p>
</div>
</body>
</html>
