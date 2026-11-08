<?php
require_once 'config/kuis_db.php';
require_once 'middleware/auth_check.php';

check_login();

// Ambil semua karya beserta nama pembuat
$stmt = $pdo->query("SELECT projects.*, users.nama AS pembuat 
                     FROM projects 
                     JOIN users ON projects.user_id = users.id 
                     ORDER BY projects.created_at DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Etalase Digital Stella</title>
    <link rel="stylesheet" href="assets/css/style.php.css?v=soft-pink">
    <link rel="stylesheet" href="assets/css/main.css?v=catalog-layout">
</head>
<body>
<div class="header">
    <h2 class="site-brand">
        <img src="assets/images/telkom-school-logo.png" alt="Logo Telkom Schools">
        <span>Etalase Digital Stella</span>
    </h2>
    <div class="header-actions">
        Halo, <b><?= htmlspecialchars($_SESSION['nama']) ?></b> (<?= $_SESSION['role'] ?>) | 
        <?php if ($_SESSION['role'] === 'super_admin'): ?>
            <a href="admin/users.php" class="btn btn-warning">Kelola Users</a>
        <?php endif; ?>
        <a href="auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<main class="catalog">
    <section class="catalog-toolbar" aria-label="Katalog Karya Siswa">
        <h1>Katalog Karya Siswa</h1>
        <form class="catalog-search" role="search">
            <label class="visually-hidden" for="project-search">Cari karya/masukan...</label>
            <input id="project-search" type="search" placeholder="Cari karya/masukan..." autocomplete="off">
            <button type="submit" class="btn btn-blue">Cari</button>
        </form>
        <a href="project_tambah.php" class="btn btn-blue catalog-add">+ Tambah Karya</a>
    </section>

    <section class="grid" aria-label="Daftar karya">
        <?php foreach($projects as $p): ?>
            <article class="card" data-search="<?= htmlspecialchars($p['nama_project'] . ' ' . $p['deskripsi'] . ' ' . $p['pembuat'], ENT_QUOTES, 'UTF-8') ?>">
                <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="Karya">
                <div class="card-body">
                    <h3><?= htmlspecialchars($p['nama_project']) ?></h3>
                    <p><small>Oleh: <b><?= htmlspecialchars($p['pembuat']) ?></b></small></p>
                    <p><?= htmlspecialchars($p['deskripsi']) ?></p>
                    <p><a href="<?= htmlspecialchars($p['link_project']) ?>" target="_blank" rel="noopener noreferrer">Lihat Project Tautan &rarr;</a></p>

                    <!-- Hak Akses Edit/Hapus: Owner / Admin / Super Admin -->
                    <?php if($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $p['user_id']): ?>
                        <div class="card-actions">
                            <a href="project_edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                            <a href="project_hapus.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus karya ini?')" class="btn btn-danger">Hapus</a>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
    <p class="catalog-empty" <?= $projects ? 'hidden' : '' ?>>Belum ada karya digital.</p>
    <p class="catalog-empty search-empty" hidden>Tidak ada karya yang cocok.</p>
</main>
<script>
    const searchForm = document.querySelector('.catalog-search');
    const searchInput = document.getElementById('project-search');
    const projectCards = Array.from(document.querySelectorAll('.grid .card'));
    const noProjects = document.querySelector('.catalog-empty:not(.search-empty)');
    const noSearchResults = document.querySelector('.search-empty');

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
    });

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim().toLocaleLowerCase();
        let visibleCount = 0;

        projectCards.forEach(function (card) {
            const matches = card.dataset.search.toLocaleLowerCase().includes(query);
            card.hidden = !matches;
            visibleCount += matches ? 1 : 0;
        });

        noProjects.hidden = projectCards.length > 0;
        noSearchResults.hidden = projectCards.length === 0 || visibleCount > 0 || query === '';
    });
</script>
</body>
</html>
